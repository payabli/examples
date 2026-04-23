import React, { useEffect, useState } from 'react';
import {
  Button,
  Pressable,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  View,
} from 'react-native';
import { WebView } from 'react-native-webview';
import {
  usePayabliWebView,
} from '../hooks/usePayabliWebView';
import {
  formatCurrency,
  parseCurrency,
  parseInteger,
  useCheckoutDemo,
} from '../hooks/useCheckoutDemo';

type PayabliCheckoutProps = {
  onBackToHome: () => void;
};

const StepSection = ({ currentStep }: { currentStep: 1 | 2 | 3 }) => {
  const steps = [
    { number: 1, label: 'Review' },
    { number: 2, label: 'Payment' },
    { number: 3, label: 'Result' },
  ] as const;

  return (
    <View style={styles.stepSection}>
      {steps.map((step, index) => {
        const isActive = step.number === currentStep;
        const isComplete = step.number < currentStep;

        return (
          <React.Fragment key={step.number}>
            <View style={styles.stepItem}>
              <View
                style={[
                  styles.stepCircle,
                  isActive && styles.stepCircleActive,
                  isComplete && styles.stepCircleComplete,
                ]}
              >
                <Text
                  style={[
                    styles.stepCircleText,
                    (isActive || isComplete) && styles.stepCircleTextActive,
                  ]}
                >
                  {step.number}
                </Text>
              </View>
              <Text style={[styles.stepLabel, isActive && styles.stepLabelActive]}>
                {step.label}
              </Text>
            </View>
            {index < steps.length - 1 ? <View style={styles.stepDivider} /> : null}
          </React.Fragment>
        );
      })}
    </View>
  );
};

const PayabliCheckout = ({ onBackToHome }: PayabliCheckoutProps) => {
  const [isLogOpen, setIsLogOpen] = useState(false);
  const {
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
  } = useCheckoutDemo();
  const {
    htmlShell,
    injectedBootstrap,
    webViewRef,
    isPaymentReady,
    webViewHeight,
    logEntries,
    latestMessage,
    unreadLogCount,
    handleMessage,
    handleSubmitPress,
    markLogsSeen,
  } = usePayabliWebView(paymentMethod, paymentRequest);

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

  useEffect(() => {
    handleEmbeddedMessage(latestMessage);
  }, [handleEmbeddedMessage, latestMessage]);

  useEffect(() => {
    if (screen === 'result') {
      setIsLogOpen(false);
    }
  }, [screen]);

  const handleContinue = () => {
    handleContinueToPayment();
  };

  const handleProcess = () => {
    handleProcessPayment();
    handleSubmitPress();
  };

  const handleReset = () => {
    handleStartOver();
  };

  if (screen === 'review') {
    return (
      <View style={styles.container}>
        <StepSection currentStep={1} />
        <View style={styles.headerRow}>
          <View style={styles.headerTextWrap}>
            <Text style={styles.eyebrow}>Checkout</Text>
            <Text style={styles.title}>Review Your Order</Text>
            <Text style={styles.subtitle}>
              Confirm the order details before continuing to payment.
            </Text>
          </View>
          <Pressable onPress={onBackToHome} style={styles.headerAction}>
            <Text style={styles.headerActionText}>Back to Home</Text>
          </Pressable>
        </View>
        <View style={styles.heroCard}>
          <Text style={styles.heroTitle}>Canvas Weekender Tote</Text>
          <Text style={styles.heroDescription}>
            Step one of checkout is the review section. Update the order and customer details here.
          </Text>
          <Text style={styles.heroPrice}>
            Total due: {formatCurrency(parseCurrency(checkoutDraft.totalAmount, 0))}
          </Text>
        </View>
        <View style={styles.builderCard}>
          <Text style={styles.sectionTitle}>Order Details</Text>
          <View style={styles.formRow}>
            <View style={styles.fieldHalf}>
              <Text style={styles.fieldLabel}>Order Total</Text>
              <TextInput
                value={checkoutDraft.totalAmount}
                onChangeText={(value) => updateDraftField('totalAmount', value)}
                keyboardType="decimal-pad"
                style={styles.input}
              />
            </View>
            <View style={styles.fieldHalf}>
              <Text style={styles.fieldLabel}>Service Fee</Text>
              <TextInput
                value={checkoutDraft.serviceFee}
                onChangeText={(value) => updateDraftField('serviceFee', value)}
                keyboardType="decimal-pad"
                style={styles.input}
              />
            </View>
          </View>
          <View style={styles.formRow}>
            <View style={styles.fieldWide}>
              <Text style={styles.fieldLabel}>Item Name</Text>
              <TextInput
                value={checkoutDraft.categoryLabel}
                onChangeText={(value) => updateDraftField('categoryLabel', value)}
                style={styles.input}
              />
            </View>
          </View>
          <View style={styles.formRow}>
            <View style={styles.fieldHalf}>
              <Text style={styles.fieldLabel}>Item Price</Text>
              <TextInput
                value={checkoutDraft.categoryAmount}
                onChangeText={(value) => updateDraftField('categoryAmount', value)}
                keyboardType="decimal-pad"
                style={styles.input}
              />
            </View>
            <View style={styles.fieldHalf}>
              <Text style={styles.fieldLabel}>Quantity</Text>
              <TextInput
                value={checkoutDraft.quantity}
                onChangeText={(value) => updateDraftField('quantity', value)}
                keyboardType="number-pad"
                style={styles.input}
              />
            </View>
          </View>
        </View>
        <View style={styles.builderCard}>
          <Text style={styles.sectionTitle}>Contact Details</Text>
          <View style={styles.formRow}>
            <View style={styles.fieldHalf}>
              <Text style={styles.fieldLabel}>First Name</Text>
              <TextInput
                value={checkoutDraft.firstName}
                onChangeText={(value) => updateDraftField('firstName', value)}
                style={styles.input}
              />
            </View>
            <View style={styles.fieldHalf}>
              <Text style={styles.fieldLabel}>Last Name</Text>
              <TextInput
                value={checkoutDraft.lastName}
                onChangeText={(value) => updateDraftField('lastName', value)}
                style={styles.input}
              />
            </View>
          </View>
          <View style={styles.formRow}>
            <View style={styles.fieldWide}>
              <Text style={styles.fieldLabel}>Billing Email</Text>
              <TextInput
                value={checkoutDraft.billingEmail}
                onChangeText={(value) => updateDraftField('billingEmail', value)}
                autoCapitalize="none"
                keyboardType="email-address"
                style={styles.input}
              />
            </View>
          </View>
        </View>
        <View style={styles.summaryCard}>
          <Text style={styles.sectionTitle}>Order Summary</Text>
          <Text style={styles.summaryLine}>
            {checkoutDraft.categoryLabel || 'Canvas Weekender Tote'} x {parseInteger(checkoutDraft.quantity, 1)}
          </Text>
          <Text style={styles.summaryLine}>
            Merchandise: {formatCurrency(parseCurrency(checkoutDraft.categoryAmount, 0))}
          </Text>
          <Text style={styles.summaryLine}>
            Service Fee: {formatCurrency(parseCurrency(checkoutDraft.serviceFee, 0))}
          </Text>
          <Text style={styles.summaryTotal}>
            Total: {formatCurrency(parseCurrency(checkoutDraft.totalAmount, 0))}
          </Text>
        </View>
        <Button title="Continue to Payment" onPress={handleContinue} />
      </View>
    );
  }

  if (screen === 'result' && resultState) {
    return (
      <View style={styles.container}>
        <StepSection currentStep={3} />
        <View
          style={[
            styles.resultCard,
            resultState.kind === 'success' ? styles.resultSuccess : styles.resultError,
          ]}
        >
          <View
            style={[
              styles.resultStatusBadge,
              resultState.kind === 'success'
                ? styles.resultStatusBadgeSuccess
                : styles.resultStatusBadgeError,
            ]}
          >
            <Text style={styles.resultStatusIcon}>
              {resultState.kind === 'success' ? '✓' : 'X'}
            </Text>
          </View>
          <Text style={styles.resultEyebrow}>
            {resultState.kind === 'success' ? 'Order Complete' : 'Payment Failed'}
          </Text>
          <Text style={styles.resultTitle}>{resultState.title}</Text>
          <Text style={styles.resultMessage}>{resultState.message}</Text>
          <View style={styles.resultActionStack}>
            <Button title="Return Home" onPress={onBackToHome} />
            <View style={styles.resultSecondaryAction}>
              <Button title="Start Over" onPress={handleReset} />
            </View>
          </View>
        </View>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <StepSection currentStep={2} />
      <View style={styles.headerRow}>
        <View style={styles.headerTextWrap}>
          <Text style={styles.eyebrow}>Checkout</Text>
          <Text style={styles.title}>Payment</Text>
          <Text style={styles.subtitle}>
            Complete a {formatCurrency(paymentRequest.paymentDetails.totalAmount)} order using
            {' '}card, ACH, or RDC.
          </Text>
        </View>
        <Pressable onPress={handleBackToReview} style={styles.headerAction}>
          <Text style={styles.headerActionText}>Back to Review</Text>
        </Pressable>
      </View>
      <View style={styles.tabs}>
        <Pressable
          onPress={() => setPaymentMethod('card')}
          style={[styles.tab, paymentMethod === 'card' && styles.tabActive]}
        >
          <Text style={[styles.tabText, paymentMethod === 'card' && styles.tabTextActive]}>
            Card
          </Text>
        </Pressable>
        <Pressable
          onPress={() => setPaymentMethod('ach')}
          style={[styles.tab, paymentMethod === 'ach' && styles.tabActive]}
        >
          <Text style={[styles.tabText, paymentMethod === 'ach' && styles.tabTextActive]}>
            ACH
          </Text>
        </Pressable>
        <Pressable
          onPress={() => setPaymentMethod('rdc')}
          style={[styles.tab, paymentMethod === 'rdc' && styles.tabActive]}
        >
          <Text style={[styles.tabText, paymentMethod === 'rdc' && styles.tabTextActive]}>
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
            onPress={handleProcess}
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
  stepSection: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 24,
  },
  stepItem: {
    alignItems: 'center',
  },
  stepCircle: {
    width: 34,
    height: 34,
    borderRadius: 17,
    borderWidth: 1,
    borderColor: '#d1d5db',
    backgroundColor: '#ffffff',
    alignItems: 'center',
    justifyContent: 'center',
  },
  stepCircleActive: {
    backgroundColor: '#111827',
    borderColor: '#111827',
  },
  stepCircleComplete: {
    backgroundColor: '#dbeafe',
    borderColor: '#93c5fd',
  },
  stepCircleText: {
    color: '#6b7280',
    fontSize: 13,
    fontWeight: '700',
  },
  stepCircleTextActive: {
    color: '#ffffff',
  },
  stepLabel: {
    marginTop: 8,
    fontSize: 12,
    color: '#6b7280',
    fontWeight: '600',
  },
  stepLabelActive: {
    color: '#111827',
  },
  stepDivider: {
    flex: 1,
    height: 1,
    backgroundColor: '#d1d5db',
    marginHorizontal: 10,
    marginBottom: 18,
  },
  eyebrow: {
    color: '#2563eb',
    fontSize: 12,
    fontWeight: '700',
    textTransform: 'uppercase',
    letterSpacing: 0.8,
    marginBottom: 8,
  },
  title: {
    fontSize: 28,
    lineHeight: 34,
    fontWeight: '700',
    color: '#111827',
    marginBottom: 8,
  },
  subtitle: {
    color: '#4b5563',
    fontSize: 15,
    lineHeight: 22,
    marginBottom: 20,
  },
  headerRow: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    justifyContent: 'space-between',
    gap: 16,
    marginBottom: 16,
  },
  headerTextWrap: {
    flex: 1,
  },
  headerAction: {
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 999,
    backgroundColor: '#e5e7eb',
  },
  headerActionText: {
    color: '#111827',
    fontSize: 13,
    fontWeight: '700',
  },
  heroCard: {
    backgroundColor: '#111827',
    borderRadius: 20,
    padding: 20,
    marginBottom: 16,
  },
  heroTitle: {
    color: '#f9fafb',
    fontSize: 22,
    fontWeight: '700',
    marginBottom: 8,
  },
  heroDescription: {
    color: '#d1d5db',
    fontSize: 14,
    lineHeight: 20,
    marginBottom: 14,
  },
  heroPrice: {
    color: '#93c5fd',
    fontSize: 18,
    fontWeight: '700',
  },
  builderCard: {
    backgroundColor: '#ffffff',
    borderRadius: 20,
    padding: 18,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  summaryCard: {
    backgroundColor: '#f8fafc',
    borderRadius: 20,
    padding: 18,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: '700',
    color: '#111827',
    marginBottom: 12,
  },
  formRow: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 12,
  },
  fieldHalf: {
    flex: 1,
  },
  fieldWide: {
    flex: 1,
  },
  fieldLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: '#4b5563',
    marginBottom: 6,
    textTransform: 'uppercase',
  },
  input: {
    borderWidth: 1,
    borderColor: '#d1d5db',
    borderRadius: 10,
    paddingHorizontal: 12,
    paddingVertical: 10,
    backgroundColor: '#ffffff',
    color: '#111827',
  },
  summaryLine: {
    color: '#1f2937',
    fontSize: 14,
    marginBottom: 6,
  },
  summaryTotal: {
    color: '#111827',
    fontSize: 18,
    fontWeight: '700',
    marginTop: 8,
  },
  tabs: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 16,
    flexWrap: 'wrap',
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
  webviewContainer: {
    backgroundColor: 'transparent',
    marginBottom: 12,
  },
  webview: {
    minHeight: 1,
    backgroundColor: 'transparent',
  },
  webviewLoading: {
    minHeight: 360,
    backgroundColor: 'transparent',
  },
  actionRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  submitButtonWrap: {
    flex: 1,
  },
  logToggle: {
    minWidth: 88,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 6,
    paddingHorizontal: 14,
    paddingVertical: 12,
    borderRadius: 999,
    backgroundColor: '#111827',
  },
  logToggleIcon: {
    color: '#f9fafb',
    fontSize: 12,
    fontWeight: '700',
  },
  logToggleLabel: {
    color: '#f9fafb',
    fontSize: 13,
    fontWeight: '700',
  },
  logBadge: {
    minWidth: 20,
    paddingHorizontal: 5,
    paddingVertical: 2,
    borderRadius: 999,
    backgroundColor: '#ef4444',
    alignItems: 'center',
  },
  logBadgeText: {
    color: '#ffffff',
    fontSize: 11,
    fontWeight: '700',
  },
  logPopover: {
    marginTop: 16,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#d1d5db',
    backgroundColor: '#030712',
    overflow: 'hidden',
  },
  logPopoverHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    paddingVertical: 14,
    borderBottomWidth: 1,
    borderBottomColor: '#1f2937',
  },
  logTitle: {
    color: '#f9fafb',
    fontSize: 15,
    fontWeight: '700',
  },
  logClose: {
    color: '#93c5fd',
    fontSize: 13,
    fontWeight: '700',
  },
  logScroll: {
    maxHeight: 260,
  },
  logScrollContent: {
    padding: 16,
    gap: 10,
  },
  logEmpty: {
    color: '#9ca3af',
    fontSize: 13,
  },
  logEntry: {
    gap: 4,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#111827',
  },
  logEntryType: {
    color: '#93c5fd',
    fontSize: 12,
    fontWeight: '700',
    textTransform: 'uppercase',
  },
  logText: {
    color: '#e5e7eb',
    fontSize: 12,
    lineHeight: 18,
  },
  resultCard: {
    borderRadius: 24,
    padding: 24,
    borderWidth: 1,
  },
  resultStatusBadge: {
    width: 72,
    height: 72,
    borderRadius: 36,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 18,
  },
  resultStatusBadgeSuccess: {
    backgroundColor: '#16a34a',
  },
  resultStatusBadgeError: {
    backgroundColor: '#dc2626',
  },
  resultStatusIcon: {
    color: '#ffffff',
    fontSize: 36,
    lineHeight: 40,
    fontWeight: '800',
  },
  resultSuccess: {
    backgroundColor: '#eff6ff',
    borderColor: '#bfdbfe',
  },
  resultError: {
    backgroundColor: '#fef2f2',
    borderColor: '#fecaca',
  },
  resultEyebrow: {
    color: '#374151',
    fontSize: 12,
    fontWeight: '700',
    textTransform: 'uppercase',
    letterSpacing: 0.8,
    marginBottom: 8,
  },
  resultTitle: {
    color: '#111827',
    fontSize: 30,
    lineHeight: 36,
    fontWeight: '700',
    marginBottom: 12,
  },
  resultMessage: {
    color: '#374151',
    fontSize: 15,
    lineHeight: 22,
    marginBottom: 20,
  },
  resultActionStack: {
    gap: 12,
  },
  resultSecondaryAction: {
    opacity: 0.95,
  },
});

export default PayabliCheckout;