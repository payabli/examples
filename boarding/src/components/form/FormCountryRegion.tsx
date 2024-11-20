import React, {
  useState,
  useEffect,
  useRef,
  ReactNode,
  useMemo,
  useCallback,
  memo,
} from 'react'
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
  options?: SelectOption[]
  required?: boolean
  disabled?: boolean
  showLabel?: boolean
  showTooltip?: boolean
  priorityOptions?: string[]
  whitelist?: string[]
  blacklist?: string[]
  onChange?:
    | ((value: string) => void)
    | ((value: string, index: number) => void)
  className?: string
  flag?: boolean
  flagsvg?: boolean
}

const TooltipTrigger2 = memo(
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

const FormSelect = memo(
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
    flag,
    flagsvg,
    children,
  }: FormSelectTypes & { children: React.ReactNode }) => {
    const [open, setOpen] = useState(false)
    const tooltipRef = useRef<HTMLDivElement | null>(null)

    const handleClick = useCallback(() => setOpen((prev) => !prev), [])

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
                      defaultValue={field.value}
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

const CountryFlag = memo(
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

const FormCountrySelect = memo(
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
    priorityOptions = [],
    whitelist = [],
    blacklist = [],
    onChange,
    className,
    flag,
    flagsvg,
  }: FormSelectTypes) => {
    const countries = useMemo(
      () =>
        filterCountries(
          countryRegionData,
          priorityOptions,
          whitelist,
          blacklist,
        ),
      [priorityOptions, whitelist, blacklist],
    )

    if (flag && flagsvg) {
      throw new Error('flag and flagsvg cannot be used together')
    }

    const selectItems = useMemo(
      () =>
        countries.map(({ countryName, countryShortCode }) => (
          <SelectItem key={countryShortCode} value={countryShortCode}>
            {(flag || flagsvg) && (
              <CountryFlag countryCode={countryShortCode} flagsvg={!!flagsvg} />
            )}
            {countryName}
          </SelectItem>
        )),
      [countries, flag, flagsvg],
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
        flag={flag}
        flagsvg={flagsvg}
      >
        {selectItems}
      </FormSelect>
    )
  },
)

FormCountrySelect.displayName = 'FormCountrySelect'

const FormRegionSelect = memo(
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
    priorityOptions = [],
    whitelist = [],
    blacklist = [],
    onChange,
    className,
    countryCode,
  }: FormSelectTypes & { countryCode: string }) => {
    const form = useFormContext()

    const regions = useMemo(() => {
      const country = countryRegionData.find(
        (country: CountryRegion) => country.countryShortCode === countryCode,
      )

      if (country) {
        return filterRegions(
          country.regions,
          priorityOptions,
          whitelist,
          blacklist,
        )
      }
      return []
    }, [countryCode, priorityOptions, whitelist, blacklist])

    useEffect(() => {
      const currentRegion = form.getValues(name)
      const isValidRegion = regions.some(
        (region) => region.shortCode === currentRegion,
      )

      if (!isValidRegion) {
        form.setValue(name, '')
      }
    }, [regions, form, name])

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
  },
)

FormRegionSelect.displayName = 'FormRegionSelect'

const FormCountryRegion: React.FC = () => {
  const [selectedCountry, setSelectedCountry] = useState('')

  const handleCountryChange = useCallback((value: string) => {
    setSelectedCountry(value)
  }, [])

  return (
    <div className="space-y-4">
      <FormCountrySelect
        name="country"
        label="Country"
        placeholder="Select your country"
        tooltip="Select your country of residence"
        required
        onChange={handleCountryChange}
        flagsvg
      />
      <FormRegionSelect
        name="region"
        label="State/Province"
        placeholder="Select a state/province"
        tooltip="Select your state or province"
        countryCode={selectedCountry}
        required
      />
    </div>
  )
}

export { FormCountrySelect, FormRegionSelect }
export default memo(FormCountryRegion)
