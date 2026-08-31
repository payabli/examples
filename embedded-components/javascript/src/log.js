/**
 * Renders SDK/component events as a newest-first timeline, raw payloads collapsed
 * by default.
 */
export class EventLog {
  /** @param {HTMLOListElement} listEl */
  constructor(listEl) {
    this.listEl = listEl;
  }

  /**
   * @param {object} entry
   * @param {string} entry.label
   * @param {'info'|'success'|'error'|'warn'} [entry.kind]
   * @param {unknown} [entry.detail]
   */
  add({ label, kind = 'info', detail }) {
    const item = document.createElement('li');
    item.className = `timeline-item timeline-${kind}`;

    const dot = document.createElement('span');
    dot.className = 'timeline-dot';
    dot.setAttribute('aria-hidden', 'true');

    const body = document.createElement('div');
    body.className = 'timeline-body';

    const head = document.createElement('div');
    head.className = 'timeline-head';

    const labelEl = document.createElement('span');
    labelEl.className = 'timeline-label';
    labelEl.textContent = label;

    const timeEl = document.createElement('time');
    timeEl.className = 'timeline-time';
    timeEl.textContent = new Date().toLocaleTimeString('en-US', {
      hour12: false,
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
    });

    head.append(labelEl, timeEl);
    body.append(head);

    if (detail !== undefined) {
      const details = document.createElement('details');
      const summary = document.createElement('summary');
      summary.textContent = 'Payload';
      const pre = document.createElement('pre');
      pre.textContent = JSON.stringify(detail, null, 2);
      details.append(summary, pre);
      body.append(details);
    }

    item.append(dot, body);
    this.listEl.prepend(item);
  }

  clear() {
    this.listEl.replaceChildren();
  }
}
