import { getApiUrlPrefix } from './getUrl'

// Server-only. Never import this from client code — it holds the OAuth2
// client secret and the resulting bearer token in memory.

type CachedToken = {
  accessToken: string
  expiresAt: number
}

type PayabliAccessTokenResponse = {
  token_type: string
  access_token: string
  expires_in: number
}

// Refresh this many seconds before the token's actual expiry so a
// still-in-flight request never races a 401.
const EXPIRY_BUFFER_SECONDS = 60

let cachedToken: CachedToken | null = null

/**
 * Returns a cached OAuth2 bearer token for the Payabli v2 API, fetching a new
 * one via the client-credentials flow when missing or close to expiry.
 */
export async function getAccessToken(): Promise<string> {
  const now = Date.now()
  if (cachedToken && cachedToken.expiresAt > now) {
    return cachedToken.accessToken
  }

  const clientId = import.meta.env.PAYABLI_CLIENT_ID
  const clientSecret = import.meta.env.PAYABLI_CLIENT_SECRET
  if (!clientId || !clientSecret) {
    throw new Error(
      'PAYABLI_CLIENT_ID / PAYABLI_CLIENT_SECRET are not configured',
    )
  }

  const prefix = getApiUrlPrefix()
  const response = await fetch(
    `https://api${prefix}.payabli.com/api/v2/Token/serverside`,
    {
      method: 'POST',
      headers: { 'content-type': 'application/json' },
      body: JSON.stringify({ clientId, clientSecret }),
    },
  )

  if (!response.ok) {
    const errorBody = await response.text()
    throw new Error(
      `Failed to obtain Payabli access token: ${response.status} ${errorBody}`,
    )
  }

  const body = (await response.json()) as PayabliAccessTokenResponse

  cachedToken = {
    accessToken: body.access_token,
    expiresAt: now + (body.expires_in - EXPIRY_BUFFER_SECONDS) * 1000,
  }

  return cachedToken.accessToken
}
