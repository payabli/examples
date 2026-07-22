import { createServer } from "node:http";
import { existsSync, readFileSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const serverDir = dirname(fileURLToPath(import.meta.url));
loadEnv(join(serverDir, ".env"));

const port = Number.parseInt(process.env.PORT || "8787", 10);
const bindHost = process.env.PAYABLI_LOCAL_TOKEN_SERVER_HOST || "127.0.0.1";
const allowedApiHosts = new Set(
  (process.env.PAYABLI_ALLOWED_API_HOSTS || "api-sandbox.payabli.com,api-qa.payabli.com,api.payabli.com")
    .split(",")
    .map((host) => host.trim().toLowerCase())
    .filter(Boolean)
);

let cachedToken = null; // { token, expiresAt }

const server = createServer((req, res) => {
  handleRequest(req, res).catch((error) => {
    console.error(redact(error.stack || error.message));
    sendJson(res, 500, { error: "Local token server failed", detail: redact(error.message) });
  });
});

async function handleRequest(req, res) {
  const url = new URL(req.url || "/", `http://${req.headers.host || "localhost"}`);

  if (url.pathname === "/health" && req.method === "GET") {
    sendJson(res, 200, { ok: true });
    return;
  }

  if (url.pathname === "/payabli/access-token" && req.method === "GET") {
    sendJson(res, 200, { accessToken: await resolveAccessToken() });
    return;
  }

  sendJson(res, 404, { error: "Not found" });
}

server.listen(port, bindHost, () => {
  console.log(`Payabli local token server listening on http://${bindHost}:${port}`);
  console.log(`Access token endpoint: http://${bindHost}:${port}/payabli/access-token`);
});

// The iOS app calls this endpoint to get a short-lived Payabli access token
// for both PayabliTTP and PayabliPayInPaymentFlow. In a real integration,
// this exchange happens on your own backend, authenticated by your own
// session mechanism — never in the mobile app itself.
async function resolveAccessToken() {
  const directToken = process.env.PAYABLI_ACCESS_TOKEN?.trim();
  if (directToken) {
    return directToken;
  }

  if (cachedToken && cachedToken.expiresAt > Date.now()) {
    return cachedToken.token;
  }

  const clientId = process.env.CLIENT_ID?.trim();
  const clientSecret = process.env.CLIENT_SECRET?.trim();
  if (!clientId || !clientSecret) {
    throw new Error("Set PAYABLI_ACCESS_TOKEN, or CLIENT_ID and CLIENT_SECRET, in .env.");
  }

  const apiBaseUrl = normalizeBaseUrl(process.env.PAYABLI_API_BASE_URL || "https://api-sandbox.payabli.com/api");
  if (!allowedApiHosts.has(apiBaseUrl.hostname.toLowerCase())) {
    throw new Error(`PAYABLI_API_BASE_URL host is not allowed: ${apiBaseUrl.hostname}`);
  }

  const endpoint = new URL("v2/token/serverside", apiBaseUrl);
  const upstream = await fetch(endpoint, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ clientId, clientSecret })
  });

  const text = await upstream.text();
  const payload = text ? JSON.parse(text) : {};
  if (!upstream.ok) {
    throw new Error(`Token exchange failed with HTTP ${upstream.status}: ${redact(JSON.stringify(payload))}`);
  }

  const token = payload.access_token || payload.accessToken;
  if (!token) {
    throw new Error("Token exchange response did not include an access token.");
  }

  cachedToken = { token, expiresAt: Date.now() + 5 * 60 * 1000 };
  return token;
}

function sendJson(res, status, body) {
  res.writeHead(status, { "Content-Type": "application/json; charset=utf-8", "Cache-Control": "no-store" });
  res.end(JSON.stringify(body));
}

function normalizeBaseUrl(raw) {
  const normalized = /^https?:\/\//i.test(raw) ? raw : `https://${raw}`;
  return new URL(normalized.endsWith("/") ? normalized : `${normalized}/`);
}

function loadEnv(path) {
  if (!existsSync(path)) return;
  for (const rawLine of readFileSync(path, "utf8").split(/\r?\n/)) {
    const line = rawLine.trim();
    if (!line || line.startsWith("#")) continue;
    const separatorIndex = line.indexOf("=");
    if (separatorIndex === -1) continue;
    const key = line.slice(0, separatorIndex).trim();
    let value = line.slice(separatorIndex + 1).trim();
    if ((value.startsWith('"') && value.endsWith('"')) || (value.startsWith("'") && value.endsWith("'"))) {
      value = value.slice(1, -1);
    }
    if (key && process.env[key] === undefined) {
      process.env[key] = value;
    }
  }
}

function redact(text) {
  return String(text).replace(
    /("(?:access_token|accessToken|clientSecret|client_secret)"\s*:\s*)"[^"]*"/gi,
    '$1"[REDACTED]"'
  );
}
