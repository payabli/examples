import type { APIRoute } from 'astro'
import { getApiUrlPrefix } from '../../lib/getUrl'

export const POST: APIRoute = async ({ request }) => {
  const apiToken = import.meta.env.PAYABLI_API_TOKEN
  const prefix = getApiUrlPrefix()

  try {
    const formData = await request.json()
    console.log('Submitting app:', formData.appId)

    const response = await fetch(
      `https://api${prefix}.payabli.com/api/Boarding/appsts/${formData.appId}/4/0`,
      {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          requestToken: apiToken,
        },
      },
    )

    if (!response.ok) {
      const errorBody = await response.text()
      console.error('Submit app request failed:', response.status, errorBody)
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const responseBody = await response.json()

    return new Response(JSON.stringify(responseBody.responseData), {
      status: 200,
      headers: {
        'Content-Type': 'application/json',
      },
    })
  } catch (error) {
    console.error('Error submitting application:', error)
    return new Response(
      JSON.stringify({ error: 'Failed to submit application' }),
      {
        status: 500,
        headers: {
          'Content-Type': 'application/json',
        },
      },
    )
  }
}
