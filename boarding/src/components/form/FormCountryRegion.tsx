import React, { useState, useEffect, useRef, useMemo } from 'react'
import { useFormContext, useWatch, useForm } from 'react-hook-form'
import {
  Select,
  SelectContent,
  SelectItem,
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
import { filterCountries, filterRegions } from '@/lib/helpers'
//@ts-ignore
import countryRegionData from 'country-region-data/dist/data-umd'
import ReactCountryFlag from 'react-country-flag'
import { FormWrapper, FormWrapperProps } from './FormWrapper'

export interface Region {
  name: string
  shortCode: string
}

export interface CountryRegion {
  countryName: string
  countryShortCode: string
  regions: Region[]
}

type FormCountryRegionProps = FormWrapperProps & {
  placeholder?: string
  disabled?: boolean
  onChange?: (value: string) => void
  className?: string
  flagsvg?: boolean
}

const TooltipTrigger2 = React.memo(
  ({ label, handleClick }: { label: string; handleClick: () => void }) => (
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
  ),
)

TooltipTrigger2.displayName = 'TooltipTrigger2'

const CountryFlag = React.memo(
  ({ countryCode, flagsvg }: { countryCode: string; flagsvg: boolean }) => (
    <span className="mr-2">
      <ReactCountryFlag
        countryCode={countryCode}
        svg={flagsvg}
        className="ml-[5px] h-full w-full scale-[170%]"
      />
    </span>
  ),
)

CountryFlag.displayName = 'CountryFlag'

const InternalFormCountrySelect = React.memo(
  ({
    name,
    label,
    placeholder = 'Select a country',
    tooltip,
    required,
    disabled,
    showLabel = true,
    showTooltip = true,
    onChange,
    className,
    flagsvg,
  }: FormCountryRegionProps) => {
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

    const countries = useMemo(
      () => filterCountries(countryRegionData, ['US', 'CA'], [], []),
      [],
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
        {(field) => (
          <TooltipProvider>
            <Tooltip open={open}>
              <div className="relative">
                <Select
                  onValueChange={(value) => {
                    field.onChange(value)
                    onChange && onChange(value)
                  }}
                  value={field.value}
                  disabled={disabled}
                >
                  <SelectTrigger className={`w-full ${className || ''}`}>
                    <SelectValue placeholder={placeholder} />
                  </SelectTrigger>
                  <SelectContent>
                    {countries.map(({ countryName, countryShortCode }) => (
                      <SelectItem
                        key={countryShortCode}
                        value={countryShortCode}
                      >
                        {flagsvg && (
                          <CountryFlag
                            countryCode={countryShortCode}
                            flagsvg={flagsvg}
                          />
                        )}
                        {countryName}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                {tooltip && showTooltip && (
                  <TooltipContent className="mx-4" ref={tooltipRef}>
                    <p>{tooltip}</p>
                  </TooltipContent>
                )}
              </div>
            </Tooltip>
          </TooltipProvider>
        )}
      </FormWrapper>
    )
  },
)

InternalFormCountrySelect.displayName = 'InternalFormCountrySelect'

const InternalFormRegionSelect = React.memo(
  ({
    name,
    label,
    placeholder = 'Select a region',
    tooltip,
    required,
    disabled,
    showLabel = true,
    showTooltip = true,
    onChange,
    className,
    countryCode,
  }: FormCountryRegionProps & { countryCode: string }) => {
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
    const watchedCountry = useWatch({
      control: form.control,
      name: countryCode,
      defaultValue: countryCode,
    })

    const regions = useMemo(() => {
      const country = countryRegionData.find(
        (country: CountryRegion) => country.countryShortCode === watchedCountry,
      )
      if (country) {
        return filterRegions(country.regions, [], [], [])
      }
      return []
    }, [watchedCountry])

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
          <TooltipProvider>
            <Tooltip open={open}>
              <div className="relative">
                <Select
                  onValueChange={(value) => {
                    field.onChange(value)
                    onChange && onChange(value)
                  }}
                  value={field.value}
                  disabled={disabled || regions.length === 0}
                >
                  <SelectTrigger className={`w-full ${className || ''}`}>
                    <SelectValue placeholder={placeholder} />
                  </SelectTrigger>
                  <SelectContent>
                    {regions.map(({ name, shortCode }) => (
                      <SelectItem key={shortCode} value={shortCode}>
                        {name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                {tooltip && showTooltip && (
                  <TooltipContent className="mx-4" ref={tooltipRef}>
                    <p>{tooltip}</p>
                  </TooltipContent>
                )}
              </div>
            </Tooltip>
          </TooltipProvider>
        )}
      </FormWrapper>
    )
  },
)

InternalFormRegionSelect.displayName = 'InternalFormRegionSelect'

export const FormCountrySelect = React.memo((props: FormCountryRegionProps) => {
  const form = useForm()
  return (
    <form>
      <InternalFormCountrySelect {...props} />
    </form>
  )
})

FormCountrySelect.displayName = 'FormCountrySelect'

export const FormRegionSelect = React.memo(
  ({
    countryCode,
    ...props
  }: FormCountryRegionProps & { countryCode: string }) => {
    const form = useForm({
      defaultValues: {
        [props.name]: '',
        [countryCode]: countryCode,
      },
    })

    return (
      <form>
        <InternalFormRegionSelect {...props} countryCode={countryCode} />
      </form>
    )
  },
)

FormRegionSelect.displayName = 'FormRegionSelect'

type FormCountryRegionCombinedProps = {
  countryName: string
  countryLabel: string
  countryTooltip?: string
  regionName: string
  regionLabel: string
  regionTooltip?: string
  required?: boolean
  disabled?: boolean
  className?: string
  flagsvg?: boolean
}

export const FormCountryRegionCombined = React.memo(
  ({
    countryName,
    countryLabel,
    countryTooltip,
    regionName,
    regionLabel,
    regionTooltip,
    required,
    disabled,
    className,
    flagsvg,
  }: FormCountryRegionCombinedProps) => {
    return (
      <>
        <InternalFormCountrySelect
          name={countryName}
          label={countryLabel}
          placeholder="Select a country"
          tooltip={countryTooltip}
          required={required}
          disabled={disabled}
          className={className}
          flagsvg={flagsvg}
        />
        <InternalFormRegionSelect
          name={regionName}
          label={regionLabel}
          placeholder="Select a region"
          tooltip={regionTooltip}
          required={required}
          disabled={disabled || !countryName}
          className={className}
          countryCode={countryName}
        />
      </>
    )
  },
)

FormCountryRegionCombined.displayName = 'FormCountryRegionCombined'
