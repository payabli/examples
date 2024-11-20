import React, { useState, useEffect, useRef, ReactNode } from 'react'
import { useFormContext } from 'react-hook-form'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Info } from 'lucide-react'
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'

type Option = {
  value: string
  label: string
}

type GroupLabel = {
  type: 'label'
  label: string
}

type SelectOption = Option | GroupLabel

type FormSelectTypes = {
  control?: any
  name: string
  label: string
  placeholder?: string
  tooltip?: string
  options: SelectOption[]
  required?: boolean
  disabled?: boolean
  showLabel?: boolean
  showTooltip?: boolean
  iconleft?: ReactNode
}

export default function FormSelect({
  control,
  name,
  label,
  placeholder,
  tooltip,
  options,
  required,
  disabled,
  showLabel = true,
  showTooltip = true,
  iconleft,
}: FormSelectTypes) {
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

  const renderSelectOptions = (options: SelectOption[]) => {
    let currentGroup: SelectOption[] = []
    const groups: SelectOption[][] = []

    options.forEach((option) => {
      if ('type' in option && option.type === 'label') {
        if (currentGroup.length > 0) {
          groups.push(currentGroup)
          currentGroup = []
        }
        currentGroup.push(option)
      } else {
        currentGroup.push(option)
      }
    })

    if (currentGroup.length > 0) {
      groups.push(currentGroup)
    }

    return groups.map((group, groupIndex) => (
      <SelectGroup key={groupIndex}>
        {group.map((option, optionIndex) => {
          if ('type' in option && option.type === 'label') {
            return (
              <SelectLabel key={`label-${groupIndex}-${optionIndex}`}>
                {option.label}
              </SelectLabel>
            )
          }
          return (
            <SelectItem
              key={`item-${groupIndex}-${optionIndex}`}
              value={option.value}
            >
              {option.label}
            </SelectItem>
          )
        })}
      </SelectGroup>
    ))
  }

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
                <div className="relative">
                  <Select
                    onValueChange={field.onChange}
                    defaultValue={field.value}
                    disabled={disabled}
                  >
                    <SelectTrigger
                      className={`w-full ${iconleft ? 'pl-10' : ''}`}
                      aria-invalid={
                        form.formState.errors[name] ? 'true' : 'false'
                      }
                      aria-describedby={`${name}-error`}
                    >
                      <SelectValue placeholder={placeholder} />
                    </SelectTrigger>
                    <SelectContent>
                      {renderSelectOptions(options)}
                    </SelectContent>
                  </Select>
                  {iconleft && (
                    <div className="pointer-events-none absolute inset-y-0 start-0 flex h-full w-8 items-center justify-center ps-3 text-muted-foreground/80 peer-disabled:opacity-50">
                      {iconleft}
                    </div>
                  )}
                  {tooltip && showTooltip && (
                    <TooltipContent className="mx-4" ref={tooltipRef}>
                      <p>{tooltip}</p>
                    </TooltipContent>
                  )}
                </div>
              </FormControl>
            </FormItem>
          )}
        />
      </Tooltip>
    </TooltipProvider>
  )
}
