import {
  PAYABLI_BRIDGE_CHANNEL,
  PAYABLI_BRIDGE_PROTOCOL_VERSION,
  PAYABLI_EMBEDDED_SOURCE,
  PAYABLI_NATIVE_SOURCE,
  type PaymentBridgeCommand,
} from './bridge';
import {
  PAYABLI_COMPONENT_ROOT_ID,
  type PayabliEmbeddedConfig,
} from './config';
import { type PayabliPaymentRequest } from './model';

export const buildInjectedBootstrap = (
  config: PayabliEmbeddedConfig,
  paymentRequest: PayabliPaymentRequest,
) => `
  (function () {
    var bridgeChannel = ${JSON.stringify(PAYABLI_BRIDGE_CHANNEL)};
    var protocolVersion = ${JSON.stringify(PAYABLI_BRIDGE_PROTOCOL_VERSION)};
    var embeddedSource = ${JSON.stringify(PAYABLI_EMBEDDED_SOURCE)};
    var nativeSource = ${JSON.stringify(PAYABLI_NATIVE_SOURCE)};
    var componentRootId = ${JSON.stringify(PAYABLI_COMPONENT_ROOT_ID)};
    var runtimeConfig = ${JSON.stringify(config)};
    var runtimePaymentRequest = ${JSON.stringify(paymentRequest)};
    var payComponent = null;
    var lastReadyState = null;

    function toErrorMessage(error, fallback) {
      if (error && typeof error.message === 'string') {
        return error.message;
      }

      return fallback;
    }

    function createEnvelope(type, payload) {
      return {
        channel: bridgeChannel,
        version: protocolVersion,
        source: embeddedSource,
        type: type,
        payload: payload,
      };
    }

    function postToApp(type, payload) {
      if (!window.ReactNativeWebView || typeof window.ReactNativeWebView.postMessage !== 'function') {
        return;
      }

      try {
        window.ReactNativeWebView.postMessage(JSON.stringify(createEnvelope(type, payload)));
      } catch (error) {
        window.console && window.console.warn && window.console.warn(toErrorMessage(error, 'Unable to post bridge message.'));
      }
    }

    function safeParseJson(raw) {
      try {
        return JSON.parse(raw);
      } catch {
        return null;
      }
    }

    function isSubmitCommand(command) {
      return Boolean(
        command &&
          typeof command === 'object' &&
          command.channel === bridgeChannel &&
          command.version === protocolVersion &&
          command.source === nativeSource &&
          command.type === 'submitPayment'
      );
    }

    function reportHeight() {
      var container = document.getElementById(componentRootId);
      var containerHeight = container ? container.getBoundingClientRect().height : 0;
      var bodyHeight = document.body ? document.body.getBoundingClientRect().height : 0;
      var height = Math.max(containerHeight, bodyHeight);

      postToApp('contentHeight', { height: Math.ceil(height) });
    }

    function queueHeightReports() {
      window.setTimeout(reportHeight, 0);
      window.setTimeout(reportHeight, 150);
      window.setTimeout(reportHeight, 400);
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
              isReady: nextReadyState,
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

      try {
        postToApp('submit', { status: 'started' });
        payComponent.payabliExec('pay', runtimePaymentRequest);
      } catch (error) {
        postToApp('error', { message: toErrorMessage(error, 'Failed to submit payment.') });
      }
    }

    function handleBridgeMessage(event) {
      if (!event || typeof event.data !== 'string') {
        return;
      }

      var command = safeParseJson(event.data);
      if (!isSubmitCommand(command)) {
        return;
      }

      submitPayment();
    }

    function initializeComponent() {
      if (payComponent) {
        return;
      }

      if (typeof PayabliComponent !== 'function') {
        window.setTimeout(initializeComponent, 50);
        return;
      }

      try {
        payComponent = new PayabliComponent(buildConfig());
        postToApp('loaded', { status: 'initialized' });
        queueHeightReports();
      } catch (error) {
        postToApp('error', { message: toErrorMessage(error, 'Failed to initialize Payabli.') });
      }
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