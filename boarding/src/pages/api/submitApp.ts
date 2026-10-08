import type { APIRoute } from 'astro'
import { auth } from '../../../auth'
import { clearBoardingDraft } from '../../lib/serverDb'
import {
  submitApplication,
  validateApplication,
  PayabliV2Error,
  type SubmitApplicationSigner,
} from '../../lib/boardingV2'

function jsonResponse(body: unknown, status: number) {
  return new Response(JSON.stringify(body), {
    status,
    headers: { 'Content-Type': 'application/json' },
  })
}

export const POST: APIRoute = async ({ request }) => {
  try {
    const {
      applicationReference,
      signer,
    }: { applicationReference: string; signer?: SubmitApplicationSigner } =
      await request.json()

    // Partner submit doesn't require a validation token yet (the boarding
    // team is tracking it), so it accepts incomplete applications. Gate it
    // here instead: only submit once Payabli's validation passes, which
    // also covers anything that changed after createApp's own check.
    const validation = await validateApplication(applicationReference)
    if (!validation.valid) {
      const missingFields = Object.values(validation.sections ?? {}).flatMap(
        (section) => section.missingFields ?? [],
      )
      const labels = [...new Set(missingFields.map((missing) => missing.label))]
      return jsonResponse(
        {
          error:
            labels.length > 0
              ? `Payabli needs more information before this application can be submitted. Missing: ${labels.join(', ')}.`
              : (validation.notice?.message ??
                'Payabli could not confirm the application is complete. Try again shortly.'),
          missingFields,
        },
        422,
      )
    }

    const result = await submitApplication(applicationReference, signer)

    // The submission is done, so the user's next application starts fresh
    // instead of resuming these records.
    const session = await auth.api.getSession({ headers: request.headers })
    if (session) {
      await clearBoardingDraft(session.user.id)
    }

    return jsonResponse(result, 200)
  } catch (error) {
    console.error('Error submitting application:', error)

    // Pass the API's own validation messages (400/409/422, e.g.
    // SIGNER_NOT_OWNER) through so the user sees why submit was rejected.
    const isUserFixable =
      error instanceof PayabliV2Error &&
      (error.status === 400 || error.status === 409 || error.status === 422)

    return jsonResponse(
      {
        error: isUserFixable
          ? (error.apiMessage ?? 'Payabli rejected the submission.')
          : 'Failed to submit application',
        traceId: error instanceof PayabliV2Error ? error.traceId : null,
      },
      isUserFixable ? error.status : 500,
    )
  }
}
