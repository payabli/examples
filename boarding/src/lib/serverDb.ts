import { sql } from 'drizzle-orm';
import { db, formData } from '@/db';

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

