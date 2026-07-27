# Payabli Webhook Example — Go SDK

Demonstrates the full Payabli webhook quickstart flow using the [Payabli Go SDK](https://github.com/payabli/sdk-go).

This sample uses the GetPaid v2 transaction endpoint via `MoneyIn.Getpaidv2(...)`.

## What it does

1. Starts a local HTTP server to receive webhook POSTs at `/webhook`
2. Prompts you to expose it publicly via `ngrok`, `localhost.run`, or another local environment forwarder
3. POSTs a test ping to verify the tunnel is live
4. Registers an `ApprovedPayment` webhook notification with Payabli, targeting your tunnel URL
5. Waits for you to press Enter, then fires a test $1.00 credit card transaction
6. Prints any incoming webhook payloads to the terminal and returns `200 OK`

## Prerequisites

- Go 1.22+
- A Payabli OAuth2 client ID and client secret ([Payabli Portal](https://docs.payabli.com))
- A tunnel tool such as [`ngrok`](https://ngrok.com/), [`localhost.run`](https://localhost.run/), or another local environment forwarder
- This example requires `github.com/payabli/sdk-go` v1.0.12 or later, which adds OAuth2 client-credentials support (`option.WithClientID` / `option.WithClientSecret`).

## Setup

```bash
cp .env.example .env
# Fill in PAYABLI_CLIENT_ID, PAYABLI_CLIENT_SECRET, PAYABLI_ENTRY, and OWNER_ID in .env
```

## Run

```bash
go mod tidy
go run main.go
```

When prompted, paste the public HTTPS URL from your tunnel tool.
