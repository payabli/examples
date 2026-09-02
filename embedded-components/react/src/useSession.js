import { useCallback, useEffect, useRef, useState } from 'react';
import { createSession } from './session.js';

/**
 * Mints a session for `amountCents` — via `createSession()`, whenever it changes
 * — and exposes it alongside a `retry()` that re-mints one for the same amount.
 *
 * Tracks a request id so a superseded fetch (two amount changes in quick
 * succession, or a retry firing before an earlier attempt resolves) can't
 * clobber state after the fact.
 *
 * @param {number} amountCents
 * @param {{ onEvent?: (entry: { label: string, kind?: string, detail?: unknown }) => void }} [options]
 */
export function useSession(amountCents, { onEvent } = {}) {
  const [state, setState] = useState({ session: null, status: 'loading', errorMessage: null });
  const [attempt, setAttempt] = useState(0);
  const requestId = useRef(0);
  const onEventRef = useRef(onEvent);
  onEventRef.current = onEvent;

  useEffect(() => {
    const thisRequest = ++requestId.current;
    setState({ session: null, status: 'loading', errorMessage: null });
    onEventRef.current?.({ label: 'session:request', detail: { amountCents } });

    createSession(amountCents).then(
      (session) => {
        if (thisRequest !== requestId.current) return; // superseded by a newer request
        onEventRef.current?.({
          label: 'session:created',
          kind: 'success',
          detail: { expiresAt: session.expiresAt },
        });
        setState({ session, status: 'ready', errorMessage: null });
      },
      (cause) => {
        if (thisRequest !== requestId.current) return;
        const message = cause instanceof Error ? cause.message : String(cause);
        onEventRef.current?.({ label: 'session:failed', kind: 'error', detail: message });
        setState({ session: null, status: 'error', errorMessage: message });
      },
    );
  }, [amountCents, attempt]);

  const retry = useCallback(() => setAttempt((n) => n + 1), []);

  return { ...state, retry };
}
