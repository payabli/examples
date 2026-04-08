# Payabli Webhook Example — Go SDK

Demonstrates the full Payabli webhook quickstart flow using the [Payabli Go SDK](https://github.com/payabli/sdk-go).

## What it does

1. Starts a local HTTP server to receive webhook POSTs at `/webhook`
2. Prompts you to expose it publicly with [ngrok](https://ngrok.com)
3. POSTs a test ping to verify the ngrok tunnel is live
4. Registers an `ApprovedPayment` webhook notification with Payabli, targeting your ngrok URL
5. Waits for you to press Enter, then fires a test $1.00 credit card transaction
6. Prints any incoming webhook payloads to the terminal and returns `200 OK`

## Prerequisites

- Go 1.22+
- A [Payabli API key](https://docs.payabli.com)
- [ngrok](https://ngrok.com/download) installed and authenticated

## Setup

```bash
cp .env.example .env
# Fill in PAYABLI_KEY, PAYABLI_ENTRY, and OWNER_ID in .env
```

## Run

```bash
go mod tidy
go run main.go
```

In a separate terminal, expose the local server:

```bash
ngrok http 3000
```

Paste the ngrok HTTPS URL when prompted.
