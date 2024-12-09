import { z } from 'zod'
import { toast } from '@/hooks/use-toast'
import FingerprintJS from '@fingerprintjs/fingerprintjs'
import React, { useCallback, useState, useEffect } from 'react'
import { db, formData } from './db'
import { eq } from 'drizzle-orm'

import { formSchema, useFormWithSchema } from './Schema'

type FormSchemaType = z.infer<typeof formSchema>

const DEVICE_TOKEN_KEY = 'deviceToken'

// Function to generate or retrieve the device token
async function getDeviceToken(): Promise<string> {
  let token = localStorage.getItem(DEVICE_TOKEN_KEY)
  if (!token) {
    const fp = await FingerprintJS.load()
    const result = await fp.get()
    token = result.visitorId
    localStorage.setItem(DEVICE_TOKEN_KEY, token)
  }
  return token
}

export const saveToDrizzle = async (data: FormSchemaType) => {
  try {
    const deviceToken = await getDeviceToken()
    await db.insert(formData).values({
      deviceToken,
      data: JSON.stringify(data),
    }).onConflictDoUpdate({
      target: formData.deviceToken,
      set: { data: JSON.stringify(data) },
    })
    console.log('Data saved successfully')
  } catch (error) {
    console.error('Error in saveToDrizzle:', error)
    throw error
  }
}

export const loadFromDrizzle = async (): Promise<FormSchemaType | null> => {
  try {
    const deviceToken = await getDeviceToken()
    const result = await db.select().from(formData).where(eq(formData.deviceToken, deviceToken))
    if (result.length > 0) {
      const parsedData = JSON.parse(result[0].data) as FormSchemaType
      console.log('Data loaded successfully')
      return parsedData
    }
    console.log('No data found in Drizzle')
    return null
  } catch (error) {
    console.error('Error in loadFromDrizzle:', error)
    return null
  }
}

export const clearDrizzle = async () => {
  try {
    const deviceToken = await getDeviceToken()
    await db.delete(formData).where(eq(formData.deviceToken, deviceToken))
    console.log('Drizzle data cleared successfully')
  } catch (error) {
    console.error('Error in clearDrizzle:', error)
    throw error
  }
}

// custom hook to save and clear form data
export const useDrizzle = () => {
  const form = useFormWithSchema()
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    const loadInitialData = async () => {
      try {
        const savedData = await loadFromDrizzle()
        if (savedData) {
          form.reset(savedData)
        }
      } catch (error) {
        console.error('Error loading initial data:', error)
        toast({
          variant: 'destructive',
          title: 'Error!',
          description: 'Failed to load saved form data. Please try again.',
        })
      } finally {
        setIsLoading(false)
      }
    }

    loadInitialData()
  }, [form])

  const saveForLater = useCallback(async (formData: FormSchemaType) => {
    try {
      await saveToDrizzle(formData)
      toast({
        variant: 'default',
        title: 'Saved!',
        description:
          'Your progress has been saved. You can safely close this tab and come back later.',
      })
    } catch (error) {
      console.error('Error saving form data:', error)
      toast({
        variant: 'destructive',
        title: 'Error!',
        description: 'Failed to save form data. Please try again.',
      })
    }
  }, [])

  const clearFormData = useCallback(async () => {
    try {
      form.reset()
      await clearDrizzle()
      toast({
        variant: 'default',
        title: 'Cleared!',
        description: 'Your progress has been cleared.',
      })
    } catch (error) {
      console.error('Error clearing form data:', error)
      toast({
        variant: 'destructive',
        title: 'Error!',
        description: 'Failed to clear form data. Please try again.',
      })
    }
  }, [form])

  const loadSavedData = useCallback(async (): Promise<FormSchemaType | null> => {
    try {
      setIsLoading(true)
      const savedData = await loadFromDrizzle()
      if (savedData) {
        form.reset(savedData)
        toast({
          variant: 'default',
          title: 'Data Loaded',
          description: 'Your saved progress has been loaded successfully.',
        })
        return savedData
      } else {
        toast({
          variant: 'default',
          title: 'No Saved Data',
          description: 'No previously saved data was found.',
        })
      }
      return null
    } catch (error) {
      console.error('Error loading saved data:', error)
      toast({
        variant: 'destructive',
        title: 'Error!',
        description: 'Failed to load saved form data. Please try again.',
      })
      return null
    } finally {
      setIsLoading(false)
    }
  }, [form])

  return { form, isLoading, saveForLater, clearFormData, loadSavedData }
}

