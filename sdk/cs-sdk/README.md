# Payabli C# SDK Example

A basic example application using the Payabli C# SDK to perform customer operations and payment method tokenization.
See the [Payabli C# SDK](https://github.com/payabli/sdk-csharp) for more information.

## Summary

The application has two pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.
3. **Make Transaction** - this page has an embedded component to save payment methods securely and send them to the API to process transactions.

## Setup Instructions

1. Clone this repo and navigate to the project directory:

```bash
git clone https://github.com/payabli/examples
cd examples/sdk/py-sdk
```

2. Copy the `.env.template` file to `.env` and fill in the required values.

```bash
cp .env.template .env
```

3. Restore dependencies.

```bash
dotnet restore
```

4. Start the development server.

```bash
dotnet run
```
