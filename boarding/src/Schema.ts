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
export const formSchema = z
  .object({
    templateId: requiredNumber().default(123),
    // only EITHER templateId or boardingLinkId are required, never both
    // boardingLinkId: requiredNumber.default(123),
    // orgId: requiredNumber().default(123),
    processingRegion: z.enum(['US', 'CA']),
    legalname: requiredString(),
    dbaname: requiredString(),
    website: requiredString().regex(/^[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/, {
      message: 'Must be a domain without the protocol (http/s)',
    }),
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
    bankData: z
      .array(
        z.object({
          nickname: requiredString(),
          bankName: requiredString(),
          routingAccount: requiredString().regex(/^\d{9}$/, {
            message: 'Routing number must be 9 digits',
          }),
          accountNumber: requiredString(),
          typeAccount: requiredString(),
          bankAccountHolderName: requiredString(),
          bankAccountHolderType: requiredString(),
          bankAccountFunction: z.number().min(0).max(3),
        }),
      )
      .nonempty({ message: 'At least one bank account is required' }),
    // Keep deprecated fields for backward compatibility temporarily
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
    }).optional(),
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
    }).optional(),
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
  .superRefine((data, ctx) => {
    const errorMessage = 'The sum of percentages must equal 100%'
    const total = data.binperson + data.binphone + data.binweb
    if (total !== 100) {
      // Add errors for each field if the total is invalid
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
  })

// Create a type for the form data
export type FormSchemaType = z.infer<typeof formSchema>

// Create a custom hook to use the form
export function useFormWithSchema() {
  return useForm<FormSchemaType>({
    resolver: zodResolver(formSchema),
  })
}

// Helper function to migrate from old bank account format to new bankData array
export function migrateBankAccounts(data: any): any {
  if (data.bankData) {
    // Already in new format
    return data;
  }

  const migrated = { ...data };
  const bankDataArray = [];

  // Migrate depositAccount if it exists
  if (data.depositAccount) {
    const { id, fileUpload, ...rest } = data.depositAccount;
    bankDataArray.push({
      nickname: 'Deposit Account',
      ...rest,
      bankAccountFunction: 0,
    });
  }

  // Migrate withdrawalAccount if it exists
  if (data.withdrawalAccount) {
    const { id, ...rest } = data.withdrawalAccount;
    bankDataArray.push({
      nickname: 'Withdrawal Account',
      ...rest,
      bankAccountFunction: 1,
    });
  }

  // If we have any migrated accounts, set bankData
  if (bankDataArray.length > 0) {
    migrated.bankData = bankDataArray;
    // Remove old fields
    delete migrated.depositAccount;
    delete migrated.withdrawalAccount;
  } else {
    // Default bank accounts if none exist
    migrated.bankData = [
      { nickname: 'Deposit Account', bankAccountFunction: 0 },
      { nickname: 'Withdrawal Account', bankAccountFunction: 1 },
    ];
  }

  return migrated;
}
