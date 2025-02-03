A guided tour of Payabli's temp token flow.

## Summary

This is a basic online checkout page using the Payabli embedded components to drive a temporary token flow. 
When you click the "Confirm order" button to process your transaction, the application will update the sidebar with progress messages.
At each step, the application will inform the user of what is happening behind the scenes.

## Setup Instructions

1. Clone this repo.

```bash
git clone https://github.com/payabli/examples
```

2. Navigate to the project directory.

```bash
cd examples/temp-token
```

3. Install the dependencies.

```bash
npm install
```

4. Copy the `.env.template` file to `.env` and fill in the required values.

```bash
cp .env.template .env
```

5. Start the development server.

```bash
npm run dev
```
