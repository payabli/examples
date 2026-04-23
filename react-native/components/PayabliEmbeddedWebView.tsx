import React from 'react';
import { StyleSheet, View } from 'react-native';
import {
  WebView,
  type WebViewMessageEvent,
} from 'react-native-webview';

import { type PayabliPaymentMethod } from '../hooks/usePayabliWebView';

type PayabliEmbeddedWebViewProps = {
  paymentMethod: PayabliPaymentMethod;
  htmlShell: string;
  injectedBootstrap: string;
  webViewRef: React.RefObject<WebView | null>;
  webViewHeight: number;
  onMessage: (event: WebViewMessageEvent) => void;
};

const PayabliEmbeddedWebView = ({
  paymentMethod,
  htmlShell,
  injectedBootstrap,
  webViewRef,
  webViewHeight,
  onMessage,
}: PayabliEmbeddedWebViewProps) => {
  return (
    <WebView
      key={paymentMethod}
      ref={webViewRef}
      originWhitelist={["*"]}
      source={{ html: htmlShell }}
      javaScriptEnabled
      domStorageEnabled
      startInLoadingState
      renderLoading={() => <View style={styles.loading} />}
      injectedJavaScript={injectedBootstrap}
      onMessage={onMessage}
      scrollEnabled={false}
      bounces={false}
      showsVerticalScrollIndicator={false}
      showsHorizontalScrollIndicator={false}
      automaticallyAdjustContentInsets={false}
      contentInsetAdjustmentBehavior="never"
      hideKeyboardAccessoryView
      keyboardDisplayRequiresUserAction
      allowsLinkPreview={false}
      containerStyle={styles.container}
      style={[styles.webView, { height: webViewHeight }]}
    />
  );
};

const styles = StyleSheet.create({
  container: {
    backgroundColor: 'transparent',
    marginBottom: 12,
  },
  webView: {
    minHeight: 1,
    backgroundColor: 'transparent',
  },
  loading: {
    minHeight: 360,
    backgroundColor: 'transparent',
  },
});

export default PayabliEmbeddedWebView;