import { useState } from 'react';

const AMOUNT_CHIPS = [
  { cents: 1000, label: '$10' },
  { cents: 2500, label: '$25' },
  { cents: 5000, label: '$50' },
];

/**
 * Amount chips plus a custom-dollar-amount field. Reports the committed
 * amount (in cents) via `onChange` — the custom input's own typed text lives
 * here, since only this component cares what's currently in it.
 *
 * @param {{ amountCents: number, onChange: (cents: number) => void, disabled?: boolean }} props
 */
export function AmountPicker({ amountCents, onChange, disabled }) {
  const [customAmountText, setCustomAmountText] = useState('');

  function selectChip(cents) {
    setCustomAmountText('');
    onChange(cents);
  }

  function commitCustomAmount() {
    const dollars = Number(customAmountText);
    if (!Number.isFinite(dollars) || dollars <= 0) return;
    onChange(Math.round(dollars * 100));
  }

  return (
    <div className="amount-field">
      <span className="field-label">Amount</span>
      <div className="amount-picker" role="group" aria-label="Amount">
        {AMOUNT_CHIPS.map((chip) => (
          <button
            key={chip.cents}
            type="button"
            className={`amount-chip${customAmountText === '' && amountCents === chip.cents ? ' active' : ''}`}
            disabled={disabled}
            onClick={() => selectChip(chip.cents)}
          >
            {chip.label}
          </button>
        ))}
        <label className="amount-custom">
          <span className="amount-custom-prefix">$</span>
          <input
            type="number"
            min="1"
            step="0.01"
            inputMode="decimal"
            placeholder="Custom"
            aria-label="Custom amount in dollars"
            disabled={disabled}
            value={customAmountText}
            onChange={(event) => setCustomAmountText(event.target.value)}
            onBlur={commitCustomAmount}
            onKeyDown={(event) => {
              if (event.key === 'Enter') {
                event.preventDefault();
                commitCustomAmount();
              }
            }}
          />
        </label>
      </div>
    </div>
  );
}
