// src/pages/api/create.ts
import { PayabliClient } from '@payabli/sdk-node';

export async function POST({ request }: { request: any }) {

  const apiKey = import.meta.env.PAYABLI_KEY
  const entryPoint = import.meta.env.PAYABLI_ENTRY

  try {
    // Parse the form data
    const formData = await request.formData();

    // Instantiate the Payabli client
    const payabliClient = new PayabliClient({ apiKey: apiKey });

    // Send the form data to Payabli's API
    try {
      const result = await payabliClient.customer.addCustomer(entryPoint, {
        forceCustomerCreation: true,
        body: {
          firstname: formData.get('firstname'),
          lastname: formData.get('lastname'),
          email: formData.get('email'),
          zip: formData.get('zip'),
          timeZone: formData.get('timeZone'),
          country: formData.get('country'),
          state: formData.get('state'),
          city: formData.get('city'),
          address: formData.get('address'),
          additionalFields: {
            hvac: formData.get('hvac'),
            electrical: formData.get('electrical'),
          }
        }
      })
      
      console.log(result)
      
      return new Response(
        `<input type="text" name="valid" value="Success!" aria-invalid="false" id="form-result" readonly>`,
        {
          status: 201,
          headers: {
            'Content-Type': 'application/json'
          }
        }
      );
    } catch {
      return new Response(
        `<input type="text" name="invalid" value="Error!" aria-invalid="true" id="form-result" readonly>`,
        {
          status: 200,
          headers: {
            'HX-Reswap': 'innerHTML',
            'Content-Type': 'application/json'
          }
        }
      );
    }
    
  } catch (error) {
    console.error('Error processing form submission:', error);
    
    return new Response(
      `<input type="text" name="invalid" value="Error!" aria-invalid="true" id="form-result" readonly>`,
      {
        status: 200,
        headers: {
          'HX-Reswap': 'innerHTML',
          'Content-Type': 'application/json'
        }
      }
    );
  }
};
