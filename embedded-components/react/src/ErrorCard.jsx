/** @param {{ message: string, onRetry: () => void }} props */
export function ErrorCard({ message, onRetry }) {
  return (
    <div className="state-card state-error">
      <div className="state-icon state-icon-error">!</div>
      <h3>Couldn't start a session</h3>
      <p className="muted">{message}</p>
      <button type="button" className="btn" onClick={onRetry}>
        Retry
      </button>
    </div>
  );
}
