import React, { useState, useEffect, useRef, ReactNode } from 'react'
import { useFormContext } from 'react-hook-form'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form'
import { Checkbox } from '@/components/ui/checkbox'
import { Info } from 'lucide-react'
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'

type CheckboxOption = {
  name: string
  label: string
  id?: string
  tooltip?: string
}

type FormCheckboxGroupTypes = {
  control?: any
  label: string
  tooltip?: string
  options: CheckboxOption[]
  required?: boolean
  disabled?: boolean
  showLabel?: boolean
  showTooltips?: boolean
  iconleft?: ReactNode
}

export default function FormCheckboxGroup({
  control,
  label,
  tooltip,
  options,
  required,
  disabled,
  showLabel = true,
  showTooltips = true,
  iconleft,
}: FormCheckboxGroupTypes) {
  const [openTooltips, setOpenTooltips] = useState<{ [key: string]: boolean }>(
    {},
  )
  const tooltipRefs = useRef<{ [key: string]: HTMLDivElement | null }>({})

  const handleTooltipClick = (name: string) => {
    setOpenTooltips((prev) => ({ ...prev, [name]: !prev[name] }))
  }

  useEffect(() => {
    const handleOutsideClick = (event: MouseEvent) => {
      Object.entries(tooltipRefs.current).forEach(([name, ref]) => {
        if (ref && !ref.contains(event.target as Node)) {
          setOpenTooltips((prev) => ({ ...prev, [name]: false }))
        }
      })
    }
    document.addEventListener('mousedown', handleOutsideClick)
    return () => document.removeEventListener('mousedown', handleOutsideClick)
  }, [])

  const form = useFormContext()
  const resolvedControl = control || form.control

  return (
    <TooltipProvider>
      <FormItem className="relative mb-4 md:mx-2">
        {showLabel && (
          <FormLabel className="flex items-center">
            <span className="font-bold">
            {label}
            {required && (
              <span className="ml-1 text-destructive" aria-hidden="true">
                *
              </span>
            )}
            </span>
            {tooltip && showTooltips && (
              <Tooltip open={openTooltips['group']}>
                <TooltipTrigger
                  asChild
                  className="ml-[4px] h-full w-9 rounded-e-lg border border-transparent text-muted-foreground/80 ring-offset-background transition-shadow hover:text-foreground focus-visible:border-ring focus-visible:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
                >
                  <button
                    type="button"
                    aria-label={`Info for ${label}`}
                    onClick={() => handleTooltipClick('group')}
                  >
                    <Info size={16} strokeWidth={2} aria-hidden="true" />
                  </button>
                </TooltipTrigger>
                <TooltipContent
                  className="mx-4"
                  ref={(el) => (tooltipRefs.current['group'] = el)}
                >
                  <p>{tooltip}</p>
                </TooltipContent>
              </Tooltip>
            )}
          </FormLabel>
        )}
        <div className="space-y-2">
          {iconleft && (
            <div className="text-muted-foreground/80">{iconleft}</div>
          )}
          {options.map((option) => (
            <FormField
              key={option.id || option.name}
              control={resolvedControl}
              name={option.name}
              render={({ field }) => (
                <FormItem className="flex flex-row items-start space-x-3 space-y-0">
                  <FormControl>
                    <Checkbox
                      id={option.id || option.name}
                      checked={field.value}
                      onCheckedChange={field.onChange}
                      disabled={disabled}
                    />
                  </FormControl>
                  <div className="space-y-1 leading-none">
                    <FormLabel htmlFor={option.id || option.name}>
                      {option.label}
                    </FormLabel>
                    <FormMessage />
                  </div>
                  {option.tooltip && showTooltips && (
                    <Tooltip open={openTooltips[option.name]}>
                      <TooltipTrigger
                        asChild
                        className="ml-1 h-full w-5 rounded-full border border-transparent text-muted-foreground/80 ring-offset-background transition-shadow hover:text-foreground focus-visible:border-ring focus-visible:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
                      >
                        <button
                          type="button"
                          aria-label={`Info for ${option.label}`}
                          onClick={() => handleTooltipClick(option.name)}
                        >
                          <Info size={12} strokeWidth={2} aria-hidden="true" />
                        </button>
                      </TooltipTrigger>
                      <TooltipContent
                        className="mx-4"
                        ref={(el) => (tooltipRefs.current[option.name] = el)}
                      >
                        <p>{option.tooltip}</p>
                      </TooltipContent>
                    </Tooltip>
                  )}
                </FormItem>
              )}
            />
          ))}
        </div>
      </FormItem>
    </TooltipProvider>
  )
}
