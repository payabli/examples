



import React, { useState } from 'react';
import { StyleSheet, Text, View } from 'react-native';
import { WebView } from 'react-native-webview';
import { PAYABLI_API_KEY, PAYABLI_ENTRY_POINT } from '@env';

const payabliHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payabli Integration</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      background: #ffffff;
      font-family: -apple-system, BlinkMacSystemFont, sans-serif;
    }

    body {
      padding: 16px;
      box-sizing: border-box;
    }

    h1 {
      margin: 0 0 8px;
      font-size: 24px;
      line-height: 1.2;
      color: #111827;
    }

    p {
      margin: 0 0 20px;
      color: #4b5563;
      font-size: 14px;
    }

    #pay-component-1 {
      min-height: 420px;
      box-sizing: border-box;
    }

    .hidden {
      display: none;
    }

    #submit-btn {
      background: #4f46e5;
      color: #ffffff;
      padding: 12px 24px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 16px;
      font-size: 16px;
      font-weight: 600;
    }

    #submit-btn:disabled {
      background: #9ca3af;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <h1>Payment Form</h1>
  <p>Enter your payment information below:</p>
  <div id="pay-component-1"></div>
  <button id="submit-btn" class="hidden">Process Payment</button>
  <script src="https://embedded-component-sandbox.payabli.com/component.js" data-test></script>
  <script>
    let payComponent;
    const submitButton = document.getElementById('submit-btn');

    function postToApp(type, payload) {
      if (window.ReactNativeWebView) {
        window.ReactNativeWebView.postMessage(JSON.stringify({ type, payload }));
      }
    }

    const config = {
      type: 'methodEmbedded',
      rootContainer: 'pay-component-1',
      token: '${PAYABLI_API_KEY}',
      entryPoint: '${PAYABLI_ENTRY_POINT}',
      defaultOpen: 'card',
      temporaryToken: false,
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
            label: 'NAME ON CARD',
            size: 12,
            row: 0,
            order: 0,
          },
          cardNumber: {
            label: 'CARD NUMBER',
            size: 6,
            row: 1,
            order: 0,
          },
          cardExpirationDate: {
            label: 'EXPIRATION',
            size: 6,
            row: 1,
            order: 1,
          },
          cardCvv: {
            label: 'CVV',
            size: 6,
            row: 2,
            order: 0,
          },
          cardZipcode: {
            label: 'ZIP CODE',
            size: 6,
            row: 2,
            order: 1,
          },
        },
      },
      functionCallBackSuccess: function(response) {
        console.log('Payabli success', response);
        postToApp('success', response);
      },
      functionCallBackError: function(errors) {
        console.log('Payabli error', errors);
        postToApp('error', errors);
      },
      functionCallBackReady: function(data) {
        console.log('Payabli ready', data);
        postToApp('ready', data);
        if (data[1] === true) {
          submitButton.classList.remove('hidden');
        } else {
          submitButton.classList.add('hidden');
        }
      }
    };

    window.addEventListener('load', function() {
      payComponent = new PayabliComponent(config);
      postToApp('loaded', { status: 'initialized' });
    });

    submitButton.addEventListener('click', function() {
      postToApp('submit', { status: 'started' });
      payComponent.payabliExec('pay', {
        paymentDetails: {
          totalAmount: 100.0,
          serviceFee: 0,
          categories: [{
            label: 'payment',
            amount: 100.0,
            qty: 1,
          }],
        },
        customerData: {
          firstName: 'John',
          lastName: 'Doe',
          billingEmail: 'john.doe@example.com',
        },
      });
    });
  </script>
</body>
</html>`;

const PayabliCheckout = () => {
  const [paymentLog, setPaymentLog] = useState('Waiting for payment activity...');

  const handleMessage = (event: { nativeEvent: { data: string } }) => {
    try {
      const message = JSON.parse(event.nativeEvent.data);
      setPaymentLog(JSON.stringify(message, null, 2));
    } catch {
      setPaymentLog(event.nativeEvent.data);
    }
  };

  return (
    <View style={styles.container}>
      <WebView
        originWhitelist={["*"]}
        source={{ html: payabliHtml }}
        javaScriptEnabled
        domStorageEnabled
        startInLoadingState
        onMessage={handleMessage}
        style={styles.webview}
      />
      <View style={styles.logBox}>
        <Text style={styles.logTitle}>Payment Output</Text>
        <Text selectable style={styles.logText}>{paymentLog}</Text>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    width: '100%',
    minHeight: 520,
    flex: 1,
  },
  webview: {
    flex: 1,
    minHeight: 520,
    backgroundColor: '#ffffff',
  },
  logBox: {
    marginTop: 16,
    padding: 12,
    borderRadius: 8,
    backgroundColor: '#111827',
  },
  logTitle: {
    color: '#f9fafb',
    fontSize: 14,
    fontWeight: '600',
    marginBottom: 8,
  },
  logText: {
    color: '#d1d5db',
    fontSize: 12,
    lineHeight: 18,
  },
});

export default PayabliCheckout;