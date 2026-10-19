import type { APIRoute } from 'astro'
import {
  uploadDocument,
  PayabliV2Error,
  type DocumentSubject,
} from '../../lib/boardingV2'

const SUBJECTS: DocumentSubject[] = ['person', 'paypoint', 'business']

function jsonResponse(body: unknown, status: number) {
  return new Response(JSON.stringify(body), {
    status,
    headers: { 'Content-Type': 'application/json' },
  })
}

// Uploads one file against one of the application's document requirements
// (see the `documentRequirements` createApp returns). The browser sends
// multipart form data; the server forwards it to Payabli so the API
// credentials never reach the client.
export const POST: APIRoute = async ({ request }) => {
  const form = await request.formData()
  const applicationReference = form.get('applicationReference')
  const fieldPath = form.get('fieldPath')
  const subject = form.get('subject')
  const personReference = form.get('personReference')
  const file = form.get('file')

  if (
    typeof applicationReference !== 'string' ||
    typeof fieldPath !== 'string' ||
    typeof subject !== 'string' ||
    !SUBJECTS.includes(subject as DocumentSubject) ||
    !(file instanceof File)
  ) {
    return jsonResponse({ error: 'Invalid upload request' }, 400)
  }

  try {
    await uploadDocument(applicationReference, {
      file,
      fieldPath,
      subject: subject as DocumentSubject,
      personReference:
        typeof personReference === 'string' && personReference
          ? personReference
          : undefined,
    })
    return jsonResponse({ uploaded: true }, 201)
  } catch (error) {
    console.error('Error uploading document:', error)

    // Size and format rejections (413, 422) and bad requirement matches
    // (400, 409) carry a message the user can act on.
    const isUserFixable =
      error instanceof PayabliV2Error &&
      [400, 409, 413, 422].includes(error.status)
    return jsonResponse(
      {
        error: isUserFixable
          ? (error.apiMessage ?? 'Payabli rejected the document.')
          : 'Failed to upload document',
        traceId: error instanceof PayabliV2Error ? error.traceId : null,
      },
      isUserFixable ? error.status : 502,
    )
  }
}
