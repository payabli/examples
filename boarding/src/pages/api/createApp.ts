import type { APIRoute } from 'astro'
import { z } from 'zod'
import { getApiUrlPrefix } from '../../lib/getUrl';
import { migrateBankAccounts, parseServerFormData } from '../../Schema'

export const POST: APIRoute = async ({ request }) => {
  
  const apiToken = import.meta.env.PAYABLI_API_TOKEN
  const prefix = getApiUrlPrefix()

  try {
    const requestData = await request.json()
    // Final submission path: normalize enforced prefills, then fully validate and coerce.
    const formData = parseServerFormData(migrateBankAccounts(requestData))
    
    // Log the entire application data for inspection
    console.log('\n========== FULL APPLICATION DATA ==========');
    console.log(JSON.stringify(formData, null, 2));
    console.log('==========================================\n');
    
    // Log the bankData object for detailed inspection
    console.log('\n========== BANK DATA INSPECTION ==========');
    if (formData.bankData && Array.isArray(formData.bankData)) {
      console.log('Number of bank accounts:', formData.bankData.length);
      formData.bankData.forEach((bank, index) => {
        console.log(`\nBank Account ${index + 1}:`);
        console.log('  Nickname:', bank.nickname);
        console.log('  Function:', bank.bankAccountFunction, '(0=Deposit, 1=Withdrawal, 2=Both, 3=Remittance)');
        console.log('  Bank Name:', bank.bankName);
        console.log('  Routing:', bank.routingAccount);
        console.log('  Account:', bank.accountNumber);
        console.log('  Type:', bank.typeAccount);
        console.log('  Holder Name:', bank.bankAccountHolderName);
        console.log('  Holder Type:', bank.bankAccountHolderType);
        console.log('  Has ID field?:', 'id' in bank);
      });
    } else {
      console.log('WARNING: bankData is missing or not an array!');
    }
    
    // Also check if old format fields are still present
    if (formData.depositAccount || formData.withdrawalAccount) {
      console.log('\nWARNING: Old format fields detected!');
      if (formData.depositAccount) console.log('  - depositAccount exists');
      if (formData.withdrawalAccount) console.log('  - withdrawalAccount exists');
    }
    console.log('==========================================\n');
    
    const jsonData = JSON.stringify(formData)

    const response = await fetch(`https://api${prefix}.payabli.com/api/Boarding/app`, {
      method: 'POST',
      headers: {
        'content-type': 'application/json',
        'requestToken': apiToken
      },
      body: jsonData
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const responseBody = await response.json()

    return new Response(JSON.stringify(responseBody.responseData), {
      status: 200,
      headers: {
        'Content-Type': 'application/json'
      }
    })
  } catch (error) {
    if (error instanceof z.ZodError) {
      return new Response(JSON.stringify({ error: 'Invalid form data', details: error.flatten() }), {
        status: 400,
        headers: {
          'Content-Type': 'application/json'
        }
      })
    }

    console.error('Error submitting application:', error)
    return new Response(JSON.stringify({ error: 'Failed to submit application' }), {
      status: 500,
      headers: {
        'Content-Type': 'application/json'
      }
    })
  }
}

