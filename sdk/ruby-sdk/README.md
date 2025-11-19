# Payabli Ruby SDK Example

Small Sinatra example that demonstrates basic usage of the local Payabli Ruby SDK (not published).

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

## Notes
- This example references the local SDK via the Gemfile path. Do not change to a remote gem until the SDK is published.
- If you get authorization errors, make sure the `PAYABLI_ENTRY` value matches the entrypoint for your API key.
