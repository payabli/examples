# Payabli Webhook Example — C# SDK

Demonstrates the full Payabli webhook quickstart flow using the [Payabli C# SDK](https://www.nuget.org/packages/PayabliApi).

## What it does

1. Starts a local HTTP server to receive webhook POSTs at `/webhook`
2. Prompts you to expose it publicly with [ngrok](https://ngrok.com)
3. POSTs a test ping to verify the ngrok tunnel is live
4. Registers an `ApprovedPayment` webhook notification with Payabli, targeting your ngrok URL
5. Waits for you to press Enter, then fires a test $1.00 credit card transaction
6. Prints any incoming webhook payloads to the terminal and returns `200 OK`
7. Polls Payabli's notification delivery logs every 15 seconds so you can see if Payabli is attempting delivery and whether it succeeded

## Prerequisites

- [.NET 9 SDK](https://dotnet.microsoft.com/download)
- A [Payabli API key](https://docs.payabli.com)
- [ngrok](https://ngrok.com/download) installed and authenticated

## Setup

```bash
cp .env.example .env
# Fill in PAYABLI_KEY, PAYABLI_ENTRY, and OWNER_ID in .env
```

## Run

```bash
dotnet run
```

In a separate terminal, expose the local server:

```bash
ngrok http 3000
```

Paste the ngrok HTTPS URL when prompted.
