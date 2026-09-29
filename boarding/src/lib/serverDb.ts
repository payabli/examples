import { sql } from 'drizzle-orm';
import { db, formData, sqlite } from '@/db';
import { redactDraftFormData } from '../Schema';

// Drafts are stored as plain JSON, so every value is redacted before it is written.
function redactSerializedDraft(data: string) {
  return JSON.stringify(redactDraftFormData(JSON.parse(data)));
}

// One-time sweep for drafts saved before redaction existed. VACUUM rebuilds the file
// so the overwritten values don't remain in freed database pages.
function redactStoredDrafts() {
  let redactedCount = 0;
  for (const row of db.select().from(formData).all()) {
    let redacted: string;
    try {
      redacted = redactSerializedDraft(row.data);
    } catch {
      console.warn('Deleting unreadable draft during redaction sweep');
      db.delete(formData).where(sql`${formData.userId} = ${row.userId}`).run();
      redactedCount++;
      continue;
    }
    if (redacted !== row.data) {
      db.update(formData).set({ data: redacted }).where(sql`${formData.userId} = ${row.userId}`).run();
      redactedCount++;
    }
  }
  if (redactedCount > 0) {
    sqlite.exec('VACUUM');
    console.log(`Redacted sensitive fields in ${redactedCount} stored draft(s)`);
  }
}

redactStoredDrafts();

export async function saveFormData(userId: string, data: string) {
  const redacted = redactSerializedDraft(data);
  await db.insert(formData).values({
    userId,
    data: redacted,
  }).onConflictDoUpdate({
    target: formData.userId,
    set: { data: redacted },
  });
}

export async function loadFormData(userId: string) {
  const result = await db.select().from(formData).where(sql`${formData.userId} = ${userId}`);
  return result[0]?.data || null;
}

export async function clearFormData(userId: string) {
  await db.delete(formData).where(sql`${formData.userId} = ${userId}`);
}
