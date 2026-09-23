import type { APIRoute } from 'astro'
import {
  submitApplication,
  type SubmitApplicationSigner,
} from '../../lib/boardingV2'

export const POST: APIRoute = async ({ request }) => {
  try {
    const {
      applicationReference,
      signer,
    }: { applicationReference: string; signer?: SubmitApplicationSigner } =
      await request.json()

    const result = await submitApplication(applicationReference, signer)

    return new Response(JSON.stringify(result), {
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
