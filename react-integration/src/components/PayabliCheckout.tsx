// PayabliCheckout.tsx

import { useState, useEffect } from 'react';
import { usePayabli } from '../hooks/usePayabli';

type LogEntry = { type: 'success' | 'error'; payload: unknown };
type Country = 'us' | 'ca';
type Network = 'visa' | 'mastercard' | 'amex' | 'discover';

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

export const PayabliCheckout = () => {
  const token = "o.YOE...Ws8=";
  const entryPoint = "47095bfab8";
  const rootContainer = "pay-component-1";
  const payabliButton = "btnx";

  const [amount, setAmount] = useState(100);
  const [country, setCountry] = useState<Country>('us');
  const [networks, setNetworks] = useState<Record<Network, boolean>>({
    visa: true, mastercard: true, amex: true, discover: true,
  });
  const [log, setLog] = useState<LogEntry[]>([]);
  const cssUrl = `${window.location.origin}/payabli-tailwind-iframe.css`;

  const [payabliConfig, setPayabliConfig, payabliExec] = usePayabli({
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
      setLog(prev => [...prev, { type: 'success', payload: response }]);
    },
    functionCallBackReady: (data: any) => {
      const btn = document.getElementById(payabliButton);
      if (data[1] === true && btn) btn.classList.remove("hidden");
      else if (btn) btn.classList.add("hidden");
    },
    functionCallBackError: (errors: any) => {
      setLog(prev => [...prev, { type: 'error', payload: errors }]);
    }
  },
  "pay", {
    paymentDetails: {
      totalAmount: amount,
      serviceFee: 0,
      categories: [{ label: "payment", amount: amount, qty: 1 }],
    },
  });

  useEffect(() => {
    setPayabliConfig((prev: any) => ({
      ...prev,
      card: {
        ...prev.card,
        ...networks,
        inputs: buildCardInputs(country),
      },
    }));
  }, [country, networks]);

  const toggleNetwork = (id: Network) => {
    setNetworks(prev => {
      const next = { ...prev, [id]: !prev[id] };
      // keep at least one enabled
      if (!Object.values(next).some(Boolean)) return prev;
      return next;
    });
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-zinc-900 text-zinc-100 p-4">
      <div className="w-full max-w-md bg-zinc-800 rounded-xl shadow-lg p-8 space-y-6">

        {/* Header */}
        <div className="flex items-center justify-center gap-3 mb-5">
          <img src="/payabli-logo.png" alt="Payabli" className="h-10 w-auto object-contain" />
          <span className="text-zinc-500 text-lg">+</span>
          <img src="/react-logo.svg" alt="React" className="h-8 w-auto object-contain" />
        </div>
        <h2 className="text-2xl font-bold mb-2 text-center">Payabli Embedded Checkout</h2>

        <div className="flex flex-col gap-5 px-3">
          {/* Country toggle */}
          <div className="flex flex-col gap-2">
            <span className="text-sm font-normal text-zinc-400">Country</span>
            <div className="relative flex items-center bg-zinc-900 rounded-full p-1">
              <span
                className="absolute top-1 bottom-1 rounded-full bg-blue-500 transition-all duration-300 ease-in-out"
                style={{
                  left:  country === 'us' ? '4px' : '50%',
                  right: country === 'ca' ? '4px' : '50%',
                }}
              />
              {(['us', 'ca'] as Country[]).map(c => (
                <button
                  key={c}
                  onClick={() => setCountry(c)}
                  className={`relative z-10 flex-1 flex items-center justify-center gap-2 py-1.5 rounded-full text-sm font-medium transition-colors duration-300 ${
                    country === c ? 'text-white' : 'text-zinc-400 hover:text-zinc-200'
                  }`}
                >
                  <span>{c === 'us' ? 'US' : 'CA'}</span>
                  <span className="text-xs text-zinc-400">{c === 'us' ? 'ZIP Code' : 'Postal Code'}</span>
                </button>
              ))}
            </div>
          </div>

          {/* Card network toggles */}
          <div className="flex flex-col gap-2">
            <span className="text-sm font-normal text-zinc-400">Accepted Networks</span>
            <div className="flex gap-2">
              {NETWORKS.map(({ id, label }) => (
                <button
                  key={id}
                  onClick={() => toggleNetwork(id)}
                  className={`flex-1 py-1.5 rounded-lg border text-xs font-semibold transition-all duration-200 ${
                    networks[id]
                      ? 'border-blue-500 bg-blue-500/10 text-blue-300'
                      : 'border-zinc-700 bg-zinc-900 text-zinc-500 line-through'
                  }`}
                >
                  {label}
                </button>
              ))}
            </div>
          </div>

          {/* Amount */}
          <label className="flex flex-col gap-1">
            <span className="font-normal">Amount</span>
            <input
              type="number"
              min={1}
              step={1}
              value={amount}
              onChange={e => setAmount(Number(e.target.value))}
              className="rounded-lg border border-zinc-700 bg-zinc-900 px-3 py-1.5 text-base text-zinc-100 focus:outline-none focus:ring-[2px] focus:ring-blue-500 focus:border-blue-500 transition-shadow duration-150"
            />
          </label>
        </div>

        {/* Embedded component */}
        <div className="my-4">
          <div id="pay-component-1" className="w-full" />
        </div>

        <button
          id="btnx"
          className="hidden w-full py-3 rounded-lg font-semibold text-lg bg-blue-500 hover:bg-blue-600 transition-colors"
          onClick={payabliExec}
        >
          Pay
        </button>
      </div>

      {/* Response log */}
      {log.length > 0 && (
        <div className="w-full max-w-md mt-4 space-y-3">
          {log.map((entry, i) => (
            <div
              key={i}
              className={`rounded-xl border px-4 py-3 ${entry.type === 'success' ? 'border-green-700 bg-green-950' : 'border-red-700 bg-red-950'}`}
            >
              <p className={`text-xs font-semibold uppercase tracking-widest mb-2 ${entry.type === 'success' ? 'text-green-400' : 'text-red-400'}`}>
                {entry.type === 'success' ? 'Success' : 'Error'}
              </p>
              <pre className="text-xs text-zinc-300 whitespace-pre-wrap break-all font-mono">
                {JSON.stringify(entry.payload, null, 2)}
              </pre>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

