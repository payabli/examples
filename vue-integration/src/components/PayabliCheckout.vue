
<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-zinc-900 text-zinc-100 p-4">
    <div class="w-full max-w-md bg-zinc-800 rounded-xl shadow-lg p-8 space-y-6">

      <!-- Header -->
      <div class="flex items-center justify-center gap-3 mb-5">
        <img src="/payabli-logo.png" alt="Payabli" class="h-10 w-auto object-contain" />
        <span class="text-zinc-500 text-lg">+</span>
        <img src="/vue-logo.svg" alt="Vue" class="h-8 w-auto object-contain" />
      </div>
      <h2 class="text-2xl font-bold mb-2 text-center">Payabli Embedded Checkout</h2>

      <div class="flex flex-col gap-5 px-3">
        <!-- Country toggle -->
        <div class="flex flex-col gap-2">
          <span class="text-sm font-normal text-zinc-400">Country</span>
          <div class="relative flex items-center bg-zinc-900 rounded-full p-1">
            <span
              class="absolute top-1 bottom-1 rounded-full bg-green-500 transition-all duration-300 ease-in-out"
              :style="{
                left:  country === 'us' ? '4px' : '50%',
                right: country === 'ca' ? '4px' : '50%',
              }"
            />
            <button
              v-for="c in ['us', 'ca']"
              :key="c"
              @click="setCountry(c)"
              class="relative z-10 flex-1 flex items-center justify-center gap-2 py-1.5 rounded-full text-sm font-medium transition-colors duration-300"
              :class="country === c ? 'text-white' : 'text-zinc-400 hover:text-zinc-200'"
            >
              <span>{{ c === 'us' ? 'US' : 'CA' }}</span>
              <span class="text-xs text-zinc-400">{{ c === 'us' ? 'ZIP Code' : 'Postal Code' }}</span>
            </button>
          </div>
        </div>

        <!-- Card network toggles -->
        <div class="flex flex-col gap-2">
          <span class="text-sm font-normal text-zinc-400">Accepted Networks</span>
          <div class="flex gap-2">
            <button
              v-for="net in NETWORKS"
              :key="net.id"
              @click="toggleNetwork(net.id)"
              class="flex-1 py-1.5 rounded-lg border text-xs font-semibold transition-all duration-200"
              :class="networks[net.id]
                ? 'border-green-500 bg-green-500/10 text-green-300'
                : 'border-zinc-700 bg-zinc-900 text-zinc-500 line-through'"
            >
              {{ net.label }}
            </button>
          </div>
        </div>

        <!-- Amount -->
        <label class="flex flex-col gap-1">
          <span class="font-normal">Amount</span>
          <input
            type="number"
            min="1"
            step="1"
            v-model.number="amount"
            class="rounded-lg border border-zinc-700 bg-zinc-900 px-3 py-1.5 text-base text-zinc-100 focus:outline-none focus:ring-[2px] focus:ring-green-500 focus:border-green-500 transition-shadow duration-150"
          />
        </label>
      </div>

      <!-- Embedded component -->
      <div class="my-4">
        <div id="pay-component-1" class="w-full" />
      </div>

      <button
        id="btnx"
        class="hidden w-full py-3 rounded-lg font-semibold text-lg bg-green-500 hover:bg-green-600 transition-colors"
        @click="payabliExec"
      >
        Pay
      </button>
    </div>

    <!-- Response log -->
    <div v-if="log.length > 0" class="w-full max-w-md mt-4 space-y-3">
      <div
        v-for="(entry, i) in log"
        :key="i"
        class="rounded-xl border px-4 py-3"
        :class="entry.type === 'success' ? 'border-green-700 bg-green-950' : 'border-red-700 bg-red-950'"
      >
        <p
          class="text-xs font-semibold uppercase tracking-widest mb-2"
          :class="entry.type === 'success' ? 'text-green-400' : 'text-red-400'"
        >
          {{ entry.type === 'success' ? 'Success' : 'Error' }}
        </p>
        <pre class="text-xs text-zinc-300 whitespace-pre-wrap break-all font-mono">{{ JSON.stringify(entry.payload, null, 2) }}</pre>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { usePayabli } from '../composables/usePayabli';

type Country = 'us' | 'ca';
type Network = 'visa' | 'mastercard' | 'amex' | 'discover';
type LogEntry = { type: 'success' | 'error'; payload: unknown };

const NETWORKS: { id: Network; label: string }[] = [
  { id: 'visa',       label: 'Visa' },
  { id: 'mastercard', label: 'MC' },
  { id: 'amex',       label: 'Amex' },
  { id: 'discover',   label: 'Discover' },
];

const buildCardInputs = (country: Country) => ({
  cardHolderName:     { label: 'Cardholder Name',   placeholder: 'John Doe',            floating: false, size: 12, row: 0, order: 0 },
  cardNumber:         { label: 'Card Number',        placeholder: '1234 1234 1234 1234', floating: false, size: 6,  row: 1, order: 0 },
  cardExpirationDate: { label: 'Expiration Date',    placeholder: 'MM/YY',               floating: false, size: 6,  row: 1, order: 1 },
  cardCvv:            { label: 'CVV/CVC',            placeholder: 'CVV/CVC',             floating: false, size: 6,  row: 2, order: 0 },
  cardZipcode:        {
    label: country === 'us' ? 'ZIP Code' : 'Postal Code',
    placeholder: country === 'us' ? '12345' : 'A1A 1A1',
    floating: false, size: 6, row: 2, order: 1,
    country: [country],
  },
});

const token = "o.YOE...Ws8=";
const entryPoint = "47095bfab8";
const rootContainer = "pay-component-1";
const payabliButton = "btnx";
const cssUrl = typeof window !== 'undefined' ? `${window.location.origin}/payabli-tailwind-iframe.css` : '';

const amount = ref(100);
const country = ref<Country>('us');
const networks = ref<Record<Network, boolean>>({
  visa: true, mastercard: true, amex: true, discover: true,
});
const log = ref<LogEntry[]>([]);

const [payabliConfig, payabliExec] = usePayabli({
  type: "methodEmbedded",
  rootContainer,
  defaultOpen: 'card',
  customCssUrl: cssUrl,
  token,
  entryPoint,
  card: {
    enabled: true,
    visa: true, mastercard: true, amex: true, discover: true, jcb: true, diners: true,
    inputs: buildCardInputs('us'),
  },
  ach: { enabled: false, checking: true, savings: true },
  customerData: {
    customerNumber: "00001",
    firstName: "John",
    lastName: "Doe",
    billingEmail: "johndoe@email.com"
  },
  functionCallBackSuccess: (response: any) => {
    log.value.push({ type: 'success', payload: response });
  },
  functionCallBackReady: (data: any) => {
    const btn = document.getElementById(payabliButton);
    if (data[1] === true && btn) btn.classList.remove("hidden");
    else if (btn) btn.classList.add("hidden");
  },
  functionCallBackError: (errors: any) => {
    log.value.push({ type: 'error', payload: errors });
  },
},
"pay", {
  paymentDetails: {
    totalAmount: amount.value,
    serviceFee: 0,
    categories: [{ label: "payment", amount: amount.value, qty: 1 }],
  },
});

watch([country, networks], () => {
  payabliConfig.card.inputs = buildCardInputs(country.value);
  payabliConfig.card.visa       = networks.value.visa;
  payabliConfig.card.mastercard = networks.value.mastercard;
  payabliConfig.card.amex       = networks.value.amex;
  payabliConfig.card.discover   = networks.value.discover;
}, { deep: true });

const setCountry = (c: Country) => { country.value = c; };

const toggleNetwork = (id: Network) => {
  const next = { ...networks.value, [id]: !networks.value[id] };
  if (!Object.values(next).some(Boolean)) return;
  networks.value = next;
};
</script>


