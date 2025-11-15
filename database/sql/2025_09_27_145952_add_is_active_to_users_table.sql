-- Migration: 2025_09_27_145952_add_is_active_to_users_table
-- Description: Add is_active field to users table

ALTER TABLE users
    ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT TRUE;

