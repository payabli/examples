import { PayIn, PayabliProvider } from '@payabli/components-react';
import { useEffect, useState } from 'react';
import { AmountPicker } from './AmountPicker.jsx';
import { ErrorCard } from './ErrorCard.jsx';
import { Skeleton } from './Skeleton.jsx';
import { SuccessCard } from './SuccessCard.jsx';
import { useSession } from './useSession.js';

const DEFAULT_AMOUNT_CENTS = 1000;

/**
 * Mounts a Pay In component for the selected amount via `PayabliProvider` +
 * `PayIn` from `@payabli/components-react`. Picking a different amount mints a
 * fresh session (the amount is server-side `Session/init` config), which
 * remounts the component from scratch.
 *
 * @param {{ onEvent: (entry: { label: string, kind?: string, detail?: unknown }) => void }} props
 */
export function PaymentPanel({ onEvent }) {
  const [amountCents, setAmountCents] = useState(DEFAULT_AMOUNT_CENTS);
  const [payInStatus, setPayInStatus] = useState('mounting'); // 'mounting' | 'ready' | 'success'
  const [statusOverride, setStatusOverride] = useState(null); // { kind, text } | null
  const [successPayload, setSuccessPayload] = useState(null);

  const { session, status: sessionStatus, errorMessage, retry } = useSession(amountCents, {
    onEvent,
  });

  // A new session means a freshly (re)mounted PayIn — clear whatever the previous
  // one left behind.
  useEffect(() => {
    setPayInStatus('mounting');
    setStatusOverride(null);
    setSuccessPayload(null);
  }, [session?.sessionToken, sessionStatus]);

  const stage =
    sessionStatus === 'error'
      ? 'error'
      : payInStatus === 'success'
        ? 'success'
        : payInStatus === 'ready'
          ? 'form'
          : 'loading';

  const status = sessionStatus === 'error'
    ? { kind: 'error', text: 'Could not connect' }
    : (statusOverride ??
        (payInStatus === 'ready' || payInStatus === 'success'
          ? { kind: 'ready', text: 'Ready' }
          : { kind: 'connecting', text: 'Connecting…' }));

  return (
    <section className="panel payment-panel">
      <div className="panel-head">
        <div>
          <h1>Pay In</h1>
          <p className="muted">A live payment form rendered in a secure, PCI-compliant iframe.</p>
        </div>
        <span className={`status-pill status-${status.kind}`}>
          <span className="status-dot" />
          {status.text}
        </span>
      </div>

      <AmountPicker amountCents={amountCents} onChange={setAmountCents} disabled={stage === 'loading'} />

      <div className="payment-stage">
        {stage === 'loading' && <Skeleton />}

        {session && (
          <div id="payment" hidden={stage !== 'form'}>
            <PayabliProvider
              session={session}
              loadOptions={{ scriptSrc: 'https://cdn-qa.payabli.com/components-web.js' }}
              onSessionExpired={(payload) => {
                onEvent({ label: 'sessionExpired', kind: 'error', detail: payload });
                setStatusOverride({ kind: 'error', text: 'Session expired' });
              }}
              onSessionError={(payload) => {
                onEvent({ label: 'sessionError', kind: 'error', detail: payload });
              }}
            >
              <PayIn
                appearance={{
                  layoutVariant: 'standard',
                  mode: 'light',
                  tokens: { '--primary': '#4f46e5' },
                }}
                options={{ showSubmitButton: true }}
                onReady={() => {
                  onEvent({ label: 'ready', kind: 'success' });
                  setStatusOverride(null);
                  setPayInStatus('ready');
                }}
                onChange={(payload) => onEvent({ label: 'change', detail: payload })}
                onMethodChange={(payload) => onEvent({ label: 'methodChange', detail: payload })}
                onError={(payload) => {
                  onEvent({ label: 'error', kind: 'error', detail: payload });
                  setStatusOverride({ kind: 'error', text: payload.message });
                }}
                onSuccess={(payload) => {
                  onEvent({ label: 'success', kind: 'success', detail: payload });
                  setSuccessPayload(payload);
                  setPayInStatus('success');
                }}
              />
            </PayabliProvider>
          </div>
        )}

        {stage === 'error' && <ErrorCard message={errorMessage} onRetry={retry} />}

        {stage === 'success' && (
          <SuccessCard
            amountCents={amountCents}
            transactionId={successPayload?.transactionId}
            onRestart={retry}
          />
        )}
      </div>
    </section>
  );
}
