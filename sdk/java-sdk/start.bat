@echo off

echo 🚀 Starting Payabli Java SDK Example...

REM Check if .env file exists, if not create it from template
if not exist .env (
    echo 📋 Creating .env file from template...
    copy .env.template .env
    echo ✅ .env file created. Please edit it with your actual Payabli credentials.
    echo 📝 Edit .env file with your PAYABLI_ENTRY and PAYABLI_KEY values before continuing.
    pause
)

REM Load environment variables from .env file
if exist .env (
    echo 🔧 Loading environment variables...
    for /f "delims=" %%x in (.env) do set "%%x"
)

REM Check if Java is installed
java -version >nul 2>&1
if errorlevel 1 (
    echo ❌ Java is not installed. Please install Java 11 or higher.
    pause
    exit /b 1
)

REM Check if Maven is installed
mvn -version >nul 2>&1
if errorlevel 1 (
    echo ❌ Maven is not installed. Please install Maven.
    pause
    exit /b 1
)

echo 🔧 Building Payabli SDK...
cd ..\..\..\fern-java-sdk
call gradlew clean build -q
if errorlevel 1 (
    echo ❌ SDK build failed.
    pause
    exit /b 1
)

cd ..\examples\sdk\java-sdk
if not exist lib mkdir lib

REM Find and copy the main JAR file (exclude sources and javadoc)
for %%f in (..\..\..\fern-java-sdk\build\libs\api-sdk-*.jar) do (
    echo %%f | findstr /v "sources javadoc" >nul
    if not errorlevel 1 (
        copy "%%f" lib\api-sdk.jar >nul
        echo ✅ SDK JAR copied to lib\api-sdk.jar
        goto :jar_copied
    )
)

echo ❌ Could not find main SDK JAR file
pause
exit /b 1

:jar_copied
echo 🔧 Installing dependencies...
mvn clean install -q

echo 🏃‍♂️ Starting the application...
for /f "tokens=*" %%i in ('mvn dependency:build-classpath -q -Dmdep.outputFile=NUL') do set CLASSPATH=%%i
java -cp "target\classes;lib\api-sdk.jar;%CLASSPATH%" com.payabli.example.PayabliExampleApp

echo 🎉 Application started! Visit http://localhost:8000
pause
