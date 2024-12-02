import { openDB } from 'idb'
import { z } from 'zod'
import { toast } from '@/hooks/use-toast'
import FingerprintJS from '@fingerprintjs/fingerprintjs'
import React, { useCallback } from 'react'

import { formSchema, useFormWithSchema } from './Schema'

type FormSchemaType = z.infer<typeof formSchema>

const DB_NAME = 'FormDB'
const STORE_NAME = 'formData'
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

// Function to derive an encryption key from the device token
async function getEncryptionKey(deviceToken: string): Promise<CryptoKey> {
  const encoder = new TextEncoder()
  const keyMaterial = await window.crypto.subtle.importKey(
    'raw',
    encoder.encode(deviceToken),
    { name: 'PBKDF2' },
    false,
    ['deriveKey'],
  )

  return window.crypto.subtle.deriveKey(
    {
      name: 'PBKDF2',
      salt: encoder.encode('FormDBSalt'),
      iterations: 100000,
      hash: 'SHA-256',
    },
    keyMaterial,
    { name: 'AES-GCM', length: 256 },
    false,
    ['encrypt', 'decrypt'],
  )
}

// Function to encrypt data
async function encryptData(data: any): Promise<string> {
  const deviceToken = await getDeviceToken()
  const key = await getEncryptionKey(deviceToken)
  const encoder = new TextEncoder()
  const encodedData = encoder.encode(JSON.stringify(data))
  const iv = window.crypto.getRandomValues(new Uint8Array(12))
  const encryptedData = await window.crypto.subtle.encrypt(
    { name: 'AES-GCM', iv },
    key,
    encodedData,
  )
  const encryptedArray = new Uint8Array(iv.length + encryptedData.byteLength)
  encryptedArray.set(iv)
  encryptedArray.set(new Uint8Array(encryptedData), iv.length)
  return btoa(String.fromCharCode.apply(null, encryptedArray as any))
}

// Function to decrypt data
async function decryptData(encryptedData: string): Promise<any> {
  const deviceToken = await getDeviceToken()
  const key = await getEncryptionKey(deviceToken)
  const decoder = new TextDecoder()
  const encryptedArray = new Uint8Array(
    atob(encryptedData)
      .split('')
      .map((char) => char.charCodeAt(0)),
  )
  const iv = encryptedArray.slice(0, 12)
  const data = encryptedArray.slice(12)
  const decryptedData = await window.crypto.subtle.decrypt(
    { name: 'AES-GCM', iv },
    key,
    data,
  )
  return JSON.parse(decoder.decode(decryptedData))
}

export const saveToIndexedDB = async (data: any) => {
  const db = await openDB(DB_NAME, 1, {
    upgrade(db) {
      db.createObjectStore(STORE_NAME)
    },
  })
  const encryptedData = await encryptData(data)
  await db.put(STORE_NAME, encryptedData, 'userForm')
}

export const loadFromIndexedDB = async () => {
  const db = await openDB(DB_NAME, 1)
  const encryptedData = await db.get(STORE_NAME, 'userForm')
  if (encryptedData) {
    return decryptData(encryptedData)
  }
  return null
}

export const clearIndexedDB = async () => {
  const db = await openDB(DB_NAME, 1)
  await db.clear(STORE_NAME)
}

// custom hook to save and clear form data
export const useIndexedDB = () => {
  const form = useFormWithSchema()

  const saveForLater = useCallback(async (formData: any) => {
    try {
      const db = await openDB(DB_NAME, 1, {
        upgrade(db) {
          db.createObjectStore(STORE_NAME)
        },
      })
      const encryptedData = await encryptData(formData)
      await db.put(STORE_NAME, encryptedData, 'userForm')
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
        description: 'Failed to save form data.',
      })
    }
  }, [])

  const clearFormData = useCallback(async () => {
    try {
      form.reset()
      const db = await openDB(DB_NAME, 1)
      await db.clear(STORE_NAME)
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
        description: 'Failed to clear form data.',
      })
    }
  }, [form])

  const loadSavedData = useCallback(async () => {
    try {
      const db = await openDB(DB_NAME, 1)
      const encryptedData = await db.get(STORE_NAME, 'userForm')
      if (encryptedData) {
        return await decryptData(encryptedData)
      }
    } catch (error) {
      console.error('Error loading saved data:', error)
      toast({
        variant: 'destructive',
        title: 'Error!',
        description: 'Failed to load saved form data.',
      })
    }
    return null
  }, [])

  return { saveForLater, clearFormData, loadSavedData }
}
