import { z } from 'zod'
import { toast } from '@/hooks/use-toast'
import React, { useCallback, useState, useEffect } from 'react'
import { formSchema, useFormWithSchema } from '../Schema'
import FingerprintJS, { Agent } from '@fingerprintjs/fingerprintjs'

type FormSchemaType = z.infer<typeof formSchema>

export const useDrizzle = () => {
  const form = useFormWithSchema()
  const [isLoading, setIsLoading] = useState(true)
  const [fpInstance, setFpInstance] = useState<Agent | null>(null)
  const [encryptedIdentifier, setEncryptedIdentifier] = useState<string | null>(null)
  const [isReady, setIsReady] = useState(false)

  useEffect(() => {
    let isMounted = true;
    const initializeFingerprintJS = async () => {
      try {
        const fp = await FingerprintJS.load();
        if (isMounted) {
          setFpInstance(fp);
          const identifier = await getEncryptedIdentifier(fp);
          setEncryptedIdentifier(identifier);
          setIsReady(true);
        }
      } catch (error) {
        console.error('Error initializing FingerprintJS:', error);
        if (isMounted) {
          setIsReady(true); // Set to true even on error to allow the app to proceed
        }
      }
    };

    initializeFingerprintJS();

    return () => {
      isMounted = false;
    };
  }, []);

  useEffect(() => {
    if (!isReady) return;

    const loadInitialData = async () => {
      if (!fpInstance || !encryptedIdentifier) {
        setIsLoading(false);
        return;
      }
      try {
        const savedData = await loadFromDrizzle(encryptedIdentifier, fpInstance)
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
  }, [form, fpInstance, encryptedIdentifier, isReady]);

  const saveForLater = useCallback(async (formData: FormSchemaType) => {
    if (!fpInstance || !encryptedIdentifier) return
    try {
      await saveToDrizzle(formData, encryptedIdentifier, fpInstance)
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
  }, [fpInstance, encryptedIdentifier])

  const clearFormData = useCallback(async () => {
    if (!fpInstance || !encryptedIdentifier) return
    try {
      form.reset()
      await clearDrizzle(encryptedIdentifier)
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
  }, [form, fpInstance, encryptedIdentifier])

  const loadSavedData = useCallback(async (): Promise<FormSchemaType | null> => {
    if (!fpInstance || !encryptedIdentifier) return null
    try {
      setIsLoading(true)
      const savedData = await loadFromDrizzle(encryptedIdentifier, fpInstance)
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
  }, [form, fpInstance, encryptedIdentifier])

  return { form, isLoading, saveForLater, clearFormData, loadSavedData, isReady }
}

async function saveToDrizzle(data: FormSchemaType, encryptedIdentifier: string, fpInstance: Agent) {
  try {
    const encryptedData = await encryptData(JSON.stringify(data), fpInstance);
    const response = await fetch('/api/formData', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: 'save',
        encryptedIdentifier,
        encryptedData,
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

async function loadFromDrizzle(encryptedIdentifier: string, fpInstance: Agent): Promise<FormSchemaType | null> {
  
  try {
    const response = await fetch('/api/formData', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: 'load',
        encryptedIdentifier,
      }),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(`Failed to load data: ${errorData.error}`);
    }

    const { encryptedData } = await response.json();
    
    if (encryptedData) {
      
      try {
        const decryptedData = await decryptData(encryptedData, fpInstance);
        
        const parsedData = JSON.parse(decryptedData) as FormSchemaType;
        return parsedData;
      } catch (decryptError) {
        console.error('Error during decryption or parsing:', decryptError);
        throw new Error('Failed to decrypt or parse loaded data');
      }
    }
    
    return null;
  } catch (error) {
    console.error('Error in loadFromDrizzle:', error);
    throw error;
  }
}

async function clearDrizzle(encryptedIdentifier: string) {
  
  const response = await fetch('/api/formData', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      action: 'clear',
      encryptedIdentifier,
    }),
  });

  if (!response.ok) {
    const errorData = await response.json();
    throw new Error(`Failed to clear data: ${errorData.error}`);
  }
}

async function getEncryptedIdentifier(fpInstance: Agent): Promise<string> {
  const result = await fpInstance.get();
  
  // Use only stable components that are unlikely to change between sessions
  const stableIdentifier = [
    result.visitorId,
  ].join('|');


  const encryptedId = await deterministicEncrypt(stableIdentifier);
  return encryptedId;
}

async function encryptData(data: string, fpInstance: Agent): Promise<string> {
  const result = await fpInstance.get();
  const visitorId = result.visitorId;
  const encoder = new TextEncoder();
  const dataBuffer = encoder.encode(data);
  
  try {
    const keyMaterial = await crypto.subtle.importKey(
      'raw',
      encoder.encode(visitorId),
      { name: 'PBKDF2' },
      false,
      ['deriveBits', 'deriveKey']
    );
    
    const salt = crypto.getRandomValues(new Uint8Array(16));
    const iv = crypto.getRandomValues(new Uint8Array(12));
    
    const key = await crypto.subtle.deriveKey(
      {
        name: 'PBKDF2',
        salt: salt,
        iterations: 100000,
        hash: 'SHA-256'
      },
      keyMaterial,
      { name: 'AES-GCM', length: 256 },
      false,
      ['encrypt']
    );

    const encryptedBuffer = await crypto.subtle.encrypt(
      { name: 'AES-GCM', iv: iv },
      key,
      dataBuffer
    ).catch(error => {
      console.error('Encryption operation failed:', error);
      throw error;
    });

    const encryptedArray = new Uint8Array(salt.length + iv.length + encryptedBuffer.byteLength);
    encryptedArray.set(salt, 0);
    encryptedArray.set(iv, salt.length);
    encryptedArray.set(new Uint8Array(encryptedBuffer), salt.length + iv.length);

    const encryptedBase64 = btoa(String.fromCharCode.apply(null, encryptedArray as any));

    return encryptedBase64;
  } catch (error) {
    console.error('Encryption failed:', error);
    if (error instanceof Error) {
      console.error('Error name:', error.name);
      console.error('Error message:', error.message);
      console.error('Error stack:', error.stack);
    }
    throw new Error('Failed to encrypt data: ' + (error instanceof Error ? error.message : String(error)));
  }
}

async function decryptData(encryptedData: string, fpInstance: Agent): Promise<string> {
  const result = await fpInstance.get();
  const visitorId = result.visitorId;
  const encoder = new TextEncoder();
  
  try {
    const encryptedArray = Uint8Array.from(atob(encryptedData), c => c.charCodeAt(0));

    if (encryptedArray.length < 28) {
      throw new Error('Encrypted data is too short');
    }

    const salt = encryptedArray.slice(0, 16);
    const iv = encryptedArray.slice(16, 28);
    const data = encryptedArray.slice(28);

    const keyMaterial = await crypto.subtle.importKey(
      'raw',
      encoder.encode(visitorId),
      { name: 'PBKDF2' },
      false,
      ['deriveBits', 'deriveKey']
    );

    const key = await crypto.subtle.deriveKey(
      {
        name: 'PBKDF2',
        salt: salt,
        iterations: 100000,
        hash: 'SHA-256'
      },
      keyMaterial,
      { name: 'AES-GCM', length: 256 },
      false,
      ['decrypt']
    );

    const decryptedBuffer = await crypto.subtle.decrypt(
      { name: 'AES-GCM', iv: iv },
      key,
      data
    ).catch(error => {
      console.error('Decryption operation failed:', error);
      throw error;
    });

    const decoder = new TextDecoder();
    const decryptedText = decoder.decode(decryptedBuffer);

    return decryptedText;
  } catch (error) {
    console.error('Decryption failed:', error);
    if (error instanceof Error) {
      console.error('Error name:', error.name);
      console.error('Error message:', error.message);
      console.error('Error stack:', error.stack);
    }
    throw new Error('Failed to decrypt data: ' + (error instanceof Error ? error.message : String(error)));
  }
}

async function deterministicEncrypt(data: string): Promise<string> {
  const encoder = new TextEncoder();
  const dataBuffer = encoder.encode(data);
  
  // Use SHA-256 for a deterministic hash
  const hashBuffer = await crypto.subtle.digest('SHA-256', dataBuffer);
  const hashArray = Array.from(new Uint8Array(hashBuffer));
  const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
  
  return hashHex;
}

