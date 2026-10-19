import type { APIRoute } from 'astro'
import { auth } from '../../../auth'
import { validateApplication, PayabliV2Error } from '../../lib/boardingV2'
import { loadBoardingDraft } from '../../lib/serverDb'
import { mapMissingFields } from '../../lib/validationFields'

// Re-checks an application after its documents are uploaded, so the e-sign
// step only opens once Payabli has everything it needs.
export const POST: APIRoute = async ({ request }) => {
  try {
    const { applicationReference }: { applicationReference: string } =
      await request.json()
    const validation = await validateApplication(applicationReference)
    const session = await auth.api.getSession({ headers: request.headers })
    const draft = session ? await loadBoardingDraft(session.user.id) : null
    const missingFields = mapMissingFields(
      Object.values(validation.sections ?? {}).flatMap(
        (section) => section.missingFields ?? [],
      ),
      draft?.people ?? [],
    )

    return new Response(
      JSON.stringify({
        valid: validation.valid,
        missingFields,
        notice: validation.notice?.message ?? null,
      }),
      { status: 200, headers: { 'Content-Type': 'application/json' } },
    )
  } catch (error) {
    console.error('Error validating application:', error)
    return new Response(
      JSON.stringify({
        error: 'Failed to validate application',
        traceId: error instanceof PayabliV2Error ? error.traceId : null,
      }),
      { status: 502, headers: { 'Content-Type': 'application/json' } },
    )
  }
}
