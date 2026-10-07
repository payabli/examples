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
  unlinkPersonFromBusiness,
  PayabliV2Error,
  type BusinessAddress,
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

type Step = 'paypoint' | 'people' | 'paymentMethods' | 'application'

// Everything created so far in this submission, so a later failure can undo it.
type CreatedResources = {
  businessReference?: string
  paypointReference?: string
  personReferences: string[]
  paymentMethodReferences: string[]
}

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
      addressDetails: [legalAddress, mailingAddress],
      processingMetrics: {
        monthlyReceivablesVolume: formData.avgmonthly,
        averageReceivableSize: formData.ticketamt,
        largestReceivableSize: formData.highticketamt,
        inboundPresentPercent: formData.binperson,
        inboundOnlinePercent: formData.binweb,
        inboundMotoPercent: formData.binphone,
        refundPolicy: formData.whenRefunded,
      },
    },
  }
}

// People: contacts and owners, each created and linked to the business in the
// same call via `businessRelationship`. There's no separate signer person: v2
// only accepts an owner as the signer (422 SIGNER_NOT_OWNER otherwise), so the
// wizard's `signerOwnerIndex` picks one of these owners instead.
function buildPeople(formData: FormSchemaType, businessReference: string) {
  const contacts: CreatePersonRequest[] = formData.contacts.map((contact) => ({
    firstName: contact.contactFirstName,
    lastName: contact.contactLastName,
    primaryEmail: contact.contactEmail,
    primaryPhoneNumber: contact.contactPhone,
    businessRelationship: {
      businessReference,
      personType: 'Contact',
    },
  }))

  const owners: CreatePersonRequest[] = formData.ownership.map((owner) => ({
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
      ownershipPercentage: owner.ownerpercent,
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
      'Rolled back: boarding submission failed before an application was created',
    ),
  )

  return leftovers
}

// Pass the API's own validation messages (400/409/422) through to the form so
// the user can fix their input; anything else is reported as an upstream
// failure without leaking internals.
function toErrorResponse(error: StepError, leftovers: string[]) {
  const cause = error.cause
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
      const results = await Promise.allSettled(
        [...contacts, ...owners].map((person) => createPerson(person)),
      )
      for (const result of results) {
        if (result.status === 'fulfilled') {
          created.personReferences.push(result.value.personReference)
        }
      }
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
        configurations: {
          recipientEmail: formData.recipientEmail,
          recipientEmailNotification: formData.recipientEmailNotification,
        },
      }),
    )

    return jsonResponse(
      {
        applicationReference: application.requestsReference,
        signerPersonReference,
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
