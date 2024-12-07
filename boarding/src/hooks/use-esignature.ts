import { useState, useCallback, useRef } from 'react'
import html2canvas from 'html2canvas'
import jsPDF from 'jspdf'
import { create } from 'zustand'

interface ESignatureOptions {
  documentBody: React.ReactNode[]
}

interface ESignatureState {
  isOpen: boolean
  dialogState: 'form' | 'success' | 'error'
  signature: string
  pdfUrl: string | null
  deviceType: string
  ipAddress: string
  documentBody: React.ReactNode[]
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
  setOptions: (options: Partial<ESignatureOptions>) => void
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
  setOptions: (options) => set((state) => ({ ...state, ...options })),
}))

export function useESignature(options: ESignatureOptions) {
  const store = useESignatureStore()
  const contentRef = useRef<HTMLDivElement>(null)

  const handleESignatureProcess = useCallback((appId: string) => {
    store.setOptions({
      documentBody: options.documentBody,
    })
    store.setIsOpen(true)
    store.setDeviceType(getDeviceType())
    getPublicIpAddress().then(ip => store.setIpAddress(ip)).catch(() => store.setIpAddress('Unable to fetch IP'))
  }, [options.documentBody])

  const handleConfirm = useCallback(async (appId: string) => {
    console.log('Confirming with appId: ', appId)
    if (!contentRef.current) return
    try {
      const pdf = await generatePDF(contentRef.current)
      const pdfBlob = pdf.output('blob')
      const url = URL.createObjectURL(pdfBlob)
      store.setPdfUrl(url)
      
      await attachPDF(pdf.output('datauristring').split(',')[1], appId)
      
      store.setDialogState('success')
    } catch (error) {
      console.error('Error signing document:', error)
      store.setDialogState('error')
    }
  }, [])

  return {
    handleESignatureProcess,
    handleConfirm,
    contentRef,
  }
}

function getDeviceType(): string {
  const userAgent = navigator.userAgent.toLowerCase()
  const platform = navigator.platform.toLowerCase()

  if (userAgent.includes('win')) return 'Windows'
  if (userAgent.includes('mac')) return 'macOS'
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
    })

    const imgData = canvas.toDataURL('image/jpeg', 1.0)
    if (i > 0) {
      pdf.addPage()
    }
    pdf.addImage(imgData, 'JPEG', 0, 0, pageWidth, pageHeight, '', 'FAST')
  }

  return pdf
}

async function attachPDF(pdfContent: string, appId: string) {
  console.log('Attaching PDF to application:', appId)
  const apiToken = "o.wZItfIQ05eYXjLXS3lImXPJifGW8tkKZ4SsfVM/LSheG4g0mFYhHZtNOl6M7Xo23AbWxdAHUSXktYpGhgCk1YOguts7O8YPbfScBvfYDFLRF3e7qkWcgPS6tPiM675Y3Z+pdzo/dP+Z10z+aYL6q7SCE7Sikd1xs3kXUkTLq/4R1i5lwC6bkItGws8hRUYaSZxvM0sOAZiJEtC8Le9WN7Qtx35+t12QG2+ThQW5ZDrGNfF+LucpaNynd+ILNSJhi/xayd38EsCElqbj0CHqlT//Uvmc5m7PcNqA6bzK1ntRCK5ncoPCICbobhqJl3NFEkExLKuh3RsFEn2jqzc9ibU6gFWmSa96JwSwPiN2znq4TwlK/nye5sRPw/67cZCH45yfA5AEjsmGTKJLuXYCNtpPJ8XfBnfXkbNRUF8uvkEmiB2RpVsPEQPmyL0dypy4GAfY5Mpzrpd5uRzY5q0P933lLHptjSMDiH5p0Wa1cm+dhkOHmO06MXD2xgQJL/LCA.11x5U4XvxTC/NgBGZVMd+efiCx49YMVuw+zaWtQ6xOY="
  
  const callBody = {
    attachments: [
      {
        ftype: "pdf",
        filename: "esignature.pdf",
        fContent: pdfContent
      }
    ]
  }
  
  const response = await fetch(`https://api-sandbox.payabli.com/api/Boarding/app/${appId}`, {
    method: 'PUT',
    headers: {
      'content-type': 'application/json',
      'requestToken': apiToken
    },
    body: JSON.stringify(callBody),
  })
   
  if (!response.ok) {
    throw new Error('Failed to attach PDF')
  }
}

