-- Migration: 2025_01_15_000000_enhance_multi_vendor_orders
-- Description: Add enhanced multi-vendor order support to orders and order_items tables

-- Alter orders table
ALTER TABLE orders
    ADD COLUMN pickup_schedule JSONB NULL,
    ADD COLUMN delivery_notes TEXT NULL,
    ADD COLUMN is_multi_vendor BOOLEAN NOT NULL DEFAULT FALSE;

-- Alter order_items table
ALTER TABLE order_items
    ADD COLUMN farmer_delivery_status VARCHAR(255) NOT NULL DEFAULT 'pending',
    ADD COLUMN farmer_ready_at TIMESTAMP NULL,
    ADD COLUMN farmer_notes TEXT NULL;

