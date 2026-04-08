# Payabli Webhook Example – TypeScript (`@payabli/sdk-node`)

End-to-end demonstration of receiving a Payabli `ApprovedPayment` webhook using the official Node.js SDK and a plain Node.js HTTP server.

## Prerequisites

- Node.js ≥ 18 (native `fetch` required; tested on Node 22)
- A tunnel tool such as [`ngrok`](https://ngrok.com/), [`localhost.run`](https://localhost.run/), or another local environment forwarder
- A Payabli sandbox API key, entrypoint token, and owner ID

## Setup

```bash
# 1. Install dependencies
npm install

# 2. Copy and fill in the environment file
cp .env.example .env
# Edit .env with your real credentials
```

### Environment variables

| Variable | Description |
|---|---|
| `PAYABLI_KEY` | Your sandbox API key (`requestToken`) |
| `PAYABLI_ENTRY` | Your entrypoint token (e.g. `f743aed24a`) |
| `OWNER_ID` | Your numeric owner ID (e.g. `236`) |
| `PORT` | Local port for the webhook server (default `3000`) |

## Run

```bash
# Start the example
npm start
```

The program will:

1. Start a webhook server on `http://localhost:PORT/webhook`
2. Ask you to expose it publicly (via `ngrok`, `localhost.run`, or another local environment forwarder) and paste the URL
3. Test that the tunnel is reachable
4. Register an `ApprovedPayment` notification with Payabli pointing at your tunnel endpoint
5. Wait for you to press **ENTER**, then fire a `$1.00` test credit-card transaction
6. Wait up to 30 seconds for Payabli to deliver the webhook and print the payload

## Notes

- The notification is registered via a direct `fetch` call rather than the SDK's `addNotification` method. This is necessary because the SDK types `ownerId` as `string`, which would serialise to `"236"` in JSON. The Payabli API requires an integer (`236`), so we build the request body manually.
