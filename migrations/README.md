# Database Migrations

## Directory Structure
- `schema/` - DDL changes (CREATE, ALTER, DROP)
- `data/` - DML changes (INSERT, UPDATE, DELETE)

## Naming Convention
`YYYYMMDD_HHMMSS_description.sql`

Example: `20251025_150000_add_user_table.sql`

## Running Migrations
```bash
make migrate         # Run all pending migrations
make migrate-create  # Create new migration file
make db-export       # Export current schema
```

## Migration File Format
```sql
-- Migration: Add new feature
-- Date: 2025-10-25
-- Author: Your Name

-- Up Migration
ALTER TABLE shop_product ADD COLUMN new_field VARCHAR(255);

-- Rollback (comment for reference)
-- ALTER TABLE shop_product DROP COLUMN new_field;
```
