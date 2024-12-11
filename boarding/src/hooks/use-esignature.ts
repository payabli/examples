import { useState, useCallback, useRef } from 'react'
import html2canvas from 'html2canvas'
import jsPDF from 'jspdf'
import { create } from 'zustand'

type OtherAttachment = {
  file: File | null
  type: string
  contents: string | null
  extension: string
}

interface ESignatureOptions {
  documentBody: React.ReactNode[]
  otherAttachments: OtherAttachment[]
}

interface ESignatureState {
  isOpen: boolean
  dialogState: 'form' | 'success' | 'error' | 'pricing'
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
  setDialogState: (state: 'form' | 'success' | 'error' | 'pricing') => void
  setSignature: (signature: string) => void
  setPdfUrl: (url: string | null) => void
  setDeviceType: (type: string) => void
  setIpAddress: (ip: string) => void
  setOptions: (options: Partial<ESignatureOptions>) => void
}

export const useESignatureStore = create<ESignatureState & ESignatureActions>(
  (set) => ({
    isOpen: false,
    dialogState: 'pricing',
    signature: '',
    pdfUrl: null,
    deviceType: '',
    ipAddress: '',
    documentBody: [],
    dialogTitle: 'E-Signature Agreement',
    confirmButtonText: 'Confirm and Generate PDF',
    successTitle: 'Submitted!',
    successMessage:
      'Your agreement has been recorded and a PDF has been generated.',
    errorTitle: 'Error',
    errorMessage:
      'An error occurred while processing your submission. Please try again.',
    setIsOpen: (isOpen) => set({ isOpen }),
    setDialogState: (state) => set({ dialogState: state }),
    setSignature: (signature) => set({ signature }),
    setPdfUrl: (url) => set({ pdfUrl: url }),
    setDeviceType: (type) => set({ deviceType: type }),
    setIpAddress: (ip) => set({ ipAddress: ip }),
    setOptions: (options) => set((state) => ({ ...state, ...options })),
  }),
)

export function useESignature({
  documentBody,
  otherAttachments,
}: ESignatureOptions) {
  const store = useESignatureStore()
  const contentRef = useRef<HTMLDivElement>(null)

  const handleESignatureProcess = useCallback(
    (appId: string) => {
      store.setOptions({
        documentBody: documentBody,
      })
      store.setIsOpen(true)
      store.setDeviceType(getDeviceType())
      getPublicIpAddress()
        .then((ip) => store.setIpAddress(ip))
        .catch(() => store.setIpAddress('Unable to fetch IP'))
    },
    [documentBody],
  )

  const handleConfirm = useCallback(
    async (appId: string) => {
      console.log('Confirming with appId: ', appId)
      if (!contentRef.current) return
      try {
        const pdf = await generatePDF(contentRef.current)
        const pdfBlob = pdf.output('blob')
        const url = URL.createObjectURL(pdfBlob)
        store.setPdfUrl(url)

        const pdfContent = pdf.output('datauristring').split(',')[1]

        const attachments = [
          ...otherAttachments,
          {
            file: null,
            type: 'pdf',
            contents: pdfContent,
            extension: '.pdf',
          },
        ]

        console.log(
          'Attachments:',
          JSON.stringify(
            attachments.map(({ file, type, extension }) => ({
              file,
              type,
              extension,
            })),
          ),
        )

        const response = await fetch('/api/attachFiles', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ attachments, appId }),
        })

        if (!response.ok) {
          throw new Error('Failed to attach PDF')
        }

        await fetch('/api/submitApp', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ appId }),
        })

        if (!response.ok) {
          throw new Error('Failed to submit application')
        }

        store.setDialogState('success')
      } catch (error) {
        console.error('Error signing document:', error)
        store.setDialogState('error')
      }
    },
    [otherAttachments],
  )

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

  const pages = content.querySelectorAll('.document-page')

  for (let i = 0; i < pages.length; i++) {
    const page = pages[i] as HTMLElement
    const canvas = await html2canvas(page, {
      scale: scale,
      useCORS: true,
      logging: false,
      allowTaint: true,
      onclone: (clonedDoc) => {
        const clonedPage = clonedDoc.querySelector(
          '.document-page',
        ) as HTMLElement
        if (clonedPage) {
          clonedPage.style.width = `${pageWidth}px`
          clonedPage.style.height = `${pageHeight}px`
          clonedPage.style.position = 'relative'
          clonedPage.style.overflow = 'hidden'
        }
      },
    })

    const imgData = canvas.toDataURL('image/jpeg', 1.0)
    if (i > 0) {
      pdf.addPage()
    }
    pdf.addImage(imgData, 'JPEG', 0, 0, pageWidth, pageHeight, '', 'FAST')
  }

  return pdf
}
