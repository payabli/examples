@echo off
REM Simple startup script for the Payabli Python SDK example

echo Starting Payabli Python SDK Example...

REM Check if virtual environment exists, create if not
if not exist "venv" (
    echo Creating virtual environment...
    python -m venv venv
)

REM Activate virtual environment
call venv\Scripts\activate

REM Install dependencies
echo Installing dependencies...
pip install -r requirements.txt

REM Check if .env file exists
if not exist ".env" (
    echo Creating .env file from template...
    copy .env.template .env
    echo Please edit .env file with your Payabli credentials before running the app.
    pause
    exit /b 1
)

REM Start the application
echo Starting FastAPI server...
uvicorn main:app --reload --host 0.0.0.0 --port 8000
