# Payabli Ruby SDK Example

Small Sinatra example that demonstrates basic usage of the Payabli Ruby SDK for customer operations, payment method tokenization, and v2 Money In transactions.

## Requirements
- Ruby >= 3.3.0 (SDK requires 3.3+)
- Bundler

## Setup

```bash
cd examples/sdk/ruby-sdk
cp .env.template .env
# Edit .env to add your PAYABLI_KEY and PAYABLI_ENTRY
bundle install
```

## Run

```bash
bundle exec ruby app.rb
```

Then open http://localhost:4567

## Pages
- Create Customer
- List Customers
- Make Transaction using the v2 `MoneyIn/getpaid` endpoint

## Notes
- If you get authorization errors, make sure the `PAYABLI_ENTRY` value matches the entrypoint for your API key.
