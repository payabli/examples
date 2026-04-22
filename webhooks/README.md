# Webhook Examples

These examples show how to stand up a local webhook receiver with a Payabli SDK in each supported language and validate the full notification flow end to end.

Each example is meant to demonstrate the same core webhook workflow:

1. Start a local server with a `/webhook` endpoint.
2. Expose that local endpoint through a public tunnel such as `ngrok`.
3. Register an `ApprovedPayment` webhook notification in Payabli.
4. Fire a small test transaction.
5. Receive the webhook payload locally and print it for inspection.

## Example directory

| Language | Example |
| --- | --- |
| C# | [cs-sdk](./cs-sdk/README.md) |
| Go | [go-sdk](./go-sdk/README.md) |
| Java | [java-sdk](./java-sdk/README.md) |
| PHP | [php-sdk](./php-sdk/README.md) |
| Python | [py-sdk](./py-sdk/README.md) |
| Ruby | [ruby-sdk](./ruby-sdk/README.md) |
| Rust | [rust-sdk](./rust-sdk/README.md) |
| TypeScript | [ts-sdk](./ts-sdk/README.md) |
