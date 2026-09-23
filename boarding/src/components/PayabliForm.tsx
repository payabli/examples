import { useState, useMemo, useEffect, useRef } from 'react'
import { Form } from '@/components/ui/form'
import FormInput from './form/FormInput'
import { Wizard, WizardStep } from './form/Wizard'
import {
  Calendar,
  CreditCard,
  FileText,
  Building,
  Users,
  Banknote,
  Save,
  X,
  LoaderPinwheel,
  SaveAll,
  SaveAllIcon,
  SaveIcon,
  Loader2,
  Percent,
} from 'lucide-react'
import FormSelect from './form/FormSelect'
import { motion } from 'framer-motion'
import {
  FormCountrySelect,
  FormRegionSelect,
  FormCountryRegionCombined,
} from './form/FormCountryRegion'
import FormSwitch from './form/FormSwitch'
import { useFormLogic } from '@/onSubmit'
import { DynamicFormSection } from './form/DynamicFormSection'
import { Button } from './ui/button'
import { useDrizzle } from '@/lib/clientDb'
import { formDefaultValues, useFormWithSchema } from '@/Schema'
import { documentPages } from './ESigDocument'
import { useESignature } from '@/hooks/use-esignature'
import { formSchema } from '@/Schema'
import z from 'zod'

import { ESignature } from './form/ESignature'
import { SVGLogoPlaceholder } from './form/SVGLogoPlaceholder'

type FormSchemaType = z.infer<typeof formSchema>

export function PayabliForm() {
  const formHeaderText = 'Boarding Application'

  // Loading states
  const [isLoading, setIsLoading] = useState(true)
  const [isDataLoaded, setIsDataLoaded] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [isSaveWorking, setIsSaveWorking] = useState(false)
  const [isClearWorking, setIsClearWorking] = useState(false)
  const [isFormVisible, setIsFormVisible] = useState(false)
  const isMounted = useRef(false)

  // Form state, manually manage only complex fields in form (dynamic or dependent ones)
  const [currentPage, setCurrentPage] = useState(0)
  const [contacts, setContacts] = useState([{}])
  const [ownership, setOwnership] = useState([{}])
  const [bankData, setBankData] = useState([
    {
      nickname: 'Deposit Account',
      bankName: '',
      routingAccount: '',
      accountNumber: '',
      typeAccount: 'Checking',
      bankAccountFunction: 'Deposit',
    },
    {
      nickname: 'Withdrawal Account',
      bankName: '',
      routingAccount: '',
      accountNumber: '',
      typeAccount: 'Checking',
      bankAccountFunction: 'Withdrawal',
    },
  ])
  const [ownershipCountries, setOwnershipCountries] = useState<string[]>([''])
  const [ownershipIndex, setOwnershipIndex] = useState(0)
  const [businessCountry, setBusinessCountry] = useState('')
  const [mailingCountry, setMailingCountry] = useState('')
  const [signerCountry, setSignerCountry] = useState('')

  const addContact = () => setContacts([...contacts, {}])
  const removeContact = (index: number) => {
    setContacts((prevContacts) => prevContacts.filter((_, i) => i !== index))

    const currentValues = form.getValues()
    const updatedContacts = currentValues.contacts.filter((_, i) => i !== index)
    form.setValue('contacts', updatedContacts as any)

    form.trigger('contacts')
  }

  const addOwner = () => {
    setOwnership([...ownership, {}])
    setOwnershipCountries([...ownershipCountries, ''])
  }
  const removeOwner = (index: number) => {
    setOwnership((prevOwnership) => prevOwnership.filter((_, i) => i !== index))

    const currentValues = form.getValues()
    const updatedOwnership = currentValues.ownership.filter(
      (_, i) => i !== index,
    )
    form.setValue('ownership', updatedOwnership as any)

    form.trigger('ownership')
  }

  const addBankAccount = () => {
    const newBankAccount = {
      nickname: '',
      bankName: '',
      routingAccount: '',
      accountNumber: '',
      typeAccount: 'Checking',
      bankAccountFunction: 'Deposit',
    }

    setBankData([...bankData, newBankAccount])
    
    // Update form values to include the new bank account
    const currentValues = form.getValues()
    const updatedBankData = [...(currentValues.bankData || []), newBankAccount]
    form.setValue('bankData', updatedBankData as any)
  }
  const removeBankAccount = (index: number) => {
    setBankData((prevBankData) => prevBankData.filter((_, i) => i !== index))

    const currentValues = form.getValues()
    const updatedBankData = currentValues.bankData?.filter((_, i) => i !== index) || []
    form.setValue('bankData', updatedBankData as any)

    form.trigger('bankData')
  }

  const form = useFormWithSchema()
  const { saveForLater, clearFormData, loadSavedData } = useDrizzle()

  useEffect(() => {
    isMounted.current = true
    return () => {
      isMounted.current = false
    }
  }, [])

  useEffect(() => {
    const loadData = async () => {
      setIsLoading(true)
      setIsDataLoaded(false)
      try {
        const loadedData = await loadSavedData()
        if (loadedData) {
          const savedData: FormSchemaType = loadedData

          form.reset(savedData)

          setBusinessCountry(savedData.bcountry || '')
          setMailingCountry(savedData.mcountry || '')
          setSignerCountry(savedData.signer?.country || '')

          if (savedData.contacts) {
            setContacts(savedData.contacts)
          }
          if (savedData.ownership) {
            setOwnership(savedData.ownership)
          }
          if (savedData.bankData && savedData.bankData.length > 0) {
            setBankData(
              savedData.bankData.map((account) => ({
                ...account,
                bankAccountFunction: String(account.bankAccountFunction),
              })),
            )
          } else {
            // Ensure default bank accounts if none exist
            const defaultBankData = [
              { 
                nickname: 'Deposit Account',
                bankName: '',
                routingAccount: '',
                accountNumber: '',
                typeAccount: 'Checking',
                bankAccountHolderName: '',
                bankAccountHolderType: 'Business',
                bankAccountFunction: '0',
              },
              { 
                nickname: 'Withdrawal Account',
                bankName: '',
                routingAccount: '',
                accountNumber: '',
                typeAccount: 'Checking',
                bankAccountHolderName: '',
                bankAccountHolderType: 'Business',
                bankAccountFunction: '1',
              },
            ]
            setBankData(defaultBankData)
            form.setValue('bankData', defaultBankData as any)
          }
        }

        setIsDataLoaded(true)
      } catch (error) {
        console.error('Error loading saved data:', error)
        setError('Failed to load data. Please try again.')
      } finally {
        setIsLoading(false)
      }
    }

    loadData()
  }, [loadSavedData, form])

  const handleSaveForLater = async () => {
    setIsSaveWorking(true)
    try {
      const formData = form.getValues()
      await saveForLater(formData)
    } catch (error) {
      console.error('Error saving data:', error)
      // Optionally, set an error state here to display to the user
    } finally {
      setIsSaveWorking(false)
    }
  }

  const handleClearData = async () => {
    setIsClearWorking(true)
    try {
      await clearFormData()
      form.reset(formDefaultValues)
    } catch (error) {
      console.error('Error clearing data:', error)
      // Optionally, set an error state here to display to the user
    } finally {
      setIsClearWorking(false)
    }
  }

  const controls = (
    <div className="align-center mb-6 mt-2 flex w-full md:hidden">
      <Button
        onClick={handleSaveForLater}
        className="w-44 scale-[90%] justify-start"
        type="button"
      >
        {!isSaveWorking ? (
          <SaveAll className="mr-3" />
        ) : (
          <Loader2 className="mr-3 animate-spin" />
        )}
        Save Progress
      </Button>

      <Button
        onClick={handleClearData}
        className="ml-auto w-44 scale-[90%]"
        type="button"
      >
        {!isClearWorking ? (
          <X className="mr-3" />
        ) : (
          <Loader2 className="mr-3 animate-spin" />
        )}
        Clear Progress
      </Button>
    </div>
  )

  const steps = useMemo(
    () => (
      <Wizard
        currentPage={currentPage}
        setCurrentPage={setCurrentPage}
        preChildren={controls}
        onPageChange={(page: number) => {
          // any logic to run when the page changes
          // handleSaveForLater()
          console.log('Page changed:', page)
        }}
      >
        <WizardStep icon={<Building />} label="Business Information">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 1: Business Information
          </h2>
          <div className="items-end gap-4 md:grid md:grid-cols-2">
            <FormInput
              name="legalName"
              label="Legal Name"
              tooltip="The official registered name of your business"
            />
            <FormInput
              name="doingBusinessAs"
              label="DBA Name"
              tooltip="The name your business operates under, if different from the legal name"
            />
            <FormInput
              name="website"
              label="Website"
              tooltip="Your business website URL"
            />
            <FormInput
              name="startdate"
              label="Business Start Date"
              tooltip="The date your business began operations"
              iconleft={<Calendar className="mr-2" />}
              mask="99/99/9999"
              placeholder="MM/DD/YYYY"
              includeMaskedChars
            />
            <FormInput
              name="phonenumber"
              label="Phone Number"
              tooltip="Your business phone number"
              mask="(999) 999-9999"
            />
            <FormSelect
              name="legalStructure"
              label="Business Type"
              options={[
                { value: 'limited-liability-company', label: 'Limited Liability Company' },
                { value: 'corporation', label: 'Corporation' },
                { value: 'partnership', label: 'Partnership' },
                { value: 'sole-proprietorship', label: 'Sole Proprietorship' },
                { value: 'non-profit', label: 'Non-Profit' },
                { value: 'government', label: 'Government' },
                { value: 's-corp', label: 'S-Corporation' },
              ]}
              tooltip="The legal structure of your business"
            />
          </div>
        </WizardStep>

        <WizardStep icon={<FileText />} label="Business Details">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 2: Business Details
          </h2>
          <div className="items-end gap-4 md:grid md:grid-cols-2">
            <FormInput
              name="baddress"
              label="Business Address"
              tooltip="The primary address of your business"
              // Use country and region codes that match the country and region pickers
              autoComplete={[
                '123 Main St, New York, NY 10001, US', 
                '456 Elm St, Los Angeles, CA 90001, US',
              ]}
              onAutoComplete={(value) => {
                console.log('Autocompleted:', value)
                const [address, city, stateZip, country] = value.split(',').map(part => part.trim());
                const [state, zip] = stateZip.split(' ').map(part => part.trim())
                form.setValue('baddress', address)
                form.setValue('bcity', city)
                form.setValue('bzip', zip)
                form.setValue('bcountry', country)
                setBusinessCountry(country)
                // delay setting state to allow country to update first
                // because the region select is dependent on the country
                setTimeout(() => {
                  form.setValue('bstate', state)
                }, 1)
              }}
            />
            <FormInput
              name="baddress1"
              label="Business Address Line 2"
              tooltip="Additional address information (if needed)"
            />
            <FormInput
              name="bcity"
              label="Business City"
              tooltip="The city where your business is located"
            />
            <FormCountryRegionCombined
              countryName="bcountry"
              countryLabel="Business Country"
              countryTooltip="The country where your business is located"
              regionName="bstate"
              regionLabel="Business State"
              regionTooltip="The state where your business is located"
            />
            <FormInput
              name="bzip"
              label="Business ZIP"
              tooltip="The ZIP code of your business location"
            />
            <FormInput
              name="maddress"
              label="Mailing Address"
              tooltip="The address where you receive business mail (if different from business address)"
            />
            <FormInput
              name="maddress1"
              label="Mailing Address Line 2"
              tooltip="Additional mailing address information (if needed)"
            />
            <FormInput
              name="mcity"
              label="Mailing City"
              tooltip="The city for your mailing address"
            />
            <FormCountryRegionCombined
              countryName="mcountry"
              countryLabel="Mailing Country"
              countryTooltip="The country for your mailing address"
              regionName="mstate"
              regionLabel="Mailing State"
              regionTooltip="The state for your mailing address"
            />
            <FormInput
              name="mzip"
              label="Mailing ZIP"
              tooltip="The ZIP code for your mailing address"
            />
          </div>
        </WizardStep>

        <WizardStep icon={<Users />} label="Contacts & Ownership">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 3: Contacts & Ownership
          </h2>
          <DynamicFormSection
            title="Contacts"
            items={contacts}
            addItem={addContact}
            removeItem={removeContact}
            addButtonText="Add New Contact"
          >
            <FormInput
              name="contacts[].contactFirstName"
              label="Contact First Name"
              tooltip="First name of the contact person"
            />
            <FormInput
              name="contacts[].contactLastName"
              label="Contact Last Name"
              tooltip="Last name of the contact person"
            />
            <FormInput
              name="contacts[].contactEmail"
              label="Contact Email"
              tooltip="Email address of the contact person"
            />
            <FormInput
              name="contacts[].contactTitle"
              label="Contact Title"
              tooltip="Job title or position of the contact person"
            />
            <FormInput
              name="contacts[].contactPhone"
              label="Contact Phone"
              tooltip="Phone number of the contact person"
              mask="(999) 999-9999"
            />
          </DynamicFormSection>

          <DynamicFormSection
            title="Ownership"
            items={ownership}
            addItem={addOwner}
            removeItem={removeOwner}
            addButtonText="Add New Owner"
          >
            <FormInput
              name="ownership[].ownerFirstName"
              label="Owner First Name"
              tooltip="First name of the owner"
            />
            <FormInput
              name="ownership[].ownerLastName"
              label="Owner Last Name"
              tooltip="Last name of the owner"
            />
            <FormInput
              name="ownership[].ownertitle"
              label="Owner Title"
              tooltip="Title or position of the owner"
            />
            <FormInput
              name="ownership[].ownerpercent"
              label="Ownership Percentage"
              tooltip="Percentage of ownership (0-100)"
              postfix="%"
              numeric
              maxLength={3}
            />
            <FormInput
              name="ownership[].ownerssn"
              label="Owner SSN"
              tooltip="Social Security Number of the owner (9 digits)"
              mask="999-99-9999"
            />
            <FormInput
              name="ownership[].ownerdob"
              label="Owner Date of Birth"
              tooltip="Date of birth of the owner"
              iconleft={<Calendar className="mr-2" />}
              mask="99/99/9999"
              placeholder="MM/DD/YYYY"
              includeMaskedChars
            />
            <FormInput
              name="ownership[].ownerphone1"
              label="Owner Phone 1"
              tooltip="Primary phone number of the owner"
              mask="(999) 999-9999"
            />
            <FormInput
              name="ownership[].ownerphone2"
              label="Owner Phone 2"
              tooltip="Secondary phone number of the owner (if applicable)"
              mask="(999) 999-9999"
            />
            <FormInput
              name="ownership[].owneremail"
              label="Owner Email"
              tooltip="Email address of the owner"
            />
            <FormInput
              name="ownership[].ownerdriver"
              label="Owner Driver's License"
              tooltip="Driver's license number of the owner"
            />
            <FormRegionSelect
              name="ownership[].odriverstate"
              label="Driver's License State"
              tooltip="State where the owner's driver's license was issued"
              countryCode="US"
            />
            <FormInput
              name="ownership[].oaddress"
              label="Owner Address"
              tooltip="Residential address of the owner"
            />
            <FormCountryRegionCombined
              countryName="ownership[].ocountry"
              countryLabel="Owner Country"
              countryTooltip="Country of the owner's residence"
              regionName="ownership[].ostate"
              regionLabel="Owner State"
              regionTooltip="State of the owner's residence"
            />
            <FormInput
              name="ownership[].ocity"
              label="Owner City"
              tooltip="City of the owner's residence"
            />
            <FormInput
              name="ownership[].ozip"
              label="Owner ZIP"
              tooltip="ZIP code of the owner's residence"
            />
          </DynamicFormSection>
        </WizardStep>

        <WizardStep icon={<Banknote />} label="Financial Information">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 4: Financial Information
          </h2>
          <div className="items-end gap-4 md:grid md:grid-cols-2">
            <FormInput
              name="mcc"
              label="MCC"
              tooltip="Merchant Category Code (4 digits)"
            />
            <FormInput
              name="taxReference"
              label="Tax Reference (EIN)"
              tooltip="Your Employer Identification Number (9 digits)"
              mask="99-9999999"
            />
            <FormInput
              name="bsummary"
              label="Business Summary"
              tooltip="Brief description of your business activities"
            />
            <FormSelect
              name="whenRefunded"
              label="When Refunded"
              options={[
                { value: 'Exchange Only', label: 'Exchange Only' },
                {
                  value: 'No Refund or Exchange',
                  label: 'No Refund or Exchange',
                },
                { value: '30 Days or Less', label: '30 Days or Less' },
                { value: 'More than 30 Days', label: 'More than 30 Days' },
              ]}
              tooltip="Typical timeframe for processing refunds"
            />
            <FormInput
              name="binperson"
              label="In-Person Sales (%)"
              tooltip="Percentage of sales conducted in person"
              postfix="%"
              numeric
              maxLength={3}
            />
            <FormInput
              name="binphone"
              label="Phone Sales (%)"
              tooltip="Percentage of sales conducted over the phone"
              postfix="%"
              numeric
              maxLength={3}
            />
            <FormInput
              name="binweb"
              label="Web Sales (%)"
              tooltip="Percentage of sales conducted online"
              postfix="%"
              numeric
              maxLength={3}
            />
            <FormInput
              name="annualRevenue"
              label="Annual Revenue"
              tooltip="Estimated annual revenue of your business"
              prefix="$"
              numeric
            />
            <FormInput
              name="avgmonthly"
              label="Average Monthly Volume"
              tooltip="Average monthly sales volume"
              prefix="$"
              numeric
            />
            <FormInput
              name="ticketamt"
              label="Average Ticket Amount"
              tooltip="Average amount per transaction"
              prefix="$"
              numeric
            />
            <FormInput
              name="highticketamt"
              label="Highest Ticket Amount"
              tooltip="Highest expected transaction amount"
              prefix="$"
              numeric
            />
          </div>
        </WizardStep>

        <WizardStep icon={<CreditCard />} label="Payment Information">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 5: Payment Information
          </h2>
          <div className="space-y-8">
            <DynamicFormSection
              title="Bank Accounts"
              items={bankData}
              addItem={addBankAccount}
              removeItem={removeBankAccount}
              addButtonText="Add Bank Account"
            >
              <FormInput
                name="bankData[].nickname"
                label="Account Nickname"
                tooltip="A friendly name for this account (e.g., 'Deposit Account', 'Withdrawal Account')"
              />
              <FormSelect
                name="bankData[].bankAccountFunction"
                label="Account Function"
                options={[
                  { value: 'Deposit', label: 'Deposit' },
                  { value: 'Withdrawal', label: 'Withdrawal' },
                  { value: 'Both', label: 'Both' },
                  { value: 'Remittance', label: 'Remittance' },
                ]}
                tooltip="The purpose of this bank account"
              />
              <FormInput
                name="bankData[].bankName"
                label="Bank Name"
                tooltip="Name of the bank"
              />
              <FormInput
                name="bankData[].routingAccount"
                label="Routing Number"
                tooltip="9-digit routing number"
                numeric
                maxLength={9}
              />
              <FormInput
                name="bankData[].accountNumber"
                label="Account Number"
                tooltip="Bank account number"
              />
              <FormSelect
                name="bankData[].typeAccount"
                label="Account Type"
                options={[
                  { value: 'Checking', label: 'Checking' },
                  { value: 'Savings', label: 'Savings' },
                ]}
                tooltip="Type of bank account"
              />
            </DynamicFormSection>

            <div className="space-y-4">
              <h3 className="text-lg font-semibold">Signer Information</h3>
              <div className="items-end gap-4 md:grid md:grid-cols-2">
                <FormInput
                  name="signer.firstName"
                  label="Signer First Name"
                  tooltip="First name of the person signing the application"
                />
                <FormInput
                  name="signer.lastName"
                  label="Signer Last Name"
                  tooltip="Last name of the person signing the application"
                />
                <FormInput
                  name="signer.ssn"
                  label="Signer SSN"
                  tooltip="Social Security Number of the signer (9 digits)"
                  mask="999-99-9999"
                />
                <FormInput
                  name="signer.dob"
                  label="Signer Date of Birth"
                  tooltip="Date of birth of the signer"
                  iconleft={<Calendar className="mr-2" />}
                  mask="99/99/9999"
                  placeholder="MM/DD/YYYY"
                  includeMaskedChars
                />
                <FormInput
                  name="signer.phone"
                  label="Signer Phone"
                  tooltip="Phone number of the signer"
                  mask="(999) 999-9999"
                />
                <FormInput
                  name="signer.email"
                  label="Signer Email"
                  tooltip="Email address of the signer"
                />
                <FormInput
                  name="signer.address"
                  label="Signer Address"
                  tooltip="Street address of the signer"
                />
                <FormInput
                  name="signer.address1"
                  label="Signer Address Line 2"
                  tooltip="Additional address information for the signer (if needed)"
                />
                <FormCountryRegionCombined
                  countryName="signer.country"
                  countryLabel="Signer Country"
                  countryTooltip="Country of the signer's residence"
                  regionName="signer.state"
                  regionLabel="Signer State"
                  regionTooltip="State of the signer's residence"
                />
                <FormInput
                  name="signer.city"
                  label="Signer City"
                  tooltip="City of the signer's residence"
                />
                <FormInput
                  name="signer.zip"
                  label="Signer ZIP"
                  tooltip="ZIP code of the signer's residence"
                />
              </div>
            </div>
          </div>
        </WizardStep>
      </Wizard>
    ),
    [
      currentPage,
      setCurrentPage,
      contacts,
      ownership,
      bankData,
      businessCountry,
      mailingCountry,
      signerCountry,
      ownershipCountries,
    ],
  )

  const { onSuccess, onError } = useFormLogic(steps, setCurrentPage)

  const { handleESignatureProcess, handleConfirm, contentRef } = useESignature({
    documentBody: documentPages,
  })

  const [appId, setAppId] = useState('')
  const [signerPersonReference, setSignerPersonReference] = useState('')

  const onSuccessWithForm = async (values: FormSchemaType) => {
    try {
      const created = await onSuccess(values)
      if (!created) {
        return
      }
      setAppId(created.applicationReference)
      setSignerPersonReference(created.signerPersonReference)
      handleESignatureProcess(created.applicationReference)
    } catch (error) {
      return
    }
  }

  const onConfirm = () => {
    handleConfirm(appId, signerPersonReference)
  }

  if (isLoading || !isDataLoaded) {
    return (
      <div className="flex h-screen items-center justify-center">
        <Loader2 className="h-16 w-16 animate-spin opacity-30" />
      </div>
    )
  }

  return (
    <>
      <motion.div
        initial={{ opacity: 0, scale: 0.99 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ duration: 0.25, ease: 'easeInOut', delay: 0.25 }}
      >
        <div className="grid-col-1 grid place-items-center">
          <SVGLogoPlaceholder
            text="Acme Inc."
            textSize="3xl"
            width={200}
            height={200}
            shape="square"
            className="mb-4 rounded-lg md:mb-0"
            color="teal"
          />
          <h1 className="m-4 hidden p-4 text-5xl font-bold md:block">
            {formHeaderText}
          </h1>
          <div className="mb-6 mt-2 hidden w-full justify-center md:flex">
            <Button
              onClick={handleSaveForLater}
              className="w-44 scale-[90%]"
              type="button"
            >
              {!isSaveWorking ? (
                <SaveAll className="mr-3" />
              ) : (
                <Loader2 className="mr-3 animate-spin" />
              )}
              Save Progress
            </Button>
            <Button
              onClick={handleClearData}
              className="w-44 scale-[90%]"
              type="button"
            >
              {!isClearWorking ? (
                <X className="mr-3" />
              ) : (
                <Loader2 className="mr-3 animate-spin" />
              )}
              Clear Progress
            </Button>
          </div>
        </div>
        <h1 className="mb-4 w-full text-center text-3xl font-bold md:hidden">
          {formHeaderText}
        </h1>
        <Form {...form}>
          <form onSubmit={form.handleSubmit(onSuccessWithForm, onError)}>
            {steps}
          </form>
          <ESignature contentRef={contentRef} onConfirm={onConfirm} />
        </Form>
      </motion.div>
    </>
  )
}
