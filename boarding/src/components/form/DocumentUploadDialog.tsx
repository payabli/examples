import { useState } from 'react'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { CheckCircle, Loader2, Upload } from 'lucide-react'
import type { CreatedApplication } from '@/onSubmit'

type Requirement = CreatedApplication['documentRequirements'][number]

type UploadState =
  | { status: 'idle' }
  | { status: 'uploading' }
  | { status: 'done' }
  | { status: 'error'; message: string }

type DocumentUploadDialogProps = {
  open: boolean
  applicationReference: string
  requirements: Requirement[]
  // Called once every required document is uploaded and Payabli's
  // validation passes, to move on to the e-signature step.
  onComplete: () => void
  onClose: () => void
}

// The list repeats a person-scoped requirement once per owner, so the key
// needs both parts.
function requirementKey(requirement: Requirement) {
  return `${requirement.fieldPath}:${requirement.personReference ?? ''}`
}

// `allowedFormats` lists extensions or MIME types; turn them into an
// `accept` attribute. Empty means any format.
function acceptAttribute(formats: string[]) {
  return formats
    .map((format) => (format.includes('/') ? format : `.${format}`))
    .join(',')
}

// A person-scoped requirement must be uploaded with subject `person`;
// otherwise prefer the business.
function subjectFor(requirement: Requirement) {
  if (requirement.personReference) {
    return 'person'
  }
  return requirement.subjectTypes.includes('business')
    ? 'business'
    : requirement.subjectTypes[0]
}

// Boarding v2 can require documents before an application is complete, such
// as bank statements when annual revenue is $1,000,000 or more, or a passport
// for each foreign owner. They can only be uploaded once the application
// exists, so this step sits between creating the application and signing it.
export function DocumentUploadDialog({
  open,
  applicationReference,
  requirements,
  onComplete,
  onClose,
}: DocumentUploadDialogProps) {
  const [files, setFiles] = useState<Record<string, File | undefined>>({})
  const [uploads, setUploads] = useState<Record<string, UploadState>>({})
  const [checking, setChecking] = useState(false)
  const [checkError, setCheckError] = useState<string | null>(null)

  const allUploaded = requirements.every(
    (requirement) => uploads[requirementKey(requirement)]?.status === 'done',
  )

  const upload = async (requirement: Requirement) => {
    const key = requirementKey(requirement)
    const file = files[key]
    if (!file) {
      return
    }
    setUploads((prev) => ({ ...prev, [key]: { status: 'uploading' } }))

    const body = new FormData()
    body.append('applicationReference', applicationReference)
    body.append('fieldPath', requirement.fieldPath)
    body.append('subject', subjectFor(requirement))
    if (requirement.personReference) {
      body.append('personReference', requirement.personReference)
    }
    body.append('file', file, file.name)

    const response = await fetch('/api/uploadDocument', {
      method: 'POST',
      body,
    })
    if (response.ok) {
      setUploads((prev) => ({ ...prev, [key]: { status: 'done' } }))
      return
    }
    const failure = await response.json().catch(() => null)
    setUploads((prev) => ({
      ...prev,
      [key]: {
        status: 'error',
        message: failure?.error ?? 'The document could not be uploaded.',
      },
    }))
  }

  // Re-run Payabli's validation now that the documents are attached.
  const continueToSigning = async () => {
    setChecking(true)
    setCheckError(null)
    try {
      const response = await fetch('/api/validateApp', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ applicationReference }),
      })
      const result = await response.json()
      if (response.ok && result.valid) {
        onComplete()
        return
      }
      const labels = [
        ...new Set(
          (result.missingFields ?? []).map(
            (missing: { label: string }) => missing.label,
          ),
        ),
      ]
      setCheckError(
        labels.length > 0
          ? `Payabli still needs: ${labels.join(', ')}.`
          : (result.notice ??
              result.error ??
              'Payabli could not confirm the application is complete.'),
      )
    } catch {
      setCheckError('Payabli could not confirm the application is complete.')
    } finally {
      setChecking(false)
    }
  }

  return (
    <Dialog open={open} onOpenChange={(isOpen) => !isOpen && onClose()}>
      <DialogContent className="max-w-lg">
        <DialogHeader>
          <DialogTitle>Upload required documents</DialogTitle>
          <DialogDescription>
            Payabli needs these documents before the application can be
            signed.
          </DialogDescription>
        </DialogHeader>

        <div className="space-y-4">
          {requirements.map((requirement) => {
            const key = requirementKey(requirement)
            const state = uploads[key] ?? { status: 'idle' }
            return (
              <div key={key} className="space-y-2 rounded-lg border p-3">
                <div>
                  <p className="font-medium">
                    {requirement.label}
                    {requirement.personName && ` for ${requirement.personName}`}
                  </p>
                  {requirement.hint && (
                    <p className="text-sm text-muted-foreground">
                      {requirement.hint}
                    </p>
                  )}
                </div>
                {state.status === 'done' ? (
                  <p className="flex items-center text-sm text-muted-foreground">
                    <CheckCircle className="mr-2 h-4 w-4" /> Uploaded
                  </p>
                ) : (
                  <div className="flex gap-2">
                    <Input
                      type="file"
                      accept={acceptAttribute(requirement.allowedFormats)}
                      onChange={(event) =>
                        setFiles((prev) => ({
                          ...prev,
                          [key]: event.target.files?.[0],
                        }))
                      }
                    />
                    <Button
                      type="button"
                      variant="outline"
                      disabled={!files[key] || state.status === 'uploading'}
                      onClick={() => upload(requirement)}
                    >
                      {state.status === 'uploading' ? (
                        <Loader2 className="h-4 w-4 animate-spin" />
                      ) : (
                        <Upload className="h-4 w-4" />
                      )}
                    </Button>
                  </div>
                )}
                {state.status === 'error' && (
                  <p className="text-sm text-destructive">{state.message}</p>
                )}
              </div>
            )
          })}
        </div>

        {checkError && <p className="text-sm text-destructive">{checkError}</p>}

        <Button
          type="button"
          className="w-full"
          disabled={!allUploaded || checking}
          onClick={continueToSigning}
        >
          {checking && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
          Continue to signing
        </Button>
      </DialogContent>
    </Dialog>
  )
}
