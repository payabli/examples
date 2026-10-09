import { sql } from 'drizzle-orm';
import { db, formData, boardingDrafts, sqlite } from '@/db';
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

// References to the Payabli records a user's boarding submission has created
// so far. Saved after every step so a later attempt can update them instead
// of creating duplicates. Holds no applicant data, only Payabli references
// and hashed bank account keys, so unlike form drafts it needs no redaction.
export type BoardingDraft = {
  businessReference: string;
  paypointReference: string;
  applicationReference?: string;
  // Keyed by role and position in the form, e.g. `contact:0`, `owner:1`.
  people: { key: string; personReference: string }[];
  // Keyed by a hash of routing + account number (never the raw numbers).
  paymentMethods: { key: string; paymentMethodReference: string }[];
};

export async function saveBoardingDraft(userId: string, draft: BoardingDraft) {
  const data = JSON.stringify(draft);
  await db.insert(boardingDrafts).values({ userId, data }).onConflictDoUpdate({
    target: boardingDrafts.userId,
    set: { data },
  });
}

export async function loadBoardingDraft(userId: string): Promise<BoardingDraft | null> {
  const result = await db.select().from(boardingDrafts).where(sql`${boardingDrafts.userId} = ${userId}`);
  return result[0] ? (JSON.parse(result[0].data) as BoardingDraft) : null;
}

export async function clearBoardingDraft(userId: string) {
  await db.delete(boardingDrafts).where(sql`${boardingDrafts.userId} = ${userId}`);
}
