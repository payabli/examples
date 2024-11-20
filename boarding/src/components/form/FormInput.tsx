import React, { useState, useEffect, useRef, ReactNode } from 'react'
import { useFormContext } from 'react-hook-form'
import InputMask from 'react-input-mask'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Eye, EyeOff, Info, X } from 'lucide-react'
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'

type FormInputTypes = {
  control?: any
  name: string
  label: string
  placeholder?: string
  tooltip?: string
  type?: string
  iconleft?: ReactNode
  iconright?: ReactNode
  password?: boolean
  clearable?: boolean
  mask?: string
  required?: boolean
  disabled?: boolean
  showLabel?: boolean
  showTooltip?: boolean
}

export default function FormInput({
  control,
  name,
  placeholder,
  label,
  type = 'text',
  tooltip,
  iconleft,
  iconright,
  password,
  clearable,
  mask,
  required,
  disabled,
  showLabel = true,
  showTooltip = true,
}: FormInputTypes) {
  const [visible, setVisible] = useState(false)
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

  // Check for mutual exclusivity
  if ((password ? 1 : 0) + (clearable ? 1 : 0) + (iconright ? 1 : 0) > 1) {
    throw new Error(
      'Only one of password, clearable, or iconright can be active at a time.',
    )
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
                <div className="relative flex">
                  {mask ? (
                    <InputMask
                      mask={mask}
                      value={field.value}
                      onChange={field.onChange}
                      onBlur={field.onBlur}
                      disabled={disabled}
                      className={`w-full ${iconleft ? 'ps-10' : ''} ${iconright || password || clearable ? 'pe-10' : ''}`}
                    >
                      {(inputProps: any) => (
                        <Input
                          {...inputProps}
                          id={name}
                          placeholder={placeholder}
                          type={password && !visible ? 'password' : type}
                          required={required}
                          aria-invalid={
                            form.formState.errors[name] ? 'true' : 'false'
                          }
                          aria-describedby={`${name}-error`}
                        />
                      )}
                    </InputMask>
                  ) : (
                    <Input
                      id={name}
                      className={`peer ${iconleft ? 'ps-10' : ''} ${iconright || password || clearable ? 'pe-10' : ''}`}
                      placeholder={placeholder}
                      type={password && !visible ? 'password' : type}
                      required={required}
                      disabled={disabled}
                      aria-invalid={
                        form.formState.errors[name] ? 'true' : 'false'
                      }
                      aria-describedby={`${name}-error`}
                      {...field}
                    />
                  )}
                  {password && (
                    <button
                      type="button"
                      onClick={() => setVisible((prev) => !prev)}
                      className="absolute inset-y-0 end-0 flex h-full w-10 cursor-pointer items-center justify-center pe-3 text-muted-foreground/80 peer-disabled:opacity-50"
                      aria-label={visible ? 'Hide password' : 'Show password'}
                    >
                      {visible ? <EyeOff size={18} /> : <Eye size={18} />}
                    </button>
                  )}
                  {clearable && field.value && (
                    <button
                      type="button"
                      onClick={() => field.onChange('')}
                      className="absolute inset-y-0 end-0 flex h-full w-10 cursor-pointer items-center justify-center pe-3 text-muted-foreground/80 peer-disabled:opacity-50"
                      aria-label="Clear input"
                    >
                      <X size={18} />
                    </button>
                  )}
                  {iconright && !password && !clearable && (
                    <div className="pointer-events-none absolute inset-y-0 end-0 flex h-full w-10 items-center justify-center pe-3 text-muted-foreground/80 peer-disabled:opacity-50">
                      {iconright}
                    </div>
                  )}
                  {iconleft && (
                    <div className="pointer-events-none absolute inset-y-0 start-0 flex h-full w-10 items-center justify-center ps-3 text-muted-foreground/80 peer-disabled:opacity-50">
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
