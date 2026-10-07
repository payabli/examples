import { useWatch } from 'react-hook-form'
import FormSelect from './FormSelect'
import type { FormSchemaType } from '@/Schema'

type OwnerSelectProps = {
  name: 'signerOwnerIndex' | 'primaryControllerOwnerIndex'
  label: string
  tooltip: string
}

// Picks one of the owners entered on the ownership step, by position. Boarding
// v2 designates the signer and the primary controller with flags on an owner's
// business link (`isSigner`, `isPrimaryController`) rather than as separate
// people, so both roles are chosen from the owner list.
export function OwnerSelect({ name, label, tooltip }: OwnerSelectProps) {
  const ownership = useWatch<FormSchemaType, 'ownership'>({ name: 'ownership' })

  const options = (ownership ?? []).map((owner, index) => {
    const ownerName = [owner?.ownerFirstName, owner?.ownerLastName]
      .filter(Boolean)
      .join(' ')
    return {
      value: String(index),
      label: ownerName || `Owner ${index + 1}`,
    }
  })

  return (
    <FormSelect
      name={name}
      label={label}
      placeholder="Select an owner"
      options={options}
      tooltip={tooltip}
    />
  )
}
