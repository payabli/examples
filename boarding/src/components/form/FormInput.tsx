import React, { ReactNode } from 'react'
import InputMask from 'react-input-mask'
import { FormControl } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Eye, EyeOff, X } from 'lucide-react'
import { FormWrapper, FormWrapperProps } from './FormWrapper'

type FormInputProps = Omit<FormWrapperProps, 'children'> & {
  placeholder?: string
  type?: string
  iconleft?: ReactNode
  iconright?: ReactNode
  password?: boolean
  clearable?: boolean
  mask?: string
  disabled?: boolean
  numeric?: boolean
  prefix?: string
  postfix?: string
  maxLength?: number
  includeMaskedChars?: boolean
}

export default function FormInput({
  name,
  label,
  placeholder,
  tooltip,
  type = 'text',
  iconleft,
  iconright,
  password,
  clearable,
  mask,
  required,
  disabled,
  showLabel,
  showTooltip,
  numeric,
  prefix,
  postfix,
  maxLength,
  includeMaskedChars = false,
}: FormInputProps) {
  const [visible, setVisible] = React.useState(false)

  // Check for mutual exclusivity
  if ((password ? 1 : 0) + (clearable ? 1 : 0) + (iconright ? 1 : 0) > 1) {
    throw new Error(
      'Only one of password, clearable, or iconright can be active at a time.',
    )
  }

  const handleInputChange = (
    e: React.ChangeEvent<HTMLInputElement>,
    onChange: (value: string) => void,
  ) => {
    let value = e.target.value

    if (numeric) {
      value = value.replace(/[^0-9.]/g, '')
    }

    if (mask && !includeMaskedChars) {
      value = value.replace(/\D/g, '')
    }

    onChange(value)
  }

  const renderInput = (field: any) => (
    <FormControl>
      <div className="relative flex">
        {prefix && (
          <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground/80">
            {prefix}
          </span>
        )}
        {mask ? (
          <InputMask
            mask={mask}
            value={field.value}
            onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
              handleInputChange(e, field.onChange)
            }
            onBlur={field.onBlur}
            disabled={disabled}
            maxLength={maxLength}
            className={`w-full ${iconleft || prefix ? 'ps-10' : ''} ${
              iconright || password || clearable || postfix ? 'pe-10' : ''
            }`}
          >
            {(inputProps: any) => (
              <Input
                {...inputProps}
                id={name}
                placeholder={placeholder}
                type={password && !visible ? 'password' : type}
                required={required}
                aria-invalid={field.error ? 'true' : 'false'}
                aria-describedby={`${name}-error`}
                maxLength={maxLength}
              />
            )}
          </InputMask>
        ) : (
          <Input
            id={name}
            className={`peer ${iconleft || prefix ? 'ps-10' : ''} ${
              iconright || password || clearable || postfix ? 'pe-10' : ''
            }`}
            placeholder={placeholder}
            type={password && !visible ? 'password' : type}
            required={required}
            disabled={disabled}
            aria-invalid={field.error ? 'true' : 'false'}
            aria-describedby={`${name}-error`}
            {...field}
            onChange={(e) => handleInputChange(e, field.onChange)}
            maxLength={maxLength}
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
        {iconright && !password && !clearable && !postfix && (
          <div className="pointer-events-none absolute inset-y-0 end-0 flex h-full w-10 items-center justify-center pe-3 text-muted-foreground/80 peer-disabled:opacity-50">
            {iconright}
          </div>
        )}
        {iconleft && !prefix && (
          <div className="pointer-events-none absolute inset-y-0 start-0 flex h-full w-10 items-center justify-center ps-3 text-muted-foreground/80 peer-disabled:opacity-50">
            {iconleft}
          </div>
        )}
        {postfix && (
          <span className="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground/80">
            {postfix}
          </span>
        )}
      </div>
    </FormControl>
  )

  return (
    <FormWrapper
      name={name}
      label={label}
      tooltip={tooltip}
      required={required}
      showLabel={showLabel}
      showTooltip={showTooltip}
    >
      {renderInput}
    </FormWrapper>
  )
}
