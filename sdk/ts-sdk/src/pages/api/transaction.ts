import { PayabliClient } from '@payabli/sdk-node';

export async function GET() {

  const apiKey = import.meta.env.PAYABLI_KEY
  const entryPoint = import.meta.env.PAYABLI_ENTRY

  const client = new PayabliClient({ apiKey: apiKey });

  const result = await client.moneyIn.getpaid({
    body: {
        customerData: {
            customerId: 4440,
        },
        entryPoint: entryPoint,
        paymentDetails: {
            serviceFee: 0,
            totalAmount: 100,
        },
        paymentMethod: {
            cardcvv: "999",
            cardexp: "02/27",
            cardHolder: "Kassiane Cassian",
            cardnumber: "4111111111111111",
            cardzip: "12345",
            initiator: "payor",
            method: "card",
        },
    },
  })


  return new Response(table, {
    status: 200,
    headers: {
      'Content-Type': 'text/html'
    }
  });
}
