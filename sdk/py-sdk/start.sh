#!/bin/bash
# Simple startup script for the Payabli Python SDK example

echo "Starting Payabli Python SDK Example..."

# Check if virtual environment exists, create if not
if [ ! -d "venv" ]; then
    echo "Creating virtual environment..."
    python3 -m venv venv
fi

# Activate virtual environment
source venv/bin/activate

# Install dependencies
echo "Installing dependencies..."
pip install -r requirements.txt

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo "Creating .env file from template..."
    cp .env.template .env
    echo "Please edit .env file with your Payabli credentials before running the app."
    exit 1
fi

# Start the application
echo "Starting FastAPI server..."
uvicorn main:app --reload --host 0.0.0.0 --port 8000
