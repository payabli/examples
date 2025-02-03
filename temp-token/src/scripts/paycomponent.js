import { makeOneTimeTransaction } from "../pages/api/transaction";

const $store = index => window.Alpine.store(index);
const $ping = message => $store('sidebar').messages.push(message);

const fetchJSON = (url, body) => 
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(async res => {
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        const json = await res.json(); // Read the response body once
        return json;
    });

const getTemporaryToken = tempToken => {
    $ping(`### Status: Temp Token\n\nThe embedded component processed the payment information and generated a temporary token with a value of \`${tempToken}\`.\n\nThe temp token can be used [directly in a one-time payment](https://docs.payabli.com/developer-guides/tokenization-temporary-flow#option-2-process-a-payment) or [converted to a permanent token](https://docs.payabli.com/developer-guides/tokenization-temporary-flow#option-1-create-a-permanent-token).`);
    return Promise.resolve(tempToken)
}

const convertToPermanent = tempToken => {
    return fetchJSON('/api/conversion', { token: tempToken }).then(res => {
      const out = res.data.responseData.referenceId;
      $ping(`### Status: Stored Method\n\`\`\`\nPOST /api/TokenStorage/add\n\`\`\`\n\nThe temporary token was sent to Payabli's API and converted to a permanent token with a value of \`${out}\`.\n\nThis permanent token is referred to as \`storedMethodId\` in \`/api/MoneyIn\` endpoints and can be [used to process a transaction](https://docs.payabli.com/references/money-in-schemas#make-a-transaction-with-a-stored-payment-method).`);
      return out;
    });
};


const makeTransaction = method => {
    return fetchJSON('/api/transaction', { method: method }).then(res => {
      const out = res.data.responseData.referenceId
      $ping(`### Status: Transaction\n\`\`\`\nPOST /api/MoneyIn/getpaid\n\`\`\`\n\nThe permanent token was used to make a payment with a \`PaymentTransId\` of \`${out}\`. The \`PaymentTransId\` can be used to [query the transaction](https://docs.payabli.com/api-reference/query/get-list-of-transactions-for-an-entrypoint) to check information. The payment flow is now complete!`);
      return out
    });
};

const oneTimeTransaction = tempToken => {
    return fetchJSON('/api/transaction', { token: tempToken }).then(res => {
      const out = res.data.responseData.referenceId
      $ping(`### Status: Transaction\n\`\`\`\nPOST /api/MoneyIn/getpaid\n\`\`\`\n\nThe temporary token was used to make a ***one-time payment*** with a \`PaymentTransId\` of \`${out}\`. The \`PaymentTransId\` can be used to [query the transaction](https://docs.payabli.com/api-reference/query/get-list-of-transactions-for-an-entrypoint) to check information. The payment flow is now complete!`);
      return out
    });
};

const queryTransaction = transactionId => {
    return fetchJSON('/api/query', { transactionId: transactionId }).then(res => {
      const out = res.data.Records[0]
      $ping(`### Status: Query\n\`\`\`\nGET /api/Query/transactions\n\`\`\`\n\nThe transaction was successfully queried:\n\`\`\`\n${JSON.stringify(out)}\n\`\`\`\n\nCheck the \`PaymentTransId\` field to verify the transaction:\n\`\`\`\n${JSON.stringify(out.PaymentTransId)}\n\`\`\`\n\nThe \`PaymentTransId\` from the query and the transaction should match.\n\nFinally, check that the \`orderId\` field is present:\n\`\`\`\n${JSON.stringify(out.OrderId)}\n\`\`\`\n\nIf you see the \`orderId\` field, then the temporary token flow was successfully implemented!`);
      return out
    });
};

const payabliToken = "o.R+3YBWVpwylYodK6bxJZYlvYmhgDH5/Biy2xdSqXhWR1cT4kHF62ELgT1/CHuyN6ULv+lDcDZBloaADozDr7brcmIcCwYVzpuvyLNBjgkGJNCgDuOQ+4lRDCgJCefxHrOqBQH0rOCxuqdXs9OY1lk0H0K/PomiZZ0DHUdxnV7jXn+54KG76bWX8T+9pcGXYR32fpffPi/AEgGMH9XWmQpl8ERep44ZO1L9ozPkyaOD1ENZwzNRqrIGrEVozbkGGF19BYnNYx+MgbumMI4y1ou7yhHPEoFUHna8dnSYHsb6RkjZeHJFlZCituRLswEZsNOkhDBhFUKP4PQ7ZT0T57dU9aR4ja88ynbFvc2eFoSUtZd9GWK4kn1awU9CIi19YgFPSo9/43srT1B47cxZ3xj+PK8ulE1B7Od14mtLzKVd+i5KdSuFGKuq2SGPEhMuZaIXI6hEVPYk3oDmeSTRn/O1ycYynTGNPkmC/bqWXdiLyRtpIl4i2AP3LFkO14aCp8.Zd2dL8uS1ToCTc8lXg4dozOcUcbPYj42UUWKHSqAB4Y="
const payabliEntrypoint = "41035afaa7";

var payabliConfig0 = {
  type: "methodEmbedded",
  rootContainer: "pay-component-1",
  customCssUrl: "http://localhost:4321/paycomponent-dark.css",
  defaultOpen: "card", 
  token: payabliToken,   
  entryPoint: payabliEntrypoint,
  card: {
    enabled: true,
    amex: true,
    discover: true,
    visa: true,
    mastercard: true,
    jcb: true,
    diners: true,
    inputs: {
      //here we are customizing the input fields
      cardHolderName: {
        label: "Cardholder Name",
        placeholder: "John Doe",
        floating: false,
        size: 12,
        row: 0,
        order: 0,
        value: "John Doe",
      },
      cardNumber: {
        label: "Card Number",
        placeholder: "1234 1234 1234 1234",
        floating: false,
        size: 6,
        row: 1,
        order: 0,
        value: "5146315000000055",
      },
      cardExpirationDate: {
        label: "Expiration Date",
        placeholder: "MM/YY",
        floating: false,
        size: 6,
        row: 1,
        order: 1,
        value: "12/34",
      },
      cardCvv: {
        label: "CVV",
        placeholder: "123",
        floating: false,
        size: 6,
        row: 2,
        order: 0,
        value: "998",
      },
      cardZipcode: {
        label: "Postal Code",
        placeholder: "12345",
        floating: false,
        size: 6,
        row: 2,
        order: 1,
        country: ["us", "ca"],
        value: "12345",
      },
    },
  },
  ach: {
    enabled: false,
    checking: true,
    savings: true,
  },
  customerData: {
    customerId: 41792,
  },
  functionCallBackSuccess: function (response) {
    // This callback covers both 2XX and 4XX responses
    switch (response.responseText) {
      case "Success":
        // Tokenization was successful
        if (document.getElementById("saved-payment").checked) {
          getTemporaryToken(response.responseData.referenceId)
          .then(convertToPermanent)
          .then(makeTransaction)
          .then(queryTransaction)
          .catch(error => console.error('Error:', error));
        } else {
          getTemporaryToken(response.responseData.referenceId)
          .then(oneTimeTransaction)
          .then(queryTransaction)
          .catch(error => console.error('Error:', error));
        }
        break;
      case "Declined":
        // Transaction or tokenization failed due to processor decline or validation errors
        alert(`Declined: ${response.responseData.resultText}`);
        paycomponent0.payabliExec("reinit");
        resetLoadingButton();
        break;
      default:
        // Other response text. These are normally errors with Payabli internal validations
        alert(`Error: ${response.responseText}`);
        paycomponent0.payabliExec("reinit");
        resetLoadingButton();
        break;
    }
  },
  functionCallBackReady: function (data) {
    //Callback function to capture the ready state of the component.
    var btn = document.getElementById("payabli-checkout");
    if (data[1] === true) {
      btn.removeAttribute("disabled");
    } else {
      if (!btn.attributes["disabled"]) {
        btn.setAttribute("disabled", "true");
      }
    }
  },
  functionCallBackError: function (errors) {
    // This callback covers 5XX response or parsing errors
    console.log(errors);
    paycomponent0.payabliExec("reinit");
    resetLoadingButton();
  }
}

var paycomponent0 = new PayabliComponent(payabliConfig0);

function executeAction() {
  paycomponent0.payabliExec("method", {
    paymentDetails: {
      totalAmount: 410.37,
      serviceFee: 0,
      categories: [
        {
          label: "Premium Wireless Headphones",
          amount: 199.99,
          qty: 1
        },
        {
          label: "Smart Fitness Tracker",
          amount: 89.99,
          qty: 2
        }
      ]
    }
  });
}

document.getElementById("payabli-checkout").addEventListener("click", executeAction);
