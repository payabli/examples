import { PayabliClient } from '@payabli/sdk-node';
import type { APIRoute } from 'astro';

export const POST: APIRoute = async ({ params }) => {
  try {
    const token = params.token;
    
    if (!token) {
      return new Response(
        '<input type="text" name="invalid" value="❌ No token provided" aria-invalid="true" readonly>',
        {
          status: 400,
          headers: { 'Content-Type': 'text/html' }
        }
      );
    }

    const apiKey = import.meta.env.PAYABLI_KEY;
    const entryPoint = import.meta.env.PAYABLI_ENTRY;

    console.log('Environment check:');
    console.log('apiKey exists:', !!apiKey);
    console.log('entryPoint exists:', !!entryPoint);
    console.log('entryPoint value:', entryPoint);
    console.log('All env vars:', Object.keys(process.env).filter(key => key.includes('PAYABLI')));

    if (!apiKey || !entryPoint) {
      return new Response(
        '<input type="text" name="invalid" value="❌ Server configuration error" aria-invalid="true" readonly>',
        {
          status: 500,
          headers: { 'Content-Type': 'text/html' }
        }
      );
    }

    console.log(`Converting temporary token to permanent: ${token}`);

    const client = new PayabliClient({ apiKey });

    // Step 1: Use token storage to convert temporary token to permanent
    const tokenResult = await client.tokenStorage.addMethod({
      createAnonymous: true,
      temporary: false,
      body: {
        customerData: {
          customerId: 4440 // This should be dynamic based on your needs
        },
        entryPoint: entryPoint,
        paymentMethod: {
          method: "card",
          tokenId: token // The temporary token from the embedded component
        },
        source: "web",
        methodDescription: "Main card"
      }
    });

    console.log('Token storage result:', tokenResult);

    if (!tokenResult.isSuccess) {
      return new Response(
        `<input type="text" name="invalid" value="❌ Token storage failed: ${tokenResult.responseText}" aria-invalid="true" readonly>`,
        {
          status: 400,
          headers: { 'Content-Type': 'text/html' }
        }
      );
    }

    const permanentToken = tokenResult.responseData.referenceId;
    console.log(`Permanent token created: ${permanentToken}`);

        // Step 2: Create transaction using the permanent token
    const transactionResult = await client.moneyIn.getpaid({
      body: {
        customerData: {
          customerId: 4440
        },
        entryPoint: entryPoint,
        ipaddress: "255.255.255.255",
        paymentDetails: {
          serviceFee: 0.0,
          totalAmount: 100.0
        },
        paymentMethod: {
          initiator: "payor",
          method: "card",
          storedMethodId: permanentToken,
          storedMethodUsageType: "unscheduled"
        }
      }
    });

    console.log('Transaction result:', transactionResult);

    if (transactionResult.isSuccess) {
      const referenceId = transactionResult.responseData?.referenceId || 'Unknown';
      return new Response(
        `<input type="text" name="valid" value="✅ Payment processed! Reference ID: ${referenceId}" readonly>`,
        {
          status: 200,
          headers: { 'Content-Type': 'text/html' }
        }
      );
    } else {
      return new Response(
        `<input type="text" name="invalid" value="❌ Transaction failed: ${transactionResult.responseText}" aria-invalid="true" readonly>`,
        {
          status: 400,
          headers: { 'Content-Type': 'text/html' }
        }
      );
    }

  } catch (error) {
    console.error('Transaction API error:', error);
    return new Response(
      '<input type="text" name="invalid" value="❌ Server error occurred" aria-invalid="true" readonly>',
      {
        status: 500,
        headers: { 'Content-Type': 'text/html' }
      }
    );
  }
};
