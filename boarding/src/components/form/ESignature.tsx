import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { CheckCircle, XCircle } from 'lucide-react'
import { useESignatureStore, useESignature } from '../../hooks/use-esignature'
import { useState, useEffect, useRef, ReactNode } from 'react'

export function ESignature() {
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
    setDialogState
  } = useESignatureStore()

  const { handleConfirm, contentRef } = useESignature({
    documentBody,
    onSubmit: async () => {}, // This is a placeholder, actual submission is handled in the hook
  })

  const [scale, setScale] = useState(1)
  const containerRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    const updateScale = () => {
      if (containerRef.current) {
        const containerWidth = containerRef.current.offsetWidth
        const desiredWidth = 595 // Standard A4 width in pixels at 72 DPI
        
        // Calculate scale based on width, ensuring the entire width is always visible
        const newScale = containerWidth / desiredWidth
        setScale(newScale)
      }
    }

    updateScale()
    window.addEventListener('resize', updateScale)
    return () => window.removeEventListener('resize', updateScale)
  }, [])

  const onClose = () => {
    setIsOpen(false)
    setDialogState('form')
    setSignature('')
  }

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="w-[95vw] max-w-[700px] h-[90vh] max-h-[800px] p-0 rounded-lg overflow-hidden flex flex-col">
        {dialogState === 'form' && (
          <>
            <DialogHeader className="p-4 pb-2">
              <DialogTitle>{dialogTitle}</DialogTitle>
            </DialogHeader>
            <ScrollArea className="flex-grow">
              <div className="p-4" ref={containerRef}>
                <div 
                  ref={contentRef}
                  style={{
                    transform: `scale(${scale})`,
                    transformOrigin: 'top left',
                    width: `${100 / scale}%`,
                  }}
                >
                  {documentBody.map((pageContent: ReactNode, index: number) => (
                    <div 
                      key={index} 
                      className="mb-8 bg-white shadow-md relative"
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
                        <div className="mt-8 pt-4 border-t border-gray-300">
                          <p className="text-sm text-gray-700">By signing below, I acknowledge that I have read and agree to the E-Signature Agreement stated above.</p>
                          <div className="mt-4 flex items-center">
                            <span className="text-sm font-medium text-gray-700 mr-2">Signature:</span>
                            <span className="flex-grow border-b border-gray-400">{signature}</span>
                          </div>
                          <p className="mt-2 text-sm text-gray-500">Signed on: {new Date().toLocaleDateString()}</p>
                          <p className="mt-2 text-sm text-gray-500">Device Type: {deviceType}</p>
                          <p className="mt-2 text-sm text-gray-500">IP Address: {ipAddress}</p>
                        </div>
                      )}
                      <div className="absolute bottom-2 right-2 text-xs text-gray-400">
                        Page {index + 1} of {documentBody.length}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </ScrollArea>
            <div className="p-4 pt-2 border-t">
              <Label htmlFor="signature" className="block text-sm font-medium">
                Signature
              </Label>
              <Input
                type="text"
                id="signature"
                value={signature}
                onChange={(e) => setSignature(e.target.value)}
                className="mt-1 block w-full"
                placeholder="Type your name to sign"
              />
              <DialogFooter className="mt-4">
                <Button onClick={handleConfirm} disabled={!signature} className="w-full">
                  {confirmButtonText}
                </Button>
              </DialogFooter>
            </div>
          </>
        )}
        {dialogState === 'success' && (
          <ScrollArea className="flex-grow p-6">
            <div className="flex flex-col items-center justify-center">
              <div className="w-16 h-16 mb-4 rounded-full bg-primary flex items-center justify-center">
                <CheckCircle className="w-10 h-10 text-primary-foreground" />
              </div>
              <h2 className="text-2xl font-bold mb-2">{successTitle}</h2>
              <p className="text-center text-muted-foreground mb-4">
                {successMessage}
              </p>
              {pdfUrl && (
                <Button asChild variant="outline" className="w-full mb-2">
                  <a href={pdfUrl} download="e_signature.pdf">
                    Download E-Signature Agreement PDF
                  </a>
                </Button>
              )}
              <Button onClick={onClose} className="w-full">
                Close
              </Button>
            </div>
          </ScrollArea>
        )}
        {dialogState === 'error' && (
          <ScrollArea className="flex-grow p-6">
            <div className="flex flex-col items-center justify-center">
              <div className="w-16 h-16 mb-4 rounded-full bg-destructive flex items-center justify-center">
                <XCircle className="w-10 h-10 text-destructive-foreground" />
              </div>
              <h2 className="text-2xl font-bold mb-2">{errorTitle}</h2>
              <p className="text-center text-muted-foreground mb-4">
                {errorMessage}
              </p>
              <Button onClick={onClose} className="w-full">
                Try Again
              </Button>
            </div>
          </ScrollArea>
        )}
      </DialogContent>
    </Dialog>
  )
}

