/** Placeholder shown while a session is being minted or a component is mounting. */
export function Skeleton() {
  return (
    <div className="skeleton" aria-hidden="true">
      <div className="skeleton-tabs">
        <div className="skeleton-line skeleton-tab" />
        <div className="skeleton-line skeleton-tab" />
      </div>
      <div className="skeleton-line" style={{ width: '30%', height: 12 }} />
      <div className="skeleton-line" style={{ height: 42 }} />
      <div className="skeleton-line" style={{ width: '30%', height: 12, marginTop: 8 }} />
      <div className="skeleton-line" style={{ height: 42 }} />
      <div className="skeleton-row">
        <div className="skeleton-line" style={{ height: 42 }} />
        <div className="skeleton-line" style={{ height: 42 }} />
      </div>
      <div className="skeleton-line" style={{ height: 44, marginTop: 16, borderRadius: 10 }} />
    </div>
  );
}
