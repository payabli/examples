import { useCallback, useState } from 'react';

import { type PayabliEmbeddedMessage } from '../lib/payabli/bridge';
import {
  createDefaultPaymentRequest,
  type PayabliPaymentMethod,
  type PayabliPaymentRequest,
} from '../lib/payabli/model';

export type CheckoutScreen = 'review' | 'payment' | 'result';

export type ResultState = {
  kind: 'success' | 'error';
  title: string;
  message: string;
};

export type CheckoutDraft = {
  totalAmount: string;
  serviceFee: string;
  categoryLabel: string;
  categoryAmount: string;
  quantity: string;
  firstName: string;
  lastName: string;
  billingEmail: string;
};

const defaultPaymentRequest = createDefaultPaymentRequest();

export const parseCurrency = (value: string, fallback: number) => {
  const parsed = Number.parseFloat(value);
  return Number.isFinite(parsed) ? parsed : fallback;
};

export const parseInteger = (value: string, fallback: number) => {
  const parsed = Number.parseInt(value, 10);
  return Number.isFinite(parsed) ? parsed : fallback;
};

export const formatCurrency = (value: number) => `$${value.toFixed(2)}`;

export const createDraftFromPaymentRequest = (
  paymentRequest: PayabliPaymentRequest,
): CheckoutDraft => ({
  totalAmount: String(paymentRequest.paymentDetails.totalAmount),
  serviceFee: String(paymentRequest.paymentDetails.serviceFee),
  categoryLabel: paymentRequest.paymentDetails.categories[0]?.label ?? 'Canvas Weekender Tote',
  categoryAmount: String(
    paymentRequest.paymentDetails.categories[0]?.amount ?? paymentRequest.paymentDetails.totalAmount,
  ),
  quantity: String(paymentRequest.paymentDetails.categories[0]?.qty ?? 1),
  firstName: paymentRequest.customerData.firstName,
  lastName: paymentRequest.customerData.lastName,
  billingEmail: paymentRequest.customerData.billingEmail,
});

export const buildPaymentRequestFromDraft = (
  draft: CheckoutDraft,
): PayabliPaymentRequest => ({
  paymentDetails: {
    totalAmount: parseCurrency(
      draft.totalAmount,
      defaultPaymentRequest.paymentDetails.totalAmount,
    ),
    serviceFee: parseCurrency(
      draft.serviceFee,
      defaultPaymentRequest.paymentDetails.serviceFee,
    ),
    categories: [
      {
        label: draft.categoryLabel || 'Canvas Weekender Tote',
        amount: parseCurrency(
          draft.categoryAmount,
          defaultPaymentRequest.paymentDetails.categories[0]?.amount ?? 0,
        ),
        qty: parseInteger(
          draft.quantity,
          defaultPaymentRequest.paymentDetails.categories[0]?.qty ?? 1,
        ),
      },
    ],
  },
  customerData: {
    firstName: draft.firstName || defaultPaymentRequest.customerData.firstName,
    lastName: draft.lastName || defaultPaymentRequest.customerData.lastName,
    billingEmail: draft.billingEmail || defaultPaymentRequest.customerData.billingEmail,
  },
});

export const deriveResultState = (message: PayabliEmbeddedMessage): ResultState | null => {
  if (message.type === 'success') {
    const payload = (message.payload ?? {}) as {
      responseText?: string;
      responseData?: { resultText?: string };
    };
    const responseText = payload.responseText ?? 'Success';
    const resultText = payload.responseData?.resultText ?? 'Your payment was completed.';

    if (responseText === 'Success') {
      return {
        kind: 'success',
        title: 'Thank you!',
        message: resultText,
      };
    }

    return {
      kind: 'error',
      title: 'Error!',
      message: resultText || responseText,
    };
  }

  if (message.type === 'error') {
    const payload = message.payload as { message?: string } | string | undefined;
    return {
      kind: 'error',
      title: 'Error!',
      message:
        typeof payload === 'string'
          ? payload
          : payload?.message ?? 'There was a problem processing this payment.',
    };
  }

  return null;
};

export const useCheckoutDemo = () => {
  const [paymentMethod, setPaymentMethod] = useState<PayabliPaymentMethod>('card');
  const [screen, setScreen] = useState<CheckoutScreen>('review');
  const [isAwaitingPaymentResult, setIsAwaitingPaymentResult] = useState(false);
  const [resultState, setResultState] = useState<ResultState | null>(null);
  const [checkoutDraft, setCheckoutDraft] = useState<CheckoutDraft>(
    createDraftFromPaymentRequest(defaultPaymentRequest),
  );
  const [paymentRequest, setPaymentRequest] = useState<PayabliPaymentRequest>(
    defaultPaymentRequest,
  );

  const handleEmbeddedMessage = useCallback(
    (latestMessage: PayabliEmbeddedMessage | null) => {
      if (!latestMessage || screen !== 'payment' || !isAwaitingPaymentResult) {
        return;
      }

      const nextResult = deriveResultState(latestMessage);
      if (!nextResult) {
        return;
      }

      setResultState(nextResult);
      setIsAwaitingPaymentResult(false);
      setScreen('result');
    },
    [isAwaitingPaymentResult, screen],
  );

  const updateDraftField = (field: keyof CheckoutDraft, value: string) => {
    setCheckoutDraft((currentDraft) => ({
      ...currentDraft,
      [field]: value,
    }));
  };

  const handleContinueToPayment = () => {
    setPaymentRequest(buildPaymentRequestFromDraft(checkoutDraft));
    setResultState(null);
    setIsAwaitingPaymentResult(false);
    setScreen('payment');
  };

  const handleBackToReview = () => {
    setIsAwaitingPaymentResult(false);
    setScreen('review');
  };

  const handleProcessPayment = () => {
    setIsAwaitingPaymentResult(true);
  };

  const handleStartOver = () => {
    setPaymentMethod('card');
    setIsAwaitingPaymentResult(false);
    setResultState(null);
    setPaymentRequest(defaultPaymentRequest);
    setCheckoutDraft(createDraftFromPaymentRequest(defaultPaymentRequest));
    setScreen('review');
  };

  return {
    paymentMethod,
    setPaymentMethod,
    screen,
    resultState,
    checkoutDraft,
    paymentRequest,
    updateDraftField,
    handleEmbeddedMessage,
    handleContinueToPayment,
    handleBackToReview,
    handleProcessPayment,
    handleStartOver,
  };
};