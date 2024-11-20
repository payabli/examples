import React, { useState, useEffect, useRef } from 'react'
import { useFormContext } from 'react-hook-form'
import { format } from 'date-fns'
import { Calendar as CalendarIcon, Info } from 'lucide-react'
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
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

type FormDatePickerTypes = {
  control?: any
  name: string
  label: string
  placeholder?: string
  tooltip?: string
  required?: boolean
  disabled?: boolean
  showLabel?: boolean
  showTooltip?: boolean
}

export default function FormDatePicker({
  control,
  name,
  label,
  placeholder = 'Pick a date',
  tooltip,
  required,
  disabled,
  showLabel = true,
  showTooltip = true,
}: FormDatePickerTypes) {
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
  const resolvedControl = control || form.control

  return (
    <TooltipProvider>
      <Tooltip open={open}>
        <FormField
          control={resolvedControl}
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
              <FormControl>
                <Popover>
                  <PopoverTrigger asChild>
                    <Button
                      variant={'outline'}
                      className={cn(
                        'h-10 w-full justify-start text-left font-normal',
                        !field.value && 'text-muted-foreground',
                        'pl-3 pr-3', // Add left and right padding
                      )}
                      disabled={disabled}
                      aria-invalid={
                        form.formState.errors[name] ? 'true' : 'false'
                      }
                      aria-describedby={`${name}-error`}
                    >
                      <CalendarIcon
                        className="left-[10px]h-5 absolute w-5"
                        aria-hidden="true"
                      />
                      <span className="pl-7">
                        {field.value ? format(field.value, 'PPP') : placeholder}
                      </span>
                    </Button>
                  </PopoverTrigger>
                  <PopoverContent className="w-auto p-0" align="start">
                    <Calendar
                      mode="single"
                      selected={field.value}
                      onSelect={(date) => {
                        field.onChange(date)
                        form.trigger(name)
                      }}
                      disabled={disabled}
                      initialFocus
                    />
                  </PopoverContent>
                </Popover>
              </FormControl>
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
