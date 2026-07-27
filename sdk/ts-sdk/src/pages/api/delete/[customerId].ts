import { PayabliClient } from '@payabli/sdk-node';

export async function DELETE({ params }: { params: any }) {

  console.log(params);

  const clientId = import.meta.env.PAYABLI_CLIENT_ID
  const clientSecret = import.meta.env.PAYABLI_CLIENT_SECRET

  const client = new PayabliClient({ bearerAuth: { clientId, clientSecret } });

  try {
    const res = await client.customer.deleteCustomer(params.customerId);
    console.log(res);
    return new Response("", {
      status: 200,
      headers: {
        'Content-Type': 'text/html'
      }
    });
  } catch (err: any) {
    console.log(err);
    return new Response(JSON.stringify(
      {
        error: 'Error deleting customer',
        message: err.message
      }
    ), {
      status: 500,
      headers: {
        'Content-Type': 'text/html'
      }
    });
  }
}
