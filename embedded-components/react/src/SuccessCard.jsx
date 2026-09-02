/** @param {number} cents */
function formatAmount(cents) {
  return (cents / 100).toLocaleString('en-US', { style: 'currency', currency: 'USD' });
}

/** @param {{ amountCents: number, transactionId?: string, onRestart: () => void }} props */
export function SuccessCard({ amountCents, transactionId, onRestart }) {
  return (
    <div className="state-card state-success">
      <div className="state-icon state-icon-success">✓</div>
      <h3>Payment received</h3>
      <p className="muted">
        {formatAmount(amountCents)} · Transaction <code>{transactionId ?? '(no id returned)'}</code>
      </p>
      <button type="button" className="btn" onClick={onRestart}>
        Start another payment
      </button>
    </div>
  );
}
