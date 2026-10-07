import type { APIRoute } from 'astro'
import {
  submitApplication,
  PayabliV2Error,
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

    // Pass the API's own validation messages (400/409/422, e.g.
    // SIGNER_NOT_OWNER) through so the user sees why submit was rejected.
    const isUserFixable =
      error instanceof PayabliV2Error &&
      (error.status === 400 || error.status === 409 || error.status === 422)

    return new Response(
      JSON.stringify({
        error: isUserFixable
          ? (error.apiMessage ?? 'Payabli rejected the submission.')
          : 'Failed to submit application',
        traceId: error instanceof PayabliV2Error ? error.traceId : null,
      }),
      {
        status: isUserFixable ? error.status : 500,
        headers: {
          'Content-Type': 'application/json',
        },
      },
    )
  }
}
