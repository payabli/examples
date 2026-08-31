import { readFileSync } from 'node:fs';
import { defineConfig } from 'vite';

const DEV_PORT = 5173;

/**
 * Reads `.env` directly, ignoring `process.env` entirely.
 *
 * Vite's own `loadEnv` merges the file over `process.env`, so an already-exported
 * shell variable silently wins over what's in `.env`. `PAYABLI_ENTRYPOINT` and
 * `PAYABLI_ENVIRONMENT` are common enough names that a shell profile set up for
 * another Payabli project can already export them — and a session minted with
 * only some of these overridden mixes a session token issued for one environment
 * with a different `environment` value, which points the SDK's refresh calls and
 * iframe at the wrong host. It fails quietly in the browser rather than at session
 * creation. Reading the file ourselves keeps this example reproducible regardless
 * of what the shell already has set.
 *
 * @param {string} path
 * @returns {Record<string, string>}
 */
function readDotEnv(path) {
  let contents;
  try {
    contents = readFileSync(path, 'utf8');
  } catch {
    return {};
  }

  const values = {};
  for (const line of contents.split('\n')) {
    const trimmed = line.trim();
    if (!trimmed || trimmed.startsWith('#')) {
      continue;
    }
    const eq = trimmed.indexOf('=');
    if (eq === -1) {
      continue;
    }
    const key = trimmed.slice(0, eq).trim();
    let value = trimmed.slice(eq + 1).trim();
    if (
      (value.startsWith('"') && value.endsWith('"')) ||
      (value.startsWith("'") && value.endsWith("'"))
    ) {
      value = value.slice(1, -1);
    }
    values[key] = value;
  }
  return values;
}

const DEFAULT_AMOUNT_CENTS = 1000;
const MIN_AMOUNT_CENTS = 100;
const MAX_AMOUNT_CENTS = 100_000_00;

/** @param {Record<string, string>} env */
function readSessionConfig(env) {
  return {
    apiOrigin: env.PAYABLI_API_ORIGIN || 'https://api-qa.payabli.com',
    entryName: env.PAYABLI_ENTRYNAME || 'payabli',
    entryPoint: env.PAYABLI_ENTRYPOINT || '',
    clientId: env.PAYABLI_CLIENT_ID || '',
    clientSecret: env.PAYABLI_CLIENT_SECRET || '',
    environment: env.PAYABLI_ENVIRONMENT || 'qa',
    parentOrigin: env.PAYABLI_PARENT_ORIGIN || `http://localhost:${DEV_PORT}`,
  };
}

/**
 * Reads the origin the browser actually loaded this page from — `Origin` first (sent
 * on every POST, same-origin or not), falling back to `Referer`'s origin.
 *
 * `allowedParentOrigins` has to name whatever page is embedding the component. A
 * static `.env` value can only ever be right for one hostname at a time, which breaks
 * the moment you access the same dev server through more than one origin — say,
 * `http://localhost:5173` directly and an `https://*.ngrok-free.app` tunnel to it in
 * the same session. Reading it per-request instead means both work at once with no
 * `.env` edits. `PAYABLI_PARENT_ORIGIN` still exists as the fallback for requests
 * that carry neither header (rare, but possible with some HTTP clients).
 *
 * @param {import('node:http').IncomingMessage} req
 * @param {string} fallback
 * @returns {string}
 */
function resolveParentOrigin(req, fallback) {
  const originHeader = req.headers.origin;
  if (originHeader) {
    return originHeader;
  }
  const referer = req.headers.referer;
  if (referer) {
    try {
      return new URL(referer).origin;
    } catch {
      // Malformed Referer header — fall through to the default.
    }
  }
  return fallback;
}

/**
 * Mints a real ECv2 session against the Payabli API, server-side.
 *
 * Creating a session needs an OAuth bearer token, which needs the client secret. The
 * secret must never reach the browser, so this runs inside the Vite dev server and the
 * page only ever fetches the finished session from `/api/session` — the same split a
 * real integration's backend would perform. No refresh endpoint is needed here: the
 * SDK refreshes sessions on its own, directly against the Payabli API.
 *
 * Accepts an optional `{ amountCents }` JSON body so the page can re-mint a session
 * for a different amount — the amount is server-side session config, not something
 * the client SDK can change after the fact.
 *
 * Dev only. `apply: 'serve'` keeps it out of `vite build`; a real app performs this
 * same exchange from its own backend, not from a bundler plugin.
 *
 * @param {Record<string, string>} env
 * @returns {import('vite').Plugin}
 */
function payabliSessionEndpoint(env) {
  const config = readSessionConfig(env);

  return {
    name: 'payabli-session-endpoint',
    apply: 'serve',
    configureServer(server) {
      server.middlewares.use('/api/session', (req, res) => {
        const fail = (status, message, detail) => {
          res.statusCode = status;
          res.setHeader('Content-Type', 'application/json');
          res.end(JSON.stringify({ error: message, detail }));
        };

        if (req.method !== 'POST') {
          fail(405, 'Use POST.');
          return;
        }
        if (!config.clientId || !config.clientSecret || !config.entryPoint) {
          fail(
            500,
            'Missing configuration. Copy .env.example to .env and fill in ' +
              'PAYABLI_CLIENT_ID, PAYABLI_CLIENT_SECRET and PAYABLI_ENTRYPOINT.',
          );
          return;
        }

        let raw = '';
        req.on('data', (chunk) => {
          raw += chunk;
        });
        req.on('end', () => {
          void (async () => {
            try {
              const requestBody = raw ? JSON.parse(raw) : {};
              const parentOrigin = resolveParentOrigin(req, config.parentOrigin);
              const amountCents = Math.min(
                MAX_AMOUNT_CENTS,
                Math.max(
                  MIN_AMOUNT_CENTS,
                  Number.isFinite(requestBody.amountCents)
                    ? Math.round(requestBody.amountCents)
                    : DEFAULT_AMOUNT_CENTS,
                ),
              );

              const tokenResponse = await fetch(`${config.apiOrigin}/api/v2/Token/serverside`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                  clientId: config.clientId,
                  clientSecret: config.clientSecret,
                }),
              });

              if (!tokenResponse.ok) {
                fail(
                  502,
                  `Token/serverside failed (${tokenResponse.status}).`,
                  await tokenResponse.text(),
                );
                return;
              }

              const { access_token: accessToken } = await tokenResponse.json();
              if (!accessToken) {
                fail(502, 'Token/serverside returned no access_token.');
                return;
              }

              const initResponse = await fetch(
                `${config.apiOrigin}/api/v2/${config.entryName}/Session/init`,
                {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${accessToken}`,
                  },
                  body: JSON.stringify({
                    entryPoint: config.entryPoint,
                    // Must match the origin this request actually came from (see
                    // resolveParentOrigin). The API snapshots this list into the render
                    // token, and payhub writes it into
                    // `Content-Security-Policy: frame-ancestors`, so any other parent is
                    // refused at render time.
                    allowedParentOrigins: [parentOrigin],
                    components: [
                      {
                        type: 'payin',
                        config: {
                          operation: 'Pay',
                          methods: ['card', 'ach'],
                          amount: { amount: amountCents / 100, currency: 'USD' },
                        },
                      },
                    ],
                  }),
                },
              );

              const initBody = await initResponse.json();

              if (!initResponse.ok || !initBody.data) {
                fail(502, `Session/init failed (${initResponse.status}).`, initBody);
                return;
              }

              res.setHeader('Content-Type', 'application/json');
              res.setHeader('Cache-Control', 'no-store');
              res.end(
                JSON.stringify({
                  sessionToken: initBody.data.sessionToken,
                  renderToken: initBody.data.renderToken,
                  // The slug in the URL path, not the entryPoint. The SDK builds
                  // `/api/v2/{entryName}/Session/refresh` from this.
                  entryName: config.entryName,
                  environment: config.environment,
                  expiresAt: initBody.data.expiresAt,
                  amountCents,
                }),
              );
            } catch (error) {
              fail(500, 'Could not create a session.', error instanceof Error ? error.message : error);
            }
          })();
        });
      });
    },
  };
}

export default defineConfig(() => {
  // .env only, never process.env: see readDotEnv for why. A key this file doesn't
  // define falls through to readSessionConfig's own default, not to whatever the
  // shell happens to have exported. These stay server-side — nothing in this file
  // is inlined into the client bundle.
  const env = readDotEnv(new URL('.env', import.meta.url).pathname);

  return {
    plugins: [payabliSessionEndpoint(env)],
    server: {
      port: DEV_PORT,
      // Vite rejects unrecognized Host headers by default. Widen it for ngrok tunnels
      // testing this dev server from an https origin.
      allowedHosts: ['.ngrok-free.app', '.ngrok.io', '.ngrok.app'],
    },
  };
});
