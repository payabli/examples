import React, { ReactNode } from 'react'
import { FormControl } from '@/components/ui/form'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { FormWrapper, FormWrapperProps } from './FormWrapper'

type Option = {
  value: string
  label: string
}

type GroupLabel = {
  type: 'label'
  label: string
}

type SelectOption = Option | GroupLabel

type FormSelectProps = FormWrapperProps & {
  placeholder?: string
  options: SelectOption[]
  disabled?: boolean
  iconleft?: ReactNode
}

export default function FormSelect({
  name,
  label,
  placeholder,
  tooltip,
  options,
  required,
  disabled,
  showLabel,
  showTooltip,
  iconleft,
}: FormSelectProps) {
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
          } else if ('value' in option && 'label' in option) {
            return (
              <SelectItem
                key={`item-${groupIndex}-${optionIndex}`}
                value={option.value}
              >
                {option.label}
              </SelectItem>
            )
          }
        })}
      </SelectGroup>
    ))
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
          <div className="relative">
            <Select
              onValueChange={field.onChange}
              value={String(field.value)}
              disabled={disabled}
            >
              <SelectTrigger className={`w-full ${iconleft ? 'pl-10' : ''}`}>
                <SelectValue placeholder={placeholder} />
              </SelectTrigger>
              <SelectContent>{renderSelectOptions(options)}</SelectContent>
            </Select>
            {iconleft && (
              <div className="pointer-events-none absolute inset-y-0 start-0 flex h-full w-8 items-center justify-center ps-3 text-muted-foreground/80 peer-disabled:opacity-50">
                {iconleft}
              </div>
            )}
          </div>
        </FormControl>
      )}
    </FormWrapper>
  )
}
