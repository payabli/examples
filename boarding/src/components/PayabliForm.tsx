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
} from 'lucide-react'
import FormSelect from './form/FormSelect'
import { motion } from 'framer-motion'
import {
  FormCountrySelect,
  FormRegionSelect,
  FormCountryRegionCombined,
} from './form/FormCountryRegion'
import FormSwitch from './form/FormSwitch'
import FormFileUpload from './form/FormFileUpload'
import FormCheckboxGroup from './form/FormCheckboxGroup'
import { useFormLogic } from '@/onSubmit'
import { DynamicFormSection } from './form/DynamicFormSection'
import { Button } from './ui/button'
import { useDrizzle } from '@/lib/clientDb'
import { useFormWithSchema } from '@/Schema'
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
        const savedData = await loadSavedData()
        if (savedData) {
          await form.reset(savedData)

          setBusinessCountry(savedData.bcountry || '')
          setMailingCountry(savedData.mcountry || '')
          setSignerCountry(savedData.signer?.country || '')

          if (savedData.contacts) {
            setContacts(savedData.contacts)
          }
          if (savedData.ownership) {
            setOwnership(savedData.ownership)
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
      form.reset({}) // Reset the form after clearing data
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
      >
        <WizardStep icon={<Building />} label="Business Information">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 1: Business Information
          </h2>
          <div className="items-end gap-4 md:grid md:grid-cols-2">
            <FormInput
              name="legalname"
              label="Legal Name"
              tooltip="The official registered name of your business"
            />
            <FormInput
              name="dbaname"
              label="DBA Name"
              tooltip="The name your business operates under, if different from the legal name"
            />
            <FormInput
              name="website"
              label="Website"
              tooltip="Your business website URL"
            />
            <FormInput
              name="ein"
              label="EIN"
              tooltip="Your Employer Identification Number (9 digits)"
            />
            <FormInput
              name="taxfillname"
              label="Tax Filing Name"
              tooltip="The name used for tax filing purposes"
            />
            <FormInput
              name="license"
              label="Business License"
              tooltip="Your business license number"
            />
            <FormRegionSelect
              name="licstate"
              label="License State"
              tooltip="The state where your business license was issued"
              countryCode="US"
            />
            <FormInput
              name="startdate"
              label="Business Start Date"
              tooltip="The date your business began operations"
              iconleft={<Calendar className="mr-2" />}
              mask="99/99/9999"
              placeholder="MM/DD/YYYY"
            />
            <FormInput
              name="phonenumber"
              label="Phone Number"
              tooltip="Your business phone number"
            />
            <FormInput
              name="faxnumber"
              label="Fax Number"
              tooltip="Your business fax number (if applicable)"
            />
            <FormSelect
              name="btype"
              label="Business Type"
              options={[
                {
                  value: 'Limited Liability Company',
                  label: 'Limited Liability Company',
                },
                { value: 'Corporation', label: 'Corporation' },
                { value: 'Partnership', label: 'Partnership' },
                { value: 'Sole Proprietorship', label: 'Sole Proprietorship' },
              ]}
              tooltip="The legal structure of your business"
            />
            <FormInput
              name="mcc"
              label="MCC"
              tooltip="Merchant Category Code (4 digits)"
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
            <FormCountrySelect
              name="bcountry"
              label="Business Country"
              tooltip="The country where your business is located"
              onChange={(value: string) => setBusinessCountry(value)}
            />
            <FormRegionSelect
              name="bstate"
              label="Business State"
              tooltip="The state where your business is located"
              countryCode={businessCountry}
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
            <FormCountrySelect
              name="mcountry"
              label="Mailing Country"
              tooltip="The country for your mailing address"
              onChange={(value: string) => setMailingCountry(value)}
            />
            <FormRegionSelect
              name="mstate"
              label="Mailing State"
              tooltip="The state for your mailing address"
              countryCode={mailingCountry}
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
              name="contacts[].contactName"
              label="Contact Name"
              tooltip="Full name of the contact person"
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
              name="ownership[].ownername"
              label="Owner Name"
              tooltip="Full name of the owner"
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
            />
            <FormInput
              name="ownership[].ownerssn"
              label="Owner SSN"
              tooltip="Social Security Number of the owner (9 digits)"
            />
            <FormInput
              name="ownership[].ownerdob"
              label="Owner Date of Birth"
              tooltip="Date of birth of the owner"
              iconleft={<Calendar className="mr-2" />}
              mask="99/99/9999"
              placeholder="MM/DD/YYYY"
            />
            <FormInput
              name="ownership[].ownerphone1"
              label="Owner Phone 1"
              tooltip="Primary phone number of the owner"
            />
            <FormInput
              name="ownership[].ownerphone2"
              label="Owner Phone 2"
              tooltip="Secondary phone number of the owner (if applicable)"
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
              name="bsummary"
              label="Business Summary"
              tooltip="Brief description of your business activities"
            />
            <FormSelect
              name="whenCharged"
              label="When Charged"
              options={[
                {
                  value: 'When Service Provided',
                  label: 'When Service Provided',
                },
                {
                  value: 'Before Service Provided',
                  label: 'Before Service Provided',
                },
                {
                  value: 'After Service Provided',
                  label: 'After Service Provided',
                },
              ]}
              tooltip="When customers are typically charged for your services"
            />
            <FormSelect
              name="whenProvided"
              label="When Provided"
              options={[
                { value: '30 Days or Less', label: '30 Days or Less' },
                { value: 'Over 30 Days', label: 'Over 30 Days' },
              ]}
              tooltip="Typical timeframe for providing services after charging"
            />
            <FormSelect
              name="whenDelivered"
              label="When Delivered"
              options={[
                { value: '30 Days or Less', label: '30 Days or Less' },
                { value: 'Over 30 Days', label: 'Over 30 Days' },
              ]}
              tooltip="Typical timeframe for delivering products or services"
            />
            <FormSelect
              name="whenRefunded"
              label="When Refunded"
              options={[
                { value: '30 Days or Less', label: '30 Days or Less' },
                { value: 'Over 30 Days', label: 'Over 30 Days' },
              ]}
              tooltip="Typical timeframe for processing refunds"
            />
            <FormInput
              name="binperson"
              label="In-Person Sales (%)"
              tooltip="Percentage of sales conducted in person"
              type="number"
            />
            <FormInput
              name="binphone"
              label="Phone Sales (%)"
              tooltip="Percentage of sales conducted over the phone"
            />
            <FormInput
              name="binweb"
              label="Web Sales (%)"
              tooltip="Percentage of sales conducted online"
            />
            <FormInput
              type="number"
              name="annualRevenue"
              label="Annual Revenue"
              tooltip="Estimated annual revenue of your business"
            />
            <FormInput
              name="avgmonthly"
              label="Average Monthly Volume"
              tooltip="Average monthly sales volume"
            />
            <FormInput
              name="ticketamt"
              label="Average Ticket Amount"
              tooltip="Average amount per transaction"
            />
            <FormInput
              name="highticketamt"
              label="Highest Ticket Amount"
              tooltip="Highest expected transaction amount"
            />
            <FormInput
              name="averageBillSize"
              label="Average Bill Size"
              tooltip="Average amount of each bill you pay through our service"
            />
            <FormInput
              name="averageMonthlyBill"
              label="Average Monthly Bill"
              tooltip="Average monthly bill amount"
            />
            <FormInput
              name="creditLimit"
              label="Credit Limit"
              tooltip="Maximum amount our lending partner has authorized to your business"
            />
            <FormSelect
              name="processingRegion"
              label="Processing Region"
              tooltip="Region where your business processes transactions"
              options={[
                { label: 'US', value: 'US' },
                { label: 'CA', value: 'CA' },
              ]}
            />
          </div>
        </WizardStep>

        <WizardStep icon={<CreditCard />} label="Payment Information">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 5: Payment Information
          </h2>
          <div className="space-y-8">
            <div className="items-end gap-4 md:grid md:grid-cols-2">
              <FormInput
                name="payoutAverageMonthlyVolume"
                label="Payout Average Monthly Volume"
                tooltip="Expected average monthly payout volume"
              />
              <FormInput
                name="payoutHighTicketAmount"
                label="Payout High Ticket Amount"
                tooltip="Highest expected payout amount"
              />
              <FormInput
                name="payoutAveragTicketAmount"
                label="Payout Average Ticket Amount"
                tooltip="Average expected payout amount"
              />
              <FormInput
                name="payoutCreditLimit"
                label="Payout Credit Limit"
                tooltip="Maximum credit limit for payouts"
              />
              <FormInput
                name="recipientEmail"
                label="Recipient Email"
                tooltip="Email address for receiving notifications"
              />
            </div>

            <div className="space-y-4">
              <h3 className="text-lg font-semibold">Deposit Account</h3>
              <div className="items-end gap-4 md:grid md:grid-cols-2">
                <FormInput
                  name="depositAccount.bankName"
                  label="Bank Name"
                  tooltip="Name of the bank for deposits"
                />
                <FormInput
                  name="depositAccount.routingAccount"
                  label="Routing Number"
                  tooltip="9-digit routing number for the deposit account"
                />
                <FormInput
                  name="depositAccount.accountNumber"
                  label="Account Number"
                  tooltip="Account number for deposits"
                />
                <FormSelect
                  name="depositAccount.typeAccount"
                  label="Account Type"
                  options={[
                    { value: 'Checking', label: 'Checking' },
                    { value: 'Savings', label: 'Savings' },
                  ]}
                  tooltip="Type of deposit account"
                />
                <FormInput
                  name="depositAccount.bankAccountHolderName"
                  label="Accountholder Name"
                  tooltip="Name of the deposit accountholder"
                />
                <FormSelect
                  name="depositAccount.bankAccountHolderType"
                  label="Accountholder Type"
                  options={[
                    { value: 'Business', label: 'Business' },
                    { value: 'Personal', label: 'Personal' },
                  ]}
                  tooltip="Type of deposit accountholder"
                />
              </div>
            </div>

            <FormFileUpload
              name="voidCheck"
              label="Voided Check"
              tooltip="Upload a voided check as proof of account"
            />

            <div className="space-y-4">
              <h3 className="text-lg font-semibold">Withdrawal Account</h3>
              <div className="items-end gap-4 md:grid md:grid-cols-2">
                <FormInput
                  name="withdrawalAccount.bankName"
                  label="Bank Name"
                  tooltip="Name of the bank for withdrawals"
                />
                <FormInput
                  name="withdrawalAccount.routingAccount"
                  label="Routing Number"
                  tooltip="9-digit routing number for the withdrawal account"
                />
                <FormInput
                  name="withdrawalAccount.accountNumber"
                  label="Account Number"
                  tooltip="Account number for withdrawals"
                />
                <FormSelect
                  name="withdrawalAccount.typeAccount"
                  label="Account Type"
                  options={[
                    { value: 'Checking', label: 'Checking' },
                    { value: 'Savings', label: 'Savings' },
                  ]}
                  tooltip="Type of withdrawal account"
                />
                <FormInput
                  name="withdrawalAccount.bankAccountHolderName"
                  label="Accountholder Name"
                  tooltip="Name of the withdrawal accountholder"
                />
                <FormSelect
                  name="withdrawalAccount.bankAccountHolderType"
                  label="Accountholder Type"
                  options={[
                    { value: 'Business', label: 'Business' },
                    { value: 'Personal', label: 'Personal' },
                  ]}
                  tooltip="Type of withdrawal accountholder"
                />
              </div>
            </div>

            <FormFileUpload
              name="voidCheck"
              label="Voided Check"
              tooltip="Upload a voided check as proof of account"
            />

            <div className="space-y-4">
              <h3 className="text-lg font-semibold">Services</h3>
              <div className="md:grid md:grid-cols-2 md:px-2">
                <FormCheckboxGroup
                  label="Card Services"
                  tooltip="Select the card services you accept"
                  options={[
                    { name: 'services.card.acceptVisa', label: 'Visa' },
                    {
                      name: 'services.card.acceptMastercard',
                      label: 'Mastercard',
                    },
                    {
                      name: 'services.card.acceptDiscover',
                      label: 'Discover',
                    },
                    { name: 'services.card.acceptAmex', label: 'Amex' },
                  ]}
                />
                <FormCheckboxGroup
                  label="ACH Services"
                  tooltip="Select the ACH services you accept"
                  options={[
                    { name: 'services.ach.acceptWeb', label: 'WEB' },
                    { name: 'services.ach.acceptPPD', label: 'PPD' },
                    { name: 'services.ach.acceptCCD', label: 'CCD' },
                  ]}
                />
              </div>
            </div>

            <div className="space-y-4">
              <h3 className="text-lg font-semibold">Signer Information</h3>
              <div className="items-end gap-4 md:grid md:grid-cols-2">
                <FormInput
                  name="signer.name"
                  label="Signer Name"
                  tooltip="Full name of the person signing the application"
                />
                <FormInput
                  name="signer.ssn"
                  label="Signer SSN"
                  tooltip="Social Security Number of the signer (9 digits)"
                />
                <FormInput
                  name="signer.dob"
                  label="Signer Date of Birth"
                  tooltip="Date of birth of the signer"
                  iconleft={<Calendar className="mr-2" />}
                  mask="99/99/9999"
                  placeholder="MM/DD/YYYY"
                />
                <FormInput
                  name="signer.phone"
                  label="Signer Phone"
                  tooltip="Phone number of the signer"
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

  const onSuccessWithForm = async (values: FormSchemaType) => {
    try {
      setAppId(await onSuccess(values))
    } catch (error) {
      return
    }
    handleESignatureProcess(appId)
  }

  const onConfirm = () => {
    handleConfirm(appId)
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
