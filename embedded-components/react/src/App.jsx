import { EventLogPanel } from './EventLogPanel.jsx';
import { PaymentPanel } from './PaymentPanel.jsx';
import { useEventLog } from './useEventLog.js';

export function App() {
  const { entries, add: logEvent, clear: clearLog } = useEventLog();

  return (
    <div className="page">
      <header className="site-header">
        <div className="brand">
          <span className="brand-mark">P</span>
          <span className="brand-name">Payabli</span>
        </div>
        <div className="badges">
          <span className="badge">React</span>
          <span className="badge badge-muted">ECv2 · Sandbox preview</span>
        </div>
      </header>

      <main className="layout">
        <PaymentPanel onEvent={logEvent} />

        <aside className="sidebar">
          <div className="panel how-it-works">
            <h2>How this works</h2>
            <ol className="steps">
              <li>
                <strong>Your server mints a session.</strong>
                It exchanges your client credentials for a bearer token, then calls{' '}
                <code>Session/init</code>. The secret never leaves your backend.
              </li>
              <li>
                <strong>
                  <code>PayabliProvider</code> loads the SDK.
                </strong>
                It calls <code>loadPayabli()</code> for you and hands the session to every
                component mounted underneath it.
              </li>
              <li>
                <strong>Payabli renders the form.</strong>
                A secure, PCI-compliant iframe handles card data directly — it never touches
                your code.
              </li>
            </ol>
          </div>

          <EventLogPanel entries={entries} onClear={clearLog} />
        </aside>
      </main>

      <footer className="site-footer">
        <span>
          Built with <code>@payabli/components-react</code>
        </span>
        <a href="https://docs.payabli.com" target="_blank" rel="noreferrer">
          Docs ↗
        </a>
      </footer>
    </div>
  );
}
