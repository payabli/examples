import { drizzle } from 'drizzle-orm/better-sqlite3';
import { sql } from 'drizzle-orm';
import { sqliteTable, text } from 'drizzle-orm/sqlite-core';
import Database from 'better-sqlite3';

// Define the schema
export const formData = sqliteTable('formData', {
  encryptedIdentifier: text('encryptedIdentifier').primaryKey(),
  encryptedData: text('encryptedData').notNull(),
});

// Create a database connection
let sqlite;
if (typeof Bun !== 'undefined') {
  // Bun environment
  sqlite = new Bun.SQLite('form.db');
} else {
  // Node.js environment
  sqlite = new Database('form.db');
}

export const db = drizzle(sqlite);

// Drop the existing table if it exists and create a new one
db.run(sql`
  DROP TABLE IF EXISTS formData;
  CREATE TABLE formData (
    encryptedIdentifier TEXT PRIMARY KEY,
    encryptedData TEXT NOT NULL
  );
`);

