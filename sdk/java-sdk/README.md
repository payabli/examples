A basic example application using the Payabli Java SDK to perform customer operations and payment method tokenization.
See the [Payabli Java SDK](https://github.com/payabli/sdk-java) for more information.

## Summary

The application has two pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.
3. **Make Transaction** - this page has an embedded component to save payment methods securely and send them to the API to process transactions.

## Setup Instructions

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
mvn compile exec:java
```

