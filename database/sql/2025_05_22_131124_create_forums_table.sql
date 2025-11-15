-- Migration: 2025_05_22_131124_create_forums_table
-- Description: Create forums table

CREATE TABLE forums (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(255) NOT NULL,
    video_path VARCHAR(255) NULL,
    video_original_name VARCHAR(255) NULL,
    views INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

