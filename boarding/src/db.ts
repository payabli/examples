import { drizzle } from 'drizzle-orm/better-sqlite3'
import { sql } from 'drizzle-orm'
import { sqliteTable, text } from 'drizzle-orm/sqlite-core'
import Database from 'better-sqlite3'

// Define the schema
export const formData = sqliteTable('formData', {
  deviceToken: text('deviceToken').primaryKey(),
  data: text('data').notNull(),
})

// Create a database connection
const sqlite = new Database('form.db')
export const db = drizzle(sqlite)

// Create the table if it doesn't exist
db.run(sql`
  CREATE TABLE IF NOT EXISTS formData (
    deviceToken TEXT PRIMARY KEY,
    data TEXT NOT NULL
  )
`)
