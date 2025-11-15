-- Migration: 2025_09_27_150404_add_moderation_fields_to_forum_replies_table
-- Description: Add moderation fields to forum_replies table

ALTER TABLE forum_replies
    ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active',
    ADD COLUMN is_flagged BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN moderation_notes TEXT NULL,
    ADD COLUMN moderated_by BIGINT NULL,
    ADD COLUMN moderated_at TIMESTAMP NULL;

ALTER TABLE forum_replies
    ADD CONSTRAINT forum_replies_moderated_by_fkey FOREIGN KEY (moderated_by) REFERENCES users(id) ON DELETE SET NULL;

