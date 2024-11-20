import React, { useState, useMemo } from 'react'
import { Form } from '@/components/ui/form'
import FormInput from './form/FormInput'
import { Wizard, WizardStep } from './form/Wizard'
import { CreditCard, FileText, Building, Users, Banknote } from 'lucide-react'
import FormSelect from './form/FormSelect'
import FormDatePicker from './form/FormDatePicker'
import { FormCountrySelect, FormRegionSelect } from './form/FormCountryRegion'
import FormSwitch from './form/FormSwitch'
import { useFormLogic } from '@/onSubmit'
import { DynamicFormSection } from './form/DynamicFormSection'

export function PayabliForm() {
  const [currentPage, setCurrentPage] = useState(0)
  const [contacts, setContacts] = useState([{}])
  const [ownership, setOwnership] = useState([{}])
  const [ownershipCountries, setOwnershipCountries] = useState<string[]>([''])

  // Add state for country fields
  const [businessCountry, setBusinessCountry] = useState('')
  const [mailingCountry, setMailingCountry] = useState('')
  const [signerCountry, setSignerCountry] = useState('')

  const addContact = () => setContacts([...contacts, {}])
  const removeContact = (index: number) => {
    if (contacts.length > 1) {
      setContacts(contacts.filter((_, i) => i !== index))
    }
  }

  const addOwner = () => {
    setOwnership([...ownership, {}])
    setOwnershipCountries([...ownershipCountries, ''])
  }
  const removeOwner = (index: number) => {
    if (ownership.length > 1) {
      setOwnership(ownership.filter((_, i) => i !== index))
      setOwnershipCountries(ownershipCountries.filter((_, i) => i !== index))
    }
  }

  const steps = useMemo(
    () => (
      <Wizard currentPage={currentPage} setCurrentPage={setCurrentPage}>
        <WizardStep icon={<Building />} label="Business Information">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 1: Business Information
          </h2>
          <div className="grid grid-cols-2 items-end gap-4">
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
            <FormDatePicker
              name="startdate"
              label="Business Start Date"
              tooltip="The date your business began operations"
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
          <div className="grid grid-cols-2 items-end gap-4">
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
              onChange={(value) => setBusinessCountry(value)}
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
              onChange={(value) => setMailingCountry(value)}
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
            <FormDatePicker
              name="ownership[].ownerdob"
              label="Owner Date of Birth"
              tooltip="Date of birth of the owner"
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
            <FormCountrySelect
              name="ownership[].ocountry"
              label="Owner Country"
              tooltip="Country of the owner's residence"
              onChange={(value: string, index: number) => {
                const newOwnershipCountries = [...ownershipCountries]
                newOwnershipCountries[index] = value
                setOwnershipCountries(newOwnershipCountries)
              }}
            />
            <FormRegionSelect
              name="ownership[].ostate"
              label="Owner State"
              tooltip="State of the owner's residence"
              countryCode={ownershipCountries[0]}
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
          <div className="grid grid-cols-2 items-end gap-4">
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
          </div>
        </WizardStep>

        <WizardStep icon={<CreditCard />} label="Payment Information">
          <h2 className="mb-4 w-full text-center text-2xl font-bold">
            Step 5: Payment Information
          </h2>
          <div className="space-y-8">
            <div className="grid grid-cols-2 items-end gap-4">
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
              <div className="grid grid-cols-2 items-end gap-4">
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
                  label="Account Holder Name"
                  tooltip="Name of the deposit account holder"
                />
                <FormSelect
                  name="depositAccount.bankAccountHolderType"
                  label="Account Holder Type"
                  options={[
                    { value: 'Business', label: 'Business' },
                    { value: 'Personal', label: 'Personal' },
                  ]}
                  tooltip="Type of deposit account holder"
                />
              </div>
            </div>

            <div className="space-y-4">
              <h3 className="text-lg font-semibold">Withdrawal Account</h3>
              <div className="grid grid-cols-2 items-end gap-4">
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
                  label="Account Holder Name"
                  tooltip="Name of the withdrawal account holder"
                />
                <FormSelect
                  name="withdrawalAccount.bankAccountHolderType"
                  label="Account Holder Type"
                  options={[
                    { value: 'Business', label: 'Business' },
                    { value: 'Personal', label: 'Personal' },
                  ]}
                  tooltip="Type of withdrawal account holder"
                />
              </div>
            </div>

            <div className="space-y-4">
              <h3 className="text-lg font-semibold">Services</h3>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <h4 className="mb-2 font-medium">Card Services</h4>
                  <FormSwitch
                    name="services.card.acceptVisa"
                    label="Accept Visa"
                    tooltip="Allow Visa card payments"
                  />
                  <FormSwitch
                    name="services.card.acceptMastercard"
                    label="Accept Mastercard"
                    tooltip="Allow Mastercard payments"
                  />
                  <FormSwitch
                    name="services.card.acceptDiscover"
                    label="Accept Discover"
                    tooltip="Allow Discover card payments"
                  />
                  <FormSwitch
                    name="services.card.acceptAmex"
                    label="Accept Amex"
                    tooltip="Allow American Express card payments"
                  />
                </div>
                <div>
                  <h4 className="mb-2 font-medium">ACH Services</h4>
                  <FormSwitch
                    name="services.ach.acceptWeb"
                    label="Accept WEB"
                    tooltip="Allow WEB (Internet-Initiated) ACH payments"
                  />
                  <FormSwitch
                    name="services.ach.acceptPPD"
                    label="Accept PPD"
                    tooltip="Allow PPD (Prearranged Payment and Deposit) ACH payments"
                  />
                  <FormSwitch
                    name="services.ach.acceptCCD"
                    label="Accept CCD"
                    tooltip="Allow CCD (Corporate Credit or Debit) ACH payments"
                  />
                </div>
              </div>
            </div>

            <div className="space-y-4">
              <h3 className="text-lg font-semibold">Signer Information</h3>
              <div className="grid grid-cols-2 items-end gap-4">
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
                <FormDatePicker
                  name="signer.dob"
                  label="Signer Date of Birth"
                  tooltip="Date of birth of the signer"
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
                <FormCountrySelect
                  name="signer.country"
                  label="Signer Country"
                  tooltip="Country of the signer's residence"
                  onChange={(value) => setSignerCountry(value)}
                />
                <FormRegionSelect
                  name="signer.state"
                  label="Signer State"
                  tooltip="State of the signer's residence"
                  countryCode={signerCountry}
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

          <div className="space-y-4">
            <h3 className="text-lg font-semibold">Misc.</h3>
            <div className="grid grid-cols-2 items-end gap-4">
              <FormSwitch
                name="recipientEmailNotification"
                label="Email Notifications"
                tooltip="Enable email notifications for the recipient"
              />
              <FormSwitch
                name="resumable"
                label="Resumable"
                tooltip="Allow resuming incomplete applications"
              />
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

  const { form, onSuccess, onError } = useFormLogic(steps, setCurrentPage)

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSuccess, onError)}>{steps}</form>
    </Form>
  )
}
