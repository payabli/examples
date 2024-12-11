import { sql } from 'drizzle-orm';
import { db, formData } from '@/db';

export async function saveFormData(deviceToken: string, data: string) {
  await db.insert(formData).values({
    deviceToken,
    data,
  }).onConflictDoUpdate({
    target: formData.deviceToken,
    set: { data },
  });
}

export async function loadFormData(deviceToken: string) {
  const result = await db.select().from(formData).where(sql`${formData.deviceToken} = ${deviceToken}`);
  return result[0]?.data || null;
}

export async function clearFormData(deviceToken: string) {
  await db.delete(formData).where(sql`${formData.deviceToken} = ${deviceToken}`);
}

