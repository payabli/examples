import React from 'react'
import { FieldValues, type UseFormReturn } from 'react-hook-form'
import { z } from 'zod'
import { formSchema } from './Schema'
import { toast } from '@/hooks/use-toast'
import type { DocumentRequirement } from '@/lib/boardingV2'
import type { MissingFieldForForm } from '@/lib/validationFields'

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
  // Set when Payabli's `validate` found data the form didn't supply, each
  // with the form field(s) it maps to.
  missingFields?: MissingFieldForForm[]
}

// Top-level form field name -> wizard step index, read from the rendered
// steps (field `name`s, country/region picker names, and checkbox-group
// option names). `ownership[].ownertitle` and `ownership.1.ownertitle` both
// key on `ownership`.
function topLevelKey(name: string) {
  return name.split(/[.[]/)[0]
}

function collectFieldSteps(
  steps: React.ReactElement<{ children?: React.ReactNode }>,
): Map<string, number> {
  const fieldSteps = new Map<string, number>()
  const walk = (node: React.ReactNode, step: number) => {
    React.Children.forEach(node, (child) => {
      if (!React.isValidElement(child)) {
        return
      }
      const props = child.props as {
        name?: string
        countryName?: string
        regionName?: string
        options?: { name?: string }[]
        children?: React.ReactNode
      }
      const names = [
        props.name,
        props.countryName,
        props.regionName,
        ...(props.options ?? []).map((option) => option.name),
      ]
      for (const name of names) {
        if (typeof name === 'string' && !fieldSteps.has(topLevelKey(name))) {
          fieldSteps.set(topLevelKey(name), step)
        }
      }
      walk(props.children, step)
    })
  }
  React.Children.forEach(steps.props.children, (step, index) =>
    walk(step, index),
  )
  return fieldSteps
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
  // The form the wizard renders, so errors set here show on its fields.
  form: UseFormReturn<FormSchemaType>,
) {
  // Jumps to the earliest step that has any of these fields.
  function goToEarliestStep(fieldNames: string[]) {
    const fieldSteps = collectFieldSteps(steps)
    const stepIndexes = fieldNames
      .map((name) => fieldSteps.get(topLevelKey(name)))
      .filter((step): step is number => step !== undefined)
    if (stepIndexes.length > 0) {
      setCurrentPage(Math.min(...stepIndexes))
    }
  }

  // Shows Payabli's validation gaps on the fields they belong to and jumps
  // to the first one. Returns the gaps the form has no field for (e.g. a
  // template requirement this form doesn't collect).
  function applyValidationErrors(
    missingFields: MissingFieldForForm[],
  ): MissingFieldForForm[] {
    const highlighted: string[] = []
    for (const missing of missingFields) {
      for (const field of missing.formFields) {
        form.setError(field as keyof FormSchemaType, {
          type: 'server',
          message: missing.formMessage,
        })
        highlighted.push(field)
      }
    }
    goToEarliestStep(highlighted)
    return missingFields.filter((missing) => missing.formFields.length === 0)
  }

  // "Fix the highlighted fields." plus anything that couldn't be highlighted.
  function describeValidationFailure(missingFields: MissingFieldForForm[]) {
    const unmapped = applyValidationErrors(missingFields)
    const highlightedCount = missingFields.length - unmapped.length
    const parts = [
      highlightedCount > 0
        ? `Fix the ${highlightedCount === 1 ? 'highlighted field' : `${highlightedCount} highlighted fields`} and submit again.`
        : '',
      ...unmapped.map((missing) => missing.formMessage),
    ]
    return parts.filter(Boolean).join(' ')
  }

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
        throw new SubmissionError(
          failure?.missingFields?.length
            ? describeValidationFailure(failure.missingFields)
            : (failure?.error ??
                'The form could not be submitted successfully.'),
          failure,
        )
      }

      const responseData = (await response.json()) as CreatedApplication

      return responseData
    } catch (error) {
      toast({
        variant: 'destructive',
        title:
          error instanceof SubmissionError && error.details?.missingFields?.length
            ? 'Payabli needs more information'
            : 'Error',
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

    // Zod has already set the field errors; jump to the first step with one.
    goToEarliestStep(Object.keys(errors))
    toast({
      variant: 'destructive',
      title: 'Error!',
      description: 'Some fields need attention. They are highlighted in the form.',
    })
    console.log(errors)
  }

  return {
    onSuccess,
    onError,
    applyValidationErrors,
  }
}
