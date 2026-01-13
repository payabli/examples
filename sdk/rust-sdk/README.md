A basic example application using the Payabli Rust SDK to perform customer operations and payment method tokenization.
See the [Payabli Rust SDK](https://github.com/payabli/sdk-rust) for more information.

## Summary

The application has three pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.
3. **Make Transaction** - this page has an embedded component to save payment methods securely and send them to the API to process transactions.

## Setup Instructions

1. Clone this repo and navigate to the project directory:

```bash
git clone https://github.com/payabli/examples
cd examples/sdk/rust-sdk
```

2. Copy the `.env.template` file to `.env` and fill in the required values:

```bash
cp .env.template .env
```

Edit the `.env` file:
```
PAYABLI_ENTRY="your_paypoint_entry_id"
PAYABLI_KEY="your_api_key"
PAYABLI_PUBLIC_TOKEN="your_public_token"
```

3. Build and run the development server:

```bash
cargo run
```

The server will start at `http://localhost:3000`.
