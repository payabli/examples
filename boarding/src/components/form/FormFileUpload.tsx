import React, { useCallback, useState, useRef, useEffect } from 'react'
import { Upload, X, FileIcon, Info } from 'lucide-react'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form'
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'
import { useFormContext } from 'react-hook-form'

interface FileUploadProps {
  name: string
  label: string
  tooltip?: string
  maxSizeMB?: number
  accept?: string
  required?: boolean
  disabled?: boolean
}

export default function FileUpload({
  name,
  label,
  tooltip,
  maxSizeMB = 5,
  accept = 'image/*,.pdf',
  required,
  disabled,
}: FileUploadProps) {
  const [dragActive, setDragActive] = useState(false)
  const [preview, setPreview] = useState<string | null>(null)
  const [tooltipOpen, setTooltipOpen] = useState(false)
  const tooltipRef = useRef<HTMLDivElement>(null)

  const form = useFormContext()

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (
        tooltipRef.current &&
        !tooltipRef.current.contains(event.target as Node)
      ) {
        setTooltipOpen(false)
      }
    }

    document.addEventListener('mousedown', handleClickOutside)
    return () => {
      document.removeEventListener('mousedown', handleClickOutside)
    }
  }, [])

  const handleClick = () => setTooltipOpen((prev) => !prev)

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
    (e: React.DragEvent, onChange: (file: File | null) => void) => {
      e.preventDefault()
      e.stopPropagation()
      setDragActive(false)
      if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        handleFile(e.dataTransfer.files[0], onChange)
      }
    },
    [],
  )

  const handleChange = useCallback(
    (
      e: React.ChangeEvent<HTMLInputElement>,
      onChange: (file: File | null) => void,
    ) => {
      e.preventDefault()
      if (e.target.files && e.target.files[0]) {
        handleFile(e.target.files[0], onChange)
      }
    },
    [],
  )

  const handleFile = (file: File, onChange: (file: File | null) => void) => {
    // Clear any existing errors for this field
    form.clearErrors(name);

    if (file.size > maxSizeMB * 1024 * 1024) {
      form.setError(name, {
        type: 'manual',
        message: `File size exceeds ${maxSizeMB}MB limit`,
      });
      return;
    }

    onChange(file);

    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onloadend = () => {
        setPreview(reader.result as string);
      };
      reader.readAsDataURL(file);
    } else {
      setPreview(null);
    }
  };

  const removeFile = useCallback((onChange: (file: File | null) => void) => {
    onChange(null)
    setPreview(null)
  }, [])

  return (
    <FormField
      control={form.control}
      name={name}
      render={({ field }) => (
        <FormItem className="md:mx-2">
          <FormLabel>
            <div className="flex items-end">
            <span>{label}</span>
            {required && <span className="text-destructive"> *</span>}
            {tooltip && (
              <TooltipProvider>
                <Tooltip open={tooltipOpen}>
                  <TooltipTrigger
                    asChild
                    className="ml-[4px] h-full w-9 rounded-e-lg border border-transparent text-muted-foreground/80 ring-offset-background transition-shadow hover:text-foreground focus-visible:border-ring focus-visible:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
                  >
                    <button
                      type="button"
                      aria-label={`Info for ${label}`}
                      onClick={handleClick}
                    >
                      <Info
                        size={16}
                        strokeWidth={2}
                        aria-hidden="true"
                        className="translate-y-[2px]"
                      />
                    </button>
                  </TooltipTrigger>
                  <TooltipContent
                    side="top"
                    align="center"
                    sideOffset={4}
                    ref={tooltipRef}
                  >
                    <p>{tooltip}</p>
                  </TooltipContent>
                </Tooltip>
              </TooltipProvider>
            )}
            </div>
          </FormLabel>
          <FormControl>
            <div
              className={`relative flex h-64 w-full flex-col items-center justify-center rounded-lg border-2 border-dashed transition-colors ${
                dragActive ? 'border-primary bg-primary/10' : 'border-input'
              }`}
              onDragEnter={handleDrag}
              onDragLeave={handleDrag}
              onDragOver={handleDrag}
              onDrop={(e) => handleDrop(e, field.onChange)}
            >
              <input
                type="file"
                className="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                onChange={(e) => handleChange(e, field.onChange)}
                accept={accept}
                disabled={disabled}
              />
              {field.value ? (
                <>
                  {preview ? (
                    <img
                      src={preview}
                      alt="Preview"
                      className="mb-4 h-24 w-24 rounded-lg object-cover"
                    />
                  ) : (
                    <FileIcon className="mb-4 h-16 w-16 text-muted-foreground" />
                  )}
                  <p className="max-w-full truncate px-2 text-sm text-muted-foreground">
                    {field.value.name}
                  </p>
                </>
              ) : (
                <>
                  <Upload className="mb-3 h-10 w-10 text-muted-foreground" />
                  <p className="text-sm text-muted-foreground">
                    <span className="font-semibold">Click to upload</span> or
                    drag and drop
                  </p>
                  <p className="mt-1 text-xs text-muted-foreground">
                    SVG, PNG, JPG or PDF (MAX. {maxSizeMB}MB)
                  </p>
                </>
              )}
            </div>
          </FormControl>
          {field.value && (
            <div className="mt-2 flex items-center justify-between text-sm">
              <span className="text-muted-foreground">
                {(field.value.size / 1024 / 1024).toFixed(2)} MB
              </span>
              <button
                type="button"
                onClick={() => removeFile(field.onChange)}
                className="text-destructive hover:text-destructive/90"
              >
                Remove
              </button>
            </div>
          )}
          <FormMessage />
        </FormItem>
      )}
    />
  )
}

