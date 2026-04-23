export type PayabliLogEntry = {
  id: number;
  type: string;
  payloadText: string;
};

export type PayabliPaymentMethod = 'card' | 'ach' | 'rdc';

export type PayabliCategory = {
  label: string;
  amount: number;
  qty: number;
};

export type PayabliCustomerData = {
  firstName: string;
  lastName: string;
  billingEmail: string;
};

export type PayabliPaymentRequest = {
  paymentDetails: {
    totalAmount: number;
    serviceFee: number;
    categories: PayabliCategory[];
  };
  customerData: PayabliCustomerData;
};

export const createDefaultPaymentRequest = (): PayabliPaymentRequest => ({
  paymentDetails: {
    totalAmount: 100,
    serviceFee: 0,
    categories: [
      {
        label: 'payment',
        amount: 100,
        qty: 1,
      },
    ],
  },
  customerData: {
    firstName: 'John',
    lastName: 'Doe',
    billingEmail: 'john.doe@example.com',
  },
});