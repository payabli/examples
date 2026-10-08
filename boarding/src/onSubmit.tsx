import React from 'react'
import { FieldValues } from 'react-hook-form'
import { z } from 'zod'
import { formSchema } from './Schema'
import { toast, useToast } from '@/hooks/use-toast'
import { useFormWithSchema } from './Schema'
import type { DocumentRequirement } from '@/lib/boardingV2'

type FormSchemaType = z.infer<typeof formSchema>

export type CreatedApplication = {
  applicationReference: string
  signerPersonReference: string
  // Required documents Payabli still needs before the application can be
  // signed (e.g. bank statements at $1M+ annual revenue). Empty when none.
  documentRequirements: (DocumentRequirement & { personName?: string })[]
}

type CreateAppFailure = {
  error: string
  step?: string
  traceId?: string | null
  // Set when Payabli's `validate` found data the form didn't supply.
  missingFields?: { field: string; label: string }[]
}

// "Payabli needs more information...: Business Phone, Title" -- the labels
// are Payabli's names for the missing fields.
function describeFailure(failure: CreateAppFailure | null): string {
  const message =
    failure?.error ?? 'The form could not be submitted successfully.'
  const labels = [
    ...new Set((failure?.missingFields ?? []).map((missing) => missing.label)),
  ]
  return labels.length > 0 ? `${message} Missing: ${labels.join(', ')}.` : message
}

class SubmissionError extends Error {
  constructor(
    message: string,
    readonly details: CreateAppFailure | null,
  ) {
    super(message)
  }
}

export function useFormLogic(
  steps: React.ReactElement<{ children?: React.ReactNode }>,
  setCurrentPage: React.Dispatch<React.SetStateAction<number>>,
) {
  const form = useFormWithSchema()

  async function onSuccess(
    values: FormSchemaType,
  ): Promise<CreatedApplication | undefined> {
    try {
      const response = await fetch('/api/createApp', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(values),
      })

      if (!response.ok) {
        // createApp returns the API's own message for fixable errors (e.g. a
        // duplicate bank account). Anything it created is kept, so the next
        // submission resumes from it.
        const failure = (await response.json().catch(() => null)) as
          | CreateAppFailure
          | null
        throw new SubmissionError(describeFailure(failure), failure)
      }

      const responseData = (await response.json()) as CreatedApplication

      return responseData
    } catch (error) {
      toast({
        variant: 'destructive',
        title: 'Error',
        description:
          error instanceof SubmissionError
            ? error.message
            : 'The form could not be submitted successfully.',
      })
      console.error(
        'Submission error:',
        error instanceof SubmissionError ? error.details : error,
      )
    }
  }

  function onError(errors: FieldValues) {
    const { binphone, binperson, binweb } = form.getValues()
    const sum = Number(binphone) + Number(binperson) + Number(binweb)

    if (sum !== 100) {
      const errorMessage = 'The sum of percentages must equal 100'
      form.setError('binphone', { type: 'manual', message: errorMessage })
      form.setError('binperson', { type: 'manual', message: errorMessage })
      form.setError('binweb', { type: 'manual', message: errorMessage })
    }

    form.trigger().then(() => {
      if (steps && steps.props && steps.props.children) {
        const errorPages = React.Children.map(
          steps.props.children,
          (step: React.ReactNode, index: number) => {
            const errorElementIndex = findErrorElementIndex(step)
            return errorElementIndex !== -1 ? index : 0
          },
        )
        const earliestErrorPage = Math.min(...(errorPages ?? [])) || 0

        toast({
          variant: 'destructive',
          title: 'Error!',
          description: 'The form could not be submitted successfully.',
        })

        console.log(errors)

        // Update the current page to the earliest error page
        setCurrentPage(earliestErrorPage)
      } else {
        toast({
          variant: 'destructive',
          title: 'Error!',
          description: 'An unexpected error occurred.',
        })
      }
    })
  }

  function findErrorElementIndex(element: React.ReactNode): number {
    if (React.isValidElement(element)) {
      const name = (element.props as { name?: string }).name as keyof FormSchemaType
      if (name && form.formState.errors[name]) {
        return 0
      }
    }

    if (Array.isArray(element)) {
      for (let i = 0; i < element.length; i++) {
        const errorElementIndex = findErrorElementIndex(element[i])
        if (errorElementIndex !== -1) {
          return i
        }
      }
    }

    if (
      React.isValidElement(element) &&
      (element.props as React.PropsWithChildren<{}>).children
    ) {
      const errorElementIndex = findErrorElementIndex(
        (element.props as React.PropsWithChildren<{}>).children,
      )
      if (errorElementIndex !== -1) {
        return errorElementIndex
      }
    }

    return -1
  }

  return {
    onSuccess,
    onError,
  }
}
