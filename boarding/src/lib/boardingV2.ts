import { getApiUrlPrefix } from './getUrl'
import { getAccessToken } from './payabliAuth'

// Server-only. Thin, typed wrappers around the Boarding v2 endpoints this app
// uses, mirroring the OAS field names exactly (fern/apis/payabliApi-oas) so
// there's no silent transform bug between what we send and what the API
// expects. Every response is unwrapped from the standard
// `{ success, message, traceId, data }` envelope.

type Envelope<TData> = {
  success: boolean
  message: string | null
  traceId: string | null
  data: TData
}

async function v2Fetch<TData>(
  path: string,
  init: RequestInit = {},
): Promise<TData> {
  const token = await getAccessToken()
  const prefix = getApiUrlPrefix()

  const response = await fetch(`https://api${prefix}.payabli.com/api/v2${path}`, {
    ...init,
    headers: {
      'content-type': 'application/json',
      authorization: `Bearer ${token}`,
      ...init.headers,
    },
  })

  if (!response.ok) {
    const errorBody = await response.text()
    throw new Error(
      `Payabli v2 request failed: ${init.method ?? 'GET'} ${path} -> ${response.status} ${errorBody}`,
    )
  }

  const envelope = (await response.json()) as Envelope<TData>
  return envelope.data
}

// ---------------------------------------------------------------------------
// Businesses + paypoints
// ---------------------------------------------------------------------------

export type AddressType =
  | 'legal'
  | 'billing'
  | 'shipping'
  | 'remittance'
  | 'physical'
  | 'mailing'

export type BusinessAddress = {
  type?: AddressType
  location?: string
  addressLine1?: string
  addressLine2?: string
  cityLocality?: string
  stateProvince?: string
  postalCode?: string
  country?: string
}

export type LegalStructure =
  | 'limited-liability-company'
  | 'corporation'
  | 'partnership'
  | 'sole-proprietorship'
  | 'non-profit'
  | 'government'
  | 's-corp'

export type ProcessingMetrics = {
  monthlyReceivablesVolume?: number
  averageReceivableSize?: number
  largestReceivableSize?: number
  inboundPresentPercent?: number
  inboundOnlinePercent?: number
  inboundMotoPercent?: number
  numberOfTransactions?: number
  refundPolicy?: string
}

export type CreateBusinessRequest = {
  legalName: string
  legalStructure: LegalStructure
  taxReference: string
  establishedDate?: string
  website?: string
  description?: string
  merchantCatCode?: string
  annualRevenue?: number
  addressDetails?: BusinessAddress[]
  processingMetrics?: ProcessingMetrics
  customData?: Record<string, unknown>
}

export type CreatePaypointWithInlineBusinessRequest = {
  doingBusinessAs: string
  business: CreateBusinessRequest
}

export type PaypointCreatedResponse = {
  paypointReference: string
  businessReference: string | null
  doingBusinessAs: string
  paypointStatus: string
  createdAt: string
}

export function createPaypointWithBusiness(
  input: CreatePaypointWithInlineBusinessRequest,
) {
  return v2Fetch<PaypointCreatedResponse>('/paypoints', {
    method: 'POST',
    body: JSON.stringify(input),
  })
}

// ---------------------------------------------------------------------------
// People (created and linked to a business in one call)
// ---------------------------------------------------------------------------

export type PersonType = 'Owner' | 'Director' | 'Contact' | 'Signer' | 'AuthorizedUser'

export type PersonAddressType =
  | 'Legal'
  | 'Billing'
  | 'Shipping'
  | 'Remittance'
  | 'Mailing'
  | 'Physical'
  | 'Residential'

export type PersonAddressRequest = {
  type?: PersonAddressType
  addressLine1?: string
  addressLine2?: string
  cityLocality?: string
  stateProvince?: string
  postalCode?: string
  country?: string
}

export type IdentificationDocumentRequest = {
  type?: 'drivers_license' | 'passport' | 'state_id'
  number?: string
  issuingState?: string
  issuingCountry?: string
  expirationDate?: string
}

export type BusinessRelationshipRequest = {
  businessReference: string
  personType: PersonType
  ownershipPercentage?: number
  isPrimaryController?: boolean
}

export type CreatePersonRequest = {
  firstName: string
  lastName: string
  primaryEmail?: string
  primaryPhoneNumber?: string
  secondaryPhoneNumber?: string
  dateOfBirth?: string
  ssn?: string
  nationality?: string
  driversLicenseNumber?: string
  driversLicenseState?: string
  addresses?: PersonAddressRequest[]
  identificationDocuments?: IdentificationDocumentRequest[]
  businessRelationship: BusinessRelationshipRequest
}

export type PersonDetailResponse = {
  personReference: string
  firstName: string | null
  lastName: string | null
  verificationStatus: string
  createdAt: string
}

export function createPerson(input: CreatePersonRequest) {
  return v2Fetch<PersonDetailResponse>('/people', {
    method: 'POST',
    body: JSON.stringify(input),
  })
}

// ---------------------------------------------------------------------------
// Payment methods
// ---------------------------------------------------------------------------

export type PaymentMethodOwnerType = 'person' | 'business'

// NOTE(DOC-2274): the OAS lists this full enum, but per the boarding v2 docs
// team the live API has at times only validated a subset of these values at
// runtime (an eng ticket was tracking the gap as of 2026-09). If a value here
// gets rejected in sandbox, check DOC-2274 for the current runtime-accepted set.
export type PaymentMethodUsage =
  | 'deposits'
  | 'billing'
  | 'withdrawals'
  | 'payOutFunding'
  | 'refunds'
  | 'returns'

export type CreatePaymentMethodRequest = {
  type: 'bank_account'
  accountOwner: string
  ownerType: PaymentMethodOwnerType
  nickname?: string
  financialInstitution?: string
  accountType: 'checking' | 'savings'
  accountNumber: string
  routingNumber: string
  usage: PaymentMethodUsage[]
}

export type PaymentMethodResult = {
  paymentMethodReference: string
  type: string
  accountOwner: string
  ownerType: PaymentMethodOwnerType
  status: string
  accountNumber: string
  routingNumber: string
  usage: PaymentMethodUsage[] | null
  createdAt: string
}

export function createPaymentMethods(data: CreatePaymentMethodRequest[]) {
  return v2Fetch<PaymentMethodResult[]>('/payment-methods', {
    method: 'POST',
    body: JSON.stringify({ data }),
  })
}

// ---------------------------------------------------------------------------
// Applications (requests)
// ---------------------------------------------------------------------------

export type CreateApplicationRequest = {
  businessReference: string
  paypointReference?: string
  externalId?: string
  requestTemplate?: string
  tags?: string[]
  configurations?: Record<string, unknown> | null
}

export type Application = {
  requestsReference: string
  businessReference: string
  applicationStatus: string
  applicationStatusCode: number
  allowedActions: string[]
  createdAt: string
}

export function createApplication(input: CreateApplicationRequest) {
  return v2Fetch<Application>('/requests', {
    method: 'POST',
    body: JSON.stringify(input),
  })
}

export type TransitionResult = {
  requestsReference: string
  applicationStatus: string
  allowedActions: string[]
}

// Typed and drawn variants take `signature` inline; the out-of-band variant
// (already signed via an external e-sign vendor) sends `signedDocumentReference`
// instead and omits `signature`/`signatureType`. This app only supports the
// typed variant -- it has no signature-drawing canvas.
export type SubmitApplicationSigner = {
  personReference: string
  // The signer's printed name. Required separately from `signature` -- the
  // API rejects a request with `signature` alone ("Signer name is
  // required"), confirmed empirically against sandbox on 2026-09-22 (not
  // documented in the description Cole gave us).
  name: string
  signature: string
  signatureType: 'type' | 'draw'
  acceptance: boolean
  pciAttestation: boolean
}

// Not yet in the published OAS (fern/apis/payabliApi-oas), but confirmed live
// against sandbox on 2026-09-17: POST /v2/requests/{ref}/submit transitions a
// draft application to `submitted`. DOC-2545 tracked this as an open gap;
// resolved. Per Cole (2026-09-22): there is no separate attachment/e-sign
// endpoint -- the signature is captured directly on this call. The signer
// must already be linked to the business as a `Signer` (see createPerson in
// createApp.ts) so `signer.personReference` resolves to a real person.
export function submitApplication(
  requestsReference: string,
  signer?: SubmitApplicationSigner,
) {
  return v2Fetch<TransitionResult>(`/requests/${requestsReference}/submit`, {
    method: 'POST',
    body: signer ? JSON.stringify({ signer }) : undefined,
  })
}
