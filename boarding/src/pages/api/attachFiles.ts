import type { APIRoute } from 'astro'
import { getApiUrlPrefix } from '../../lib/getUrl'

export const POST: APIRoute = async ({ request }) => {
  const { attachments, appId } = await request.json()

  const apiToken = import.meta.env.PAYABLI_API_TOKEN
  const prefix = getApiUrlPrefix()

  if (!apiToken) {
    console.error('API token not found in environment variables')
    return new Response(
      JSON.stringify({ success: false, error: 'API token not configured' }),
      {
        status: 500,
        headers: {
          'Content-Type': 'application/json',
        },
      },
    )
  }

  const callBody = {
    attachments: [
      {
        ftype: 'pdf',
        filename: 'esignature.pdf',
        fContent: attachments[2].contents,
      },
      attachments[0].file && {
        ftype: attachments[0].extension.slice(1),
        filename: `deposit${attachments[0].extension}`,
        fContent: attachments[0].contents,
      },
      attachments[1].file && {
        ftype: attachments[1].extension.slice(1),
        filename: `withdrawal${attachments[1].extension}`,
        fContent: attachments[1].contents,
      },
    ].filter(Boolean),
  }

  console.log(
    JSON.stringify(
      callBody.attachments.map(({ ftype, filename }) => ({ ftype, filename })),
    ),
  )

  try {
    const response = await fetch(
      `https://api${prefix}.payabli.com/api/Boarding/app/${appId}`,
      {
        method: 'PUT',
        headers: {
          'content-type': 'application/json',
          requestToken: apiToken,
        },
        body: JSON.stringify(callBody),
      },
    )

    if (!response.ok) {
      throw new Error('Failed to attach files')
    }

    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: {
        'Content-Type': 'application/json',
      },
    })
  } catch (error) {
    console.error('Error attaching files:', error)
    return new Response(
      JSON.stringify({ success: false, error: 'Failed to attach files' }),
      {
        status: 500,
        headers: {
          'Content-Type': 'application/json',
        },
      },
    )
  }
}
