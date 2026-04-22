# React Native Example

This example shows how to embed Payabli's Embedded Method UI inside a React Native and Expo application using `react-native-webview`.

The app is structured as a small mobile checkout demo:

1. A home screen with a checkout button
2. A 3-step checkout flow walks through review, payment, and result states
3. The payment step renders Payabli's embedded experience in a WebView with native tabs for Card, ACH, and RDC
4. Payment results are driven by messages posted back from the embedded component

## What this example does

- Uses Expo to run a React Native Payabli sample app
- Renders Payabli's Embedded Method UI component in a WebView
- Passes transaction amounts and metadata from native React state into the embedded checkout
- Switches between Card, ACH, and RDC payment methods
- Handles `postMessage` events from the embedded component to update native UI state

## Requirements

- Node.js 18+
- npm
- Expo Go or a local iOS or Android simulator

## Setup

1. Install dependencies.

```bash
cd examples/react-native
npm install
```

2. Copy the environment template and add your Payabli credentials.

```bash
cp .env.template .env
```

Fill in:

- `PAYABLI_API_KEY`
- `PAYABLI_ENTRY_POINT`

## Run

Start the Expo development server:

```bash
npm start
```

You can then launch the app with one of the built-in scripts:

```bash
npm run ios
npm run android
npm run web
```