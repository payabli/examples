import type { APIRoute } from 'astro'
import { z } from 'zod'
import { getApiUrlPrefix } from '../../lib/getUrl';
import { migrateBankAccounts, parseServerFormData } from '../../Schema'

export const POST: APIRoute = async ({ request }) => {
  
  const apiToken = import.meta.env.PAYABLI_API_TOKEN
  const prefix = getApiUrlPrefix()

  try {
    const requestData = await request.json()
    // Final submission path: normalize enforced prefills, then fully validate and coerce.
    const formData = parseServerFormData(migrateBankAccounts(requestData))
    
    const jsonData = JSON.stringify(formData)

    const response = await fetch(`https://api${prefix}.payabli.com/api/Boarding/app`, {
      method: 'POST',
      headers: {
        'content-type': 'application/json',
        'requestToken': apiToken
      },
      body: jsonData
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const responseBody = await response.json()

    return new Response(JSON.stringify(responseBody.responseData), {
      status: 200,
      headers: {
        'Content-Type': 'application/json'
      }
    })
  } catch (error) {
    if (error instanceof z.ZodError) {
      return new Response(JSON.stringify({ error: 'Invalid form data', details: error.flatten() }), {
        status: 400,
        headers: {
          'Content-Type': 'application/json'
        }
      })
    }

    console.error('Error submitting application:', error)
    return new Response(JSON.stringify({ error: 'Failed to submit application' }), {
      status: 500,
      headers: {
        'Content-Type': 'application/json'
      }
    })
  }
}

