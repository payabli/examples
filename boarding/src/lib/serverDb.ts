import { sql } from 'drizzle-orm';
import { db, formData, boardingDrafts } from '@/db';

export async function saveFormData(userId: string, data: string) {
  await db.insert(formData).values({
    userId,
    data,
  }).onConflictDoUpdate({
    target: formData.userId,
    set: { data },
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
// of creating duplicates.
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
