import type { APIRoute } from 'astro'
import { z } from 'zod'
import { parseServerFormData, type FormSchemaType } from '../../Schema'
import {
  createPaypointWithBusiness,
  createPerson,
  createPaymentMethods,
  createApplication,
  deactivateBusiness,
  deactivatePaymentMethod,
  getDocumentRequirements,
  unlinkPersonFromBusiness,
  validateApplication,
  withdrawApplication,
  PayabliV2Error,
  type BusinessAddress,
  type DocumentRequirement,
  type OperatingSeason,
  type RequestedServices,
  type ValidationMissingField,
  type CreatePaymentMethodRequest,
  type CreatePersonRequest,
  type PaymentMethodUsage,
} from '../../lib/boardingV2'

// The wizard collects MM/DD/YYYY; the v2 API expects YYYY-MM-DD.
function toIsoDate(mmddyyyy: string): string {
  const [month, day, year] = mmddyyyy.split('/')
  return `${year}-${month}-${day}`
}

const BANK_ACCOUNT_FUNCTION_TO_USAGE: Record<
  FormSchemaType['bankData'][number]['bankAccountFunction'],
  PaymentMethodUsage[]
> = {
  Deposit: ['deposits'],
  Withdrawal: ['withdrawals'],
  Both: ['deposits', 'withdrawals'],
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

// Everything created so far in this submission, so a later failure can undo it.
type CreatedResources = {
  businessReference?: string
  paypointReference?: string
  personReferences: string[]
  paymentMethodReferences: string[]
  applicationReference?: string
}

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

function operatingSeasons(formData: FormSchemaType): OperatingSeason[] {
  const seasons: [OperatingSeason, boolean][] = [
    ['Spring', formData.seasonSpring],
    ['Summer', formData.seasonSummer],
    ['Fall', formData.seasonFall],
    ['Winter', formData.seasonWinter],
  ]
  return seasons.filter(([, selected]) => selected).map(([season]) => season)
}

function buildPaypointRequest(formData: FormSchemaType) {
  const legalAddress: BusinessAddress = {
    type: 'legal',
    addressLine1: formData.baddress,
    addressLine2: formData.baddress1,
    cityLocality: formData.bcity,
    stateProvince: formData.bstate,
    postalCode: formData.bzip,
    country: formData.bcountry,
  }
  const mailingAddress: BusinessAddress = {
    type: 'mailing',
    addressLine1: formData.maddress,
    addressLine2: formData.maddress1,
    cityLocality: formData.mcity,
    stateProvince: formData.mstate,
    postalCode: formData.mzip,
    country: formData.mcountry,
  }

  return {
    doingBusinessAs: formData.doingBusinessAs,
    business: {
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
      addressDetails: [legalAddress, mailingAddress],
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
    },
  }
}

// People: contacts and owners, each created and linked to the business in the
// same call via `businessRelationship`. Contacts are `Employee` people (v2 is
// retiring the `Contact` type). There's no separate signer person: the
// signer and primary controller are flags on the owners the wizard picked
// (`signerOwnerIndex`, `primaryControllerOwnerIndex`).
function buildPeople(formData: FormSchemaType, businessReference: string) {
  const contacts: CreatePersonRequest[] = formData.contacts.map((contact) => ({
    firstName: contact.contactFirstName,
    lastName: contact.contactLastName,
    primaryEmail: contact.contactEmail,
    primaryPhoneNumber: contact.contactPhone,
    businessRelationship: {
      businessReference,
      personType: 'Employee',
      title: contact.contactTitle,
    },
  }))

  const owners: CreatePersonRequest[] = formData.ownership.map((owner, index) => ({
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
  }))

  return { contacts, owners }
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

async function runStep<T>(step: Step, fn: () => Promise<T>): Promise<T> {
  try {
    return await fn()
  } catch (error) {
    throw new StepError(step, error)
  }
}

// Undo a partially completed submission, newest resources first. v2 only has
// soft deletes here, and none at all for paypoints or person records, so the
// best available cleanup is: deactivate payment methods, unlink people, then
// deactivate the business (which retires its paypoint with it). Each call is
// attempted even if an earlier one fails; returns the references that
// couldn't be cleaned up.
async function rollback(created: CreatedResources): Promise<string[]> {
  const { businessReference } = created
  if (!businessReference) {
    return []
  }

  const leftovers: string[] = []
  const attempt = async (reference: string, fn: () => Promise<unknown>) => {
    try {
      await fn()
    } catch (error) {
      leftovers.push(reference)
      console.error(`Rollback failed for ${reference}:`, error)
    }
  }

  const { applicationReference } = created
  if (applicationReference) {
    await attempt(applicationReference, () =>
      withdrawApplication(
        applicationReference,
        'Rolled back: the application failed validation',
      ),
    )
  }

  await Promise.all(
    created.paymentMethodReferences.map((ref) =>
      attempt(ref, () => deactivatePaymentMethod(ref)),
    ),
  )
  await Promise.all(
    created.personReferences.map((ref) =>
      attempt(ref, () => unlinkPersonFromBusiness(businessReference, ref)),
    ),
  )
  await attempt(businessReference, () =>
    deactivateBusiness(
      businessReference,
      'Rolled back: boarding submission failed',
    ),
  )

  return leftovers
}

// Pass the API's own validation messages (400/409/422) through to the form so
// the user can fix their input; anything else is reported as an upstream
// failure without leaking internals.
function toErrorResponse(error: StepError, leftovers: string[]) {
  const cause = error.cause

  if (cause instanceof IncompleteApplicationError) {
    return jsonResponse(
      {
        error:
          'Payabli needs more information before this application can be submitted.',
        step: error.step,
        missingFields: cause.missingFields,
        rolledBack: leftovers.length === 0,
      },
      422,
    )
  }
  if (cause instanceof ValidationUnavailableError) {
    return jsonResponse(
      {
        error: cause.message,
        step: error.step,
        rolledBack: leftovers.length === 0,
      },
      503,
    )
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
      rolledBack: leftovers.length === 0,
    },
    isUserFixable ? cause.status : 502,
  )
}

export const POST: APIRoute = async ({ request }) => {
  const requestData = await request.json()

  // Validate everything that can be checked locally before creating anything:
  // v2 has no single atomic "create application" call (unlike v1's
  // `POST /api/Boarding/app`), so an error caught here costs nothing, while
  // the same error from the API partway through leaves resources behind.
  let formData: FormSchemaType
  try {
    formData = parseServerFormData(requestData)
  } catch (error) {
    if (error instanceof z.ZodError) {
      return jsonResponse(
        { error: 'Invalid form data', details: error.flatten() },
        400,
      )
    }
    throw error
  }

  // The business/paypoint, people, payment methods, and application are
  // separate resources created in sequence. Each step records what it
  // created, and if a later step fails, everything is rolled back so a
  // resubmission starts clean instead of piling up orphaned businesses.
  const created: CreatedResources = {
    personReferences: [],
    paymentMethodReferences: [],
  }
  // Names for person-scoped document requirements (e.g. a foreign owner's
  // passport), which only carry a personReference.
  const personNames = new Map<string, string>()

  try {
    const paypoint = await runStep('paypoint', async () => {
      const result = await createPaypointWithBusiness(
        buildPaypointRequest(formData),
      )
      created.paypointReference = result.paypointReference
      created.businessReference = result.businessReference ?? undefined
      if (!result.businessReference) {
        throw new Error('Paypoint creation did not return a business reference')
      }
      return result
    })
    const businessReference = paypoint.businessReference as string

    // allSettled rather than all: if one person is rejected, the others may
    // still have been created, and their references are needed for rollback.
    const signerPersonReference = await runStep('people', async () => {
      const { contacts, owners } = buildPeople(formData, businessReference)
      const people = [...contacts, ...owners]
      const results = await Promise.allSettled(
        people.map((person) => createPerson(person)),
      )
      results.forEach((result, index) => {
        if (result.status === 'fulfilled') {
          const { personReference } = result.value
          created.personReferences.push(personReference)
          personNames.set(
            personReference,
            `${people[index].firstName} ${people[index].lastName}`,
          )
        }
      })
      const rejected = results.find((result) => result.status === 'rejected')
      if (rejected) {
        throw rejected.reason
      }
      const signer = results[
        contacts.length + formData.signerOwnerIndex
      ] as PromiseFulfilledResult<Awaited<ReturnType<typeof createPerson>>>
      return signer.value.personReference
    })

    await runStep('paymentMethods', async () => {
      const results = await createPaymentMethods(
        buildPaymentMethods(formData, businessReference),
      )
      created.paymentMethodReferences.push(
        ...results.map((method) => method.paymentMethodReference),
      )
    })

    const application = await runStep('application', () =>
      createApplication({
        businessReference,
        paypointReference: paypoint.paypointReference,
        requestTemplate:
          import.meta.env.PAYABLI_BOARDING_TEMPLATE_REFERENCE || undefined,
        services: SERVICES,
        configurations: {
          recipientEmail: formData.recipientEmail,
          recipientEmailNotification: formData.recipientEmailNotification,
        },
      }),
    )
    created.applicationReference = application.requestsReference

    // Check the application against its services' requirements before the
    // user signs. Partner submit doesn't enforce this, so without it an
    // incomplete application would only surface later in review. Documents can only
    // be uploaded once the application exists, so missing documents are
    // returned for the upload step; anything else is missing data the form
    // should have caught, so the submission is rolled back.
    const documentRequirements = await runStep('validation', async () => {
      const [validation, requirements] = await Promise.all([
        validateApplication(application.requestsReference),
        getDocumentRequirements(application.requestsReference),
      ])
      return checkValidation(validation, requirements)
    })

    return jsonResponse(
      {
        applicationReference: application.requestsReference,
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

    const leftovers = await rollback(created)
    if (created.businessReference) {
      console.error(
        leftovers.length === 0
          ? `Rolled back business ${created.businessReference} and its resources.`
          : `Rollback incomplete; still active: ${leftovers.join(', ')}`,
      )
    }

    return toErrorResponse(stepError, leftovers)
  }
}
