import type { APIRoute } from 'astro';
import { saveFormData, loadFormData, clearFormData } from '../../lib/serverDb';
import { normalizeServerFormData, safeParseServerFormData } from '../../Schema';

// Draft persistence path: enforce server-owned prefills without requiring a complete valid submission.
function normalizeSerializedFormData(serialized: string) {
  const parsedData = JSON.parse(serialized);
  const normalizedData = normalizeServerFormData(parsedData);
  return JSON.stringify(normalizedData);
}

// A saved draft from a previous version of this form's schema won't validate
// against the current one (field names/shape can change between releases).
// Treat that as "no draft" rather than handing the client a stale shape it
// can't cleanly render.
function isDraftCompatibleWithCurrentSchema(serialized: string): boolean {
  try {
    const parsedData = JSON.parse(serialized);
    return safeParseServerFormData(parsedData).success;
  } catch {
    return false;
  }
}

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
        let normalizedEncryptedData: string;
        try {
          normalizedEncryptedData = normalizeSerializedFormData(encryptedData);
        } catch (error) {
          console.error('Invalid serialized form data received for save action', error);
          return new Response(JSON.stringify({ error: 'Invalid encryptedData payload' }), {
            status: 400,
            headers: { 'Content-Type': 'application/json' }
          });
        }

        console.log(`Saving data. Encrypted data length: ${normalizedEncryptedData.length}`);
        await saveFormData(encryptedIdentifier, normalizedEncryptedData);
        console.log('Save operation completed');
        return new Response(JSON.stringify({ success: true }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      case 'load':
        console.log(`Loading data for identifier: ${encryptedIdentifier}`);
        const loadedData = await loadFormData(encryptedIdentifier);
        const compatibleLoadedData =
          loadedData && isDraftCompatibleWithCurrentSchema(loadedData) ? loadedData : null;
        if (loadedData && !compatibleLoadedData) {
          console.log('Saved draft no longer matches the current form schema; discarding it.');
          await clearFormData(encryptedIdentifier);
        }
        const normalizedLoadedData = compatibleLoadedData
          ? normalizeSerializedFormData(compatibleLoadedData)
          : null;
        console.log('Load operation completed, data:', normalizedLoadedData ? `found (length: ${normalizedLoadedData.length})` : 'not found');
        if (normalizedLoadedData) {
          console.log('Loaded data (first 50 chars):', normalizedLoadedData.substring(0, 50) + '...');
        }
        return new Response(JSON.stringify({ encryptedData: normalizedLoadedData }), {
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

