#!/bin/bash

echo "🚀 Starting Payabli Go SDK Example App..."

# Check if Go is installed
if ! command -v go &> /dev/null; then
    echo "❌ Go is not installed. Please install Go 1.21 or higher."
    echo "   Visit https://golang.org/dl/ to download Go."
    exit 1
fi

# Check Go version
GO_VERSION=$(go version | cut -d ' ' -f 3)
echo "✅ Found Go: $GO_VERSION"

# Install dependencies
echo "📦 Installing dependencies..."
go mod tidy

if [ $? -ne 0 ]; then
    echo "❌ Failed to install dependencies"
    exit 1
fi

# Create .env file if it doesn't exist
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file from template..."
    cp .env.template .env
    echo "⚠️  Please edit .env file with your Payabli API credentials before running the app."
    echo "   You need to set:"
    echo "   - PAYABLI_KEY: Your API key"
    echo "   - PAYABLI_ENTRY: Your paypoint entry ID"
    echo ""
    read -p "Press Enter once you've updated the .env file..."
fi

# Start the server
echo "🌟 Starting server on http://localhost:8080..."
echo "   Press Ctrl+C to stop the server"
echo ""

go run main.go
