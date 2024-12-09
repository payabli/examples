import { z } from 'zod'
import { toast } from '@/hooks/use-toast'
import FingerprintJS from '@fingerprintjs/fingerprintjs'
import React, { useCallback, useState, useEffect } from 'react'

import { formSchema, useFormWithSchema } from '../Schema'

type FormSchemaType = z.infer<typeof formSchema>

const DEVICE_TOKEN_KEY = 'deviceToken'

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

async function apiRequest(action: string, data?: FormSchemaType) {
  const deviceToken = await getDeviceToken();
  const response = await fetch('/api/formData', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ action, deviceToken, data }),
  });
  
  if (!response.ok) {
    const text = await response.text();
    console.error('API Error:', text);
    throw new Error(`API request failed: ${response.status} ${response.statusText}`);
  }
  
  return response.json();
}

export const saveToDrizzle = async (data: FormSchemaType) => {
  try {
    await apiRequest('save', data);
    console.log('Data saved successfully');
  } catch (error) {
    console.error('Error in saveToDrizzle:', error);
    throw error;
  }
}

export const loadFromDrizzle = async (): Promise<FormSchemaType | null> => {
  try {
    const { data } = await apiRequest('load');
    if (data) {
      console.log('Data loaded successfully');
      return JSON.parse(data);
    }
    console.log('No data found');
    return null;
  } catch (error) {
    console.error('Error in loadFromDrizzle:', error);
    return null;
  }
}

export const clearDrizzle = async () => {
  try {
    await apiRequest('clear');
    console.log('Data cleared successfully');
  } catch (error) {
    console.error('Error in clearDrizzle:', error);
    throw error;
  }
}

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
        return savedData
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

