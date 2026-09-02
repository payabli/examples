# Payabli Embedded Components (JavaScript)

A vanilla JavaScript integration of Payabli's embedded components, using
[`@payabli/components-web`](https://www.npmjs.com/package/@payabli/components-web)
to render a Pay In component.

## Setup

```bash
git clone https://github.com/payabli/examples
cd examples/embedded-components/javascript
npm install
cp .env.example .env  # fill in your credentials
npm run dev
```

Open http://localhost:5173.

## How it works

Your backend exchanges API credentials for a session (`vite.config.js` does this
for local dev), then your page hands that session to the SDK, which renders a
PCI-compliant payment form in a secure iframe. `src/main.js` mounts the component
and wires up its events.

## Learn more

- [Embedded components overview](https://docs.payabli.com/guides/embedded-components-overview)
- [Session initialization](https://docs.payabli.com/developers/api-reference/session/init-component-session)
