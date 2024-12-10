import React, { useState, useCallback, useRef } from 'react'
import { Upload, FileIcon } from 'lucide-react'
import { FormControl } from '@/components/ui/form'
import { FormWrapper, FormWrapperProps } from './FormWrapper'

type FormFileUploadProps = FormWrapperProps & {
  maxSizeMB?: number
  accept?: string
  disabled?: boolean
  file: File | null
  setFile: (file: File | null) => void
  type: string
  setType: (type: string) => void
  extension: string
  setExtension: (extension: string) => void
  contents: string | null
  setContents: (contents: string | null) => void
  id?: string
}

export default function FormFileUpload({
  name,
  label,
  tooltip,
  maxSizeMB = 5,
  accept = 'image/*,.pdf',
  required,
  disabled,
  file,
  setFile,
  type,
  setType,
  extension,
  setExtension,
  contents,
  setContents,
  id = 'file-upload',
  showLabel = true,
  showTooltip = true,
}: FormFileUploadProps) {
  const [preview, setPreview] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)
  const [dragActive, setDragActive] = useState(false)
  const fileInputRef = useRef<HTMLInputElement>(null)

  const handleFile = useCallback(
    (selectedFile: File) => {
      setError(null)

      if (selectedFile.size > maxSizeMB * 1024 * 1024) {
        setError(`File size exceeds ${maxSizeMB}MB limit`)
        return
      }

      setFile(selectedFile)
      setType(selectedFile.type)
      setExtension(`.${selectedFile.name.split('.').pop()}`)

      const reader = new FileReader()
      reader.onloadend = () => {
        const base64String = reader.result as string
        setContents(base64String.split(',')[1])
        if (selectedFile.type.startsWith('image/')) {
          setPreview(base64String)
        } else {
          setPreview(null)
        }
      }
      reader.readAsDataURL(selectedFile)
    },
    [setFile, setType, setExtension, setContents, maxSizeMB],
  )

  const handleChange = useCallback(
    (e: React.ChangeEvent<HTMLInputElement>) => {
      if (e.target.files && e.target.files[0]) {
        handleFile(e.target.files[0])
      }
    },
    [handleFile],
  )

  const handleDrag = useCallback((e: React.DragEvent) => {
    e.preventDefault()
    e.stopPropagation()
    if (e.type === 'dragenter' || e.type === 'dragover') {
      setDragActive(true)
    } else if (e.type === 'dragleave') {
      setDragActive(false)
    }
  }, [])

  const handleDrop = useCallback(
    (e: React.DragEvent) => {
      e.preventDefault()
      e.stopPropagation()
      setDragActive(false)
      if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        handleFile(e.dataTransfer.files[0])
      }
    },
    [handleFile],
  )

  const removeFile = useCallback(() => {
    setFile(null)
    setContents(null)
    setPreview(null)
    setType('')
    setExtension('')
  }, [setFile, setContents, setType, setExtension])

  const handleBoxClick = useCallback(() => {
    fileInputRef.current?.click()
  }, [])

  const truncateFileName = (name: string, maxLength: number = 20) => {
    if (name.length <= maxLength) return name
    const extension = name.split('.').pop()
    const nameWithoutExtension = name.slice(0, name.lastIndexOf('.'))
    const truncatedName = nameWithoutExtension.slice(
      0,
      maxLength - 3 - (extension?.length || 0),
    )
    return `${truncatedName}...${extension}`
  }

  return (
    <FormWrapper
      name={name}
      label={label}
      tooltip={tooltip}
      required={required}
      showLabel={showLabel}
      showTooltip={showTooltip}
    >
      {(field) => (
        <FormControl>
          <div className="space-y-2">
            <div
              className={`flex w-full items-center justify-center ${
                dragActive
                  ? 'border-primary'
                  : 'border-input hover:border-primary'
              } h-64 cursor-pointer rounded-md border-2 border-dashed bg-background p-4 transition ${
                disabled ? 'cursor-not-allowed opacity-50' : ''
              }`}
              onDragEnter={handleDrag}
              onDragLeave={handleDrag}
              onDragOver={handleDrag}
              onDrop={handleDrop}
              onClick={handleBoxClick}
            >
              <input
                id={id}
                ref={fileInputRef}
                type="file"
                className="hidden"
                onChange={(e) => {
                  handleChange(e)
                  field.onChange(e)
                }}
                accept={accept}
                disabled={disabled}
              />
              <div className="space-y-2 text-center">
                {file ? (
                  <div>
                    {preview ? (
                      <img
                        src={preview}
                        alt="Preview"
                        className="mx-auto h-32 w-32 rounded-md object-cover"
                      />
                    ) : (
                      <FileIcon className="mx-auto h-10 w-10 text-muted-foreground" />
                    )}
                    <p className="mt-2 text-sm text-muted-foreground">
                      {truncateFileName(file.name)}
                    </p>
                  </div>
                ) : (
                  <>
                    <Upload className="mx-auto h-10 w-10 text-muted-foreground" />
                    <div className="text-sm text-muted-foreground">
                      <span className="font-semibold text-primary">
                        Click here
                      </span>{' '}
                      or drag and drop to upload a file
                    </div>
                    <p className="text-xs text-muted-foreground">
                      PNG, JPG or PDF (MAX {maxSizeMB}MB)
                    </p>
                  </>
                )}
              </div>
            </div>
            {file && (
              <div className="flex items-center justify-between text-sm">
                <span className="text-muted-foreground">
                  {(file.size / 1024 / 1024).toFixed(2)} MB
                </span>
                <button
                  type="button"
                  onClick={removeFile}
                  className="text-destructive hover:text-destructive/90"
                >
                  Remove
                </button>
              </div>
            )}
            {error && (
              <p className="text-sm font-medium text-destructive">{error}</p>
            )}
          </div>
        </FormControl>
      )}
    </FormWrapper>
  )
}
