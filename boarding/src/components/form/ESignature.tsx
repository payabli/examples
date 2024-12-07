import React, { useRef, useState, useEffect } from 'react'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { CheckCircle, XCircle, User, Check, Loader2Icon, Download } from 'lucide-react'
import { useESignatureStore } from '@/hooks/use-esignature'
import { Separator } from '@/components/ui/separator'
import { motion, AnimatePresence } from 'framer-motion'

interface ESignatureProps {
  contentRef: React.RefObject<HTMLDivElement>
  onConfirm: () => void
}

export function ESignature({ contentRef, onConfirm }: ESignatureProps) {
  const {
    isOpen,
    dialogState,
    signature,
    setSignature,
    pdfUrl,
    deviceType,
    ipAddress,
    documentBody,
    dialogTitle,
    confirmButtonText,
    successTitle,
    successMessage,
    errorTitle,
    errorMessage,
    setIsOpen,
    setDialogState,
  } = useESignatureStore()

  const [loading, setLoading] = useState(false)
  const containerRef = useRef<HTMLDivElement>(null)
  const scrollAreaRef = useRef<HTMLDivElement>(null)
  const signatureLineRef = useRef<HTMLDivElement>(null)

  const inputHandler = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSignature(e.target.value)
    if (scrollAreaRef.current && signatureLineRef.current) {
      const scrollContainer = scrollAreaRef.current.querySelector('[data-radix-scroll-area-viewport]') as HTMLElement
      if (scrollContainer) {
        const signatureLineRect = signatureLineRef.current.getBoundingClientRect()
        const scrollContainerRect = scrollContainer.getBoundingClientRect()
        const centerPosition = signatureLineRect.top + signatureLineRect.height / 2 - scrollContainerRect.top - scrollContainerRect.height / 2
        scrollContainer.scrollTop += centerPosition
      }
    }
  }

  const clickHandler = () => {
    setLoading(true)
    onConfirm()
  }

  const onClose = () => {
    setIsOpen(false)
    setDialogState('form')
    setSignature('')
  }

  useEffect(() => {
    if (containerRef.current) {
      const pages = containerRef.current.querySelectorAll('.document-page')
      pages.forEach((page) => {
        ;(page as HTMLElement).style.width = '595px'
        ;(page as HTMLElement).style.minHeight = '842px'
      })
    }
  }, [documentBody])

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent
        className="flex h-[90vh] max-h-[900px] w-[95vw] max-w-[1000px] flex-col overflow-hidden rounded-lg p-0"
        onPointerDownOutside={(e) => e.preventDefault()}
      >
        <AnimatePresence mode="wait">
          {dialogState === 'form' && (
            <motion.div
              key="form"
              className="flex h-full flex-col"
              initial={{ x: '-100%', opacity: 0 }}
              animate={{ x: 0, opacity: 1 }}
              exit={{ x: '100%', opacity: 0 }}
              transition={{ type: 'tween', ease: 'easeInOut', duration: 0.3 }}
            >
              <DialogHeader className="p-4 pb-2">
                <DialogTitle>{dialogTitle}</DialogTitle>
              </DialogHeader>
              <Separator />
              <ScrollArea className="flex-grow overflow-y-auto" ref={scrollAreaRef}>
                <div className="p-4 grid place-items-center" ref={containerRef}>
                  <div ref={contentRef}>
                    {documentBody.map((pageContent: React.ReactNode, index: number) => (
                      <div
                        key={index}
                        className="document-page relative mb-8 bg-white shadow-md text-black"
                        style={{
                          width: '595px',
                          minHeight: '842px',
                          padding: '40px',
                          fontSize: '12px',
                          lineHeight: '1.5',
                        }}
                      >
                        {pageContent}
                        {index === documentBody.length - 1 && (
                          <div className="mt-8 border-t border-gray-300 pt-4">
                            <p className="text-sm text-gray-700">
                              By signing below, I acknowledge that I have read
                              and agree to the E-Signature Agreement stated
                              above.
                            </p>
                            <div className="mt-4 flex items-center" ref={signatureLineRef}>
                              <span className="mr-2 text-sm font-medium text-gray-700">
                                Signature:
                              </span>
                              <span className="flex-grow border-b border-gray-400 h-4 relative">
                                <span className="absolute text-xl bottom-[0.025rem]">
                                  {signature}
                                </span>
                              </span>
                            </div>
                            <p className="mt-2 text-sm text-gray-500">
                              Signed on: {new Date().toLocaleDateString()}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                              Device Type: {deviceType}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                              IP Address: {ipAddress}
                            </p>
                          </div>
                        )}
                        <div className="absolute bottom-2 right-2 text-xs text-gray-400">
                          Page {index + 1} of {documentBody.length}
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
                <ScrollBar orientation="horizontal" />
              </ScrollArea>
              <Separator />
              <div className="bg-background p-4 pt-2">
                <div className="max-w-sm mx-auto items-center justify-center space-y-4">
                  <div className="space-y-2">
                    <Label htmlFor="signature">
                      Signature
                    </Label>
                    <div className="relative flex rounded-lg shadow-sm shadow-black/5">
                      <span className="pointer-events-none absolute inset-y-0 start-0 flex items-center justify-center ps-3 text-sm text-muted-foreground">
                        <User size={22} strokeWidth={2} aria-hidden="true" />
                      </span>
                      <Input
                        id="signature"
                        value={signature}
                        onChange={inputHandler}
                        className="-me-px rounded-e-none ps-10 shadow-none"
                        placeholder="First and Last Name"
                        type="text"
                      />
                      <button onClick={clickHandler} disabled={!signature} className="inline-flex items-center rounded-e-lg border border-input bg-background px-3 text-sm font-medium text-foreground outline-offset-2 transition-colors hover:bg-accent hover:text-foreground focus:z-10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-ring/70 disabled:cursor-not-allowed disabled:opacity-50">
                        Sign {!loading ? <Check size={16} className="ml-1" /> : <Loader2Icon size={16} className="ml-1 animate-spin"/>}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </motion.div>
          )}
          {dialogState === 'success' && (
            <motion.div
              key="success"
              className="h-full"
              initial={{ x: '100%', opacity: 0 }}
              animate={{ x: 0, opacity: 1 }}
              exit={{ x: '-100%', opacity: 0 }}
              transition={{ type: 'tween', ease: 'easeInOut', duration: 0.3 }}
            >
              <ScrollArea className="h-full">
                <div className="flex min-h-full items-center justify-center p-6 pt-36 md:p-48">
                  <div className="text-center">
                    <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary">
                      <CheckCircle className="h-10 w-10 text-primary-foreground" />
                    </div>
                    <h2 className="mb-2 text-2xl font-bold">{successTitle}</h2>
                    <p className="mb-4 text-center text-muted-foreground">
                      {successMessage}
                    </p>
                    {pdfUrl && (
                      <Button asChild variant="outline" className="mb-2 w-full">
                        <a href={pdfUrl} download="e_signature.pdf">
                          <Download className="mr-2 p-[0.1rem]"/> Download PDF
                        </a>
                      </Button>
                    )}
                    <Button onClick={onClose} className="w-full">
                      Close
                    </Button>
                  </div>
                </div>
              </ScrollArea>
            </motion.div>
          )}
          {dialogState === 'error' && (
            <motion.div
              key="error"
              className="h-full"
              initial={{ x: '100%', opacity: 0 }}
              animate={{ x: 0, opacity: 1 }}
              exit={{ x: '-100%', opacity: 0 }}
              transition={{ type: 'tween', ease: 'easeInOut', duration: 0.3 }}
            >
              <ScrollArea className="h-full">
                <div className="flex min-h-full items-center justify-center p-6 pt-36 md:p-48">
                  <div className="text-center">
                    <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-destructive">
                      <XCircle className="h-10 w-10 text-destructive-foreground" />
                    </div>
                    <h2 className="mb-2 text-2xl font-bold">{errorTitle}</h2>
                    <p className="mb-4 text-center text-muted-foreground">
                      {errorMessage}
                    </p>
                    <Button onClick={onClose} className="w-full">
                      Try Again
                    </Button>
                  </div>
                </div>
              </ScrollArea>
            </motion.div>
          )}
        </AnimatePresence>
      </DialogContent>
    </Dialog>
  )
}

