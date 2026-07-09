# Local Token Server

Stands in for a partner backend: it mints a Payabli access token so the app never embeds a `clientSecret`. See the [top-level README](../README.md) for how this fits in.

## Setup

```bash
cp .env.template .env
```

Fill in `.env` — either:
- `CLIENT_ID` / `CLIENT_SECRET`, exchanged automatically for a token, or
- `PAYABLI_ACCESS_TOKEN`, a sandbox token you already have (simpler, but expires and needs replacing).

Then:

```bash
npm start
```

The Simulator reaches it at `http://127.0.0.1:8787/payabli/access-token`.

## Physical device

A physical iPhone can't reach `127.0.0.1` on your Mac — use your Mac's LAN IP instead. Update `tokenServerURL` in `Secrets.swift`:

```text
http://<mac-lan-ip>:8787/payabli/access-token
```

Bind the server to all interfaces:

```bash
PAYABLI_LOCAL_TOKEN_SERVER_HOST=0.0.0.0 npm start
```

Trusted networks only, and stop the server when you're done.

## Contract

`GET /payabli/access-token` → `{ "accessToken": "..." }`. `Secrets.fetchAccessToken()` in the app expects exactly that.
