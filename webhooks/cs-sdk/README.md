# Payabli Webhook Example — C# SDK

Demonstrates the full Payabli webhook quickstart flow using the [Payabli C# SDK](https://www.nuget.org/packages/PayabliApi).

## What it does

1. Starts a local HTTP server to receive webhook POSTs at `/webhook`
2. Prompts you to expose it publicly via `ngrok`, `localhost.run`, or another local environment forwarder
3. POSTs a test ping to verify the tunnel is live
4. Registers an `ApprovedPayment` webhook notification with Payabli, targeting your tunnel URL
5. Waits for you to press Enter, then fires a test $1.00 credit card transaction
6. Prints any incoming webhook payloads to the terminal and returns `200 OK`

## Prerequisites

- [.NET 9 SDK](https://dotnet.microsoft.com/download)
- A [Payabli API key](https://docs.payabli.com)
- A tunnel tool such as [`ngrok`](https://ngrok.com/), [`localhost.run`](https://localhost.run/), or another local environment forwarder

## Setup

```bash
cp .env.example .env
# Fill in PAYABLI_KEY, PAYABLI_ENTRY, and OWNER_ID in .env
```

## Run

```bash
dotnet run
```

When prompted, paste the public HTTPS URL from your tunnel tool.
