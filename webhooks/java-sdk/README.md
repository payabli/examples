# Payabli Webhook Example — Java SDK

Demonstrates the full Payabli webhook quickstart flow using the [Payabli Java SDK](https://central.sonatype.com/artifact/io.github.payabli/sdk-java).

## What it does

1. Starts a local Javalin server to receive webhook POSTs at `/webhook`
2. Prompts you to expose it publicly with [ngrok](https://ngrok.com)
3. POSTs a test ping to verify the ngrok tunnel is live
4. Registers an `ApprovedPayment` webhook notification with Payabli, targeting your ngrok URL
5. Waits for you to press Enter, then fires a test $1.00 credit card transaction
6. Prints any incoming webhook payloads to the terminal and returns `200 OK`

## Prerequisites

- Java 11+
- Maven 3.6+
- A [Payabli API key](https://docs.payabli.com)
- [ngrok](https://ngrok.com/download) installed and authenticated

## Setup

```bash
cp .env.example .env
# Fill in PAYABLI_KEY, PAYABLI_ENTRY, and OWNER_ID in .env
```

## Run

```bash
mvn exec:java
```

In a separate terminal, expose the local server:

```bash
ngrok http 3000
```

Paste the ngrok HTTPS URL when prompted.

## Build a standalone JAR

```bash
mvn package
java -jar target/webhook-example-1.0.0.jar
```
