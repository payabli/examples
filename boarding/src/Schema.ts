import { z } from 'zod'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'

// Helper functions to create common fields
const requiredString = () => z.string().min(1, { message: "This field is required" })
const requiredNumber = () => z.number({ required_error: "This field is required" })
const requiredBoolean = () => z.boolean({ required_error: "This field is required" })
const requiredDate = () => z.string().regex(/^\d{2}\/\d{2}\/\d{4}$/, { message: "Date must be in MM/DD/YYYY format" })

// Define the form schema
export const formSchema = z.object({
  templateId: requiredNumber(),
  orgId: requiredNumber(),
  boardingLinkId: requiredString(),
  legalname: requiredString(),
  dbaname: requiredString(),
  website: requiredString().url({ message: "Invalid URL" }),
  ein: requiredString().regex(/^\d{9}$/, { message: "EIN must be 9 digits" }),
  taxfillname: requiredString(),
  license: requiredString(),
  licstate: requiredString().length(2, { message: "State must be 2 characters" }),
  startdate: requiredDate(),
  phonenumber: requiredString().regex(/^\d{10}$/, { message: "Phone number must be 10 digits" }),
  faxnumber: requiredString().regex(/^\d{10}$/, { message: "Fax number must be 10 digits" }),
  btype: requiredString(),
  baddress: requiredString(),
  baddress1: requiredString(),
  bcity: requiredString(),
  bstate: requiredString().length(2, { message: "State must be 2 characters" }),
  bzip: requiredString().regex(/^\d{5}$/, { message: "ZIP code must be 5 digits" }),
  bcountry: requiredString().length(2, { message: "Country must be 2 characters" }),
  maddress: requiredString(),
  maddress1: requiredString(),
  mcity: requiredString(),
  mstate: requiredString().length(2, { message: "State must be 2 characters" }),
  mzip: requiredString().regex(/^\d{5}$/, { message: "ZIP code must be 5 digits" }),
  mcountry: requiredString().length(2, { message: "Country must be 2 characters" }),
  mcc: requiredString().regex(/^\d{4}$/, { message: "MCC must be 4 digits" }),
  bsummary: requiredString(),
  whenCharged: requiredString(),
  whenProvided: requiredString(),
  whenDelivered: requiredString(),
  whenRefunded: requiredString(),
  binperson: requiredNumber().min(0).max(100),
  binphone: requiredNumber().min(0).max(100),
  binweb: requiredNumber().min(0).max(100),
  annualRevenue: requiredNumber().positive(),
  avgmonthly: requiredNumber().positive(),
  ticketamt: requiredNumber().positive(),
  highticketamt: requiredNumber().positive(),
  payoutAverageMonthlyVolume: requiredNumber().positive(),
  payoutHighTicketAmount: requiredNumber().positive(),
  payoutAveragTicketAmount: requiredNumber().positive(),
  payoutCreditLimit: requiredNumber().positive(),
  recipientEmail: requiredString().email({ message: "Invalid email address" }),
  recipientEmailNotification: requiredBoolean(),
  resumable: requiredBoolean(),
  contacts: z.array(z.object({
    contactName: requiredString(),
    contactEmail: requiredString().email({ message: "Invalid email address" }),
    contactTitle: requiredString(),
    contactPhone: requiredString().regex(/^\d{10}$/, { message: "Phone number must be 10 digits" }),
  })).nonempty(),
  ownership: z.array(z.object({
    ownername: requiredString(),
    ownertitle: requiredString(),
    ownerpercent: requiredNumber().min(0).max(100),
    ownerssn: requiredString().regex(/^\d{9}$/, { message: "SSN must be 9 digits" }),
    ownerdob: requiredDate(),
    ownerphone1: requiredString().regex(/^\d{10}$/, { message: "Phone number must be 10 digits" }),
    ownerphone2: requiredString().regex(/^\d{10}$/, { message: "Phone number must be 10 digits" }),
    owneremail: requiredString().email({ message: "Invalid email address" }),
    ownerdriver: requiredString(),
    odriverstate: requiredString().length(2, { message: "State must be 2 characters" }),
    oaddress: requiredString(),
    ostate: requiredString().length(2, { message: "State must be 2 characters" }),
    ocountry: requiredString().length(2, { message: "Country must be 2 characters" }),
    ocity: requiredString(),
    ozip: requiredString().regex(/^\d{5}$/, { message: "ZIP code must be 5 digits" }),
  })).nonempty(),
  depositAccount: z.object({
    id: requiredNumber(),
    bankName: requiredString(),
    routingAccount: requiredString().regex(/^\d{9}$/, { message: "Routing number must be 9 digits" }),
    accountNumber: requiredString(),
    typeAccount: requiredString(),
    bankAccountHolderName: requiredString(),
    bankAccountHolderType: requiredString(),
    bankAccountFunction: requiredNumber(),
  }),
  withdrawalAccount: z.object({
    id: requiredNumber(),
    bankName: requiredString(),
    routingAccount: requiredString().regex(/^\d{9}$/, { message: "Routing number must be 9 digits" }),
    accountNumber: requiredString(),
    typeAccount: requiredString(),
    bankAccountHolderName: requiredString(),
    bankAccountHolderType: requiredString(),
    bankAccountFunction: requiredNumber(),
  }),
  remittanceAccount: z.object({
    id: requiredNumber(),
    bankName: requiredString(),
    routingAccount: requiredString().regex(/^\d{9}$/, { message: "Routing number must be 9 digits" }),
    accountNumber: requiredString(),
    typeAccount: requiredString(),
    bankAccountHolderName: requiredString(),
    bankAccountHolderType: requiredString(),
    bankAccountFunction: requiredNumber(),
  }),
  services: z.object({
    card: z.object({
      acceptVisa: requiredBoolean(),
      acceptMastercard: requiredBoolean(),
      acceptDiscover: requiredBoolean(),
      acceptAmex: requiredBoolean(),
    }),
    ach: z.object({
      acceptWeb: requiredBoolean(),
      acceptPPD: requiredBoolean(),
      acceptCCD: requiredBoolean(),
      acceptVisa: requiredBoolean(),
      acceptMastercard: requiredBoolean(),
      acceptDiscover: requiredBoolean(),
      acceptAmex: requiredBoolean(),
    }),
  }),
  attachments: z.array(z.object({
    type: requiredString(),
    url: requiredString().url({ message: "Invalid URL" }),
  })).nonempty(),
  signer: z.object({
    name: requiredString(),
    ssn: requiredString().regex(/^\d{9}$/, { message: "SSN must be 9 digits" }),
    dob: requiredDate(),
    phone: requiredString().regex(/^\d{10}$/, { message: "Phone number must be 10 digits" }),
    email: requiredString().email({ message: "Invalid email address" }),
    address: requiredString(),
    address1: z.string(),
    state: requiredString().length(2, { message: "State must be 2 characters" }),
    country: requiredString().length(2, { message: "Country must be 2 characters" }),
    city: requiredString(),
    zip: requiredString().regex(/^\d{5}$/, { message: "ZIP code must be 5 digits" }),
  }),
})

// Create a type for the form data
export type FormSchemaType = z.infer<typeof formSchema>

// Create a custom hook to use the form with default values
export function useFormWithSchema(defaultValues: Partial<FormSchemaType> = {}) {
  return useForm<FormSchemaType>({
    resolver: zodResolver(formSchema),
    defaultValues: {
      ...defaultValues,
    },
  })
}
