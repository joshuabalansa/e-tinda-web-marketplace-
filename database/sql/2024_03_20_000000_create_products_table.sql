-- Migration: 2024_03_20_000000_create_products_table
-- Description: Create products table

CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price_per_unit DECIMAL(10, 2) NOT NULL,
    unit_type VARCHAR(255) NOT NULL,
    stock_quantity INTEGER NOT NULL,
    harvest_date DATE NOT NULL,
    image_url VARCHAR(255) NULL,
    category VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'available' CHECK (status IN ('available', 'unavailable', 'out_of_stock')),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

