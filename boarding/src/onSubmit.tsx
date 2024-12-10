import React from 'react'
import { FieldValues } from 'react-hook-form'
import { z } from 'zod'
import { formSchema } from './Schema'
import { toast, useToast } from '@/hooks/use-toast'
import { useFormWithSchema } from './Schema'

type FormSchemaType = z.infer<typeof formSchema>

export function useFormLogic(
  steps: React.ReactElement,
  setCurrentPage: React.Dispatch<React.SetStateAction<number>>,
) {
  const form = useFormWithSchema()

  async function onSuccess(values: FormSchemaType) {
    try {
      const response = await fetch('/api/submitApp', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(values),
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const responseData = await response.json()

      return responseData
    } catch (error) {
      toast({
        variant: 'destructive',
        title: 'Error',
        description: 'The form could not be submitted successfully.',
      })
      console.error('Submission error:', error)
    }
  }

  function onError(errors: FieldValues) {
    form.trigger().then(() => {
      if (steps && steps.props && steps.props.children) {
        const errorPages = React.Children.map(
          steps.props.children,
          (step: React.ReactElement, index: number) => {
            const errorElementIndex = findErrorElementIndex(step)
            return errorElementIndex !== -1 ? index : 0
          },
        )
        const earliestErrorPage = Math.min(...errorPages) || 0

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
      const name = element.props.name as keyof FormSchemaType
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
