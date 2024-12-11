import { z } from 'zod'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'

// Helper functions to create common fields
const requiredString = () =>
  z.string().min(1, { message: 'This field is required' })
const requiredNumber = () =>
  z.coerce.number().min(1, { message: 'This field is required' })
const requiredDate = () =>
  z.string().regex(/^\d{2}\/\d{2}\/\d{4}$/, {
    message: 'Date must be in MM/DD/YYYY format',
  })

// Define the form schema
export const formSchema = z.object({
  templateId: requiredNumber().default(123),
  // only EITHER templateId or boardingLinkId are required, never both
  // boardingLinkId: requiredNumber.default(123),
  // orgId: requiredNumber().default(123),
  processingRegion: z.enum(['US', 'CA']),
  legalname: requiredString(),
  dbaname: requiredString(),
  website: requiredString().url({ message: 'Invalid URL' }),
  ein: requiredString().regex(/^\d{9}$/, { message: 'EIN must be 9 digits' }),
  taxfillname: requiredString(),
  license: requiredString(),
  licstate: requiredString(),
  startdate: requiredDate(),
  phonenumber: requiredString().regex(/^\d{10}$/, {
    message: 'Phone number must be 10 digits',
  }),
  faxnumber: requiredString().regex(/^\d{10}$/, {
    message: 'Fax number must be 10 digits',
  }),
  btype: requiredString(),
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
  whenCharged: requiredString(),
  whenProvided: requiredString(),
  whenDelivered: requiredString(),
  whenRefunded: requiredString(),
  binperson: requiredNumber().min(0).max(100),
  binphone: requiredNumber().min(0).max(100),
  binweb: requiredNumber().min(0).max(100),
  annualRevenue: requiredNumber(),
  avgmonthly: requiredNumber(),
  ticketamt: requiredNumber(),
  highticketamt: requiredNumber(),
  creditLimit: requiredNumber(),
  averageMonthlyBill: z.string(),
  averageBillSize: z.string(),
  recipientEmail: z.string().default(''),
  recipientEmailNotification: z.boolean().default(false),
  resumable: z.boolean().default(false),
  contacts: z
    .array(
      z.object({
        contactName: requiredString(),
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
  ownership: z
    .array(
      z.object({
        ownername: requiredString(),
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
  depositAccount: z.object({
    id: z.number().default(123),
    bankName: requiredString(),
    routingAccount: requiredString().regex(/^\d{9}$/, {
      message: 'Routing number must be 9 digits',
    }),
    accountNumber: requiredString(),
    typeAccount: requiredString(),
    bankAccountHolderName: requiredString(),
    bankAccountHolderType: requiredString(),
    bankAccountFunction: z.number().min(0).max(2).default(0),
    fileUpload: z.any(),
  }),
  withdrawalAccount: z.object({
    id: z.number().default(123),
    bankName: requiredString(),
    routingAccount: requiredString().regex(/^\d{9}$/, {
      message: 'Routing number must be 9 digits',
    }),
    accountNumber: requiredString(),
    typeAccount: requiredString(),
    bankAccountHolderName: requiredString(),
    bankAccountHolderType: requiredString(),
    bankAccountFunction: z.number().min(0).max(2).default(1),
  }),
  services: z.object({
    card: z.object({
      acceptVisa: z.boolean().default(false),
      acceptMastercard: z.boolean().default(false),
      acceptDiscover: z.boolean().default(false),
      acceptAmex: z.boolean().default(false),
    }),
    ach: z.object({
      acceptWeb: z.boolean().default(false),
      acceptPPD: z.boolean().default(false),
      acceptCCD: z.boolean().default(false),
    }),
  }),
  signer: z.object({
    name: requiredString(),
    ssn: requiredString().regex(/^\d{9}$/, { message: 'SSN must be 9 digits' }),
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
  }),
})

// Create a type for the form data
export type FormSchemaType = z.infer<typeof formSchema>

// Create a custom hook to use the form
export function useFormWithSchema() {
  return useForm<FormSchemaType>({
    resolver: zodResolver(formSchema),
  })
}
