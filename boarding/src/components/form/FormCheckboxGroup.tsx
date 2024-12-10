import React, { ReactNode } from 'react'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
} from '@/components/ui/form'
import { Checkbox } from '@/components/ui/checkbox'
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'
import { Info } from 'lucide-react'
import { useFormContext } from 'react-hook-form'

type CheckboxOption = {
  name: string
  label: string
  tooltip?: string
}

type FormCheckboxGroupProps = {
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
  label,
  tooltip,
  options,
  required,
  disabled,
  showLabel = true,
  showTooltips = true,
  iconleft,
}: FormCheckboxGroupProps) {
  const form = useFormContext()

  return (
    <div className="space-y-4">
      {showLabel && (
        <div className="flex items-center">
          <span className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
            {label}
          </span>
          {required && <span className="ml-1 text-destructive">*</span>}
          {tooltip && showTooltips && (
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger asChild>
                  <button
                    type="button"
                    className="ml-1 h-4 w-4 rounded-full text-muted-foreground/80"
                    aria-label={`Info for ${label}`}
                  >
                    <Info size={12} strokeWidth={2} />
                  </button>
                </TooltipTrigger>
                <TooltipContent>
                  <p>{tooltip}</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          )}
        </div>
      )}
      {iconleft && <div className="text-muted-foreground/80">{iconleft}</div>}
      <div className="space-y-2">
        {options.map((option) => (
          <FormField
            key={option.name}
            control={form.control}
            name={option.name}
            render={({ field }) => (
              <FormItem className="flex flex-row items-start space-x-3 space-y-0">
                <FormControl>
                  <Checkbox
                    checked={field.value}
                    onCheckedChange={field.onChange}
                    disabled={disabled}
                  />
                </FormControl>
                <div className="space-y-1 leading-none">
                  <FormLabel htmlFor={option.name}>{option.label}</FormLabel>
                </div>
                {option.tooltip && showTooltips && (
                  <TooltipProvider>
                    <Tooltip>
                      <TooltipTrigger asChild>
                        <button
                          type="button"
                          className="ml-1 h-4 w-4 rounded-full text-muted-foreground/80"
                          aria-label={`Info for ${option.label}`}
                        >
                          <Info size={12} strokeWidth={2} />
                        </button>
                      </TooltipTrigger>
                      <TooltipContent>
                        <p>{option.tooltip}</p>
                      </TooltipContent>
                    </Tooltip>
                  </TooltipProvider>
                )}
              </FormItem>
            )}
          />
        ))}
      </div>
    </div>
  )
}
