import type { APIRoute } from 'astro';
import { saveFormData, loadFormData, clearFormData } from '../../lib/serverDb';

export const POST: APIRoute = async ({ request }) => {
  try {
    const { action, deviceToken, data } = await request.json();
    console.log(`Received request: action=${action}, deviceToken=${deviceToken}`);

    if (!action || !deviceToken) {
      console.error('Missing action or deviceToken');
      return new Response(JSON.stringify({ error: 'Missing action or deviceToken' }), { 
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    switch (action) {
      case 'save':
        if (!data) {
          console.error('Missing data for save action');
          return new Response(JSON.stringify({ error: 'Missing data for save action' }), { 
            status: 400,
            headers: { 'Content-Type': 'application/json' }
          });
        }
        await saveFormData(deviceToken, JSON.stringify(data));
        console.log('Save operation completed');
        return new Response(JSON.stringify({ success: true }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      case 'load':
        const loadedData = await loadFormData(deviceToken);
        console.log('Load operation completed');
        return new Response(JSON.stringify({ data: loadedData }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      case 'clear':
        await clearFormData(deviceToken);
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
    return new Response(JSON.stringify({ error: 'Internal server error', details: errorMessage }), { 
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};

// Add this line to export all HTTP methods
export const ALL: APIRoute = POST;

