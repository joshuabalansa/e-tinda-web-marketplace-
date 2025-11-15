-- Migration: 2025_09_21_143809_add_farmer_fields_to_orders_table
-- Description: Add farmer-specific fields to orders table

ALTER TABLE orders
    ADD COLUMN delivery_option VARCHAR(50) NOT NULL DEFAULT 'pickup' CHECK (delivery_option IN ('pickup', 'delivery')),
    ADD COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'cash' CHECK (payment_method IN ('cash', 'gcash', 'bank_transfer')),
    ADD COLUMN special_instructions TEXT NULL,
    ADD COLUMN pickup_date TIMESTAMP NULL,
    ADD COLUMN delivery_date TIMESTAMP NULL,
    ADD COLUMN farmer_notes VARCHAR(255) NULL;

