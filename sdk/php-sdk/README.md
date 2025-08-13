# Payabli PHP SDK Example

A basic example application using the Payabli PHP SDK to perform customer operations.
See [`payabli/payabli`](https://packagist.org/packages/payabli/payabli) for more information.

## Summary

The application has two pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.

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

### Alternative Setup Methods

#### Using PHP's Built-in Server Directly

```bash
php -S localhost:8000
```

#### Using Apache/Nginx

Point your web server's document root to this directory and configure it to handle PHP files.

### Manual Setup

If you prefer not to use Composer scripts:

1. Install dependencies: `composer install`
2. Copy environment file: `cp .env.template .env`
3. Edit `.env` with your Payabli credentials
4. Start server: `php -S localhost:8000`
5. Visit `http://localhost:8000`

## Configuration

Set your Payabli API credentials in the `.env` file:

```bash
PAYABLI_KEY=your_api_key_here
PAYABLI_ENTRY=your_entry_point_here
```

## API Endpoints

- `GET /` - Customer creation form
- `GET /list` - Customer list page
- `POST /api/create` - Create new customer
- `GET /api/list` - Get customers as HTML table
- `DELETE /api/delete/{id}` - Delete customer by ID

## Features

- ✅ Customer creation with form validation
- ✅ Customer listing with dynamic table
- ✅ Customer deletion with confirmation
- ✅ HTMX-powered interactions
- ✅ Pico CSS styling
- ✅ Error handling and user feedback

## Troubleshooting

### Common Issues

1. **"Class not found" errors**: Run `composer install` to install dependencies
2. **Environment variables not loaded**: Ensure `.env` file exists and is readable
3. **API errors**: Verify your API key and entry point in `.env`
4. **Permission errors**: Ensure PHP can read the project directory

### Development

For development with hot reload, you can use tools like:
- [PHP Server Monitor](https://github.com/php-pm/php-pm)
- File watchers with automatic restart

### Production Deployment

For production use:
1. Use a proper web server (Apache, Nginx)
2. Set appropriate file permissions
3. Use environment variables instead of `.env` files
4. Enable error logging
5. Configure HTTPS
