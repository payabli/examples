import React, { useState, useEffect, useRef, ReactNode } from 'react'
import { useFormContext } from 'react-hook-form'
import {
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form'
import { Info } from 'lucide-react'
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'

export type FormWrapperProps = {
  name: string
  label: string
  tooltip?: string
  required?: boolean
  showLabel?: boolean
  showTooltip?: boolean
}

type FormWrapperWithChildrenProps = FormWrapperProps & {
  children: (field: any) => ReactNode
}

export function FormWrapper({
  name,
  label,
  tooltip,
  required,
  showLabel = true,
  showTooltip = true,
  children,
}: FormWrapperWithChildrenProps) {
  const [open, setOpen] = useState(false)
  const tooltipRef = useRef<HTMLDivElement | null>(null)

  const handleClick = () => setOpen((prev) => !prev)

  useEffect(() => {
    const handleOutsideClick = (event: MouseEvent) => {
      if (
        tooltipRef.current &&
        !tooltipRef.current.contains(event.target as Node)
      ) {
        setOpen(false)
      }
    }
    document.addEventListener('mousedown', handleOutsideClick)
    return () => document.removeEventListener('mousedown', handleOutsideClick)
  }, [])

  const form = useFormContext()

  return (
    <TooltipProvider>
      <Tooltip open={open}>
        <FormField
          control={form.control}
          name={name}
          render={({ field }) => (
            <FormItem className="relative mb-4 md:mx-2">
              {showLabel && (
                <FormLabel className="flex items-center">
                  {label}
                  {required && (
                    <span className="ml-1 text-destructive" aria-hidden="true">
                      *
                    </span>
                  )}
                  {tooltip && showTooltip && (
                    <TooltipTrigger
                      asChild
                      className="ml-[4px] h-full w-9 rounded-e-lg border border-transparent text-muted-foreground/80 ring-offset-background transition-shadow hover:text-foreground focus-visible:border-ring focus-visible:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
                    >
                      <button
                        type="button"
                        aria-label={`Info for ${label}`}
                        onClick={handleClick}
                      >
                        <Info size={16} strokeWidth={2} aria-hidden="true" />
                      </button>
                    </TooltipTrigger>
                  )}
                </FormLabel>
              )}
              <FormMessage id={`${name}-error`} />
              {children(field)}
              {tooltip && showTooltip && (
                <TooltipContent className="mx-4" ref={tooltipRef}>
                  <p>{tooltip}</p>
                </TooltipContent>
              )}
            </FormItem>
          )}
        />
      </Tooltip>
    </TooltipProvider>
  )
}
