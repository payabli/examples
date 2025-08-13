# Payabli C# SDK Example

A basic example application using the Payabli C# SDK to perform customer operations.
See the [Payabli C# SDK](https://github.com/payabli/sdk-csharp) for more information.

## Summary

The application has two pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.

## Setup Instructions

### Option 1: Quick Start (Recommended)

**Windows:**
```cmd
start.bat
```

**macOS/Linux:**
```bash
chmod +x start.sh
./start.sh
```

The start scripts will automatically:
- Check for .NET 9 SDK
- Create `.env` file from template if needed
- Prompt you to add your Payabli credentials
- Start the development server

### Option 2: Manual Setup

1. Navigate to the project directory.

```bash
cd cs-sdk
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

## Prerequisites

- [.NET 9 SDK](https://dotnet.microsoft.com/download/dotnet/9.0) or later
- Valid Payabli API credentials (get them from [Partner Hub](https://partnerhub.payabli.com))
