A basic example application using the Payabli Java SDK to perform customer operations.
See the [Payabli Java SDK](https://github.com/payabli/sdk-java) for more information.

## Summary

The application has two pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.

## Setup Instructions

### Option 1: Quick Start (Unix/Linux/macOS)

1. Clone this repo and navigate to the project directory:

```bash
git clone https://github.com/payabli/examples
cd examples/sdk/java-sdk
```

2. Run the startup script:

```bash
chmod +x start.sh
./start.sh
```

The script will:
- Check for Java and Maven installation
- Build and install the Payabli Java SDK to Maven local repository
- Install dependencies
- Create a .env file from the template
- Start the development server

### Option 2: Quick Start (Windows)

1. Clone this repo and navigate to the project directory:

```cmd
git clone https://github.com/payabli/examples
cd examples/sdk/java-sdk
```

2. Run the startup script:

```cmd
start.bat
```

### Option 3: Manual Setup

1. Clone this repo and navigate to the project directory:

```bash
git clone https://github.com/payabli/examples
cd examples/sdk/java-sdk
```

2. Make sure you have Java 11+ and Maven installed:

```bash
java -version
mvn -version
```

3. Build and install the Payabli Java SDK:

```bash
chmod +x install-sdk.sh
./install-sdk.sh
```

4. Install the dependencies:

```bash
mvn clean install
```

5. Copy the `.env.template` file to `.env` and fill in the required values:

```bash
cp .env.template .env
```

Edit the `.env` file:
```
PAYABLI_ENTRY="your_paypoint_entry_id"
PAYABLI_KEY="your_api_key"
```

6. Start the development server:

```bash
# Build the classpath and run
mvn dependency:build-classpath -Dmdep.outputFile=cp.txt
java -cp "target/classes:lib/api-sdk.jar:$(cat cp.txt)" com.payabli.example.PayabliExampleApp
```

Or use the exec plugin (may have classpath issues):
```bash
mvn exec:java -Dexec.mainClass="com.payabli.example.PayabliExampleApp"
```

The application will start on http://localhost:8000

## Building a JAR

To create an executable JAR file:

```bash
mvn package
```

Then run the JAR:

```bash
java -jar target/sdk-example-1.0.0.jar
```
