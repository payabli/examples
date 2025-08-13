@echo off
echo 🚀 Starting Payabli Go SDK Example App...

:: Check if Go is installed
go version >nul 2>&1
if errorlevel 1 (
    echo ❌ Go is not installed. Please install Go 1.21 or higher.
    echo    Visit https://golang.org/dl/ to download Go.
    pause
    exit /b 1
)

:: Display Go version
for /f "tokens=3" %%i in ('go version') do set GO_VERSION=%%i
echo ✅ Found Go: %GO_VERSION%

:: Install dependencies
echo 📦 Installing dependencies...
go mod tidy
if errorlevel 1 (
    echo ❌ Failed to install dependencies
    pause
    exit /b 1
)

:: Create .env file if it doesn't exist
if not exist ".env" (
    echo 📝 Creating .env file from template...
    copy ".env.template" ".env"
    echo ⚠️  Please edit .env file with your Payabli API credentials before running the app.
    echo    You need to set:
    echo    - PAYABLI_KEY: Your API key
    echo    - PAYABLI_ENTRY: Your paypoint entry ID
    echo.
    pause
)

:: Start the server
echo 🌟 Starting server on http://localhost:8080...
echo    Press Ctrl+C to stop the server
echo.

go run main.go
