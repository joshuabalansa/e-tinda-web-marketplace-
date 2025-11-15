-- Migration: 2024_03_22_000000_create_related_tables
-- Description: Create associations, buyers, farmers, inventory_reports, and reviews tables

-- Create associations table
CREATE TABLE associations (
    association_id BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location TEXT NOT NULL,
    total_members INTEGER NOT NULL DEFAULT 0,
    date_established DATE NOT NULL,
    contact_person VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create buyers table
CREATE TABLE buyers (
    buyer_id BIGSERIAL PRIMARY KEY,
    preferred_payment_method VARCHAR(50) NOT NULL CHECK (preferred_payment_method IN ('GCash', 'Palawan Pay', 'COD')),
    buyer_rating DECIMAL(3, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create farmers table
CREATE TABLE farmers (
    farmer_id BIGSERIAL PRIMARY KEY,
    association_id BIGINT NULL,
    farm_location TEXT NOT NULL,
    land_size DECIMAL(10, 2) NOT NULL,
    years_of_experience INTEGER NOT NULL DEFAULT 0,
    government_id_url VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (association_id) REFERENCES associations(association_id) ON DELETE SET NULL
);

-- Create inventory_reports table
CREATE TABLE inventory_reports (
    report_id BIGSERIAL PRIMARY KEY,
    farmer_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    quantity_sold INTEGER NOT NULL,
    remaining_stock INTEGER NOT NULL,
    report_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (farmer_id) REFERENCES farmers(farmer_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Create reviews table
CREATE TABLE reviews (
    review_id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    buyer_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    rating SMALLINT NOT NULL,
    comment TEXT NULL,
    review_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES buyers(buyer_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

