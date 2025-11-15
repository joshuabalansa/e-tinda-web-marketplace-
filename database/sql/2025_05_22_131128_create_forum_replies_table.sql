-- Migration: 2025_05_22_131128_create_forum_replies_table
-- Description: Create forum_replies table

CREATE TABLE forum_replies (
    id BIGSERIAL PRIMARY KEY,
    forum_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    content TEXT NOT NULL,
    video_path VARCHAR(255) NULL,
    video_original_name VARCHAR(255) NULL,
    helpful_votes INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (forum_id) REFERENCES forums(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

