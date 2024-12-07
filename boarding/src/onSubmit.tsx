import React from 'react'
import { FieldValues } from 'react-hook-form'
import { z } from 'zod'
import { formSchema } from './Schema'
import { toast, useToast } from '@/hooks/use-toast'
import { useFormWithSchema } from './Schema'
import { clearIndexedDB } from './dbUtils'

type FormSchemaType = z.infer<typeof formSchema>

// 1. Create a hook that returns our success and error handlers
export function useFormLogic(
  steps: React.ReactElement,
  setCurrentPage: React.Dispatch<React.SetStateAction<number>>,
) {
  const form = useFormWithSchema()

  // 2. Create a success handler
  async function onSuccess(values: FormSchemaType) {
    
    const apiToken = "o.wZItfIQ05eYXjLXS3lImXPJifGW8tkKZ4SsfVM/LSheG4g0mFYhHZtNOl6M7Xo23AbWxdAHUSXktYpGhgCk1YOguts7O8YPbfScBvfYDFLRF3e7qkWcgPS6tPiM675Y3Z+pdzo/dP+Z10z+aYL6q7SCE7Sikd1xs3kXUkTLq/4R1i5lwC6bkItGws8hRUYaSZxvM0sOAZiJEtC8Le9WN7Qtx35+t12QG2+ThQW5ZDrGNfF+LucpaNynd+ILNSJhi/xayd38EsCElqbj0CHqlT//Uvmc5m7PcNqA6bzK1ntRCK5ncoPCICbobhqJl3NFEkExLKuh3RsFEn2jqzc9ibU6gFWmSa96JwSwPiN2znq4TwlK/nye5sRPw/67cZCH45yfA5AEjsmGTKJLuXYCNtpPJ8XfBnfXkbNRUF8uvkEmiB2RpVsPEQPmyL0dypy4GAfY5Mpzrpd5uRzY5q0P933lLHptjSMDiH5p0Wa1cm+dhkOHmO06MXD2xgQJL/LCA.11x5U4XvxTC/NgBGZVMd+efiCx49YMVuw+zaWtQ6xOY="
    const jsonData = JSON.stringify(values)
    console.log(jsonData)
    const response = await fetch('https://api-sandbox.payabli.com/api/Boarding/app', {
      method: 'POST',
      headers: {
        'content-type': 'application/json',
        'requestToken': apiToken
      },
      body: jsonData
    })
     
    if (!response.ok) {
      toast({
        variant: 'destructive',
        title: `Error: ${response.status}`,
        description: 'The form could not be submitted successfully.'
      })
    } 
    
    const responseBody = await response.json()
    
    console.log(responseBody.responseData)
    return responseBody.responseData
    
    // Uncomment the line below to clear the form data after a successful form submission
    // recommended for production use
    // clearIndexedDB()
  }

  // 3. Create an error handler
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
