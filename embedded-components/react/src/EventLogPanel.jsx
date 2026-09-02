function formatTime(date) {
  return date.toLocaleTimeString('en-US', {
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  });
}

/**
 * The sidebar panel showing SDK/component events as a newest-first timeline,
 * raw payloads collapsed by default.
 *
 * @param {{ entries: Array<{ id: number, label: string, kind: string, detail?: unknown, time: Date }>, onClear: () => void }} props
 */
export function EventLogPanel({ entries, onClear }) {
  return (
    <div className="panel event-log">
      <div className="event-log-head">
        <h2>Event log</h2>
        <button type="button" className="btn-ghost" onClick={onClear}>
          Clear
        </button>
      </div>

      {entries.length === 0 ? (
        <p className="timeline-empty muted">Events will appear here.</p>
      ) : (
        <ol className="timeline">
          {entries.map((entry) => (
            <li key={entry.id} className={`timeline-item timeline-${entry.kind}`}>
              <span className="timeline-dot" aria-hidden="true" />
              <div className="timeline-body">
                <div className="timeline-head">
                  <span className="timeline-label">{entry.label}</span>
                  <time className="timeline-time">{formatTime(entry.time)}</time>
                </div>
                {entry.detail !== undefined && (
                  <details>
                    <summary>Payload</summary>
                    <pre>{JSON.stringify(entry.detail, null, 2)}</pre>
                  </details>
                )}
              </div>
            </li>
          ))}
        </ol>
      )}
    </div>
  );
}
