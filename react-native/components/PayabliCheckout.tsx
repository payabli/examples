



import React, { useEffect, useState } from 'react';
import { Button, Pressable, ScrollView, StyleSheet, Text, View } from 'react-native';
import { WebView } from 'react-native-webview';
import {
  usePayabliWebView,
  type PayabliPaymentMethod,
} from '../hooks/usePayabliWebView';

const PayabliCheckout = () => {
  const [paymentMethod, setPaymentMethod] = useState<PayabliPaymentMethod>('card');
  const [isLogOpen, setIsLogOpen] = useState(false);
  const {
    htmlShell,
    injectedBootstrap,
    webViewRef,
    isPaymentReady,
    webViewHeight,
    logEntries,
    unreadLogCount,
    handleMessage,
    handleSubmitPress,
    markLogsSeen,
  } = usePayabliWebView(paymentMethod);

  const toggleLog = () => {
    setIsLogOpen((currentValue) => {
      const nextValue = !currentValue;
      if (nextValue) {
        markLogsSeen();
      }
      return nextValue;
    });
  };

  useEffect(() => {
    if (isLogOpen && unreadLogCount > 0) {
      markLogsSeen();
    }
  }, [isLogOpen, unreadLogCount, markLogsSeen]);

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Payment Form</Text>
      <Text style={styles.subtitle}>Enter your payment information below.</Text>
      <View style={styles.tabs}>
        <Pressable
          onPress={() => setPaymentMethod('card')}
          style={[
            styles.tab,
            paymentMethod === 'card' && styles.tabActive,
          ]}
        >
          <Text
            style={[
              styles.tabText,
              paymentMethod === 'card' && styles.tabTextActive,
            ]}
          >
            Card
          </Text>
        </Pressable>
        <Pressable
          onPress={() => setPaymentMethod('ach')}
          style={[
            styles.tab,
            paymentMethod === 'ach' && styles.tabActive,
          ]}
        >
          <Text
            style={[
              styles.tabText,
              paymentMethod === 'ach' && styles.tabTextActive,
            ]}
          >
            ACH
          </Text>
        </Pressable>
        <Pressable
          onPress={() => setPaymentMethod('rdc')}
          style={[
            styles.tab,
            paymentMethod === 'rdc' && styles.tabActive,
          ]}
        >
          <Text
            style={[
              styles.tabText,
              paymentMethod === 'rdc' && styles.tabTextActive,
            ]}
          >
            RDC
          </Text>
        </Pressable>
      </View>
      <WebView
        key={paymentMethod}
        ref={webViewRef}
        originWhitelist={["*"]}
        source={{ html: htmlShell }}
        javaScriptEnabled
        domStorageEnabled
        startInLoadingState
        renderLoading={() => <View style={styles.webviewLoading} />}
        injectedJavaScript={injectedBootstrap}
        onMessage={handleMessage}
        scrollEnabled={false}
        bounces={false}
        showsVerticalScrollIndicator={false}
        showsHorizontalScrollIndicator={false}
        automaticallyAdjustContentInsets={false}
        contentInsetAdjustmentBehavior="never"
        hideKeyboardAccessoryView
        keyboardDisplayRequiresUserAction
        allowsLinkPreview={false}
        containerStyle={styles.webviewContainer}
        style={[styles.webview, { height: webViewHeight }]}
      />
      <View style={styles.actionRow}>
        <View style={styles.submitButtonWrap}>
          <Button
            title="Process Payment"
            onPress={handleSubmitPress}
            disabled={!isPaymentReady}
          />
        </View>
        <Pressable onPress={toggleLog} style={styles.logToggle}>
          <Text style={styles.logToggleIcon}>{'>_'}</Text>
          <Text style={styles.logToggleLabel}>Logs</Text>
          {unreadLogCount > 0 ? (
            <View style={styles.logBadge}>
              <Text style={styles.logBadgeText}>
                {unreadLogCount > 99 ? '99+' : String(unreadLogCount)}
              </Text>
            </View>
          ) : null}
        </Pressable>
      </View>
      {isLogOpen ? (
        <View style={styles.logPopover}>
          <View style={styles.logPopoverHeader}>
            <Text style={styles.logTitle}>Event Log</Text>
            <Pressable onPress={toggleLog}>
              <Text style={styles.logClose}>Close</Text>
            </Pressable>
          </View>
          <ScrollView style={styles.logScroll} contentContainerStyle={styles.logScrollContent}>
            {logEntries.length === 0 ? (
              <Text style={styles.logEmpty}>No events yet.</Text>
            ) : (
              logEntries.map((entry) => (
                <View key={entry.id} style={styles.logEntry}>
                  <Text style={styles.logEntryType}>{entry.type}</Text>
                  <Text selectable style={styles.logText}>{entry.payloadText}</Text>
                </View>
              ))
            )}
          </ScrollView>
        </View>
      ) : null}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    width: '100%',
    minHeight: 520,
    flex: 1,
  },
  title: {
    fontSize: 24,
    lineHeight: 30,
    fontWeight: '700',
    color: '#111827',
    marginBottom: 8,
  },
  subtitle: {
    color: '#4b5563',
    fontSize: 14,
    marginBottom: 16,
  },
  tabs: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 16,
  },
  tab: {
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 999,
    backgroundColor: '#e5e7eb',
  },
  tabActive: {
    backgroundColor: '#111827',
  },
  tabText: {
    color: '#374151',
    fontSize: 14,
    fontWeight: '600',
  },
  tabTextActive: {
    color: '#f9fafb',
  },
  webview: {
    minHeight: 1,
    backgroundColor: 'transparent',
  },
  webviewContainer: {
    backgroundColor: 'transparent',
  },
  webviewLoading: {
    flex: 1,
    backgroundColor: 'transparent',
  },
  actionRow: {
    marginTop: 16,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    gap: 12,
  },
  submitButtonWrap: {
    flex: 1,
  },
  logToggle: {
    minWidth: 92,
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 999,
    backgroundColor: '#111827',
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
    position: 'relative',
  },
  logToggleIcon: {
    color: '#f9fafb',
    fontFamily: 'Courier',
    fontSize: 13,
    fontWeight: '700',
  },
  logToggleLabel: {
    color: '#f9fafb',
    fontSize: 14,
    fontWeight: '600',
  },
  logBadge: {
    position: 'absolute',
    top: -6,
    right: -4,
    minWidth: 22,
    height: 22,
    borderRadius: 11,
    backgroundColor: '#ef4444',
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: 6,
  },
  logBadgeText: {
    color: '#ffffff',
    fontSize: 11,
    fontWeight: '700',
  },
  logPopover: {
    position: 'absolute',
    top: 110,
    right: 0,
    width: '92%',
    maxWidth: 420,
    maxHeight: 320,
    borderRadius: 16,
    padding: 14,
    backgroundColor: '#0f172a',
    borderWidth: 1,
    borderColor: '#1f2937',
    zIndex: 20,
    elevation: 8,
    shadowColor: '#000000',
    shadowOpacity: 0.22,
    shadowRadius: 18,
    shadowOffset: { width: 0, height: 10 },
  },
  logPopoverHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  logClose: {
    color: '#93c5fd',
    fontSize: 13,
    fontWeight: '600',
  },
  logTitle: {
    color: '#f9fafb',
    fontSize: 14,
    fontWeight: '600',
    marginBottom: 8,
  },
  logScroll: {
    maxHeight: 250,
  },
  logScrollContent: {
    gap: 10,
    paddingBottom: 4,
  },
  logEmpty: {
    color: '#9ca3af',
    fontSize: 13,
  },
  logEntry: {
    borderRadius: 10,
    backgroundColor: '#111827',
    padding: 10,
  },
  logEntryType: {
    color: '#93c5fd',
    fontSize: 12,
    fontWeight: '700',
    textTransform: 'uppercase',
    marginBottom: 6,
  },
  logText: {
    color: '#d1d5db',
    fontSize: 12,
    lineHeight: 18,
  },
});

export default PayabliCheckout;