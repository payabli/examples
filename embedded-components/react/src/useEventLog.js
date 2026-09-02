import { useCallback, useRef, useState } from 'react';

/**
 * Tracks SDK/component events as a newest-first list for the sidebar timeline.
 */
export function useEventLog() {
  const [entries, setEntries] = useState([]);
  const nextId = useRef(0);

  const add = useCallback(({ label, kind = 'info', detail }) => {
    const entry = { id: ++nextId.current, label, kind, detail, time: new Date() };
    setEntries((current) => [entry, ...current]);
  }, []);

  const clear = useCallback(() => setEntries([]), []);

  return { entries, add, clear };
}
