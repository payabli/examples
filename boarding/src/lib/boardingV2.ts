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
      // Multipart bodies (document uploads) need fetch to set the
      // content-type itself so it includes the boundary.
      ...(init.body instanceof FormData
        ? {}
        : { 'content-type': 'application/json' }),
      authorization: `Bearer ${token}`,
      ...init.headers,
    },
  })

  if (!response.ok) {
    throw new PayabliV2Error(
      init.method ?? 'GET',
      path,
      response.status,
      await response.text(),
    )
  }

  // Some deactivate/unlink endpoints answer 204 with no body.
  if (response.status === 204) {
    return undefined as TData
  }

  const envelope = (await response.json()) as Envelope<TData>
  return envelope.data
}

// v2 error bodies look like `{ success, error, message, traceId }`, e.g.
// `{ "error": "VALIDATION_ERROR", "message": "A payment method with the same
// account and routing number already exists ...", "traceId": "..." }`. The
// parsed fields are kept so callers can surface the API's own message.
export class PayabliV2Error extends Error {
  readonly apiMessage: string | null
  readonly apiError: string | null
  readonly traceId: string | null

  constructor(
    readonly method: string,
    readonly path: string,
    readonly status: number,
    readonly body: string,
  ) {
    super(`Payabli v2 request failed: ${method} ${path} -> ${status} ${body}`)
    this.name = 'PayabliV2Error'

    let parsed: { error?: unknown; message?: unknown; traceId?: unknown } = {}
    try {
      parsed = JSON.parse(body)
    } catch {
      // Non-JSON body (e.g. a gateway error page); keep the raw text only.
    }
    this.apiError = typeof parsed.error === 'string' ? parsed.error : null
    this.apiMessage = typeof parsed.message === 'string' ? parsed.message : null
    this.traceId = typeof parsed.traceId === 'string' ? parsed.traceId : null
  }
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
  // Monthly payment count.
  numberOfTransactions?: number
  // Average days between payment and delivery; 0 for delivery at purchase.
  advancedDeliveryDays?: number
  refundPolicy?: string
}

export type OperatingSeason = 'Spring' | 'Summer' | 'Fall' | 'Winter'

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
  phone?: string[]
  emails?: string[]
  // `incorporationState` and `taxIdCountry` are required by
  // `POST /v2/requests/{ref}/validate` but missing from the published OAS;
  // they're in the live API spec (/swagger/v2/swagger.json).
  incorporationState?: string
  taxIdCountry?: string
  isForeignOwned?: boolean
  operatingSeasons?: OperatingSeason[]
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

// Soft delete: the business moves to `Deactivated` and the record is kept.
// There's no paypoint delete endpoint, so deactivating the business is how a
// paypoint created by a failed submission gets retired.
export function deactivateBusiness(businessReference: string, reason: string) {
  return v2Fetch<unknown>(`/businesses/${businessReference}`, {
    method: 'DELETE',
    body: JSON.stringify({ reason }),
  })
}

// ---------------------------------------------------------------------------
// People (created and linked to a business in one call)
// ---------------------------------------------------------------------------

// The OAS still lists Director, Contact, Signer, and AuthorizedUser, but per
// the boarding team (Cole, 2026-10-07) every type except Owner and Employee is
// being retired. Employees serve as the business's contacts, and the signer
// and primary controller are flags on an owner's link instead of types.
export type PersonType = 'Owner' | 'Employee'

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
  // Exactly one owner must be the primary controller for the application to
  // be submittable.
  isPrimaryController?: boolean
  // Marks the owner who signs the application. Not in the OAS yet; confirmed
  // by the boarding team. Only an owner can sign (422 SIGNER_NOT_OWNER).
  isSigner?: boolean
  // The person's job title at this business (1-100 characters). Lives on
  // the business link, not the person record.
  title?: string
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

// Removes the person <-> business link. v2 has no endpoint to delete the
// person record itself, so the person survives, just unattached.
export function unlinkPersonFromBusiness(
  businessReference: string,
  personReference: string,
) {
  return v2Fetch<void>(
    `/businesses/${businessReference}/people/${personReference}`,
    { method: 'DELETE' },
  )
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

// The batch is all-or-nothing: if any entry is rejected (e.g. a 409 for a
// duplicate account/routing pair on the same owner), none are created.
export function createPaymentMethods(data: CreatePaymentMethodRequest[]) {
  return v2Fetch<PaymentMethodResult[]>('/payment-methods', {
    method: 'POST',
    body: JSON.stringify({ data }),
  })
}

// Soft delete: the payment method moves to `INACTIVE` and the record is kept.
export function deactivatePaymentMethod(paymentMethodReference: string) {
  return v2Fetch<unknown>(`/payment-methods/${paymentMethodReference}`, {
    method: 'DELETE',
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
  services?: RequestedServices
}

export type MoneyInService =
  | 'Ach'
  | 'Card'
  | 'Cloud'
  | 'Device'
  | 'Wallet'
  | 'Cash'
  | 'Check'

export type MoneyOutService =
  | 'Ach'
  | 'VCard'
  | 'Managed'
  | 'Check'
  | 'Rtp'
  | 'Wire'
  | 'Ghost'

// The services an application declares drive what `validate` and the
// document requirements check. An application with no services can't be
// validated (`SERVICES_REQUIRED`).
export type RequestedServices = {
  moneyIn?: MoneyInService[]
  moneyOut?: MoneyOutService[]
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

// Moves a draft application to `withdrawn`. Used to roll back an application
// that failed validation, since applications can't be deleted.
export function withdrawApplication(requestsReference: string, reason: string) {
  return v2Fetch<TransitionResult>(`/requests/${requestsReference}/withdraw`, {
    method: 'POST',
    body: JSON.stringify({ reason }),
  })
}

export type ValidationMissingField = {
  // An entity path, e.g. `business.phone` or `people.{personReference}.title`.
  // Missing required documents appear here too, under their `fieldPath`.
  field: string
  label: string
  reason: string
}

export type ValidationSection = {
  complete: boolean
  missingFields: ValidationMissingField[]
}

export type ValidationResult = {
  valid: boolean
  servicesEvaluated?: string[]
  sections?: Record<string, ValidationSection>
  // Set when the check itself couldn't run (e.g. VALIDATION_UNAVAILABLE);
  // `valid` is false with no missing fields in that case.
  notice?: { code?: string; message?: string } | null
}

// Checks the application's linked business, people, and payment methods
// against the requirements of its declared services, including required
// documents and format rules. Changes nothing. Partner submit doesn't enforce
// it (as of 2026-10-08), so the app runs it before signing: per the API, a
// passing result means submission won't reject the data.
export function validateApplication(requestsReference: string) {
  return v2Fetch<ValidationResult>(`/requests/${requestsReference}/validate`, {
    method: 'POST',
  })
}

// ---------------------------------------------------------------------------
// Application documents
// ---------------------------------------------------------------------------

export type DocumentSubject = 'person' | 'paypoint' | 'business'

export type DocumentRequirement = {
  // Upload against this, e.g. `documents.bankStatement`.
  fieldPath: string
  label: string
  hint?: string | null
  // `template` or `businessRule` (bank statements at $1M+ annual revenue, a
  // passport per foreign owner).
  source: string
  subjectTypes: DocumentSubject[]
  // Set for person-scoped requirements, listed once per owner.
  personReference?: string | null
  required: boolean
  maxUploads: number
  // Empty means any format.
  allowedFormats: string[]
  uploadedCount: number
}

export function getDocumentRequirements(requestsReference: string) {
  return v2Fetch<DocumentRequirement[]>(
    `/requests/${requestsReference}/documents/requirements`,
  )
}

export type UploadDocumentInput = {
  file: File
  fieldPath: string
  subject: DocumentSubject
  personReference?: string
}

// Multipart upload of one file (10 MB max) against one requirement. Not in
// the published OAS yet; shape from the live API spec.
export function uploadDocument(
  requestsReference: string,
  input: UploadDocumentInput,
) {
  const body = new FormData()
  body.append('File', input.file, input.file.name)
  body.append('FieldPath', input.fieldPath)
  body.append('Subject', input.subject)
  if (input.personReference) {
    body.append('PersonReference', input.personReference)
  }
  return v2Fetch<unknown>(`/requests/${requestsReference}/documents`, {
    method: 'POST',
    body,
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
