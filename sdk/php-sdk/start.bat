@echo off
setlocal enabledelayedexpansion

echo 🚀 Starting Payabli PHP SDK Example...

REM Check if PHP is installed
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP is not installed. Please install PHP 8.1 or higher.
    pause
    exit /b 1
)

REM Check if Composer is installed
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Composer is not installed. Please install Composer first.
    echo    Visit: https://getcomposer.org/download/
    pause
    exit /b 1
)

REM Install dependencies
echo 📦 Installing dependencies...
composer install
if %errorlevel% neq 0 (
    echo ❌ Failed to install dependencies
    pause
    exit /b 1
)

REM Check if .env file exists
if not exist .env (
    echo ⚙️  Creating .env file from template...
    copy .env.template .env
    echo 📝 Please edit .env file with your Payabli API credentials before continuing.
    echo    PAYABLI_KEY=your_api_key_here
    echo    PAYABLI_ENTRY=your_entry_point_here
    echo.
    pause
)

REM Start the server
echo 🌐 Starting server at http://localhost:8000
echo    Press Ctrl+C to stop the server
echo.
composer run serve
