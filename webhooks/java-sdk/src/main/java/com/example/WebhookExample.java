package com.example;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.node.ObjectNode;
import com.sun.net.httpserver.HttpServer;
import io.github.cdimascio.dotenv.Dotenv;
import io.github.payabli.api.PayabliApiClient;
import io.github.payabli.api.PayabliApiClientBuilder;
import io.github.payabli.api.resources.moneyin.requests.RequestPayment;
import io.github.payabli.api.resources.moneyin.types.TransRequestBody;
import io.github.payabli.api.types.PaymentDetail;
import io.github.payabli.api.types.PaymentMethod;
import io.github.payabli.api.types.PayMethodCredit;
import io.github.payabli.api.types.PayorDataRequest;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.InetSocketAddress;
import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.nio.charset.StandardCharsets;
import java.util.concurrent.Executors;
import java.util.concurrent.LinkedBlockingQueue;
import java.util.concurrent.TimeUnit;

public class WebhookExample {

    // Thread-safe queue: the HTTP handler thread pushes received payloads
    // here; the main thread blocks on take() until one arrives.
    private static final LinkedBlockingQueue<String> webhookQueue = new LinkedBlockingQueue<>();

    public static void main(String[] args) throws Exception {
        // ── Load .env ──────────────────────────────────────────────────────
        Dotenv dotenv = Dotenv.configure().ignoreIfMissing().load();

        String apiKey     = firstNonNull(dotenv.get("PAYABLI_KEY"),   System.getenv("PAYABLI_KEY"));
        String entrypoint = firstNonNull(dotenv.get("PAYABLI_ENTRY"), System.getenv("PAYABLI_ENTRY"));
        String ownerIdStr = firstNonNull(dotenv.get("OWNER_ID"),      System.getenv("OWNER_ID"));
        String portStr    = firstNonNull(dotenv.get("PORT"),          System.getenv("PORT"), "3000");

        if (apiKey     == null) { System.err.println("PAYABLI_KEY missing in .env");  System.exit(1); }
        if (entrypoint == null) { System.err.println("PAYABLI_ENTRY missing in .env"); System.exit(1); }
        if (ownerIdStr == null) { System.err.println("OWNER_ID missing in .env");     System.exit(1); }

        int ownerId = Integer.parseInt(ownerIdStr);
        int port    = Integer.parseInt(portStr);

        // ── Build Payabli client ────────────────────────────────────────────
        PayabliApiClient client = new PayabliApiClientBuilder()
                .apiKey(apiKey)
                .build();

        // ── Start the HTTP server (JDK built-in) ───────────────────────────
        // Using com.sun.net.httpserver.HttpServer instead of a framework to
        // eliminate any routing or threading quirks.
        HttpServer server = HttpServer.create(new InetSocketAddress("0.0.0.0", port), 0);
        server.createContext("/", exchange -> {
            try {
                String method = exchange.getRequestMethod();
                String path   = exchange.getRequestURI().getPath();

                // Log every inbound request.
                System.out.printf("→ %s %s (Content-Type: %s, User-Agent: %s)%n",
                        method, path,
                        exchange.getRequestHeaders().getFirst("Content-Type"),
                        exchange.getRequestHeaders().getFirst("User-Agent"));
                System.out.flush();

                if ("POST".equals(method) && "/webhook".equals(path)) {
                    // Read the full body and push it to the main thread.
                    byte[] bodyBytes = exchange.getRequestBody().readAllBytes();
                    String body = new String(bodyBytes, StandardCharsets.UTF_8);
                    System.out.printf("[webhook handler] received %d bytes, pushing to queue%n", body.length());
                    System.out.flush();
                    webhookQueue.offer(body);
                    exchange.sendResponseHeaders(200, 0);
                    exchange.getResponseBody().close();
                } else if ("GET".equals(method)) {
                    byte[] response = "Payabli Webhook Test".getBytes(StandardCharsets.UTF_8);
                    exchange.sendResponseHeaders(200, response.length);
                    try (OutputStream os = exchange.getResponseBody()) {
                        os.write(response);
                    }
                } else {
                    exchange.sendResponseHeaders(404, 0);
                    exchange.getResponseBody().close();
                }
            } catch (Exception e) {
                System.err.printf("Request handler error: %s%n", e.getMessage());
                try {
                    exchange.sendResponseHeaders(500, 0);
                    exchange.getResponseBody().close();
                } catch (Exception ignored) {}
            } finally {
                exchange.close();
            }
        });
        server.setExecutor(Executors.newCachedThreadPool());
        server.start();
        System.out.printf("%nWebhook server listening on http://localhost:%d/webhook%n", port);

        // ── Prompt for the tunnel URL ───────────────────────────────────────
        System.out.printf("%nExpose your local server publicly (e.g. ngrok http %d, localhost.run, etc.)%n", port);
        System.out.print("Paste your public tunnel URL (e.g. https://xxxx.ngrok-free.app): ");
        BufferedReader stdin = new BufferedReader(new InputStreamReader(System.in));
        String tunnelUrl = stdin.readLine().trim();

        // ── Verify the tunnel is reachable ─────────────────────────────────
        testTunnel(tunnelUrl);

        // ── Register the ApprovedPayment webhook with Payabli ───────────────
        createWebhookNotification(apiKey, tunnelUrl, ownerId);

        // ── Wait for confirmation before sending a live transaction ─────────
        System.out.print("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...");
        stdin.readLine();

        // Discard anything that arrived before the transaction (e.g. the tunnel test ping).
        webhookQueue.clear();

        // ── Fire a $1.00 test transaction ───────────────────────────────────
        triggerTransaction(client, entrypoint);

        // ── Self-test: verify the server+queue chain works independently ────────
        // POST directly to our own /webhook to confirm the mechanism works.
        // If this appears in the output below but Payabli's delivery does not,
        // the issue is 100% on the notification registration side.
        System.out.println("\n[self-test] POSTing directly to localhost to verify queue...");
        try {
            HttpClient selfHttp = HttpClient.newHttpClient();
            HttpResponse<String> selfResp = selfHttp.send(
                    HttpRequest.newBuilder()
                            .uri(URI.create("http://localhost:" + port + "/webhook"))
                            .header("Content-Type", "application/json")
                            .POST(HttpRequest.BodyPublishers.ofString("{\"self-test\":true}"))
                            .build(),
                    HttpResponse.BodyHandlers.ofString());
            System.out.printf("[self-test] POST to localhost /webhook: HTTP %d%n", selfResp.statusCode());
        } catch (Exception e) {
            System.err.printf("[self-test] FAILED: %s%n", e.getMessage());
        }

        // Drain the self-test ping from the queue before waiting for Payabli.
        webhookQueue.poll(3, TimeUnit.SECONDS);

        System.out.println("\nWaiting up to 30 seconds for Payabli webhook delivery...");
        System.out.println("(Watch for '\u2192 POST /webhook' above \u2014 if it never appears, Payabli is not delivering to your tunnel URL)");

        // ── Print webhook payloads as they arrive ─────────────────────────
        String payload = webhookQueue.poll(30, TimeUnit.SECONDS);
        if (payload == null) {
            System.out.println("\nNo webhook received within 30 seconds.");
            System.out.println("Possible causes:");
            System.out.println("  1. The notification was not registered successfully — check 'Notification raw response' above.");
            System.out.println("  2. The URL you pasted already included '/webhook' \u2014 target would be '.../webhook/webhook'.");
            System.out.println("  3. Payabli is delivering to a previously-registered notification's dead URL.");
            System.out.println("  4. The tunnel expired before Payabli made the delivery.");
        } else {
            System.out.printf("%nReceived webhook payload:%n%s%n",
                    payload.isEmpty() ? "(empty body)" : payload);
            System.out.flush();
            // Keep printing any further deliveries.
            //noinspection InfiniteLoopStatement
            while (true) {
                payload = webhookQueue.poll(60, TimeUnit.SECONDS);
                if (payload == null) break;
                System.out.printf("%nReceived webhook payload:%n%s%n", payload);
                System.out.flush();
            }
        }
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /** POST a small test payload to the tunnel to confirm it is live. */
    private static void testTunnel(String tunnelUrl) {
        String base = tunnelUrl.replaceAll("/$", "");
        if (base.endsWith("/webhook")) base = base.substring(0, base.length() - 8);
        String webhookUrl = base + "/webhook";
        System.out.printf("%nTesting tunnel by POSTing to %s...%n", webhookUrl);
        try {
            HttpClient http = HttpClient.newHttpClient();
            HttpRequest req = HttpRequest.newBuilder()
                    .uri(URI.create(webhookUrl))
                    .header("Content-Type", "application/json")
                    .POST(HttpRequest.BodyPublishers.ofString("{\"test\":\"ping\"}"))
                    .build();
            HttpResponse<String> resp = http.send(req, HttpResponse.BodyHandlers.ofString());
            System.out.printf("Tunnel test response: HTTP %d%n", resp.statusCode());
        } catch (Exception e) {
            System.err.printf("Tunnel test FAILED - tunnel may not be running or URL is wrong: %s%n",
                    e.getMessage());
        }
    }

    /**
     * Register an ApprovedPayment webhook notification with Payabli.
     *
     * We build the JSON body manually via Jackson rather than using the SDK's
     * AddNotificationRequest builder, because the Java SDK types ownerId as
     * Optional&lt;String&gt; which serializes to a JSON string ("236") instead of
     * the integer (236) that the Payabli API requires.
     */
    private static void createWebhookNotification(String apiKey,
                                                   String tunnelUrl,
                                                   int ownerId) {
        String webhookUrl = tunnelUrl.replaceAll("/$", "");
        if (webhookUrl.endsWith("/webhook")) {
            webhookUrl = webhookUrl.substring(0, webhookUrl.length() - 8);
        }
        webhookUrl = webhookUrl + "/webhook";
        System.out.println("\nRegistering webhook notification with Payabli...");
        System.out.printf("Notification request: Target=%s, OwnerId=%d%n", webhookUrl, ownerId);
        try {
            ObjectMapper mapper = new ObjectMapper();

            ObjectNode body = mapper.createObjectNode();
            body.putObject("content").put("eventType", "ApprovedPayment");
            body.put("frequency", "untilcancelled");
            body.put("method", "web");
            body.put("ownerId", ownerId);
            body.put("ownerType", 0);
            body.put("status", 1);
            body.put("target", webhookUrl);

            String jsonBody = mapper.writeValueAsString(body);
            System.out.printf("Notification request body: %s%n", jsonBody);
            System.out.flush();

            HttpClient http = HttpClient.newHttpClient();
            HttpRequest req = HttpRequest.newBuilder()
                    .uri(URI.create("https://api-sandbox.payabli.com/api/Notification"))
                    .header("Content-Type", "application/json")
                    .header("requestToken", apiKey)
                    .POST(HttpRequest.BodyPublishers.ofString(jsonBody))
                    .build();

            HttpResponse<String> resp = http.send(req, HttpResponse.BodyHandlers.ofString());
            System.out.printf("Notification response: HTTP %d%n", resp.statusCode());
            // Always print the raw body so any error reason is visible.
            System.out.printf("Notification raw response: %s%n", resp.body());
            System.out.flush();

            // Parse and print the key response fields for easy diagnosis.
            try {
                ObjectNode result = (ObjectNode) mapper.readTree(resp.body());
                boolean isSuccess = result.path("isSuccess").asBoolean(false);
                System.out.printf("Webhook registered: IsSuccess=%b, ResponseCode=%s, NotificationId=%s%n",
                        isSuccess,
                        result.path("responseCode").asText(""),
                        result.path("responseData").asText(""));
                if (!isSuccess) {
                    System.err.printf("WARNING: Notification registration failed — ResponseText: %s%n",
                            result.path("responseText").asText("(none)"));
                    System.err.println("No webhook will be delivered. Check your PAYABLI_KEY, OWNER_ID, and PAYABLI_ENTRY.");
                }
            } catch (Exception ignored) {}
        } catch (Exception e) {
            System.err.printf("Failed to register webhook: %s%n", e.getMessage());
        }
    }

    /**
     * Send a test $1.00 credit card transaction against the configured entrypoint
     * to generate an ApprovedPayment event and trigger the webhook.
     */
    private static void triggerTransaction(PayabliApiClient client, String entrypoint) {
        System.out.println("\nTriggering a test transaction to generate webhook...");
        System.out.printf("Transaction request: EntryPoint=%s, Amount=1.00%n", entrypoint);
        try {
            RequestPayment request = RequestPayment.builder()
                    .body(TransRequestBody.builder()
                            .paymentDetails(PaymentDetail.builder()
                                    .totalAmount(1.00)
                                    .serviceFee(0.0)
                                    .build())
                            .paymentMethod(PaymentMethod.of(
                                    PayMethodCredit.builder()
                                            .cardexp("02/27")
                                            .cardnumber("4111111111111111")
                                            .cardcvv("999")
                                            .cardHolder("Test User")
                                            .cardzip("12345")
                                            .initiator("payor")
                                            .build()
                            ))
                            .entryPoint(entrypoint)
                            .ipaddress("255.255.255.255")
                            .customerData(PayorDataRequest.builder()
                                    .customerId(4440L)
                                    .build())
                            .build())
                    .build();

            var resp = client.moneyIn().getpaid(request);
            System.out.printf("Transaction sent: IsSuccess=%b, ResponseText=%s%n",
                    resp.getIsSuccess(), resp.getResponseText());
        } catch (Exception e) {
            System.err.printf("Failed to trigger transaction: %s%n", e.getMessage());
        }
    }

    /** Returns the first non-null value from the provided candidates. */
    private static String firstNonNull(String... candidates) {
        for (String s : candidates) {
            if (s != null) return s;
        }
        return null;
    }
}
