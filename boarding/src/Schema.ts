import { z } from 'zod'
import { useForm, type Resolver } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { createFormSchemaKit } from '@/lib/schemaPrefill'

// Helper functions to create common fields
const requiredString = () =>
  z.string().min(1, { message: 'This field is required' })
const requiredNumber = () =>
  z.coerce.number().min(1, { message: 'This field is required' })
const requiredDate = () =>
  z.string().regex(/^\d{2}\/\d{2}\/\d{4}$/, {
    message: 'Date must be in MM/DD/YYYY format',
  })

type PercentageDistribution = {
  binperson: number
  binphone: number
  binweb: number
}

function validatePercentageDistribution(
  data: PercentageDistribution,
  ctx: z.RefinementCtx,
) {
  const errorMessage = 'The sum of percentages must equal 100%'
  const total = data.binperson + data.binphone + data.binweb
  if (total !== 100) {
    ctx.addIssue({
      path: ['binperson'],
      fatal: false,
      code: 'custom',
      message: errorMessage,
    })
    ctx.addIssue({
      path: ['binphone'],
      fatal: false,
      code: 'custom',
      message: errorMessage,
    })
    ctx.addIssue({
      path: ['binweb'],
      fatal: false,
      code: 'custom',
      message: errorMessage,
    })
  }
}

// Field names below mirror the Boarding v2 OAS as closely as the flat form
// shape allows (see fern/apis/payabliApi-oas/openapi/components/schemas/
// {businesses,paypoints,people,paymentMethods,applications}.yaml on the
// payabliDocs feature branch). The API-shaped payload (nested addresses,
// processingMetrics, businessRelationship, etc.) is assembled from this flat
// shape server-side in src/pages/api/createApp.ts, not here — this schema is
// about validating what the wizard collects.
const formSchemaKit = createFormSchemaKit({
  // -- Business (-> CreateBusinessRequest, plus doingBusinessAs on the paypoint) --
  legalName: requiredString(),
  doingBusinessAs: requiredString(),
  website: requiredString().regex(/^[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/, {
    message: 'Must be a domain without the protocol (http/s)',
  }),
  taxReference: requiredString().regex(/^\d{9}$/, {
    message: 'Tax reference (EIN) must be 9 digits',
  }),
  startdate: requiredDate(),
  phonenumber: requiredString().regex(/^\d{10}$/, {
    message: 'Phone number must be 10 digits',
  }),
  legalStructure: z.enum([
    'limited-liability-company',
    'corporation',
    'partnership',
    'sole-proprietorship',
    'non-profit',
    'government',
    's-corp',
  ]),
  baddress: requiredString(),
  baddress1: z.string().optional(),
  bcity: requiredString(),
  bstate: requiredString(),
  bzip: requiredString().regex(/^\d{5}$/, {
    message: 'ZIP code must be 5 digits',
  }),
  bcountry: requiredString().length(2, {
    message: 'Country must be 2 characters',
  }),
  maddress: requiredString(),
  maddress1: z.string().optional(),
  mcity: requiredString(),
  mstate: requiredString(),
  mzip: requiredString().regex(/^\d{5}$/, {
    message: 'ZIP code must be 5 digits',
  }),
  mcountry: requiredString(),
  mcc: requiredString().regex(/^\d{4}$/, { message: 'MCC must be 4 digits' }),
  bsummary: requiredString(),
  whenRefunded: requiredString(),
  binperson: requiredNumber().min(0).max(100),
  binphone: requiredNumber().min(0).max(100),
  binweb: requiredNumber().min(0).max(100),
  annualRevenue: requiredNumber(),
  avgmonthly: requiredNumber(),
  ticketamt: requiredNumber(),
  highticketamt: requiredNumber(),

  // -- Application-level (-> CreateApplicationRequest.configurations) --
  recipientEmail: z.string().default(''),
  recipientEmailNotification: z.boolean().default(false),

  // -- Contacts (-> Person, personType: Contact) --
  contacts: z
    .array(
      z.object({
        contactFirstName: requiredString(),
        contactLastName: requiredString(),
        contactEmail: requiredString().email({
          message: 'Invalid email address',
        }),
        contactTitle: requiredString(),
        contactPhone: requiredString().regex(/^\d{10}$/, {
          message: 'Phone number must be 10 digits',
        }),
      }),
    )
    .nonempty(),

  // -- Ownership (-> Person, personType: Owner, businessRelationship.ownershipPercentage) --
  ownership: z
    .array(
      z.object({
        ownerFirstName: requiredString(),
        ownerLastName: requiredString(),
        ownertitle: requiredString(),
        ownerpercent: requiredNumber().min(0).max(100),
        ownerssn: requiredString().regex(/^\d{9}$/, {
          message: 'SSN must be 9 digits',
        }),
        ownerdob: requiredDate(),
        ownerphone1: requiredString().regex(/^\d{10}$/, {
          message: 'Phone number must be 10 digits',
        }),
        ownerphone2: z
          .string()
          .regex(/^(\d{10})?$/, { message: 'Phone number must be 10 digits' })
          .optional(),
        owneremail: requiredString().email({
          message: 'Invalid email address',
        }),
        ownerdriver: requiredString(),
        odriverstate: requiredString(),
        oaddress: requiredString(),
        ostate: z.string().optional(),
        ocountry: requiredString(),
        ocity: requiredString(),
        ozip: requiredString().regex(/^\d{5}$/, {
          message: 'ZIP code must be 5 digits',
        }),
      }),
    )
    .nonempty(),

  // -- Bank accounts (-> CreatePaymentMethodRequest, one batch call) --
  bankData: z
    .array(
      z.object({
        nickname: requiredString(),
        bankName: requiredString(),
        routingAccount: requiredString().regex(/^\d{9}$/, {
          message: 'Routing number must be 9 digits',
        }),
        accountNumber: requiredString(),
        typeAccount: z.enum(['Checking', 'Savings']),
        // Maps to a v2 `usage[]` combination in the API-payload builder:
        // Deposit -> [deposits], Withdrawal -> [withdrawals],
        // Both -> [deposits, withdrawals], Remittance -> [payOutFunding].
        bankAccountFunction: z.enum([
          'Deposit',
          'Withdrawal',
          'Both',
          'Remittance',
        ]),
      }),
    )
    .nonempty({ message: 'At least one bank account is required' }),

  // -- Signer (-> Person, personType: Signer; also drives the e-sign step) --
  signer: z.object({
    firstName: requiredString(),
    lastName: requiredString(),
    ssn: requiredString().regex(/^\d{9}$/, {
      message: 'SSN must be 9 digits',
    }),
    dob: requiredDate(),
    phone: requiredString().regex(/^\d{10}$/, {
      message: 'Phone number must be 10 digits',
    }),
    email: requiredString().email({ message: 'Invalid email address' }),
    address: requiredString(),
    address1: z.string().optional(),
    state: requiredString(),
    country: requiredString(),
    city: requiredString(),
    zip: requiredString().regex(/^\d{5}$/, {
      message: 'ZIP code must be 5 digits',
    }),
    acceptance: z.boolean().default(true),
  }),
})

// Define the form schema
export const formSchema = formSchemaKit.clientSchema.superRefine((data, ctx) => {
  validatePercentageDistribution(data, ctx)
})

export const serverFormSchema = formSchemaKit.serverSchema.superRefine(
  (data, ctx) => {
    validatePercentageDistribution(data, ctx)
  },
)

export const formDefaultValues = formSchemaKit.defaultValues

// Use normalize when partial payloads are allowed and only server-owned prefill fields must be enforced.
export function normalizeServerFormData(input: unknown) {
  return formSchemaKit.normalizeInput(input)
}

// Use parse when the payload must be fully validated after prefill enforcement and coercion.
export function parseServerFormData(input: unknown) {
  return serverFormSchema.parse(normalizeServerFormData(input))
}

// Use safeParse when the payload must be fully validated after prefill enforcement but without throwing.
export function safeParseServerFormData(input: unknown) {
  return serverFormSchema.safeParse(normalizeServerFormData(input))
}

// Create a type for the form data
export type FormSchemaType = z.infer<typeof formSchema>

// Create a custom hook to use the form
export function useFormWithSchema() {
  return useForm<FormSchemaType>({
    // The client schema is built from a generic schema-kit mapped type, which
    // defeats zodResolver's own field-type inference; the runtime resolver is
    // unaffected, so this only restores the type we already export as FormSchemaType.
    resolver: zodResolver(formSchema) as unknown as Resolver<FormSchemaType>,
    defaultValues: formDefaultValues,
  })
}
