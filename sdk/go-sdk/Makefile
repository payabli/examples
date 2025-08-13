# Makefile for Payabli Go SDK Example

.PHONY: help build run test clean deps dev

# Default target
help:
	@echo "Payabli Go SDK Example - Available commands:"
	@echo ""
	@echo "  make run     - Start the development server"
	@echo "  make build   - Build the application binary"
	@echo "  make test    - Run tests and verify setup"
	@echo "  make deps    - Install/update dependencies"
	@echo "  make clean   - Clean build artifacts"
	@echo "  make dev     - Start with auto-reload (requires air)"
	@echo ""
	@echo "Quick start: make deps && make run"

# Install dependencies
deps:
	@echo "📦 Installing dependencies..."
	go mod tidy
	go mod download

# Build the application
build:
	@echo "🔨 Building application..."
	go build -o payabli-example main.go

# Run the development server
run: deps
	@echo "🌟 Starting development server on http://localhost:8080..."
	@echo "Press Ctrl+C to stop"
	go run main.go

# Run tests
test:
	@echo "🧪 Running tests..."
	go mod tidy
	go build -o test-build main.go
	@rm -f test-build
	@echo "✅ All tests passed!"

# Clean build artifacts
clean:
	@echo "🧹 Cleaning build artifacts..."
	@rm -f payabli-example test-build
	@echo "✅ Clean complete"

# Development with auto-reload (requires air: go install github.com/cosmtrek/air@latest)
dev:
	@which air > /dev/null || (echo "❌ Air not found. Install with: go install github.com/cosmtrek/air@latest" && exit 1)
	@echo "🔄 Starting development server with auto-reload..."
	air

# Setup .env file
setup-env:
	@if [ ! -f .env ]; then \
		echo "📝 Creating .env file from template..."; \
		cp .env.template .env; \
		echo "⚠️  Please edit .env file with your Payabli API credentials"; \
	else \
		echo "✅ .env file already exists"; \
	fi
