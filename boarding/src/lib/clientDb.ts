import { z } from 'zod'
import { toast } from '@/hooks/use-toast'
import React, { useCallback, useState, useEffect } from 'react'
import { formSchema, useFormWithSchema } from '../Schema'
import { authClient } from './authClient'

type FormSchemaType = z.infer<typeof formSchema>

export const useDrizzle = () => {
  const form = useFormWithSchema()
  const [isLoading, setIsLoading] = useState(true)
  const [userId, setUserId] = useState<string | null>(null)
  const [isReady, setIsReady] = useState(false)

  useEffect(() => {
    let isMounted = true;
    const initializeAuth = async () => {
      try {
        const { data } = await authClient.getSession();
        if (isMounted && data?.user?.id) {
          setUserId(data.user.id);
          setIsReady(true);
        }
      } catch (error) {
        console.error('Error initializing auth:', error);
        if (isMounted) {
          setIsReady(true); // Set to true even on error to allow the app to proceed
        }
      }
    };

    initializeAuth();

    return () => {
      isMounted = false;
    };
  }, []);

  useEffect(() => {
    if (!isReady || !userId) return;

    const loadInitialData = async () => {
      try {
        const savedData = await loadFromDrizzle(userId)
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

    loadInitialData();
  }, [form, userId, isReady]);

  const saveForLater = useCallback(async (formData: FormSchemaType) => {
    if (!userId) return
    try {
      await saveToDrizzle(formData, userId)
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
  }, [userId])

  const clearFormData = useCallback(async () => {
    if (!userId) return
    try {
      form.reset()
      await clearDrizzle(userId)
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
  }, [form, userId])

  const loadSavedData = useCallback(async (): Promise<FormSchemaType | null> => {
    if (!userId) return null
    try {
      setIsLoading(true)
      const savedData = await loadFromDrizzle(userId)
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
  }, [form, userId])

  return { form, isLoading, saveForLater, clearFormData, loadSavedData, isReady }
}

async function saveToDrizzle(data: FormSchemaType, userId: string) {
  try {
    const response = await fetch('/api/formData', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: 'save',
        encryptedIdentifier: userId, // Use userId as the encryptedIdentifier
        encryptedData: JSON.stringify(data), // Encrypt data if needed
      }),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(`Failed to save data: ${errorData.error}`);
    }
  } catch (error) {
    console.error('Error in saveToDrizzle:', error);
    if (error instanceof Error) {
      console.error('Error name:', error.name);
      console.error('Error message:', error.message);
      console.error('Error stack:', error.stack);
    }
    throw error;
  }
}

async function loadFromDrizzle(userId: string): Promise<FormSchemaType | null> {
  try {
    const response = await fetch('/api/formData', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: 'load',
        encryptedIdentifier: userId, // Use userId as the encryptedIdentifier
      }),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(`Failed to load data: ${errorData.error}`);
    }

    const { encryptedData } = await response.json();
    
    if (encryptedData) {
      return JSON.parse(encryptedData) as FormSchemaType;
    }
    
    return null;
  } catch (error) {
    console.error('Error in loadFromDrizzle:', error);
    throw error;
  }
}

async function clearDrizzle(userId: string) {
  const response = await fetch('/api/formData', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      action: 'clear',
      encryptedIdentifier: userId, // Use userId as the encryptedIdentifier
    }),
  });

  if (!response.ok) {
    const errorData = await response.json();
    throw new Error(`Failed to clear data: ${errorData.error}`);
  }
}
