import React from 'react'
import { FieldValues } from 'react-hook-form'
import { z } from 'zod'
import { formSchema } from './Schema'
import { toast } from '@/hooks/use-toast'
import { useFormWithSchema } from './Schema'
import { saveToIndexedDB, loadFromIndexedDB, clearIndexedDB } from './dbUtils'

type FormSchemaType = z.infer<typeof formSchema>

// 1. Create a hook that returns our success and error handlers
export function useFormLogic(
  steps: React.ReactElement,
  setCurrentPage: React.Dispatch<React.SetStateAction<number>>,
) {
  const form = useFormWithSchema()

  // 2. Create a success handler
  function onSuccess(values: FormSchemaType) {
    console.log(values)
    toast({
      variant: 'default',
      title: 'Success!',
      description: (
        <pre className="mt-2 w-fit rounded-md border-[1px] border-white bg-slate-950 p-4">
          <code className="text-white">{JSON.stringify(values, null, 2)}</code>
        </pre>
      ),
    })
    clearIndexedDB()
  }

  // 3. Create an error handler
  function onError(errors: FieldValues) {
    form.trigger().then(() => {
      if (steps && steps.props && steps.props.children) {
        const errorPages = React.Children.map(
          steps.props.children,
          (step: React.ReactElement, index: number) => {
            const errorElementIndex = findErrorElementIndex(step)
            return errorElementIndex !== -1 ? index : Infinity
          },
        )
        const earliestErrorPage = Math.min(...errorPages)

        toast({
          variant: 'destructive',
          title: 'Error!',
          description: 'The form could not be submitted successfully.',
        })

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
