# Payabli Embedded Components (JavaScript)

A vanilla JavaScript integration of Payabli's embedded components, using
[`@payabli/components-web`](https://www.npmjs.com/package/@payabli/components-web)
to render a Pay In component.

## Setup

1. Clone this repo and navigate here.

   ```bash
   git clone https://github.com/payabli/examples
   cd examples/embedded-components/javascript
   ```

2. Install dependencies.

   ```bash
   npm install
   ```

3. Copy `.env.example` to `.env` and fill in your credentials.

   ```bash
   cp .env.example .env
   ```

4. Start the dev server.

   ```bash
   npm run dev
   ```

5. Open http://localhost:5173.

## How it works

Your backend exchanges API credentials for a session, then your page hands that
session to the SDK, which renders a PCI-compliant payment form in a secure iframe.
`vite.config.js` handles session creation for local development; `src/main.js`
mounts the component and wires up its events.

## Learn more

- [Embedded components overview](https://docs.payabli.com)
- [Embedded components framework integrations](https://docs.payabli.com/developers/developer-guides/embedded-components-frameworks)
