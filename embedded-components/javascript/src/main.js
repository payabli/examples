import { loadPayabli } from '@payabli/components-web';
import { EventLog } from './log.js';
import { createSession } from './session.js';

const DEFAULT_AMOUNT_CENTS = 1000;

/** @param {string} selector @returns {Element} */
function requireEl(selector) {
  const el = document.querySelector(selector);
  if (!el) {
    throw new Error(`Expected an element matching "${selector}" in index.html.`);
  }
  return el;
}

const statusPill = requireEl('#status-pill');
const skeletonEl = requireEl('#skeleton');
const mountEl = requireEl('#payment');
const errorCard = requireEl('#error-card');
const errorMessageEl = requireEl('#error-message');
const retryBtn = requireEl('#retry-btn');
const successCard = requireEl('#success-card');
const successAmountEl = requireEl('#success-amount');
const successTxEl = requireEl('#success-tx');
const restartBtn = requireEl('#restart-btn');
const amountPicker = requireEl('#amount-picker');
const amountCustomInput = requireEl('#amount-custom-input');
const timelineEl = requireEl('#timeline');
const timelineEmptyEl = requireEl('#timeline-empty');
const clearLogBtn = requireEl('#clear-log');

const log = new EventLog(timelineEl);

/** @param {'connecting'|'ready'|'error'} kind @param {string} text */
function setStatus(kind, text) {
  statusPill.className = `status-pill status-${kind}`;
  statusPill.innerHTML = `<span class="status-dot"></span>${text}`;
}

/** @param {'loading'|'form'|'error'|'success'} stage */
function setStage(stage) {
  skeletonEl.hidden = stage !== 'loading';
  mountEl.hidden = stage !== 'form';
  errorCard.hidden = stage !== 'error';
  successCard.hidden = stage !== 'success';
}

function onTimelineChange() {
  timelineEmptyEl.hidden = timelineEl.childElementCount > 0;
}

/** @param {number} cents */
function formatAmount(cents) {
  return (cents / 100).toLocaleString('en-US', { style: 'currency', currency: 'USD' });
}

/** @param {number|null} amountCents */
function setActiveChip(amountCents) {
  const chips = amountPicker.querySelectorAll('.amount-chip');
  for (const chip of chips) {
    chip.classList.toggle('active', Number(chip.dataset.amountCents) === amountCents);
  }
  if (amountCents === null) {
    amountCustomInput.value = '';
  }
}

/** @param {boolean} disabled */
function setPickerDisabled(disabled) {
  amountPicker.querySelectorAll('.amount-chip').forEach((chip) => {
    chip.disabled = disabled;
  });
  amountCustomInput.disabled = disabled;
}

let sdkPromise = null;
function getSdk() {
  // ECv2 hasn't launched yet, so the SDK isn't published to the default production
  // CDN this points at (https://cdn.payabli.com/...) — only the sandbox host serves
  // it today. Drop this override once @payabli/components-web ships against the
  // production CDN.
  sdkPromise ??= loadPayabli({ scriptSrc: 'https://cdn-qa.payabli.com/components-web.js' });
  return sdkPromise;
}

let activePayabli = null;
let activePayIn = null;
let currentAmountCents = DEFAULT_AMOUNT_CENTS;
let requestId = 0;

/** @param {number} amountCents */
async function mountPayment(amountCents) {
  const thisRequest = ++requestId;
  currentAmountCents = amountCents;

  activePayIn?.destroy();
  activePayabli?.destroy();
  activePayIn = null;
  activePayabli = null;
  mountEl.replaceChildren();

  setStage('loading');
  setStatus('connecting', 'Connecting…');
  setPickerDisabled(true);
  log.add({ label: 'session:request', detail: { amountCents } });

  try {
    const session = await createSession(amountCents);
    if (thisRequest !== requestId) return; // superseded by a newer amount selection

    log.add({ label: 'session:created', kind: 'success', detail: { expiresAt: session.expiresAt } });

    const Payabli = await getSdk();
    if (thisRequest !== requestId) return;

    const payabli = Payabli({ session });
    activePayabli = payabli;

    payabli.on('sessionExpired', (error) => {
      log.add({ label: 'sessionExpired', kind: 'error', detail: error });
      setStatus('error', 'Session expired');
    });
    payabli.on('sessionError', (error) => {
      log.add({ label: 'sessionError', kind: 'error', detail: error });
    });

    const payIn = payabli.create('payin', {
      appearance: {
        layoutVariant: 'standard',
        mode: 'light',
        tokens: { '--primary': '#4f46e5' },
      },
      options: { showSubmitButton: true },
    });
    activePayIn = payIn;

    payIn.on('ready', () => {
      if (thisRequest !== requestId) return;
      log.add({ label: 'ready', kind: 'success' });
      setStatus('ready', 'Ready');
      setStage('form');
      setPickerDisabled(false);
    });
    payIn.on('change', (payload) => {
      log.add({ label: 'change', detail: payload });
    });
    payIn.on('methodChange', (payload) => {
      log.add({ label: 'methodChange', detail: payload });
    });
    payIn.on('error', (payload) => {
      log.add({ label: 'error', kind: 'error', detail: payload });
      setStatus('error', payload.message);
    });
    payIn.on('success', (payload) => {
      log.add({ label: 'success', kind: 'success', detail: payload });
      successAmountEl.textContent = formatAmount(amountCents);
      successTxEl.textContent = payload.transactionId ?? '(no id returned)';
      setStage('success');
      setPickerDisabled(false);
    });

    payIn.mount(mountEl);
  } catch (error) {
    if (thisRequest !== requestId) return;
    const message = error instanceof Error ? error.message : String(error);
    log.add({ label: 'session:failed', kind: 'error', detail: message });
    setStatus('error', 'Could not connect');
    errorMessageEl.textContent = message;
    setStage('error');
    setPickerDisabled(false);
  } finally {
    onTimelineChange();
  }
}

amountPicker.addEventListener('click', (event) => {
  const chip = event.target.closest('.amount-chip');
  if (!chip) return;
  const amountCents = Number(chip.dataset.amountCents);
  setActiveChip(amountCents);
  void mountPayment(amountCents);
});

amountCustomInput.addEventListener('change', () => {
  const dollars = Number(amountCustomInput.value);
  if (!Number.isFinite(dollars) || dollars <= 0) return;
  const amountCents = Math.round(dollars * 100);
  setActiveChip(null);
  void mountPayment(amountCents);
});

retryBtn.addEventListener('click', () => {
  void mountPayment(currentAmountCents);
});

restartBtn.addEventListener('click', () => {
  void mountPayment(currentAmountCents);
});

clearLogBtn.addEventListener('click', () => {
  log.clear();
  onTimelineChange();
});

setActiveChip(DEFAULT_AMOUNT_CENTS);
void mountPayment(DEFAULT_AMOUNT_CENTS);
