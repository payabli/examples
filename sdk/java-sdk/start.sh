#!/bin/bash

echo "🚀 Starting Payabli Java SDK Example..."

# Check if .env file exists, if not create it from template
if [ ! -f .env ]; then
    echo "📋 Creating .env file from template..."
    cp .env.template .env
    echo "✅ .env file created. Please edit it with your actual Payabli credentials."
    echo "📝 Edit .env file with your PAYABLI_ENTRY and PAYABLI_KEY values before continuing."
    read -p "Press Enter after editing .env file..."
fi

# Load environment variables from .env file
if [ -f .env ]; then
    echo "🔧 Loading environment variables..."
    export $(grep -v '^#' .env | xargs)
fi

# Check if Java is installed
if ! command -v java &> /dev/null; then
    echo "❌ Java is not installed. Please install Java 11 or higher."
    exit 1
fi

# Check if Maven is installed
if ! command -v mvn &> /dev/null; then
    echo "❌ Maven is not installed. Please install Maven."
    exit 1
fi

echo "🔧 Building and installing Payabli SDK..."
chmod +x install-sdk.sh
./install-sdk.sh

if [ $? -ne 0 ]; then
    echo "❌ SDK installation failed"
    exit 1
fi

echo "🔧 Installing dependencies..."
mvn clean install -q

if [ $? -ne 0 ]; then
    echo "❌ Maven build failed"
    exit 1
fi

echo "🏃‍♂️ Starting the application..."
java -cp "target/classes:lib/api-sdk.jar:$(mvn dependency:build-classpath -q -Dmdep.outputFile=/dev/stdout)" com.payabli.example.PayabliExampleApp &

APP_PID=$!
sleep 2

# Check if the application started successfully
if ps -p $APP_PID > /dev/null; then
    echo "🎉 Application started successfully! Visit http://localhost:8000"
    echo "Press Ctrl+C to stop the application"
    wait $APP_PID
else
    echo "❌ Application failed to start"
    exit 1
fi
