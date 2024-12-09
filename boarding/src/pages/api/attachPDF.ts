import type { APIRoute } from 'astro'
import { getApiUrlPrefix } from '../../lib/getUrl';

export const POST: APIRoute = async ({ request }) => {
  const { pdfContent, appId } = await request.json()

  const apiToken = import.meta.env.PAYABLI_API_TOKEN
  const prefix = getApiUrlPrefix()

  if (!apiToken) {
    console.error('API token not found in environment variables')
    return new Response(JSON.stringify({ success: false, error: 'API token not configured' }), {
      status: 500,
      headers: {
        'Content-Type': 'application/json'
      }
    })
  }

  const callBody = {
    attachments: [
      {
        ftype: "pdf",
        filename: "esignature.pdf",
        fContent: pdfContent
      }
    ]
  }
  
  try {
    const response = await fetch(`https://api${prefix}.payabli.com/api/Boarding/app/${appId}`, {
      method: 'PUT',
      headers: {
        'content-type': 'application/json',
        'requestToken': apiToken
      },
      body: JSON.stringify(callBody),
    })
    
    if (!response.ok) {
      throw new Error('Failed to attach PDF')
    }

    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: {
        'Content-Type': 'application/json'
      }
    })
  } catch (error) {
    console.error('Error attaching PDF:', error)
    return new Response(JSON.stringify({ success: false, error: 'Failed to attach PDF' }), {
      status: 500,
      headers: {
        'Content-Type': 'application/json'
      }
    })
  }
}
