import React, { type ReactNode, useState, useRef, useEffect } from "react"
import InputMask from "react-input-mask"
import { FormControl } from "@/components/ui/form"
import { Input } from "@/components/ui/input"
import { Eye, EyeOff, X } from "lucide-react"
import { FormWrapper, type FormWrapperProps } from "./FormWrapper"
import { cn } from "@/lib/utils"
import { motion } from "framer-motion"

type FormInputProps = Omit<FormWrapperProps, "children"> & {
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
  autoComplete?: string[]
  onAutoComplete?: (value: string) => void
}

export default function FormInput({
  name,
  label,
  placeholder,
  tooltip,
  type = "text",
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
  autoComplete,
  onAutoComplete,
}: FormInputProps) {
  const [visible, setVisible] = React.useState(false)
  const [showAutoComplete, setShowAutoComplete] = useState(false)
  const inputRef = useRef<HTMLInputElement>(null)
  const [inputValue, setInputValue] = useState("")
  const autoCompleteRef = useRef<HTMLDivElement>(null)
  const [selectedIndex, setSelectedIndex] = useState(-1)
  const [hoveredIndex, setHoveredIndex] = useState(-1)

  // Check for mutual exclusivity
  if ((password ? 1 : 0) + (clearable ? 1 : 0) + (iconright ? 1 : 0) > 1) {
    throw new Error("Only one of password, clearable, or iconright can be active at a time.")
  }

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>, onChange: (value: string) => void) => {
    let value = e.target.value

    if (numeric) {
      value = value.replace(/[^0-9.]/g, "")
    }

    if (mask && !includeMaskedChars) {
      value = value.replace(/\D/g, "")
    }

    setInputValue(value)
    onChange(value)
    setSelectedIndex(-1)
    setHoveredIndex(-1)
    if (autoComplete && value) {
      setShowAutoComplete(true)
    } else {
      setShowAutoComplete(false)
    }
  }

  const handleAutoComplete = (value: string) => {
    if (inputRef.current) {
      inputRef.current.value = value
      inputRef.current.dispatchEvent(new Event("input", { bubbles: true }))
    }
    setInputValue(value)
    setShowAutoComplete(false)
    if (onAutoComplete) {
      onAutoComplete(value)
    }
    inputRef.current?.focus()
  }

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (autoCompleteRef.current && !autoCompleteRef.current.contains(event.target as Node)) {
        setShowAutoComplete(false)
      }
    }

    document.addEventListener("mousedown", handleClickOutside)
    return () => {
      document.removeEventListener("mousedown", handleClickOutside)
    }
  }, [])

  const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (!showAutoComplete) return

    const filteredOptions =
      autoComplete?.filter((item) => item.toLowerCase().includes(inputValue.toLowerCase())).slice(0, 10) || []

    switch (e.key) {
      case "ArrowDown":
        e.preventDefault()
        setSelectedIndex((prevIndex) => (prevIndex < filteredOptions.length - 1 ? prevIndex + 1 : prevIndex))
        setHoveredIndex(-1)
        break
      case "ArrowUp":
        e.preventDefault()
        setSelectedIndex((prevIndex) => (prevIndex > 0 ? prevIndex - 1 : -1))
        setHoveredIndex(-1)
        break
      case "Tab":
        e.preventDefault()
        setSelectedIndex((prevIndex) => (prevIndex < filteredOptions.length - 1 ? prevIndex + 1 : -1))
        setHoveredIndex(-1)
        break
      case "Enter":
        e.preventDefault()
        if (selectedIndex !== -1) {
          handleAutoComplete(filteredOptions[selectedIndex])
        }
        setShowAutoComplete(false)
        break
      case "Escape":
        setShowAutoComplete(false)
        break
    }
  }

  const renderInput = (field: any) => (
    <FormControl>
      <div className="relative flex" ref={autoCompleteRef}>
        {prefix && (
          <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground/80">{prefix}</span>
        )}
        {mask ? (
          <InputMask
            mask={mask}
            value={field.value}
            onChange={(e: React.ChangeEvent<HTMLInputElement>) => handleInputChange(e, field.onChange)}
            onFocus={() => setShowAutoComplete(!!inputValue && !!autoComplete)}
            onBlur={field.onBlur}
            onKeyDown={handleKeyDown}
            disabled={disabled}
            maxLength={maxLength}
            className={`w-full ${iconleft || prefix ? "ps-10" : ""} ${
              iconright || password || clearable || postfix ? "pe-10" : ""
            }`}
          >
            {(inputProps: any) => (
              <Input
                {...inputProps}
                ref={inputRef}
                id={name}
                placeholder={placeholder}
                type={password && !visible ? "password" : type}
                required={required}
                aria-invalid={field.error ? "true" : "false"}
                aria-describedby={`${name}-error`}
                maxLength={maxLength}
                autoComplete="off"
              />
            )}
          </InputMask>
        ) : (
          <Input
            id={name}
            ref={inputRef}
            className={`peer ${iconleft || prefix ? "ps-10" : ""} ${
              iconright || password || clearable || postfix ? "pe-10" : ""
            }`}
            placeholder={placeholder}
            type={password && !visible ? "password" : type}
            required={required}
            disabled={disabled}
            aria-invalid={field.error ? "true" : "false"}
            aria-describedby={`${name}-error`}
            {...field}
            onChange={(e) => handleInputChange(e, field.onChange)}
            onFocus={() => setShowAutoComplete(!!inputValue && !!autoComplete)}
            onKeyDown={handleKeyDown}
            maxLength={maxLength}
            autoComplete="off"
          />
        )}
        {password && (
          <button
            type="button"
            onClick={() => setVisible((prev) => !prev)}
            className="absolute inset-y-0 end-0 flex h-full w-10 cursor-pointer items-center justify-center pe-3 text-muted-foreground/80 peer-disabled:opacity-50"
            aria-label={visible ? "Hide password" : "Show password"}
          >
            {visible ? <EyeOff size={18} /> : <Eye size={18} />}
          </button>
        )}
        {clearable && field.value && (
          <button
            type="button"
            onClick={() => {
              field.onChange("")
              setInputValue("")
            }}
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
          <span className="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground/80">{postfix}</span>
        )}
        {autoComplete && showAutoComplete && (
          <motion.div
            initial={{ opacity: 0, y: -5 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -5 }}
            transition={{ duration: 0.2 }}
            className="absolute left-0 right-0 top-full z-50 mt-1 overflow-hidden rounded-md border bg-popover text-popover-foreground shadow-md data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2"
          >
            <div className="w-full p-1">
              {autoComplete
                .filter((item) => item.toLowerCase().includes(inputValue.toLowerCase()))
                .slice(0, 10)
                .map((item, index) => (
                  <div
                    key={item}
                    onClick={() => handleAutoComplete(item)}
                    onMouseEnter={() => setHoveredIndex(index)}
                    onMouseLeave={() => setHoveredIndex(-1)}
                    onMouseDown={(e) => e.preventDefault()} // Prevent onBlur from firing before onClick
                    className={cn(
                      "relative flex w-full cursor-default select-none items-center rounded-sm py-1.5 pl-8 pr-2 text-sm outline-none transition-colors focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50",
                      (index === selectedIndex || index === hoveredIndex) && "bg-accent text-accent-foreground",
                    )}
                  >
                    <span className="absolute left-2 flex h-3.5 w-3.5 items-center justify-center">
                      {(index === selectedIndex || index === hoveredIndex) && (
                        <motion.svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="24"
                          height="24"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          strokeWidth="2"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          className="h-4 w-4"
                          initial={{ scale: 0 }}
                          animate={{ scale: 1 }}
                          transition={{ duration: 0.2 }}
                        >
                          <polyline points="20 6 9 17 4 12"></polyline>
                        </motion.svg>
                      )}
                    </span>
                    <span>{item}</span>
                  </div>
                ))}
            </div>
          </motion.div>
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

