import React, { useState, useEffect, useRef, ReactNode, useMemo } from 'react'
import { useFormContext, useWatch, Controller } from 'react-hook-form'
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

export interface Region {
  name: string
  shortCode: string
}

export interface CountryRegion {
  countryName: string
  countryShortCode: string
  regions: Region[]
}

type FormSelectTypes = {
  control?: any
  name: string
  label: string
  placeholder?: string
  tooltip?: string
  required?: boolean
  disabled?: boolean
  showLabel?: boolean
  showTooltip?: boolean
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

const FormSelect = React.memo(
  ({
    control,
    name,
    label,
    placeholder,
    tooltip,
    required,
    disabled,
    showLabel = true,
    showTooltip = true,
    onChange,
    className,
    children,
  }: FormSelectTypes & { children: React.ReactNode }) => {
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
                      <span
                        className="ml-1 text-destructive"
                        aria-hidden="true"
                      >
                        *
                      </span>
                    )}
                    {tooltip && showTooltip && (
                      <TooltipTrigger2
                        label={label}
                        handleClick={handleClick}
                      />
                    )}
                  </FormLabel>
                )}
                <FormMessage id={`${name}-error`} />
                <FormControl>
                  <div className="relative">
                    <Select
                      onValueChange={(value) => {
                        field.onChange(value)
                        onChange && onChange(value)
                      }}
                      value={field.value}
                      disabled={disabled}
                    >
                      <SelectTrigger
                        className={`w-full ${className || ''}`}
                        aria-invalid={
                          form.formState.errors[name] ? 'true' : 'false'
                        }
                        aria-describedby={`${name}-error`}
                      >
                        <SelectValue placeholder={placeholder} />
                      </SelectTrigger>
                      <SelectContent>{children}</SelectContent>
                    </Select>
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
  },
)

FormSelect.displayName = 'FormSelect'

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
    control,
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
  }: FormSelectTypes) => {
    const countries = useMemo(
      () => filterCountries(countryRegionData, [], [], []),
      []
    )

    const selectItems = useMemo(
      () =>
        countries.map(({ countryName, countryShortCode }) => (
          <SelectItem key={countryShortCode} value={countryShortCode}>
            {flagsvg && (
              <CountryFlag countryCode={countryShortCode} flagsvg={flagsvg} />
            )}
            {countryName}
          </SelectItem>
        )),
      [countries, flagsvg],
    )

    return (
      <FormSelect
        control={control}
        name={name}
        label={label}
        placeholder={placeholder}
        tooltip={tooltip}
        required={required}
        disabled={disabled}
        showLabel={showLabel}
        showTooltip={showTooltip}
        onChange={onChange}
        className={className}
      >
        {selectItems}
      </FormSelect>
    )
  },
)

InternalFormCountrySelect.displayName = 'InternalFormCountrySelect'

const InternalFormRegionSelect = React.memo(
  ({
    control,
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
  }: FormSelectTypes & { countryCode: string }) => {
    const form = useFormContext()
    const watchedCountry = useWatch({ control: form.control, name: countryCode })

    const regions = useMemo(() => {
      const country = countryRegionData.find(
        (country: CountryRegion) => country.countryShortCode === watchedCountry
      )
      if (country) {
        return filterRegions(country.regions, [], [], [])
      }
      return []
    }, [watchedCountry])

    const selectItems = useMemo(
      () =>
        regions.map(({ name, shortCode }) => (
          <SelectItem key={shortCode} value={shortCode}>
            {name}
          </SelectItem>
        )),
      [regions],
    )

    return (
      <FormSelect
        control={control}
        name={name}
        label={label}
        placeholder={placeholder}
        tooltip={tooltip}
        required={required}
        disabled={disabled || !watchedCountry}
        showLabel={showLabel}
        showTooltip={showTooltip}
        onChange={onChange}
        className={className}
      >
        {selectItems}
      </FormSelect>
    )
  },
)

InternalFormRegionSelect.displayName = 'InternalFormRegionSelect'

export const FormCountrySelect = React.memo(
  ({
    control,
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
  }: FormSelectTypes) => {
    return (
      <InternalFormCountrySelect
        control={control}
        name={name}
        label={label}
        placeholder={placeholder}
        tooltip={tooltip}
        required={required}
        disabled={disabled}
        showLabel={showLabel}
        showTooltip={showTooltip}
        onChange={onChange}
        className={className}
        flagsvg={flagsvg}
      />
    )
  }
)

FormCountrySelect.displayName = 'FormCountrySelect'

export const FormRegionSelect = React.memo(
  ({
    control,
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
  }: FormSelectTypes & { countryCode: string }) => {
    const regions = useMemo(() => {
      const country = countryRegionData.find(
        (country: CountryRegion) => country.countryShortCode === countryCode
      )
      if (country) {
        return filterRegions(country.regions, [], [], [])
      }
      return []
    }, [countryCode])

    const selectItems = useMemo(
      () =>
        regions.map(({ name, shortCode }) => (
          <SelectItem key={shortCode} value={shortCode}>
            {name}
          </SelectItem>
        )),
      [regions],
    )

    return (
      <FormSelect
        control={control}
        name={name}
        label={label}
        placeholder={placeholder}
        tooltip={tooltip}
        required={required}
        disabled={disabled || !countryCode}
        showLabel={showLabel}
        showTooltip={showTooltip}
        onChange={onChange}
        className={className}
      >
        {selectItems}
      </FormSelect>
    )
  }
)

FormRegionSelect.displayName = 'FormRegionSelect'

type FormCountryRegionProps = {
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

export const FormCountryRegionCombined = React.memo(({
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
}: FormCountryRegionProps) => {
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
        disabled={disabled}
        className={className}
        countryCode={countryName}
      />
    </>
  )
})

FormCountryRegionCombined.displayName = 'FormCountryRegionCombined'

