import { useWatch } from 'react-hook-form'
import FormSelect from './FormSelect'
import type { FormSchemaType } from '@/Schema'

// Boarding v2 only accepts an owner as the application signer, so the signer
// is picked from the owners entered on the ownership step rather than
// collected as a separate person.
export function SignerOwnerSelect() {
  const ownership = useWatch<FormSchemaType, 'ownership'>({ name: 'ownership' })

  const options = (ownership ?? []).map((owner, index) => {
    const name = [owner?.ownerFirstName, owner?.ownerLastName]
      .filter(Boolean)
      .join(' ')
    return {
      value: String(index),
      label: name || `Owner ${index + 1}`,
    }
  })

  return (
    <FormSelect
      name="signerOwnerIndex"
      label="Signer"
      placeholder="Select an owner"
      options={options}
      tooltip="The owner who signs the application. Only an owner can sign."
    />
  )
}
