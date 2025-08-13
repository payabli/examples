#!/bin/bash
echo "Starting Payabli C# SDK Example..."
echo ""

# Check if .env file exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found!"
    echo "Creating .env file from template..."
    cp .env.template .env
    echo ""
    echo "📝 Please edit .env file with your actual Payabli credentials:"
    echo "   PAYABLI_KEY=your_api_key_here"
    echo "   PAYABLI_ENTRY=your_entry_point_here"
    echo ""
    exit 1
fi

# Check if dotnet is installed
if ! command -v dotnet &> /dev/null; then
    echo "❌ .NET SDK not found!"
    echo "Please install .NET 8 SDK from https://dotnet.microsoft.com/download"
    exit 1
fi

echo "🚀 Starting the application..."
echo "The app will be available at: http://localhost:5000"
echo "Press Ctrl+C to stop"
echo ""

dotnet run
