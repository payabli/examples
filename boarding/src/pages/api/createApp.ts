import type { APIRoute } from 'astro'
import { z } from 'zod'
import { parseServerFormData, type FormSchemaType } from '../../Schema'
import {
  createPaypointWithBusiness,
  createPerson,
  createPaymentMethods,
  createApplication,
  type BusinessAddress,
  type CreatePaymentMethodRequest,
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

export const POST: APIRoute = async ({ request }) => {
  const requestData = await request.json()

  let formData: FormSchemaType
  try {
    formData = parseServerFormData(requestData)
  } catch (error) {
    if (error instanceof z.ZodError) {
      return new Response(
        JSON.stringify({ error: 'Invalid form data', details: error.flatten() }),
        { status: 400, headers: { 'Content-Type': 'application/json' } },
      )
    }
    throw error
  }

  // Boarding v2 has no single-call equivalent of v1's `POST /api/Boarding/app`:
  // the business/paypoint, people, payment methods, and application are
  // separate resources created in sequence. Unlike the v1 call, this isn't
  // atomic -- a failure partway through can leave e.g. a business created
  // with no application. Each step is logged so partial failures are visible.
  try {
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

    const paypoint = await createPaypointWithBusiness({
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
    })

    if (!paypoint.businessReference) {
      throw new Error('Paypoint creation did not return a business reference')
    }
    const businessReference = paypoint.businessReference

    // People: contacts, owners, and the signer, each created and linked to
    // the business in the same call via `businessRelationship`.
    await Promise.all(
      formData.contacts.map((contact) =>
        createPerson({
          firstName: contact.contactFirstName,
          lastName: contact.contactLastName,
          primaryEmail: contact.contactEmail,
          primaryPhoneNumber: contact.contactPhone,
          businessRelationship: {
            businessReference,
            personType: 'Contact',
          },
        }),
      ),
    )

    await Promise.all(
      formData.ownership.map((owner) =>
        createPerson({
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
        }),
      ),
    )

    const signerPerson = await createPerson({
      firstName: formData.signer.firstName,
      lastName: formData.signer.lastName,
      primaryEmail: formData.signer.email,
      primaryPhoneNumber: formData.signer.phone,
      dateOfBirth: toIsoDate(formData.signer.dob),
      ssn: formData.signer.ssn,
      nationality: 'US',
      addresses: [
        {
          type: 'Residential',
          addressLine1: formData.signer.address,
          cityLocality: formData.signer.city,
          stateProvince: formData.signer.state,
          postalCode: formData.signer.zip,
          country: formData.signer.country,
        },
      ],
      businessRelationship: {
        businessReference,
        personType: 'Signer',
      },
    })

    // Payment methods -- all bank accounts owned by the business.
    const paymentMethods: CreatePaymentMethodRequest[] = formData.bankData.map(
      (bank) => ({
        type: 'bank_account',
        accountOwner: businessReference,
        ownerType: 'business',
        nickname: bank.nickname,
        financialInstitution: bank.bankName,
        accountType: bank.typeAccount === 'Checking' ? 'checking' : 'savings',
        accountNumber: bank.accountNumber,
        routingNumber: bank.routingAccount,
        usage: BANK_ACCOUNT_FUNCTION_TO_USAGE[bank.bankAccountFunction],
      }),
    )
    await createPaymentMethods(paymentMethods)

    // The application itself.
    const application = await createApplication({
      businessReference,
      paypointReference: paypoint.paypointReference,
      requestTemplate:
        import.meta.env.PAYABLI_BOARDING_TEMPLATE_REFERENCE || undefined,
      configurations: {
        recipientEmail: formData.recipientEmail,
        recipientEmailNotification: formData.recipientEmailNotification,
      },
    })

    return new Response(
      JSON.stringify({
        applicationReference: application.requestsReference,
        signerPersonReference: signerPerson.personReference,
      }),
      {
        status: 200,
        headers: { 'Content-Type': 'application/json' },
      },
    )
  } catch (error) {
    console.error('Error creating v2 boarding application:', error)
    return new Response(JSON.stringify({ error: 'Failed to submit application' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' },
    })
  }
}
