import type { APIRoute } from 'astro'
import { validateApplication, PayabliV2Error } from '../../lib/boardingV2'

// Re-checks an application after its documents are uploaded, so the e-sign
// step only opens once Payabli has everything it needs.
export const POST: APIRoute = async ({ request }) => {
  try {
    const { applicationReference }: { applicationReference: string } =
      await request.json()
    const validation = await validateApplication(applicationReference)
    const missingFields = Object.values(validation.sections ?? {}).flatMap(
      (section) => section.missingFields ?? [],
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
