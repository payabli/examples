import { drizzle } from 'drizzle-orm/better-sqlite3'
import { sql } from 'drizzle-orm'
import { sqliteTable, text } from 'drizzle-orm/sqlite-core'
import Database from 'better-sqlite3'

// Define the schema
export const formData = sqliteTable('formData', {
  userId: text('userId').primaryKey(),
  data: text('data').notNull(),
})

// Payabli references created by a user's in-progress boarding submission, so
// a failed submission can be resumed instead of recreated (v2 rejects a second
// business with the same EIN, or a second bank account with the same numbers,
// even when the first was deactivated). JSON in `data`; see BoardingDraft.
export const boardingDrafts = sqliteTable('boardingDrafts', {
  userId: text('userId').primaryKey(),
  data: text('data').notNull(),
})

// Create a database connection
const sqlite = new Database('form.db')
export const db = drizzle(sqlite)

// Create the table if it doesn't exist
db.run(sql`
  CREATE TABLE IF NOT EXISTS formData (
    userId TEXT PRIMARY KEY,
    data TEXT NOT NULL
  )
`)

db.run(sql`
  CREATE TABLE IF NOT EXISTS boardingDrafts (
    userId TEXT PRIMARY KEY,
    data TEXT NOT NULL
  )
`)
