-- Migration: 2025_09_21_132210_add_profile_fields_to_users_table
-- Description: Add profile, business, location, payment, privacy and notification fields to users table

ALTER TABLE users
    -- Personal Information
    ADD COLUMN phone VARCHAR(255) NULL,
    ADD COLUMN address TEXT NULL,
    ADD COLUMN profile_picture VARCHAR(255) NULL,

    -- Business Information
    ADD COLUMN business_name VARCHAR(255) NULL,
    ADD COLUMN business_type VARCHAR(255) NULL,
    ADD COLUMN business_description TEXT NULL,
    ADD COLUMN business_license VARCHAR(255) NULL,
    ADD COLUMN tax_id VARCHAR(255) NULL,

    -- Location Information
    ADD COLUMN farm_address TEXT NULL,
    ADD COLUMN city VARCHAR(255) NULL,
    ADD COLUMN state VARCHAR(255) NULL,
    ADD COLUMN zip_code VARCHAR(255) NULL,
    ADD COLUMN country VARCHAR(255) NULL,
    ADD COLUMN delivery_radius INTEGER NULL,
    ADD COLUMN coordinates VARCHAR(255) NULL,

    -- Payment Information
    ADD COLUMN bank_name VARCHAR(255) NULL,
    ADD COLUMN account_number VARCHAR(255) NULL,
    ADD COLUMN routing_number VARCHAR(255) NULL,
    ADD COLUMN paypal_email VARCHAR(255) NULL,
    ADD COLUMN preferred_payment_method VARCHAR(255) NULL,

    -- Privacy Settings
    ADD COLUMN profile_visibility VARCHAR(255) NOT NULL DEFAULT 'public',
    ADD COLUMN show_contact_info BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN show_business_info BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN allow_messages BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN data_sharing BOOLEAN NOT NULL DEFAULT FALSE,

    -- Notification Preferences
    ADD COLUMN email_notifications BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN sms_notifications BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN order_notifications BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN marketing_notifications BOOLEAN NOT NULL DEFAULT FALSE;

