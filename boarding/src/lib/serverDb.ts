import Database from 'better-sqlite3';
import { drizzle } from 'drizzle-orm/better-sqlite3';
import { sql } from 'drizzle-orm';
import { sqliteTable, text } from 'drizzle-orm/sqlite-core';

// Define the schema
export const formData = sqliteTable('formData', {
  deviceToken: text('deviceToken').primaryKey(),
  data: text('data').notNull(),
});

// Create a database connection
const sqlite = new Database('form.db');
export const db = drizzle(sqlite);

// Create the table if it doesn't exist
db.run(sql`
  CREATE TABLE IF NOT EXISTS formData (
    deviceToken TEXT PRIMARY KEY,
    data TEXT NOT NULL
  )
`);

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

