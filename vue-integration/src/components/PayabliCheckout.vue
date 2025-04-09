<template>
  <div>
    <div id="pay-component-1"></div>
    <button id="btnx" class="hidden" @click="payabliExec">Pay</button>
    <button @click.prevent="switchToJohnnyDover">Switch to Johnny Dover</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePayabli } from '../composables/usePayabli.ts';

const token = "o.z8j8aaztW9tUtUg4dlVeYAx+L2MazOFGr0DY8yuK3u79MCYlGK4/q0t5AD1UgLAjXOohnxN8VTZfPswyZcwtChGNn1a8jFMmYWHmLN2cPDW9IrBt1RtrSuu+85HJI+4kML5sIk9SYvULDAU2k0X0E1KFYcPwjmmkUjktrEGtz48XCUM70aKUupkrTh8nL7CXpAXATzVUZ2gEld9jGINwECPPLWmu+cZ4CJb7QMJxnzKFD073+nq/eL+pMth7+u/SkmAWC0+jn8y+Lf6T5Q5PqB6wN7Mvosp8g7U7lbEW2wC0DA92pjblfDHVJOQUkjgT7B1GvryMokLvBjoiaLhKa55iKZE1YDlyqruILkoNF+zGSPS9r17qU6w4ziKhoMdSPzPBJBlLhQhz3MVANXbjfEfJwmtr/JJ1uStUfBFJ710cS1x7goxMJO/cl+q+LVtPy788EKFkgMc5OjfBNCsNL+dBDVbK5CiIJUSbOFzdqdjY/VJ14MEodsHYOwMAjuF4.KRFMeEj0SOur8MLZ362c/UZ/U/Az3CSUkr3/8EVDE6Y=";
const entryPoint = "bozeman-aikido";
const rootContainer = "pay-component-1";
const payabliButton = "btnx";

const [payabliConfig, payabliExec] = usePayabli({
  type: "methodEmbedded",
  rootContainer: rootContainer,
  defaultOpen: 'card',
  token: token,
  entryPoint: entryPoint,
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
        label: "NAME ON CARD",
        placeholder: "",
        floating: false,
        value: "John Doe",
        size: 12,
        row: 0,
        order: 0
      },
      cardNumber: {
        label: "CARD NUMBER",
        placeholder: "1234 1234 1234 1234",
        floating: false,
        size: 6,
        row: 1,
        order: 0
      },
      cardExpirationDate: {
        label: "EXPIRATION DATE",
        placeholder: "MM/YY",
        floating: false,
        size: 6,
        row: 1,
        order: 1
      },
      cardCvv: {
        label: "CVV/CVC",
        placeholder: "CVV/CVC",
        floating: false,
        size: 6,
        row: 2,
        order: 0,
      },
      cardZipcode: {
        label: "ZIP/POSTAL CODE",
        placeholder: "ZIP/POSTAL CODE",
        floating: false,
        size: 6,
        row: 2,
        order: 1,
        country: ["us", "ca"],
      }
    }
  },
  ach: {
    enabled: false,
    checking: true,
    savings: true
  },
  customerData: {
    customerNumber: "00001",
    firstName: "John",
    lastName: "Doe",
    billingEmail: "johndoe@email.com"
  },
  functionCallBackSuccess: (response) => {
    const containerEl = document.getElementById(rootContainer);
    const responseText = JSON.stringify(response.responseText);
    const responseData = JSON.stringify(response.responseData);
    alert(responseText + " " + responseData);
    containerEl.innerHTML += `
      <hr/>
      <p><b>Embedded Component Response:</b></p>
      <p>${responseText}</p>
      <p>${responseData}</p>
      <hr/>
    `;
  },
  functionCallBackReady: (data) => {
    const btn = document.getElementById(payabliButton);
    if (data[1] === true && btn) {
      btn.classList.remove("hidden");
    } else if (btn) {
      btn.classList.add("hidden");
    }
  },
  functionCallBackError: (errors) => {
    alert('Error!');
    console.log(errors);
  }
},
"pay", {
  paymentDetails: {
    totalAmount: 100,
    serviceFee: 0,
    categories: [
      {
        label: "payment",
        amount: 100,
        qty: 1,
      },
    ],
  },
});

const switchToJohnnyDover = () => {
  payabliConfig.card.inputs.cardHolderName.value = "Johnny Dover";
};
</script>

<style scoped>
.hidden {
  display: none;
}
</style>
