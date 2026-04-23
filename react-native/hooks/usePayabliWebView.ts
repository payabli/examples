import { useCallback, useRef, useState } from 'react';
import { type WebView, type WebViewMessageEvent } from 'react-native-webview';

import {
  appendUniqueLogEntry,
  createSubmitPaymentCommand,
  getEmbeddedMessageHeight,
  isReadyMessage,
  parseEmbeddedMessage,
  serializeLogMessage,
  serializeNativeCommand,
  shouldLogMessage,
  type PayabliEmbeddedMessage,
} from '../lib/payabli/bridge';
import { buildInjectedBootstrap } from '../lib/payabli/bootstrap';
import { buildPayabliConfig, createHtmlShell } from '../lib/payabli/config';
import {
  createDefaultPaymentRequest,
  type PayabliCategory,
  type PayabliCustomerData,
  type PayabliLogEntry,
  type PayabliPaymentMethod,
  type PayabliPaymentRequest,
} from '../lib/payabli/model';

export type {
  PayabliCategory,
  PayabliCustomerData,
  PayabliLogEntry,
  PayabliPaymentMethod,
  PayabliPaymentRequest,
};

export { createDefaultPaymentRequest };

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
  const [latestMessage, setLatestMessage] = useState<PayabliEmbeddedMessage | null>(null);

  const htmlShell = createHtmlShell();
  const injectedBootstrap = buildInjectedBootstrap(
    buildPayabliConfig(paymentMethod, paymentRequest),
    paymentRequest,
  );

  const appendLogEntry = useCallback((message: PayabliEmbeddedMessage) => {
    if (!shouldLogMessage(message)) {
      return;
    }

    const payloadText = serializeLogMessage(message);

    setLogEntries((currentEntries) => {
      const nextEntry: PayabliLogEntry = {
        id: nextLogIdRef.current,
        type: message.type,
        payloadText,
      };

      nextLogIdRef.current += 1;
      return appendUniqueLogEntry(currentEntries, nextEntry);
    });

    setUnreadLogCount((count) => count + 1);
  }, []);

  const markLogsSeen = useCallback(() => {
    setUnreadLogCount(0);
  }, []);

  const handleMessage = useCallback(
    (event: WebViewMessageEvent) => {
      const message = parseEmbeddedMessage(event.nativeEvent.data);
      if (!message) {
        return;
      }

      setLatestMessage(message);

      const nextHeight = getEmbeddedMessageHeight(message);
      if (nextHeight !== null) {
        setWebViewHeight(nextHeight);
      }

      if (message.type === 'ready') {
        setIsPaymentReady(isReadyMessage(message));
      }

      appendLogEntry(message);
    },
    [appendLogEntry],
  );

  const handleSubmitPress = useCallback(() => {
    webViewRef.current?.postMessage(
      serializeNativeCommand(createSubmitPaymentCommand()),
    );
  }, []);

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