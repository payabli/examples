#!/bin/bash
set -e

echo "🚀 Starting Payabli PHP SDK Example..."

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.1 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
REQUIRED_VERSION="8.1"

if [[ "$(printf '%s\n' "$REQUIRED_VERSION" "$PHP_VERSION" | sort -V | head -n1)" != "$REQUIRED_VERSION" ]]; then
    echo "❌ PHP $REQUIRED_VERSION or higher is required. Current version: $PHP_VERSION"
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    echo "   Visit: https://getcomposer.org/download/"
    exit 1
fi

# Install dependencies
echo "📦 Installing dependencies..."
composer install

# Check if .env file exists
if [ ! -f .env ]; then
    echo "⚙️  Creating .env file from template..."
    cp .env.template .env
    echo "📝 Please edit .env file with your Payabli API credentials before continuing."
    echo "   PAYABLI_KEY=your_api_key_here"
    echo "   PAYABLI_ENTRY=your_entry_point_here"
    echo ""
    read -p "Press Enter when you have configured your .env file..."
fi

# Start the server
echo "🌐 Starting server at http://localhost:8000"
echo "   Press Ctrl+C to stop the server"
echo ""
composer run serve
