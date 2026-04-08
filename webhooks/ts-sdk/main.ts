
import * as http from "http";
import * as readline from "readline";
import * as dotenv from "dotenv";
import { PayabliClient } from "@payabli/sdk-node";

dotenv.config();

const API_KEY = process.env.PAYABLI_KEY ?? "";
const ENTRY = process.env.PAYABLI_ENTRY ?? "";
const OWNER_ID = parseInt(process.env.OWNER_ID ?? "0", 10);
const PORT = parseInt(process.env.PORT ?? "3000", 10);

if (!API_KEY) { console.error("PAYABLI_KEY missing from .env"); process.exit(1); }
if (!ENTRY)   { console.error("PAYABLI_ENTRY missing from .env"); process.exit(1); }
if (!OWNER_ID){ console.error("OWNER_ID missing from .env");  process.exit(1); }

const payloadBuffer: string[] = [];
let pendingResolve: ((payload: string) => void) | null = null;

function enqueue(payload: string): void {
  console.log(`[webhook handler] received ${payload.length} bytes, pushing to queue`);
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

const rl = readline.createInterface({ input: process.stdin, output: process.stdout });

function prompt(question: string): Promise<string> {
  return new Promise((resolve) => rl.question(question, resolve));
}

async function testTunnel(webhookUrl: string): Promise<void> {
  console.log(`\nTesting tunnel by POSTing to ${webhookUrl}...`);
  try {
    const res = await fetch(webhookUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ test: "ping" }),
    });
    console.log(`Tunnel test response: HTTP ${res.status}`);
  } catch (err) {
    console.error(`Tunnel test FAILED – tunnel may not be running or URL is wrong: ${err}`);
  }
}

// We POST directly and keep ownerId as a number (the SDK types it as string
// which would produce "236" in JSON, but the Payabli API requires an integer).
async function createWebhookNotification(targetBase: string): Promise<void> {
  const webhookUrl = targetBase + "/webhook";
  console.log("\nRegistering webhook notification with Payabli...");
  console.log(`Notification request: Target=${webhookUrl}, OwnerId=${OWNER_ID}`);

  const body = {
    content: { eventType: "ApprovedPayment" },
    frequency: "untilcancelled",
    method: "web",
    ownerId: OWNER_ID,
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

async function main(): Promise<void> {
  await startServer();

  console.log(`\nExpose your local server publicly (e.g. ngrok http ${PORT}, localhost.run, etc.)`);
  const rawUrl = await prompt("Paste your public tunnel URL (e.g. https://xxxx.ngrok-free.app): ");
  let baseUrl = rawUrl.trim().replace(/\/$/, "");
  if (baseUrl.endsWith("/webhook")) {
    baseUrl = baseUrl.slice(0, -8);
  }

  await testTunnel(baseUrl + "/webhook");
  await createWebhookNotification(baseUrl);

  await prompt("\nPress ENTER to trigger a test transaction and generate a webhook (or Ctrl+C to exit)...");

  // Discard any tunnel-test payload that arrived before the transaction.
  payloadBuffer.length = 0;

  await triggerTransaction();

  await selfTest();

  // Drain the self-test ping (give it a moment to arrive).
  const selfTestPayload = await waitForWebhook(3000);
  if (selfTestPayload !== null) {
    console.log("[self-test] self-test ping drained from queue.");
  }

  console.log("\nWaiting up to 30 seconds for Payabli webhook delivery...");
  console.log("(Watch for '→ POST /webhook' above – if it never appears, Payabli is not delivering to your tunnel URL)");

  const payload = await waitForWebhook(30_000);
  if (payload === null) {
    console.log("\nNo webhook received within 30 seconds.");
    console.log("Possible causes:");
    console.log("  1. The notification was not registered successfully – check 'Notification raw response' above.");
    console.log("  2. The ngrok URL you pasted already included '/webhook' – target would be '.../webhook/webhook'.");
    console.log("  3. Payabli is delivering to a previously-registered notification's dead URL.");
    console.log("  4. The tunnel expired before Payabli made the delivery.");
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
