# Payabli Embedded Components (JavaScript)

A vanilla JavaScript integration of Payabli's embedded components (ECv2), using the
`@payabli/components-web` npm package to load the Pay In component — no framework,
no build-time type checker, just `loadPayabli()` and the DOM. Pick an amount, fill
out the form, and it processes a real payment — with a live event log showing every
SDK/component event as it happens.

> **Preview.** ECv2 has not launched yet — this example is pointed at Payabli's
> sandbox environment. See [Pointing somewhere else](#pointing-somewhere-else) for
> how to retarget it once production is live.

## How it works

A session needs an OAuth bearer token, which needs your client secret. The secret
must never reach the browser, so the Vite dev server exposes `POST /api/session`
(see `vite.config.js`), which calls `Token/serverside` then `Session/init` and
returns only the finished session:

```
browser  ->  POST /api/session              (dev server, holds the secret)
             POST /api/v2/Token/serverside
             POST /api/v2/{entryName}/Session/init
         <-  { sessionToken, renderToken, entryName, environment, expiresAt }
```

That split is what a real integration's backend does too. `src/session.js` fetches
the session and `src/main.js` hands it to the SDK via `loadPayabli()`. From there
the SDK owns session refresh on its own — it calls `Session/refresh` directly
against the Payabli API, so no refresh endpoint is needed here.

Picking an amount chip (or entering a custom one) posts `{ amountCents }` to
`/api/session` and mints a fresh session at that amount — the amount is server-side
session config, not something the client SDK can change after the fact, so changing
it always means a new session and a remounted component. `src/log.js` renders every
SDK and component event as a timeline so you can see the session lifecycle, field
validation, and the eventual `success`/`error` result as they happen.

## Setup

1. Clone this repo.

   ```bash
   git clone https://github.com/payabli/examples
   ```

2. Navigate to this example.

   ```bash
   cd examples/embedded-components/javascript
   ```

3. Install dependencies.

   ```bash
   npm install
   ```

4. Copy `.env.example` to `.env` and fill in `PAYABLI_CLIENT_ID`, `PAYABLI_CLIENT_SECRET`,
   and `PAYABLI_ENTRYPOINT` for your paypoint.

   ```bash
   cp .env.example .env
   ```

5. Start the dev server.

   ```bash
   npm run dev
   ```

6. Open http://localhost:5173.

## Pointing somewhere else

`PAYABLI_API_ORIGIN` and `PAYABLI_ENVIRONMENT` in `.env` control which API host a
session is created against. Change both together — `environment` has to name the
host a session was actually created against, since the SDK uses it to pick the
refresh host and iframe origin.

The SDK script itself is also loaded from a sandbox-specific URL in `src/main.js`,
since ECv2 isn't published to the default production CDN yet. Drop the `scriptSrc`
override there once it is.

## Things to know

- **A new amount means a new session.** The amount is server-side `Session/init`
  config, so switching chips (or entering a custom amount) re-mints a session and
  remounts the component rather than updating the existing one in place.
- **`PAYABLI_PARENT_ORIGIN` must equal this dev server's origin** — it becomes the
  render token's origin allowlist, and payhub answers with a 403 for any other
  parent.
- **The `[hidden]` CSS rule near the top of `src/style.css` is load-bearing.**
  Several panel states (`.state-card`, `.skeleton`) set their own `display`, which
  has the same specificity as the browser default `[hidden] { display: none }` and
  would otherwise win the cascade tie-break — silently leaving a "hidden" element
  on screen. Keep that rule (or an equivalent) if you restyle these states.

## Learn more

- [Embedded components overview](https://docs.payabli.com)
- [Embedded components framework integrations](https://docs.payabli.com/developers/developer-guides/embedded-components-frameworks)
