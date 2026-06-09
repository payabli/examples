A basic example application using the Payabli Python SDK to perform customer operations, payment method tokenization, and v2 Money In transactions.
See the [Payabli Python SDK](https://github.com/payabli/sdk-python) for more information.

## Summary

The application has three pages:
1. **Create Customer** - this page has a form that allows you to create a new customer in your paypoint.
2. **List Customers** - this page lists all the customers in your paypoint.
3. **Make Transaction** - this page has an embedded component to save payment methods securely and send them to the API to process transactions with the v2 `MoneyIn/getpaid` endpoint.

## Setup Instructions

1. Clone this repo and navigate to the project directory:

```bash
git clone https://github.com/payabli/examples
cd examples/sdk/py-sdk
```

2. Create and activate a virtual environment:

```bash
python3 -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate
```

3. Install the dependencies:

```bash
pip install -r requirements.txt
```

4. Copy the `.env.template` file to `.env` and fill in the required values:

```bash
cp .env.template .env
```

Edit the `.env` file:
```
PAYABLI_ENTRY="your_paypoint_entry_id"
PAYABLI_KEY="your_api_key"
```

5. Start the development server:

```bash
uvicorn main:app --reload
```
