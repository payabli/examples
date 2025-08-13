A basic example application using the Payabli Go SDK to perform customer operations.
See the [Payabli Go SDK](https://github.com/payabli/sdk-go) for more information.

## Summary

The application has two pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.

## Setup Instructions

### Option 1: Quick Start (Unix/Linux/macOS)

1. Clone this repo and navigate to the project directory:

```bash
git clone https://github.com/payabli/examples
cd examples/sdk/go-sdk
```

2. Run the startup script:

```bash
chmod +x start.sh
./start.sh
```

The script will:
- Install Go dependencies
- Create a .env file from the template
- Start the development server

### Option 2: Quick Start (Windows)

1. Clone this repo and navigate to the project directory:

```cmd
git clone https://github.com/payabli/examples
cd examples/sdk/go-sdk
```

2. Run the startup script:

```cmd
start.bat
```

### Option 3: Manual Setup

1. Clone this repo and navigate to the project directory:

```bash
git clone https://github.com/payabli/examples
cd examples/sdk/go-sdk
```

2. Install Go dependencies:

```bash
go mod tidy
```

3. Copy the `.env.template` file to `.env` and fill in the required values:

```bash
cp .env.template .env
```

Edit the `.env` file:
```
PAYABLI_ENTRY="your_paypoint_entry_id"
PAYABLI_KEY="your_api_key"
```

4. Start the development server:

```bash
go run main.go
```

### Option 4: Using Make (if available)

```bash
make deps && make run
```

### Option 5: Testing Setup

To verify everything is working before starting:

```bash
# Unix/Linux/macOS
chmod +x test.sh
./test.sh

# Windows
test.bat
```

