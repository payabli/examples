import { PAYABLI_API_KEY, PAYABLI_ENTRY_POINT } from '@env';

import {
  type PayabliPaymentMethod,
  type PayabliPaymentRequest,
} from './model';

export const PAYABLI_COMPONENT_ROOT_ID = 'pay-component-1';

const PAYABLI_COMPONENT_SCRIPT_URL =
  'https://embedded-component-sandbox.payabli.com/component.js';

export type PayabliEmbeddedConfig = {
  type: 'methodEmbedded';
  rootContainer: string;
  token: string | undefined;
  entryPoint: string | undefined;
  temporaryToken: boolean;
  customCssStyle: string;
  defaultOpen?: PayabliPaymentMethod;
  card?: Record<string, unknown>;
  ach?: Record<string, unknown>;
  rdc?: Record<string, unknown>;
};

export const createHtmlShell = () => `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <style>
    :root, body, html {
      background-color: transparent !important;
      margin: 0 !important;
      padding: 0 !important;
    }

    #${PAYABLI_COMPONENT_ROOT_ID} {
      background-color: transparent !important;
      margin-bottom: 0 !important;
      padding-bottom: 0 !important;
    }
  </style>
  <title>Payabli Integration</title>
  <script src="${PAYABLI_COMPONENT_SCRIPT_URL}" data-test></script>
</head>
<body>
  <div id="${PAYABLI_COMPONENT_ROOT_ID}"></div>
</body>
</html>`;

const sharedConfig: PayabliEmbeddedConfig = {
  type: 'methodEmbedded',
  rootContainer: PAYABLI_COMPONENT_ROOT_ID,
  token: PAYABLI_API_KEY,
  entryPoint: PAYABLI_ENTRY_POINT,
  temporaryToken: false,
  customCssStyle: `
    .payabliPaymentForm {
      padding: 20px;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
      color: white !important;
    }

    label {
      font-weight: 600 !important;
    }

    input {
      background-color: #111827 !important;
      color: white !important;
      border: 1px solid #374151 !important;
    }

    input:focus {
      box-shadow: 0 0 0 2px #818cf8 !important;
    }

    :root, html body, #main-loading-layer {
      background-color: transparent;
    }
  `,
};

const cardConfig: PayabliEmbeddedConfig = {
  ...sharedConfig,
  defaultOpen: 'card',
  card: {
    enabled: true,
    amex: true,
    discover: true,
    visa: true,
    mastercard: true,
    jcb: true,
    diners: true,
    inputs: {
      cardHolderName: {
        label: 'Cardholder Name',
        size: 12,
        row: 0,
        order: 0,
        floating: false,
      },
      cardNumber: {
        label: 'Card Number',
        size: 6,
        row: 1,
        order: 0,
        floating: false,
      },
      cardExpirationDate: {
        label: 'Expiration Date',
        size: 6,
        row: 1,
        order: 1,
        floating: false,
      },
      cardCvv: {
        label: 'CVV',
        size: 6,
        row: 2,
        order: 0,
        floating: false,
      },
      cardZipcode: {
        label: 'ZIP Code',
        size: 6,
        row: 2,
        order: 1,
        floating: false,
      },
    },
  },
};

const achConfig: PayabliEmbeddedConfig = {
  ...sharedConfig,
  defaultOpen: 'ach',
  ach: {
    enabled: true,
    checking: true,
    savings: true,
    inputs: {
      achAccountHolderName: {
        label: 'Account Holder Name',
        size: 12,
        row: 0,
        order: 0,
        floating: false,
      },
      achRouting: {
        label: 'Routing Number',
        size: 6,
        row: 1,
        order: 0,
        floating: false,
      },
      achAccount: {
        label: 'Account Number',
        size: 6,
        row: 1,
        order: 1,
        floating: false,
      },
      achAccountType: {
        label: 'Account Type',
        size: 12,
        row: 2,
        order: 0,
        floating: false,
      },
    },
  },
};

const buildRdcConfig = (paymentRequest: PayabliPaymentRequest): PayabliEmbeddedConfig => ({
  ...sharedConfig,
  defaultOpen: 'rdc',
  card: { enabled: true },
  rdc: {
    enabled: true,
    amount: paymentRequest.paymentDetails.totalAmount,
    inputs: {
      rdcAccountHolderName: {
        label: 'Account Holder Name',
        placeholder: 'Account Holder Name',
        floating: false,
        size: 6,
        row: 0,
        order: 0,
      },
      rdcAmount: {
        label: 'Amount',
        placeholder: 'Amount',
        floating: false,
        size: 6,
        row: 0,
        order: 1,
      },
    },
  },
});

export const buildPayabliConfig = (
  paymentMethod: PayabliPaymentMethod,
  paymentRequest: PayabliPaymentRequest,
): PayabliEmbeddedConfig => {
  if (paymentMethod === 'ach') {
    return achConfig;
  }

  if (paymentMethod === 'rdc') {
    return buildRdcConfig(paymentRequest);
  }

  return cardConfig;
};