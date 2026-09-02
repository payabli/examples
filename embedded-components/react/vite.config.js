import { readFileSync } from 'node:fs';
import react from '@vitejs/plugin-react';
import { defineConfig } from 'vite';

const DEV_PORT = 5174;

/**
 * Reads `.env` directly, ignoring `process.env`, so config here doesn't depend on
 * whatever the shell happens to have exported.
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
 * Resolves the embedding page's origin from the request itself, falling back to
 * `fallback` if neither `Origin` nor `Referer` is present.
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
 * Mints a session server-side, so the client secret never reaches the browser.
 * The page fetches the finished session from `/api/session`.
 *
 * Accepts an optional `{ amountCents }` JSON body to mint a session for a
 * different amount.
 *
 * Dev only. `apply: 'serve'` keeps it out of `vite build`; a real app performs
 * this exchange from its own backend, not from a bundler plugin.
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
  const env = readDotEnv(new URL('.env', import.meta.url).pathname);

  return {
    plugins: [react(), payabliSessionEndpoint(env)],
    server: {
      port: DEV_PORT,
      // Vite rejects unrecognized Host headers by default.
      allowedHosts: ['.ngrok-free.app', '.ngrok.io', '.ngrok.app'],
    },
  };
});
