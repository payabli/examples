import type { APIRoute } from 'astro';

export const prerender = false; // Disable prerendering


export const POST: APIRoute = async ({ request }) => {
  try {
    const requestData = await request.json();
    const method = requestData.method;

    if (!method) {
      return new Response(null, {
        status: 404,
        statusText: 'Not found',
      });
    }

    const response = await makeTransaction(method);
    return response; // return the response directly
  } catch (error) {
    console.error('Error in POST request:', error);
    return new Response(null, {
      status: 500,
      statusText: 'Internal server error',
    });
  }
};

export const makeTransaction = async (method: string): Promise<Response> => {
  console.log(method)
  const PAYABLI_API_TOKEN = import.meta.env.PAYABLI_API_TOKEN; // get API token from .env
  const PAYABLI_API_PAYPOINT = import.meta.env.PAYABLI_API_PAYPOINT; // get paypoint from .env

  const url = "https://api-sandbox.payabli.com/api/MoneyIn/getpaid?forceCustomerCreation=false";

  const headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/*+json',
    //'idempotencyKey': 'bf21c30673244141b39d81d73a42613d',
    'requestToken': PAYABLI_API_TOKEN, 
  };

  const body = JSON.stringify({
      entryPoint: PAYABLI_API_PAYPOINT,
      paymentMethod: {
          method: "card",
          storedMethodId: method 
      },
      paymentDetails: {
          totalAmount: 20.00,
          serviceFee: 0
      },
      customerData: {
          customerId: 41792,
          customerNumber: "888",
          billingAddress1: "5127 Linkwood Ave"
      }
  });

  try {
    const fetchResponse = await fetch(url, {
      method: 'POST',
      headers: headers,
      body: body,
    });

    // Log the response status and body to debug
    console.log('Response Status:', fetchResponse.status);
    const rawResponseText = await fetchResponse.text();
    console.log('Raw Response Body:', rawResponseText);

    // Check if the response is empty or if it contains valid JSON
    if (!fetchResponse.ok) {
      return new Response(null, {
        status: fetchResponse.status,
        statusText: 'API request failed',
      });
    }

    // Check if the content type is JSON before parsing
    const contentType = fetchResponse.headers.get('Content-Type');
    if (contentType && contentType.includes('application/json')) {
      try {
        const data = JSON.parse(rawResponseText); // Explicitly parse the response
        const transId = data.responseData.referenceId;
        console.log('Transaction ID:', transId);
        return new Response(JSON.stringify({
          status: 200,
          statusText: 'Successfully processed transaction',
          data: data, // Include the response data
        }));
      } catch (error) {
        console.error('Failed to parse JSON response:', error);
        return new Response(null, {
          status: 500,
          statusText: 'Failed to parse JSON response',
        });
      }
    } else {
      console.error('Non-JSON response received:', rawResponseText);
      return new Response(null, {
        status: 500,
        statusText: 'Expected JSON response, but received a different format',
      });
    }

  } catch (error) {
    console.error('Error during transaction:', error);
    return new Response(null, {
      status: 500,
      statusText: 'Internal server error',
    });
  }
}
