/**
 * Payabli Webhook Example – TypeScript / @payabli/sdk-node
 *
 * Flow:
 *  1. Start a local HTTP server on PORT (default 3000).
 *  2. Prompt for a public ngrok URL and verify the tunnel is live.
 *  3. Register an ApprovedPayment webhook notification via direct HTTP
 *     (bypassing the SDK for this call because the SDK types ownerId as
 *     string, which would serialise to "236" instead of the integer 236
 *     that the Payabli API requires).
 *  4. Wait for confirmation, then fire a $1.00 test transaction via the SDK.
 *  5. Self-test the server/queue to confirm the local plumbing works.
 *  6. Wait up to 30 s for Payabli to deliver the webhook and print the payload.
 */

import * as http from "http";
import * as readline from "readline";
import * as dotenv from "dotenv";
import { PayabliClient } from "@payabli/sdk-node";

dotenv.config();

// ---------------------------------------------------------------------------
// Config
// ---------------------------------------------------------------------------
const API_KEY = process.env.PAYABLI_KEY ?? "";
const ENTRY = process.env.PAYABLI_ENTRY ?? "";
const OWNER_ID = parseInt(process.env.OWNER_ID ?? "0", 10);
const PORT = parseInt(process.env.PORT ?? "3000", 10);

if (!API_KEY) { console.error("PAYABLI_KEY missing from .env"); process.exit(1); }
if (!ENTRY)   { console.error("PAYABLI_ENTRY missing from .env"); process.exit(1); }
if (!OWNER_ID){ console.error("OWNER_ID missing from .env");  process.exit(1); }

// ---------------------------------------------------------------------------
// Webhook queue
// ---------------------------------------------------------------------------
// Payloads pushed by the HTTP handler are picked up by waitForWebhook().
const payloadBuffer: string[] = [];
let pendingResolve: ((payload: string) => void) | null = null;

function enqueue(payload: string): void {
  const preview = payload.slice(0, 200).replace(/\n/g, " ");
  console.log(
    `[webhook handler] received ${payload.length} bytes: ${preview}${payload.length > 200 ? "\u2026" : ""}`
  );
  if (pendingResolve) {
    const resolve = pendingResolve;
    pendingResolve = null;
    resolve(payload);
  } else {
    payloadBuffer.push(payload);
  }
}

function waitForWebhook(timeoutMs: number): Promise<string | null> {
  return new Promise((resolve) => {
    const buffered = payloadBuffer.shift();
    if (buffered !== undefined) {
      resolve(buffered);
      return;
    }
    const timer = setTimeout(() => {
      pendingResolve = null;
      resolve(null);
    }, timeoutMs);
    pendingResolve = (payload) => {
      clearTimeout(timer);
      resolve(payload);
    };
  });
}

// ---------------------------------------------------------------------------
// HTTP server
// ---------------------------------------------------------------------------
function startServer(): Promise<void> {
  return new Promise((resolve) => {
    const server = http.createServer((req, res) => {
      console.log(
        `→ ${req.method} ${req.url} (Content-Type: ${req.headers["content-type"]}, User-Agent: ${req.headers["user-agent"]})`
      );

      if (req.method === "POST" && req.url === "/webhook") {
        let body = "";
        req.on("data", (chunk: Buffer) => { body += chunk.toString(); });
        req.on("end", () => {
          enqueue(body);
          res.writeHead(200);
          res.end();
        });
      } else if (req.method === "GET") {
        res.writeHead(200, { "Content-Type": "text/plain" });
        res.end("Payabli Webhook Test");
      } else {
        res.writeHead(404);
        res.end();
      }
    });

    server.listen(PORT, "0.0.0.0", () => {
      console.log(`\nWebhook server listening on http://localhost:${PORT}/webhook`);
      resolve();
    });
  });
}

// ---------------------------------------------------------------------------
// readline helpers
// ---------------------------------------------------------------------------
const rl = readline.createInterface({ input: process.stdin, output: process.stdout });

function prompt(question: string): Promise<string> {
  return new Promise((resolve) => rl.question(question, resolve));
}

// ---------------------------------------------------------------------------
// Tunnel test
// ---------------------------------------------------------------------------
async function testNgrokTunnel(webhookUrl: string): Promise<void> {
  console.log(`\nTesting ngrok tunnel by POSTing to ${webhookUrl}...`);
  try {
    const res = await fetch(webhookUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "ngrok-skip-browser-warning": "1",
      },
      body: JSON.stringify({ test: "ping" }),
    });
    console.log(`Tunnel test response: HTTP ${res.status}`);
  } catch (err) {
    console.error(`Tunnel test FAILED – ngrok may not be running or URL is wrong: ${err}`);
  }
}

// ---------------------------------------------------------------------------
// Register ApprovedPayment notification
// ---------------------------------------------------------------------------
// We POST directly and keep ownerId as a number (the SDK types it as string
// which would produce "236" in JSON, causing the notification to target the
// wrong owner or silently fail).
// ---------------------------------------------------------------------------
async function createWebhookNotification(targetBase: string): Promise<void> {
  const webhookUrl = targetBase + "/webhook";
  console.log("\nRegistering webhook notification with Payabli...");
  console.log(`Notification request: Target=${webhookUrl}, OwnerId=${OWNER_ID}`);

  const body = {
    content: { eventType: "ApprovedPayment" },
    frequency: "untilcancelled",
    method: "web",
    ownerId: OWNER_ID,  // integer – critical
    ownerType: 0,
    status: 1,
    target: webhookUrl,
  };

  console.log(`Notification request body: ${JSON.stringify(body)}`);

  try {
    const res = await fetch("https://api-sandbox.payabli.com/api/Notification", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        requestToken: API_KEY,
      },
      body: JSON.stringify(body),
    });

    const text = await res.text();
    console.log(`Notification response: HTTP ${res.status}`);
    console.log(`Notification raw response: ${text}`);

    try {
      const json = JSON.parse(text) as Record<string, unknown>;
      const isSuccess = Boolean(json["isSuccess"]);
      console.log(
        `Webhook registered: IsSuccess=${isSuccess}, ResponseCode=${json["responseCode"] ?? ""}, NotificationId=${json["responseData"] ?? ""}`
      );
      if (!isSuccess) {
        console.error(`WARNING: Notification registration failed – ResponseText: ${json["responseText"] ?? "(none)"}`);
        console.error("No webhook will be delivered. Check your PAYABLI_KEY, OWNER_ID, and PAYABLI_ENTRY.");
      }
    } catch {
      // JSON parse failed – raw response already printed above
    }
  } catch (err) {
    console.error(`Failed to register webhook: ${err}`);
  }
}

// ---------------------------------------------------------------------------
// Trigger test transaction via SDK
// ---------------------------------------------------------------------------
async function triggerTransaction(): Promise<void> {
  console.log("\nTriggering a test transaction to generate webhook...");
  console.log(`Transaction request: EntryPoint=${ENTRY}, Amount=1.00`);

  const client = new PayabliClient({ apiKey: API_KEY });

  try {
    const res = await client.moneyIn.getpaid({
      body: {
        customerData: { customerId: 4440 },
        entryPoint: ENTRY,
        ipaddress: "255.255.255.255",
        paymentDetails: { totalAmount: 1.00, serviceFee: 0 },
        paymentMethod: {
          cardexp:    "02/27",
          cardnumber: "4111111111111111",
          cardcvv:    "999",
          cardHolder: "Test User",
          cardzip:    "12345",
          initiator:  "payor",
          method:     "card",
        },
      },
    });
    console.log(`Transaction response: ${JSON.stringify(res)}`);
  } catch (err) {
    console.error(`Transaction failed: ${err}`);
  }
}

// ---------------------------------------------------------------------------
// Self-test: POST directly to localhost /webhook
// ---------------------------------------------------------------------------
async function selfTest(): Promise<void> {
  console.log("\n[self-test] POSTing directly to localhost to verify queue...");
  try {
    const res = await fetch(`http://localhost:${PORT}/webhook`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ "self-test": true }),
    });
    console.log(`[self-test] POST to localhost /webhook: HTTP ${res.status}`);
  } catch (err) {
    console.error(`[self-test] FAILED: ${err}`);
  }
}

// ---------------------------------------------------------------------------
// Main
// ---------------------------------------------------------------------------
async function main(): Promise<void> {
  await startServer();

  // ── Startup self-test ──────────────────────────────────────────────────
  // Verify the HTTP server and queue mechanism work before asking for any
  // user input. Fails fast instead of wasting 30+ seconds later.
  console.log("\n[startup-test] Verifying server and queue mechanism...");
  try {
    const r = await fetch(`http://localhost:${PORT}/webhook`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ "startup-test": true }),
    });
    console.log(`[startup-test] POST response: HTTP ${r.status}`);
  } catch (err) {
    console.error(`[startup-test] FAILED to reach own server: ${err}`);
    process.exit(1);
  }
  const startupPayload = await waitForWebhook(2000);
  if (startupPayload === null) {
    console.error("[startup-test] FAILED – server is not delivering payloads to queue. Exiting.");
    process.exit(1);
  }
  console.log("[startup-test] PASSED – server and queue are working.");
  payloadBuffer.length = 0;
  pendingResolve = null;

  console.log(`\nNow open a new terminal and run: ngrok http ${PORT}`);
  const rawUrl = await prompt("Paste your public Ngrok URL (e.g. https://xxxx.ngrok.io): ");
  let baseUrl = rawUrl.trim().replace(/\/$/, "");
  if (baseUrl.endsWith("/webhook")) {
    baseUrl = baseUrl.slice(0, -8);
  }

  await testNgrokTunnel(baseUrl + "/webhook");
  await createWebhookNotification(baseUrl);

  await prompt("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...");

  // Discard any tunnel-test payload that arrived before the transaction.
  payloadBuffer.length = 0;
  pendingResolve = null;

  await triggerTransaction();

  await selfTest();

  // Drain the self-test ping (give it a moment to arrive).
  const selfTestPayload = await waitForWebhook(3000);
  if (selfTestPayload !== null) {
    console.log("[self-test] PASSED – self-test ping received and drained.");
  } else {
    console.warn("[self-test] WARNING: self-test ping was not received within 3 seconds.");
    console.warn("  The server may not be accepting POST requests. Check the logs above.");
  }

  console.log("\nWaiting up to 30 seconds for Payabli webhook delivery...");
  console.log("(Watch for '→ POST /webhook' above – if it never appears, Payabli is not delivering to your ngrok URL)");

  const payload = await waitForWebhook(30_000);
  if (payload === null) {
    console.log("\nNo webhook received within 30 seconds.");
    console.log("Possible causes:");
    console.log("  1. The notification was not registered successfully – check 'Notification raw response' above.");
    console.log("  2. The ngrok URL you pasted already included '/webhook' – target would be '.../webhook/webhook'.");
    console.log("  3. Payabli is delivering to a previously-registered notification's dead URL.");
    console.log("  4. The ngrok tunnel expired before Payabli made the delivery.");
  } else {
    console.log(`\nReceived webhook payload:\n${payload || "(empty body)"}`);

    // Keep printing any further deliveries.
    let next: string | null;
    while ((next = await waitForWebhook(60_000)) !== null) {
      console.log(`\nReceived webhook payload:\n${next}`);
    }
  }

  rl.close();
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
