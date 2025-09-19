# Payabli PHP SDK Example

A basic example application using the Payabli PHP SDK to perform customer operations and payment method tokenization.
See [`payabli/payabli`](https://packagist.org/packages/payabli/payabli) for more information.

## Summary

The application has two pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.
3. **Make Transaction** - this page has an embedded component to save payment methods securely and send them to the API to process transactions.

## Setup Instructions

### Requirements

- PHP 8.1 or higher
- Composer

### Installation

1. Clone this repo.

```bash
git clone https://github.com/payabli/examples
```

2. Navigate to the project directory.

```bash
cd examples/php-sdk
```

3. Install dependencies.

```bash
composer install
```

4. Copy the `.env.template` file to `.env` and fill in the required values.

```bash
cp .env.template .env
```

5. Start the development server.

```bash
composer run serve
```
