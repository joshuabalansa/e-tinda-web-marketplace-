-- Migration: 2025_09_13_143406_create_inventory_table
-- Description: Create inventory table

CREATE TABLE inventory (
    id BIGSERIAL PRIMARY KEY,
    farmer_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    quantity_in INTEGER NOT NULL DEFAULT 0,
    quantity_out INTEGER NOT NULL DEFAULT 0,
    current_stock INTEGER NOT NULL DEFAULT 0,
    unit_cost DECIMAL(10, 2) NULL,
    total_value DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    transaction_type VARCHAR(255) NOT NULL DEFAULT 'adjustment',
    notes TEXT NULL,
    transaction_date DATE NOT NULL,
    reference_number VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (farmer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE INDEX inventory_farmer_id_product_id_index ON inventory(farmer_id, product_id);
CREATE INDEX inventory_transaction_date_index ON inventory(transaction_date);
CREATE INDEX inventory_transaction_type_index ON inventory(transaction_type);

