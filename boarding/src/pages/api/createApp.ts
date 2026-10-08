import type { APIRoute } from 'astro'
import { createHash } from 'node:crypto'
import { z } from 'zod'
import { auth } from '../../../auth'
import { parseServerFormData, type FormSchemaType } from '../../Schema'
import {
  clearBoardingDraft,
  loadBoardingDraft,
  saveBoardingDraft,
  type BoardingDraft,
} from '../../lib/serverDb'
import {
  createApplication,
  createBusinessAddress,
  createPaymentMethods,
  createPaypointWithBusiness,
  createPerson,
  deactivateBusiness,
  deactivatePaymentMethod,
  getApplication,
  getBusinessUnmasked,
  getDocumentRequirements,
  getPersonUnmasked,
  listBusinessAddresses,
  listBusinessPeople,
  unlinkPersonFromBusiness,
  updateApplication,
  updateBusiness,
  updateBusinessAddress,
  updateBusinessPerson,
  updatePaymentMethod,
  updatePaypoint,
  updatePerson,
  validateApplication,
  withdrawApplication,
  PayabliV2Error,
  type BusinessAddress,
  type CreateBusinessRequest,
  type CreatePaymentMethodRequest,
  type CreatePersonRequest,
  type DocumentRequirement,
  type OperatingSeason,
  type PaymentMethodUsage,
  type RequestedServices,
  type ValidationMissingField,
} from '../../lib/boardingV2'

// The wizard collects MM/DD/YYYY; the v2 API expects YYYY-MM-DD.
function toIsoDate(mmddyyyy: string): string {
  const [month, day, year] = mmddyyyy.split('/')
  return `${year}-${month}-${day}`
}

// "Withdrawal" in the form is the account Payabli draws from (fees, refunds).
// v2 validation requires that account to carry `billing` or `refunds`; the
// `withdrawals` usage alone doesn't satisfy the org's funding rules.
const BANK_ACCOUNT_FUNCTION_TO_USAGE: Record<
  FormSchemaType['bankData'][number]['bankAccountFunction'],
  PaymentMethodUsage[]
> = {
  Deposit: ['deposits'],
  Withdrawal: ['withdrawals', 'billing', 'refunds'],
  Both: ['deposits', 'withdrawals', 'billing', 'refunds'],
  Remittance: ['payOutFunding'],
}

// Every application requests the same services. They decide what `validate`
// and the document requirements check, and an application with none can't be
// validated at all.
const SERVICES: RequestedServices = { moneyIn: ['Card', 'Ach'] }

type Step =
  | 'paypoint'
  | 'people'
  | 'paymentMethods'
  | 'application'
  | 'validation'

// `validate` found data the form didn't supply. Carries the fields so the
// user can see what to fix.
class IncompleteApplicationError extends Error {
  constructor(readonly missingFields: ValidationMissingField[]) {
    super('Application is missing required information')
  }
}

// `validate` couldn't run (e.g. VALIDATION_UNAVAILABLE).
class ValidationUnavailableError extends Error {}

class StepError extends Error {
  constructor(
    readonly step: Step,
    readonly cause: unknown,
  ) {
    super(`Boarding step "${step}" failed`)
  }
}

function jsonResponse(body: unknown, status: number) {
  return new Response(JSON.stringify(body), {
    status,
    headers: { 'Content-Type': 'application/json' },
  })
}

async function runStep<T>(step: Step, fn: () => Promise<T>): Promise<T> {
  try {
    return await fn()
  } catch (error) {
    throw new StepError(step, error)
  }
}

// Best-effort cleanup that shouldn't fail the submission.
async function attempt(label: string, fn: () => Promise<unknown>) {
  try {
    await fn()
  } catch (error) {
    console.error(`Cleanup failed for ${label}:`, error)
  }
}

// ---------------------------------------------------------------------------
// Payload builders (form -> v2 request shapes)
// ---------------------------------------------------------------------------

function operatingSeasons(formData: FormSchemaType): OperatingSeason[] {
  const seasons: [OperatingSeason, boolean][] = [
    ['Spring', formData.seasonSpring],
    ['Summer', formData.seasonSummer],
    ['Fall', formData.seasonFall],
    ['Winter', formData.seasonWinter],
  ]
  return seasons.filter(([, selected]) => selected).map(([season]) => season)
}

function buildAddresses(formData: FormSchemaType): BusinessAddress[] {
  return [
    {
      type: 'legal',
      addressLine1: formData.baddress,
      addressLine2: formData.baddress1,
      cityLocality: formData.bcity,
      stateProvince: formData.bstate,
      postalCode: formData.bzip,
      country: formData.bcountry,
    },
    {
      type: 'mailing',
      addressLine1: formData.maddress,
      addressLine2: formData.maddress1,
      cityLocality: formData.mcity,
      stateProvince: formData.mstate,
      postalCode: formData.mzip,
      country: formData.mcountry,
    },
  ]
}

function buildBusiness(formData: FormSchemaType): CreateBusinessRequest {
  return {
    legalName: formData.legalName,
    legalStructure: formData.legalStructure,
    taxReference: formData.taxReference,
    establishedDate: toIsoDate(formData.startdate),
    website: formData.website,
    merchantCatCode: formData.mcc,
    annualRevenue: formData.annualRevenue,
    description: formData.bsummary,
    phone: [formData.phonenumber],
    emails: [formData.businessEmail],
    incorporationState: formData.incorporationState.toUpperCase(),
    taxIdCountry: formData.taxIdCountry.toUpperCase(),
    isForeignOwned: formData.isForeignOwned,
    operatingSeasons: operatingSeasons(formData),
    addressDetails: buildAddresses(formData),
    processingMetrics: {
      monthlyReceivablesVolume: formData.avgmonthly,
      averageReceivableSize: formData.ticketamt,
      largestReceivableSize: formData.highticketamt,
      inboundPresentPercent: formData.binperson,
      inboundOnlinePercent: formData.binweb,
      inboundMotoPercent: formData.binphone,
      refundPolicy: formData.whenRefunded,
      advancedDeliveryDays: formData.advancedDeliveryDays,
      numberOfTransactions: formData.numberOfTransactions,
    },
  }
}

type PersonToSync = {
  // Role and position in the form, e.g. `contact:0` or `owner:1`; how a
  // person is matched to the record a previous attempt created.
  key: string
  isOwner: boolean
  request: CreatePersonRequest
}

// People: contacts and owners, each created and linked to the business in the
// same call via `businessRelationship`. Contacts are `Employee` people (v2 is
// retiring the `Contact` type). There's no separate signer person: the
// signer and primary controller are flags on the owners the wizard picked
// (`signerOwnerIndex`, `primaryControllerOwnerIndex`).
function buildPeople(
  formData: FormSchemaType,
  businessReference: string,
): PersonToSync[] {
  const contacts = formData.contacts.map(
    (contact, index): PersonToSync => ({
      key: `contact:${index}`,
      isOwner: false,
      request: {
        firstName: contact.contactFirstName,
        lastName: contact.contactLastName,
        primaryEmail: contact.contactEmail,
        primaryPhoneNumber: contact.contactPhone,
        businessRelationship: {
          businessReference,
          personType: 'Employee',
          title: contact.contactTitle,
        },
      },
    }),
  )

  const owners = formData.ownership.map(
    (owner, index): PersonToSync => ({
      key: `owner:${index}`,
      isOwner: true,
      request: {
        firstName: owner.ownerFirstName,
        lastName: owner.ownerLastName,
        primaryEmail: owner.owneremail,
        primaryPhoneNumber: owner.ownerphone1,
        secondaryPhoneNumber: owner.ownerphone2 || undefined,
        dateOfBirth: toIsoDate(owner.ownerdob),
        ssn: owner.ownerssn,
        nationality: 'US',
        addresses: [
          {
            type: 'Residential',
            addressLine1: owner.oaddress,
            cityLocality: owner.ocity,
            stateProvince: owner.ostate,
            postalCode: owner.ozip,
            country: owner.ocountry,
          },
        ],
        identificationDocuments: [
          {
            type: 'drivers_license',
            number: owner.ownerdriver,
            issuingState: owner.odriverstate,
          },
        ],
        businessRelationship: {
          businessReference,
          personType: 'Owner',
          title: owner.ownertitle,
          ownershipPercentage: owner.ownerpercent,
          isSigner: index === formData.signerOwnerIndex,
          isPrimaryController: index === formData.primaryControllerOwnerIndex,
        },
      },
    }),
  )

  return [...contacts, ...owners]
}

// Payment methods -- all bank accounts owned by the business. Duplicate
// account/routing pairs were already rejected by the form schema.
function buildPaymentMethods(
  formData: FormSchemaType,
  businessReference: string,
): CreatePaymentMethodRequest[] {
  return formData.bankData.map((bank) => ({
    type: 'bank_account',
    accountOwner: businessReference,
    ownerType: 'business',
    nickname: bank.nickname,
    financialInstitution: bank.bankName,
    accountType: bank.typeAccount === 'Checking' ? 'checking' : 'savings',
    accountNumber: bank.accountNumber,
    routingNumber: bank.routingAccount,
    usage: BANK_ACCOUNT_FUNCTION_TO_USAGE[bank.bankAccountFunction],
  }))
}

// Matches a bank account to the payment method a previous attempt created
// without storing the account number itself.
function paymentMethodKey(method: CreatePaymentMethodRequest) {
  return createHash('sha256')
    .update(`${method.routingNumber}:${method.accountNumber}`)
    .digest('hex')
}

// ---------------------------------------------------------------------------
// Resuming a previous attempt
// ---------------------------------------------------------------------------

// Boarding v2 rejects a second business with the same EIN, and a second bank
// account with the same routing and account numbers, even when the first was
// deactivated. So a submission that fails partway can't be rolled back and
// retried; instead each step below updates the records a previous attempt
// created (saved in the user's draft) and only creates what's missing.

// Returns the user's saved draft if its business can still be reused.
async function loadUsableDraft(
  userId: string,
  formData: FormSchemaType,
): Promise<BoardingDraft | null> {
  const draft = await loadBoardingDraft(userId)
  if (!draft) {
    return null
  }

  let business
  try {
    business = await getBusinessUnmasked(draft.businessReference)
  } catch (error) {
    console.error('Saved draft business is unreadable; starting over:', error)
    await clearBoardingDraft(userId)
    return null
  }
  if (business.businessStatus === 'Deactivated') {
    await clearBoardingDraft(userId)
    return null
  }

  // The EIN can't change after creation, so a new EIN needs a new business.
  // Retire the old one; its EIN no longer matches what the user is boarding.
  if (business.taxReference !== formData.taxReference) {
    const { applicationReference } = draft
    if (applicationReference) {
      await attempt(applicationReference, () =>
        withdrawApplication(applicationReference, 'Replaced: the EIN changed'),
      )
    }
    await attempt(draft.businessReference, () =>
      deactivateBusiness(draft.businessReference, 'Replaced: the EIN changed'),
    )
    await clearBoardingDraft(userId)
    return null
  }

  // Only a draft application can be updated. If the saved one moved on (for
  // example it was withdrawn), keep the business but start a new application.
  if (draft.applicationReference) {
    try {
      const application = await getApplication(draft.applicationReference)
      if (application.applicationStatus !== 'draft') {
        draft.applicationReference = undefined
      }
    } catch {
      draft.applicationReference = undefined
    }
  }

  return draft
}

async function upsertBusiness(
  draft: BoardingDraft | null,
  formData: FormSchemaType,
): Promise<BoardingDraft> {
  const business = buildBusiness(formData)

  if (!draft) {
    const paypoint = await createPaypointWithBusiness({
      doingBusinessAs: formData.doingBusinessAs,
      business,
    })
    if (!paypoint.businessReference) {
      throw new Error('Paypoint creation did not return a business reference')
    }
    return {
      businessReference: paypoint.businessReference,
      paypointReference: paypoint.paypointReference,
      people: [],
      paymentMethods: [],
    }
  }

  // `taxReference` is immutable (and unchanged, per loadUsableDraft), and
  // addresses have their own endpoints.
  const { taxReference, addressDetails, ...updatable } = business
  await updateBusiness(draft.businessReference, updatable)
  await updatePaypoint(draft.paypointReference, {
    doingBusinessAs: formData.doingBusinessAs,
    website: formData.website,
  })

  const existing = await listBusinessAddresses(draft.businessReference)
  for (const address of addressDetails ?? []) {
    const match = existing.find((stored) => stored.type === address.type)
    if (match) {
      await updateBusinessAddress(
        draft.businessReference,
        match.businessAddressReference,
        address,
      )
    } else {
      await createBusinessAddress(draft.businessReference, address)
    }
  }
  return draft
}

// Whether a saved owner can be updated in place: SSN and date of birth can't
// be changed after creation, so a change to either needs a new person.
async function ownerIdentityUnchanged(
  personReference: string,
  request: CreatePersonRequest,
) {
  const stored = await getPersonUnmasked(personReference)
  return (
    stored.ssn === request.ssn &&
    (stored.dateOfBirth ?? '').slice(0, 10) === request.dateOfBirth
  )
}

// Creates, updates, and unlinks people so the business matches the form.
// Returns the signer's personReference and records each person's name.
async function syncPeople(
  draft: BoardingDraft,
  formData: FormSchemaType,
  personNames: Map<string, string>,
): Promise<string> {
  const businessReference = draft.businessReference
  const wanted = buildPeople(formData, businessReference)
  const saved = new Map(draft.people.map((p) => [p.key, p.personReference]))

  const reuse: (PersonToSync & { personReference: string })[] = []
  const create: PersonToSync[] = []
  for (const person of wanted) {
    const personReference = saved.get(person.key)
    if (
      personReference &&
      (!person.isOwner ||
        (await ownerIdentityUnchanged(personReference, person.request)))
    ) {
      reuse.push({ ...person, personReference })
    } else {
      create.push(person)
    }
  }

  // Unlink anyone the form no longer has (removed, or replaced because their
  // identity changed). The person record itself can't be deleted.
  const keep = new Set(reuse.map((person) => person.personReference))
  for (const stale of draft.people.filter((p) => !keep.has(p.personReference))) {
    await attempt(stale.personReference, () =>
      unlinkPersonFromBusiness(businessReference, stale.personReference),
    )
  }
  draft.people = draft.people.filter((p) => keep.has(p.personReference))

  // Links are updated in two passes because v2 checks them one at a time:
  // combined ownership can't pass 100%, and only one owner can be signer or
  // primary controller. First lower ownership and clear the flags, then set
  // the final values.
  const links = new Map(
    (await listBusinessPeople(businessReference)).map((link) => [
      link.personReference,
      link,
    ]),
  )
  for (const person of reuse.filter((p) => p.isOwner)) {
    const current = links.get(person.personReference)
    const target = person.request.businessRelationship
    await updateBusinessPerson(businessReference, person.personReference, {
      isSigner: false,
      isPrimaryController: false,
      ownershipPercentage: Math.min(
        current?.ownershipPercentage ?? 0,
        target.ownershipPercentage ?? 0,
      ),
    })
  }
  for (const person of reuse) {
    const { ssn, dateOfBirth, businessRelationship, ...details } =
      person.request
    const { businessReference: _, ...link } = businessRelationship
    await updatePerson(person.personReference, details)
    await updateBusinessPerson(businessReference, person.personReference, link)
  }

  // allSettled rather than all: if one person is rejected, the others may
  // still have been created, and the draft needs their references.
  const results = await Promise.allSettled(
    create.map((person) => createPerson(person.request)),
  )
  results.forEach((result, index) => {
    if (result.status === 'fulfilled') {
      draft.people.push({
        key: create[index].key,
        personReference: result.value.personReference,
      })
    }
  })

  for (const person of wanted) {
    const reference = draft.people.find((p) => p.key === person.key)
    if (reference) {
      personNames.set(
        reference.personReference,
        `${person.request.firstName} ${person.request.lastName}`,
      )
    }
  }

  const rejected = results.find((result) => result.status === 'rejected')
  if (rejected) {
    throw rejected.reason
  }

  const signer = draft.people.find(
    (p) => p.key === `owner:${formData.signerOwnerIndex}`,
  )
  if (!signer) {
    throw new Error('The signer was not created')
  }
  return signer.personReference
}

// Creates, updates, and deactivates bank accounts so the business matches
// the form.
async function syncPaymentMethods(
  draft: BoardingDraft,
  formData: FormSchemaType,
) {
  const wanted = buildPaymentMethods(formData, draft.businessReference).map(
    (method) => ({ key: paymentMethodKey(method), method }),
  )
  const wantedKeys = new Set(wanted.map((entry) => entry.key))

  for (const stale of draft.paymentMethods.filter(
    (p) => !wantedKeys.has(p.key),
  )) {
    await attempt(stale.paymentMethodReference, () =>
      deactivatePaymentMethod(stale.paymentMethodReference),
    )
  }
  draft.paymentMethods = draft.paymentMethods.filter((p) =>
    wantedKeys.has(p.key),
  )

  const saved = new Map(
    draft.paymentMethods.map((p) => [p.key, p.paymentMethodReference]),
  )
  const create = wanted.filter((entry) => !saved.has(entry.key))
  for (const entry of wanted.filter((e) => saved.has(e.key))) {
    const { nickname, financialInstitution, accountType, usage } = entry.method
    await updatePaymentMethod(saved.get(entry.key)!, {
      nickname,
      financialInstitution,
      accountType,
      usage,
    })
  }

  // The batch is all-or-nothing, and results come back in request order.
  if (create.length > 0) {
    const results = await createPaymentMethods(
      create.map((entry) => entry.method),
    )
    results.forEach((result, index) => {
      draft.paymentMethods.push({
        key: create[index].key,
        paymentMethodReference: result.paymentMethodReference,
      })
    })
  }
}

async function upsertApplication(
  draft: BoardingDraft,
  formData: FormSchemaType,
) {
  const settings = {
    requestTemplate:
      import.meta.env.PAYABLI_BOARDING_TEMPLATE_REFERENCE || undefined,
    services: SERVICES,
    configurations: {
      recipientEmail: formData.recipientEmail,
      recipientEmailNotification: formData.recipientEmailNotification,
    },
  }

  if (draft.applicationReference) {
    await updateApplication(draft.applicationReference, settings)
    return draft.applicationReference
  }

  const application = await createApplication({
    businessReference: draft.businessReference,
    paypointReference: draft.paypointReference,
    ...settings,
  })
  draft.applicationReference = application.requestsReference
  return application.requestsReference
}

// ---------------------------------------------------------------------------
// Validation
// ---------------------------------------------------------------------------

// A missing required document shows up in `validate` under its `fieldPath`
// (business-level) or `people.{personReference}.{fieldPath}` (person-level).
function isDocumentField(
  field: string,
  requirements: DocumentRequirement[],
): boolean {
  return requirements.some(
    (requirement) =>
      field === requirement.fieldPath ||
      field === `people.${requirement.personReference}.${requirement.fieldPath}`,
  )
}

// Throws when the application is missing anything other than documents;
// otherwise returns the required documents still to upload.
function checkValidation(
  validation: Awaited<ReturnType<typeof validateApplication>>,
  requirements: DocumentRequirement[],
): DocumentRequirement[] {
  const missingFields = Object.values(validation.sections ?? {}).flatMap(
    (section) => section.missingFields ?? [],
  )
  if (!validation.valid && missingFields.length === 0) {
    throw new ValidationUnavailableError(
      validation.notice?.message ??
        'Payabli could not check the application right now. Try again shortly.',
    )
  }
  const missingData = missingFields.filter(
    (missing) => !isDocumentField(missing.field, requirements),
  )
  if (missingData.length > 0) {
    throw new IncompleteApplicationError(missingData)
  }
  return requirements.filter(
    (requirement) => requirement.required && requirement.uploadedCount === 0,
  )
}

// Pass the API's own validation messages (400/409/422) through to the form so
// the user can fix their input; anything else is reported as an upstream
// failure without leaking internals. Whatever was created is kept, so the
// next submission resumes from it.
function toErrorResponse(error: StepError) {
  const cause = error.cause

  if (cause instanceof IncompleteApplicationError) {
    return jsonResponse(
      {
        error:
          'Payabli needs more information before this application can be submitted.',
        step: error.step,
        missingFields: cause.missingFields,
      },
      422,
    )
  }
  if (cause instanceof ValidationUnavailableError) {
    return jsonResponse({ error: cause.message, step: error.step }, 503)
  }

  const isUserFixable =
    cause instanceof PayabliV2Error &&
    (cause.status === 400 || cause.status === 409 || cause.status === 422)

  return jsonResponse(
    {
      error: isUserFixable
        ? (cause.apiMessage ?? 'Payabli rejected the submission.')
        : 'Failed to submit application',
      step: error.step,
      traceId: cause instanceof PayabliV2Error ? cause.traceId : null,
    },
    isUserFixable ? cause.status : 502,
  )
}

export const POST: APIRoute = async ({ request }) => {
  const session = await auth.api.getSession({ headers: request.headers })
  if (!session) {
    return jsonResponse({ error: 'Not signed in' }, 401)
  }
  const userId = session.user.id

  // Validate everything that can be checked locally before touching Payabli.
  let formData: FormSchemaType
  try {
    formData = parseServerFormData(await request.json())
  } catch (error) {
    if (error instanceof z.ZodError) {
      return jsonResponse(
        { error: 'Invalid form data', details: error.flatten() },
        400,
      )
    }
    throw error
  }

  // Each step creates or updates one kind of record and mutates the draft,
  // which is saved whether the submission succeeds or fails partway.
  let draft: BoardingDraft | null = null
  const personNames = new Map<string, string>()
  try {
    draft = await runStep('paypoint', async () =>
      upsertBusiness(await loadUsableDraft(userId, formData), formData),
    )
    await saveBoardingDraft(userId, draft)
    const current = draft

    const signerPersonReference = await runStep('people', () =>
      syncPeople(current, formData, personNames),
    )
    await saveBoardingDraft(userId, current)

    await runStep('paymentMethods', () => syncPaymentMethods(current, formData))
    await saveBoardingDraft(userId, current)

    const applicationReference = await runStep('application', () =>
      upsertApplication(current, formData),
    )
    await saveBoardingDraft(userId, current)

    // Check the application against its services' requirements before the
    // user signs. Partner submit doesn't enforce this, so without it an
    // incomplete application would only surface later in review. Documents
    // can only be uploaded once the application exists, so missing documents
    // are returned for the upload step; anything else is missing data.
    const documentRequirements = await runStep('validation', async () => {
      const [validation, requirements] = await Promise.all([
        validateApplication(applicationReference),
        getDocumentRequirements(applicationReference),
      ])
      return checkValidation(validation, requirements)
    })

    return jsonResponse(
      {
        applicationReference,
        signerPersonReference,
        documentRequirements: documentRequirements.map((requirement) => ({
          ...requirement,
          personName: requirement.personReference
            ? personNames.get(requirement.personReference)
            : undefined,
        })),
      },
      200,
    )
  } catch (error) {
    const stepError =
      error instanceof StepError ? error : new StepError('paypoint', error)
    console.error(
      `Error creating v2 boarding application at step "${stepError.step}":`,
      stepError.cause,
    )
    if (draft) {
      await saveBoardingDraft(userId, draft)
    }
    return toErrorResponse(stepError)
  }
}
