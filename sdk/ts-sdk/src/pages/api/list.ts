import { PayabliClient } from '@payabli/sdk-node';

export async function GET() {

  const apiKey = import.meta.env.PAYABLI_KEY
  const entryPoint = import.meta.env.PAYABLI_ENTRY

  const client = new PayabliClient({ apiKey: apiKey });

  const result = await client.query.listCustomers(entryPoint)

  const tableRows = result.Records?.map((record) => `
    <tr>
      <td>${record.Firstname || ''}</td>
      <td>${record.Lastname || ''}</td>
      <td>${record.Email || ''}</td>
      <td>${record.Address || ''}</td>
      <td>${record.City || ''}</td>
      <td>${record.State || ''}</td>
      <td>${record.Zip || ''}</td>
      <td>${record.TimeZone || ''}</td>
      <td>
        <button id="delete" class="outline"
          hx-delete="/api/delete/${record.customerId}" 
          hx-swap="innerHTML" 
          hx-target="closest tr"  
          hx-on="htmx:beforeRequest: this.setAttribute('aria-busy', 'true'), htmx:afterRequest: this.removeAttribute('aria-busy')"
        >
          ❌
        </button>
      </td>
    </tr>
  `).join('');

  const table = `
    <table class="striped">
      <thead>
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Address</th>
          <th>City</th>
          <th>State</th>
          <th>Zip</th>
          <th>Time Zone</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        ${tableRows}
      </tbody>
    </table>
  `;

  return new Response(table, {
    status: 200,
    headers: {
      'Content-Type': 'text/html'
    }
  });
}
