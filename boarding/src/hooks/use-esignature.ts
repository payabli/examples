import { useState, useCallback, useRef } from 'react'
import type { jsPDF } from 'jspdf'
import { create } from 'zustand'

interface ESignatureOptions {
  documentBody: React.ReactNode[]
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
      'Your application has been submitted to Payabli. Note: this demo does not yet attach the signed PDF or bank documents to the application -- the v2 attachment API is still in development (DOC-2546).',
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

export function useESignature({ documentBody }: ESignatureOptions) {
  const store = useESignatureStore()
  const contentRef = useRef<HTMLDivElement>(null)

  const handleESignatureProcess = useCallback(
    (applicationReference: string) => {
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
    async (applicationReference: string, signerPersonReference: string) => {
      console.log('Confirming with applicationReference: ', applicationReference)
      if (!applicationReference || !signerPersonReference) {
        throw new Error(
          'Missing application reference or signer reference for e-signature submission',
        )
      }
      if (!contentRef.current) return
      try {
        // The rendered agreement PDF is kept as a local receipt for the user
        // to download (see the "Download PDF" button on the success screen).
        // It is NOT sent to Payabli -- the submit call below carries the
        // signature itself; there's no separate attachment endpoint (Cole,
        // 2026-09-22).
        const pdf = await generatePDF(contentRef.current)
        const pdfBlob = pdf.output('blob')
        const url = URL.createObjectURL(pdfBlob)
        store.setPdfUrl(url)

        const submitResponse = await fetch('/api/submitApp', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            applicationReference,
            signer: {
              personReference: signerPersonReference,
              // The dialog's signature input is a typed "First and Last Name"
              // field, so the printed name and the signature are the same string.
              name: store.signature,
              signature: store.signature,
              signatureType: 'type',
              // Gated by the dialog's "I agree to terms and conditions"
              // checkbox -- the AGREE button is disabled until it's checked,
              // so reaching this call means the user already accepted.
              acceptance: true,
              // No dedicated PCI-attestation disclosure exists in this demo's
              // UI yet; bundled into the same terms checkbox for now. A real
              // integration should confirm with Payabli whether a distinct
              // PCI attestation disclosure is required.
              pciAttestation: true,
            },
          }),
        })

        const submitResponseBody = await submitResponse
          .json()
          .catch(() => null)

        if (!submitResponse.ok) {
          throw new Error(
            submitResponseBody?.error || 'Failed to submit application',
          )
        }

        store.setDialogState('success')
      } catch (error) {
        console.error('Error signing document:', error)
        store.setDialogState('error')
      }
    },
    [],
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
  const [{ default: html2canvas }, { jsPDF }] = await Promise.all([
    import('html2canvas'),
    import('jspdf'),
  ])
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
