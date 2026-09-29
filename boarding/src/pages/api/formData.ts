import type { APIRoute } from 'astro';
import { saveFormData, loadFormData, clearFormData } from '../../lib/serverDb';
import { normalizeServerFormData, redactDraftFormData } from '../../Schema';

// Draft persistence path: enforce server-owned prefills without requiring a complete valid submission.
// Drafts are stored as plain JSON, so sensitive fields are redacted before saving and when loading older drafts.
function normalizeSerializedFormData(serialized: string) {
  const parsedData = JSON.parse(serialized);
  const normalizedData = redactDraftFormData(normalizeServerFormData(parsedData));
  return JSON.stringify(normalizedData);
}

export const POST: APIRoute = async ({ request }) => {
  try {
    const { action, userId, draftData } = await request.json();
    console.log(`Received request: action=${action}`);

    if (!action || !userId) {
      console.error('Missing action or userId');
      return new Response(JSON.stringify({ error: 'Missing action or userId' }), { 
        status: 400,
        headers: { 'Content-Type': 'application/json' }
      });
    }

    switch (action) {
      case 'save':
        if (!draftData) {
          console.error('Missing draftData for save action');
          return new Response(JSON.stringify({ error: 'Missing draftData for save action' }), { 
            status: 400,
            headers: { 'Content-Type': 'application/json' }
          });
        }
        let normalizedDraftData: string;
        try {
          normalizedDraftData = normalizeSerializedFormData(draftData);
        } catch (error) {
          console.error('Invalid serialized form data received for save action', error);
          return new Response(JSON.stringify({ error: 'Invalid draftData payload' }), {
            status: 400,
            headers: { 'Content-Type': 'application/json' }
          });
        }

        console.log(`Saving draft. Data length: ${normalizedDraftData.length}`);
        await saveFormData(userId, normalizedDraftData);
        console.log('Save operation completed');
        return new Response(JSON.stringify({ success: true }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      case 'load':
        console.log('Loading draft');
        const loadedData = await loadFormData(userId);
        const normalizedLoadedData = loadedData ? normalizeSerializedFormData(loadedData) : loadedData;
        console.log('Load operation completed, data:', normalizedLoadedData ? `found (length: ${normalizedLoadedData.length})` : 'not found');
        return new Response(JSON.stringify({ draftData: normalizedLoadedData }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      case 'clear':
        console.log('Clearing draft');
        await clearFormData(userId);
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

