import type { ValidationMissingField } from './boardingV2'
import type { BoardingDraft } from './serverDb'

// Maps the entity paths `POST /v2/requests/{ref}/validate` reports (e.g.
// `business.phone`, `people.{personReference}.title`) to the wizard field a
// user would fix, so the form can highlight it instead of only listing
// Payabli's labels. Paths with no field in this form (e.g. a template that
// requires a contact's date of birth) stay unmapped and are listed as-is.

export type MissingFieldForForm = ValidationMissingField & {
  // Dot paths of the form fields to highlight, e.g. `ownership.1.ownertitle`.
  // Empty when the form has no field for it.
  formFields: string[]
  // Text to show at those fields.
  formMessage: string
}

const BUSINESS_FIELDS: Record<string, string> = {
  'business.legalName': 'legalName',
  'business.dbaName': 'doingBusinessAs',
  'business.doingBusinessAs': 'doingBusinessAs',
  'business.website': 'website',
  'business.establishedDate': 'startdate',
  'business.phone': 'phonenumber',
  'business.primaryPhoneNumber': 'phonenumber',
  'business.legalStructure': 'legalStructure',
  'business.emails': 'businessEmail',
  'business.primaryEmail': 'businessEmail',
  'business.incorporationState': 'incorporationState',
  'business.taxIdCountry': 'taxIdCountry',
  'business.isForeignOwned': 'isForeignOwned',
  'business.operatingSeasons': 'seasonSpring',
  'business.addressDetails': 'baddress',
  'business.merchantCatCode': 'mcc',
  'business.taxReference': 'taxReference',
  'business.description': 'bsummary',
  'business.summary': 'bsummary',
  'business.annualRevenue': 'annualRevenue',
  'business.processingMetrics.monthlyReceivablesVolume': 'avgmonthly',
  'business.processingMetrics.averageReceivableSize': 'ticketamt',
  'business.processingMetrics.largestReceivableSize': 'highticketamt',
  'business.processingMetrics.numberOfTransactions': 'numberOfTransactions',
  'business.processingMetrics.advancedDeliveryDays': 'advancedDeliveryDays',
  'business.processingMetrics.refundPolicy': 'whenRefunded',
  'business.processingMetrics.inboundPresentPercent': 'binperson',
  'business.processingMetrics.cardPresentPercentage': 'binperson',
  'business.processingMetrics.inboundOnlinePercent': 'binweb',
  'business.processingMetrics.cardInternetPercentage': 'binweb',
  'business.processingMetrics.inboundMotoPercent': 'binphone',
  'business.processingMetrics.cardNotPresentPercentage': 'binphone',
  // Banking gaps (e.g. no account for billing or refunds) are fixed by
  // choosing an account's function.
  paymentMethods: 'bankData.0.bankAccountFunction',
  'people.isPrimaryController': 'primaryControllerOwnerIndex',
  'people.isSigner': 'signerOwnerIndex',
  people: 'ownership.0.ownerFirstName',
}

const OWNER_FIELDS: Record<string, string> = {
  firstName: 'ownerFirstName',
  lastName: 'ownerLastName',
  title: 'ownertitle',
  ownershipPercentage: 'ownerpercent',
  ssn: 'ownerssn',
  dateOfBirth: 'ownerdob',
  primaryPhoneNumber: 'ownerphone1',
  phone: 'ownerphone1',
  primaryEmail: 'owneremail',
  email: 'owneremail',
  billingAddress: 'oaddress',
  addresses: 'oaddress',
  addressDetails: 'oaddress',
  driversLicenseNumber: 'ownerdriver',
}

const CONTACT_FIELDS: Record<string, string> = {
  firstName: 'contactFirstName',
  lastName: 'contactLastName',
  title: 'contactTitle',
  primaryEmail: 'contactEmail',
  email: 'contactEmail',
  primaryPhoneNumber: 'contactPhone',
  phone: 'contactPhone',
}

// The form's own labels, used in place of Payabli's when the two differ
// (e.g. Payabli calls `monthlyReceivablesVolume` "Annual Payment Volume";
// this form labels it "Average Monthly Volume").
const FORM_LABELS: Record<string, string> = {
  phonenumber: 'Phone Number',
  businessEmail: 'Business Email',
  startdate: 'Business Start Date',
  bsummary: 'Business Summary',
  mcc: 'MCC',
  avgmonthly: 'Average Monthly Volume',
  ticketamt: 'Average Ticket Amount',
  highticketamt: 'Highest Ticket Amount',
  numberOfTransactions: 'Monthly Transaction Count',
  advancedDeliveryDays: 'Average Days to Delivery',
  whenRefunded: 'When Refunded',
  ownerphone1: 'Owner Phone',
  ownertitle: 'Owner Title',
  contactTitle: 'Contact Title',
}

function formLabel(formFields: string[], fallback: string) {
  const fieldName = formFields[0]?.split('.').pop()
  return (fieldName && FORM_LABELS[fieldName]) || fallback
}

function toFormFields(
  apiField: string,
  people: BoardingDraft['people'],
): string[] {
  if (BUSINESS_FIELDS[apiField]) {
    return [BUSINESS_FIELDS[apiField]]
  }

  // `people.{field}` with no person reference (e.g. `people.primaryPhoneNumber`
  // for "Owner phone") doesn't say which owner, so point at every owner.
  const ownerWide = apiField.match(/^people\.([^.]+)$/)
  if (ownerWide && OWNER_FIELDS[ownerWide[1]]) {
    return people
      .filter((p) => p.key.startsWith('owner:'))
      .map((p) => `ownership.${p.key.split(':')[1]}.${OWNER_FIELDS[ownerWide[1]]}`)
  }

  // `people.{personReference}.{field}`: find which form row that person is.
  const match = apiField.match(/^people\.([^.]+)\.(.+)$/)
  if (!match) {
    return []
  }
  const [, personReference, field] = match
  const key = people.find((p) => p.personReference === personReference)?.key
  const [role, index] = key?.split(':') ?? []
  if (role === 'owner' && OWNER_FIELDS[field]) {
    return [`ownership.${index}.${OWNER_FIELDS[field]}`]
  }
  if (role === 'contact' && CONTACT_FIELDS[field]) {
    return [`contacts.${index}.${CONTACT_FIELDS[field]}`]
  }
  return []
}

export function mapMissingFields(
  missingFields: ValidationMissingField[],
  people: BoardingDraft['people'],
): MissingFieldForForm[] {
  return missingFields.map((missing) => {
    const formFields = toFormFields(missing.field, people)
    const label = formLabel(formFields, missing.label)
    const isMissing =
      !missing.reason || missing.reason === 'required' || missing.reason === 'missing'
    return {
      ...missing,
      formFields,
      // Payabli's `message` is the most specific (e.g. "required by the
      // application's template"); otherwise build one from the reason.
      formMessage:
        missing.message ??
        (isMissing ? `${label} is required` : `${label} ${missing.reason}`),
    }
  })
}
