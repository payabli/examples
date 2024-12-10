import React, { ReactNode } from 'react'
import { FormControl } from '@/components/ui/form'
import { Switch } from '@/components/ui/switch'
import { FormWrapper, FormWrapperProps } from './FormWrapper'

type FormSwitchProps = FormWrapperProps & {
  disabled?: boolean
  iconleft?: ReactNode
  onlabel?: string
  offlabel?: string
}

export default function FormSwitch({
  name,
  label,
  tooltip,
  required,
  disabled,
  showLabel,
  showTooltip,
  iconleft,
  onlabel = 'On',
  offlabel = 'Off',
}: FormSwitchProps) {
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
          <div className="flex items-center space-x-2">
            {iconleft && (
              <div className="text-muted-foreground/80">{iconleft}</div>
            )}
            <Switch
              checked={field.value}
              onCheckedChange={field.onChange}
              disabled={disabled}
            />
            <span className="text-sm text-muted-foreground">
              {field.value ? onlabel : offlabel}
            </span>
          </div>
        </FormControl>
      )}
    </FormWrapper>
  )
}
