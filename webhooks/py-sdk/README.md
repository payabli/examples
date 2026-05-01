# Payabli Webhook Example — Python SDK

Demonstrates the full Payabli webhook quickstart flow using the [Payabli Python SDK](https://pypi.org/project/payabli/).

This sample uses the GetPaid v2 transaction endpoint via `money_in.getpaidv_2(...)`.

## What it does

1. Starts a local Flask server to receive webhook POSTs at `/webhook`
2. Prompts you to expose it publicly via `ngrok`, `localhost.run`, or another local environment forwarder
3. POSTs a test ping to verify the tunnel is live
4. Registers an `ApprovedPayment` webhook notification with Payabli, targeting your tunnel URL
5. Waits for you to press Enter, then fires a test $1.00 credit card transaction
6. Prints any incoming webhook payloads to the terminal and returns `200 OK`

## Prerequisites

- Python 3.9+
- A [Payabli API key](https://docs.payabli.com)
- A tunnel tool such as [`ngrok`](https://ngrok.com/), [`localhost.run`](https://localhost.run/), or another local environment forwarder

## Setup

```bash
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt

cp .env.example .env
# Fill in PAYABLI_KEY, PAYABLI_ENTRY, and OWNER_ID in .env
```

## Run

```bash
python3 main.py
```

When prompted, paste the public HTTPS URL from your tunnel tool.
