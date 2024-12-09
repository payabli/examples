import type { APIRoute } from 'astro';
import { saveFormData, loadFormData, clearFormData } from '../../lib/serverDb';

export const POST: APIRoute = async ({ request }) => {
  try {
    const { action, encryptedIdentifier, encryptedData } = await request.json();
    console.log(`Received request: action=${action}, encryptedIdentifier=${encryptedIdentifier}`);

    if (!action || !encryptedIdentifier) {
      console.error('Missing action or encryptedIdentifier');
      return new Response(JSON.stringify({ error: 'Missing action or encryptedIdentifier' }), { 
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    switch (action) {
      case 'save':
        if (!encryptedData) {
          console.error('Missing encryptedData for save action');
          return new Response(JSON.stringify({ error: 'Missing encryptedData for save action' }), { 
            status: 400,
            headers: { 'Content-Type': 'application/json' }
          });
        }
        console.log(`Saving data. Encrypted data length: ${encryptedData.length}`);
        await saveFormData(encryptedIdentifier, encryptedData);
        console.log('Save operation completed');
        return new Response(JSON.stringify({ success: true }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      case 'load':
        console.log(`Loading data for identifier: ${encryptedIdentifier}`);
        const loadedData = await loadFormData(encryptedIdentifier);
        console.log('Load operation completed, data:', loadedData ? `found (length: ${loadedData.length})` : 'not found');
        if (loadedData) {
          console.log('Loaded data (first 50 chars):', loadedData.substring(0, 50) + '...');
        }
        return new Response(JSON.stringify({ encryptedData: loadedData }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      case 'clear':
        console.log(`Clearing data for identifier: ${encryptedIdentifier}`);
        await clearFormData(encryptedIdentifier);
        console.log('Clear operation completed');
        return new Response(JSON.stringify({ success: true }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      default:
        console.error(`Invalid action: ${action}`);
        return new Response(JSON.stringify({ error: 'Invalid action' }), { 
          status: 400,
          headers: { 'Content-Type': 'application/json' }
        });
    }
  } catch (error: unknown) {
    console.error('Server error:', error);
    const errorMessage = error instanceof Error ? error.message : 'Unknown error occurred';
    console.error('Error details:', errorMessage);
    return new Response(JSON.stringify({ error: 'Internal server error', details: errorMessage }), { 
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};

// Add this line to export all HTTP methods
export const ALL: APIRoute = POST;

