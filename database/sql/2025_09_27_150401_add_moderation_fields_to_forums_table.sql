-- Migration: 2025_09_27_150401_add_moderation_fields_to_forums_table
-- Description: Add moderation fields to forums table

ALTER TABLE forums
    ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active',
    ADD COLUMN is_flagged BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN moderation_notes TEXT NULL,
    ADD COLUMN moderated_by BIGINT NULL,
    ADD COLUMN moderated_at TIMESTAMP NULL;

ALTER TABLE forums
    ADD CONSTRAINT forums_moderated_by_fkey FOREIGN KEY (moderated_by) REFERENCES users(id) ON DELETE SET NULL;

