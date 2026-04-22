import { useCallback, useRef, useState } from 'react';
import { type WebView, type WebViewMessageEvent } from 'react-native-webview';
import { PAYABLI_API_KEY, PAYABLI_ENTRY_POINT } from '@env';

type PaymentLogMessage = {
  type: string;
  payload: unknown;
};

type PaymentBridgeCommand = {
  channel: 'payabli-webview';
  type: 'submitPayment';
};

type PaymentHeightMessage = {
  type: 'contentHeight';
  payload: {
    height: number;
  };
};

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

const PAYABLI_BRIDGE_CHANNEL = 'payabli-webview';

const PAYABLI_COMPONENT_SCRIPT_URL =
  'https://embedded-component-sandbox.payabli.com/component.js';

const createHtmlShell = () => `<!DOCTYPE html>
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

    #pay-component-1 {
      background-color: transparent !important;
      margin-bottom: 0 !important;
      padding-bottom: 0 !important;
    }
  </style>
  <title>Payabli Integration</title>
  <script src="${PAYABLI_COMPONENT_SCRIPT_URL}" data-test></script>
</head>
<body>
  <div id="pay-component-1"></div>
</body>
</html>`;

const sharedConfig = {
  type: 'methodEmbedded',
  rootContainer: 'pay-component-1',
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
} as const;

const cardConfig = {
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
} as const;

const achConfig = {
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
} as const;

const buildRdcConfig = (paymentRequest: PayabliPaymentRequest) => ({
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

const buildPayabliConfig = (
  paymentMethod: PayabliPaymentMethod,
  paymentRequest: PayabliPaymentRequest,
) => {
  if (paymentMethod === 'ach') {
    return achConfig;
  }

  if (paymentMethod === 'rdc') {
    return buildRdcConfig(paymentRequest);
  }

  return cardConfig;
};

const buildInjectedBootstrap = (
  config: ReturnType<typeof buildPayabliConfig>,
  paymentRequest: PayabliPaymentRequest,
) => `
  (function () {
    var bridgeChannel = ${JSON.stringify(PAYABLI_BRIDGE_CHANNEL)};
    var runtimeConfig = ${JSON.stringify(config)};
    var runtimePaymentRequest = ${JSON.stringify(paymentRequest)};
    var payComponent = null;
    var lastReadyState = null;

    function postToApp(type, payload) {
      if (window.ReactNativeWebView) {
        window.ReactNativeWebView.postMessage(JSON.stringify({ type: type, payload: payload }));
      }
    }

    function reportHeight() {
      var container = document.getElementById('pay-component-1');
      var containerHeight = container ? container.getBoundingClientRect().height : 0;
      var bodyHeight = document.body ? document.body.getBoundingClientRect().height : 0;
      var height = Math.max(containerHeight, bodyHeight);

      postToApp('contentHeight', { height: Math.ceil(height) });
    }

    function buildConfig() {
      return Object.assign({}, runtimeConfig, {
        functionCallBackSuccess: function (response) {
          postToApp('success', response);
        },
        functionCallBackError: function (errors) {
          postToApp('error', errors);
        },
        functionCallBackReady: function (data) {
          var nextReadyState = Boolean(data && data[1] === true);

          if (lastReadyState !== nextReadyState) {
            lastReadyState = nextReadyState;
            postToApp('ready', {
              raw: data,
              isReady: nextReadyState
            });
          }

          window.setTimeout(reportHeight, 0);
          window.setTimeout(reportHeight, 150);
        }
      });
    }

    function submitPayment() {
      if (!payComponent) {
        postToApp('error', { message: 'Payabli component is not initialized yet.' });
        return;
      }

      postToApp('submit', { status: 'started' });
      payComponent.payabliExec('pay', runtimePaymentRequest);
    }

    function handleBridgeMessage(event) {
      if (!event || typeof event.data !== 'string') {
        return;
      }

      if (event.data.indexOf(bridgeChannel) === -1) {
        return;
      }

      try {
        var command = JSON.parse(event.data);

        if (!command || command.channel !== bridgeChannel) {
          return;
        }

        if (command.type === 'submitPayment') {
          submitPayment();
        }
      } catch {
        return;
      }
    }

    function initializeComponent() {
      if (payComponent) {
        return;
      }

      if (typeof PayabliComponent !== 'function') {
        window.setTimeout(initializeComponent, 50);
        return;
      }

      payComponent = new PayabliComponent(buildConfig());
      postToApp('loaded', { status: 'initialized' });
      window.setTimeout(reportHeight, 0);
      window.setTimeout(reportHeight, 150);
      window.setTimeout(reportHeight, 400);
    }

    if (typeof MutationObserver === 'function') {
      var observer = new MutationObserver(function () {
        reportHeight();
      });

      observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
      });
    }

    window.addEventListener('resize', reportHeight);
    window.addEventListener('message', handleBridgeMessage);
    document.addEventListener('message', handleBridgeMessage);

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initializeComponent);
    } else {
      initializeComponent();
    }
  })();
  true;
`;

const serializeLogMessage = (message: PaymentLogMessage) => {
  return JSON.stringify(message, null, 2);
};

const shouldLogMessage = (message: PaymentLogMessage) => {
  return message.type !== 'contentHeight';
};

const isReadyMessage = (message: PaymentLogMessage) => {
  if (message.type !== 'ready' || !message.payload || typeof message.payload !== 'object') {
    return false;
  }

  return Boolean((message.payload as { isReady?: boolean }).isReady);
};

export const usePayabliWebView = (
  paymentMethod: PayabliPaymentMethod,
  paymentRequest: PayabliPaymentRequest,
) => {
  const webViewRef = useRef<WebView>(null);
  const nextLogIdRef = useRef(1);
  const [isPaymentReady, setIsPaymentReady] = useState(false);
  const [webViewHeight, setWebViewHeight] = useState(360);
  const [logEntries, setLogEntries] = useState<PayabliLogEntry[]>([]);
  const [unreadLogCount, setUnreadLogCount] = useState(0);
  const [latestMessage, setLatestMessage] = useState<PaymentLogMessage | null>(null);

  const htmlShell = createHtmlShell();
  const injectedBootstrap = buildInjectedBootstrap(
    buildPayabliConfig(paymentMethod, paymentRequest),
    paymentRequest,
  );

  const appendLogEntry = useCallback((message: PaymentLogMessage) => {
    if (!shouldLogMessage(message)) {
      return;
    }

    const payloadText = serializeLogMessage(message);

    setLogEntries((currentEntries) => {
      const lastEntry = currentEntries[0];
      if (lastEntry && lastEntry.type === message.type && lastEntry.payloadText === payloadText) {
        return currentEntries;
      }

      const nextEntry: PayabliLogEntry = {
        id: nextLogIdRef.current,
        type: message.type,
        payloadText,
      };

      nextLogIdRef.current += 1;
      return [nextEntry, ...currentEntries].slice(0, 50);
    });

    setUnreadLogCount((count) => count + 1);
  }, []);

  const markLogsSeen = useCallback(() => {
    setUnreadLogCount(0);
  }, []);

  const handleMessage = (event: WebViewMessageEvent) => {
    try {
      const message = JSON.parse(event.nativeEvent.data) as PaymentLogMessage;
      setLatestMessage(message);

      if (message.type === 'contentHeight' && message.payload && typeof message.payload === 'object') {
        const nextHeight = (message as PaymentHeightMessage).payload.height;
        if (typeof nextHeight === 'number' && Number.isFinite(nextHeight) && nextHeight > 0) {
          setWebViewHeight(nextHeight);
        }
      }

      if (message.type === 'ready') {
        setIsPaymentReady(isReadyMessage(message));
      }

      appendLogEntry(message);
    } catch {
      return;
    }
  };

  const handleSubmitPress = () => {
    const command: PaymentBridgeCommand = {
      channel: PAYABLI_BRIDGE_CHANNEL,
      type: 'submitPayment',
    };
    webViewRef.current?.postMessage(JSON.stringify(command));
  };

  return {
    htmlShell,
    injectedBootstrap,
    webViewRef,
    isPaymentReady,
    webViewHeight,
    logEntries,
    unreadLogCount,
    latestMessage,
    handleMessage,
    handleSubmitPress,
    markLogsSeen,
  };
};