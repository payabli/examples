import { useState, useRef, useCallback } from 'react'
import html2canvas from 'html2canvas'
import jsPDF from 'jspdf'
import { create } from 'zustand'

interface ESignatureOptions {
  documentBody: JSX.Element[]
  onSubmit: () => any
  dialogTitle?: string
  confirmButtonText?: string
  successTitle?: string
  successMessage?: string
  errorTitle?: string
  errorMessage?: string
}

interface ESignatureState {
  isOpen: boolean
  dialogState: 'form' | 'success' | 'error'
  signature: string
  pdfUrl: string | null
  deviceType: string
  ipAddress: string
  documentBody: string[]
  dialogTitle: string
  confirmButtonText: string
  successTitle: string
  successMessage: string
  errorTitle: string
  errorMessage: string
}

interface ESignatureActions {
  setIsOpen: (isOpen: boolean) => void
  setDialogState: (state: 'form' | 'success' | 'error') => void
  setSignature: (signature: string) => void
  setPdfUrl: (url: string | null) => void
  setDeviceType: (type: string) => void
  setIpAddress: (ip: string) => void
  initializeOptions: (options: ESignatureOptions) => void
}

export const useESignatureStore = create<ESignatureState & ESignatureActions>((set) => ({
  isOpen: false,
  dialogState: 'form',
  signature: '',
  pdfUrl: null,
  deviceType: '',
  ipAddress: '',
  documentBody: [],
  dialogTitle: 'E-Signature Agreement',
  confirmButtonText: 'Confirm and Generate PDF',
  successTitle: 'Submitted!',
  successMessage: 'Your agreement has been recorded and a PDF has been generated.',
  errorTitle: 'Error',
  errorMessage: 'An error occurred while processing your submission. Please try again.',
  setIsOpen: (isOpen) => set({ isOpen }),
  setDialogState: (state) => set({ dialogState: state }),
  setSignature: (signature) => set({ signature }),
  setPdfUrl: (url) => set({ pdfUrl: url }),
  setDeviceType: (type) => set({ deviceType: type }),
  setIpAddress: (ip) => set({ ipAddress: ip }),
  initializeOptions: (options) => set((state) => ({ ...state, ...options })),
}))

export function useESignature(options: ESignatureOptions) {
  const store = useESignatureStore()
  const contentRef = useRef<HTMLDivElement>(null)

  const handleESignatureProcess = useCallback(async () => {
    store.initializeOptions(options)
    store.setIsOpen(true)
    store.setDeviceType(getDeviceType())
    try {
      const ip = await getPublicIpAddress()
      store.setIpAddress(ip)
    } catch (error) {
      console.error('Error fetching IP:', error)
      store.setIpAddress('Unable to fetch IP')
    }
  }, [options])

  const handleConfirm = useCallback(async () => {
    if (!contentRef.current) return

    try {
      const pdf = await generatePDF(contentRef.current)
      const pdfBlob = pdf.output('blob')
      const url = URL.createObjectURL(pdfBlob)
      
      await options.onSubmit()
      
      store.setPdfUrl(url)
      store.setDialogState('success')
    } catch (error) {
      console.error('Error generating PDF:', error)
      store.setDialogState('error')
    }
  }, [options.onSubmit])

  const closeDialog = useCallback(() => {
    store.setIsOpen(false)
    store.setDialogState('form')
    store.setSignature('')
  }, [])

  return {
    handleESignatureProcess,
    handleConfirm,
    closeDialog,
    setSignature: store.setSignature,
    contentRef,
  }
}

function getDeviceType(): string {
  const userAgent = navigator.userAgent.toLowerCase()
  const platform = navigator.platform.toLowerCase()

  if (userAgent.includes('win')) return 'Windows PC'
  if (userAgent.includes('mac')) return 'MacBook'
  if (userAgent.includes('iphone')) return 'iPhone'
  if (userAgent.includes('ipad')) return 'iPad'
  if (userAgent.includes('android')) {
    return userAgent.includes('mobile') ? 'Android Phone' : 'Android Tablet'
  }
  if (platform.includes('linux')) return 'Linux'

  return 'Unknown Device'
}

async function getPublicIpAddress(): Promise<string> {
  const response = await fetch('https://api.ipify.org?format=json')
  const data = await response.json()
  return data.ip
}

async function generatePDF(content: HTMLElement): Promise<jsPDF> {
  const pdf = new jsPDF({ format: 'a4', unit: 'pt' })
  const scale = 2
  const pageWidth = pdf.internal.pageSize.getWidth()
  const pageHeight = pdf.internal.pageSize.getHeight()

  const pages = content.querySelectorAll('.mb-8')
  
  for (let i = 0; i < pages.length; i++) {
    const page = pages[i] as HTMLElement
    const canvas = await html2canvas(page, {
      scale: scale,
      useCORS: true,
      logging: false,
      allowTaint: true,
      // letterRendering: true,
    })

    const imgData = canvas.toDataURL('image/jpeg', 1.0)
    if (i > 0) {
      pdf.addPage()
    }
    pdf.addImage(imgData, 'JPEG', 0, 0, pageWidth, pageHeight, '', 'FAST')
  }

  return pdf
}

