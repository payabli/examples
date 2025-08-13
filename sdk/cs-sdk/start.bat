@echo off
echo Starting Payabli C# SDK Example...
echo.

REM Check if .env file exists
if not exist .env (
    echo ⚠️  .env file not found!
    echo Creating .env file from template...
    copy .env.template .env >nul
    echo.
    echo 📝 Please edit .env file with your actual Payabli credentials:
    echo    PAYABLI_KEY=your_api_key_here
    echo    PAYABLI_ENTRY=your_entry_point_here
    echo.
    echo Get these credentials from https://partnerhub.payabli.com
    echo.
    exit /b 1
)

REM Check if dotnet is installed
dotnet --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ .NET SDK not found!
    echo Please install .NET 8 SDK from https://dotnet.microsoft.com/download
    exit /b 1
)

echo 🚀 Starting the application...
echo The app will be available at: http://localhost:5000
echo Press Ctrl+C to stop
echo.

dotnet run
