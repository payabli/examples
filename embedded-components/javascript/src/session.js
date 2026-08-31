/**
 * Fetches a session from the dev server's `/api/session` endpoint (see vite.config.js).
 * @param {number} amountCents
 * @returns {Promise<import('@payabli/components-web').Session & { amountCents: number }>}
 */
export async function createSession(amountCents) {
  const response = await fetch('/api/session', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ amountCents }),
  });
  const body = await response.json().catch(() => null);

  if (!response.ok) {
    const detail =
      body && typeof body === 'object' && 'error' in body ? String(body.error) : `HTTP ${response.status}`;
    throw new Error(detail);
  }

  return body;
}
