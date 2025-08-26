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
      // Transform the data to match the new API format
      const transformedValues = {
        ...values,
        // Remove id from bankData items for POST requests and ensure bankAccountFunction is a number
        bankData: values.bankData?.map(({ ...bank }) => {
          // Don't include id field in POST requests
          const bankWithoutId = { ...bank };
          delete (bankWithoutId as any).id;
          // Ensure bankAccountFunction is a number
          bankWithoutId.bankAccountFunction = Number(bankWithoutId.bankAccountFunction);
          return bankWithoutId;
        }) || [],
      };

      // Remove deprecated fields if they exist
      delete (transformedValues as any).depositAccount;
      delete (transformedValues as any).withdrawalAccount;

      const response = await fetch('/api/createApp', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(transformedValues),
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
