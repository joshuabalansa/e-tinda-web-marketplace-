CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'buyer' CHECK (role IN ('admin', 'farmer', 'buyer')),
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

CREATE INDEX sessions_user_id_index ON sessions(user_id);
CREATE INDEX sessions_last_activity_index ON sessions(last_activity);

CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);

CREATE INDEX jobs_queue_index ON jobs(queue);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
);

CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

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

CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    zip VARCHAR(255) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

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

CREATE TABLE buyers (
    buyer_id BIGSERIAL PRIMARY KEY,
    preferred_payment_method VARCHAR(50) NOT NULL CHECK (preferred_payment_method IN ('GCash', 'Palawan Pay', 'COD')),
    buyer_rating DECIMAL(3, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

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

ALTER TABLE orders
    ADD COLUMN pickup_schedule JSONB NULL,
    ADD COLUMN delivery_notes TEXT NULL,
    ADD COLUMN is_multi_vendor BOOLEAN NOT NULL DEFAULT FALSE;

ALTER TABLE order_items
    ADD COLUMN farmer_delivery_status VARCHAR(255) NOT NULL DEFAULT 'pending',
    ADD COLUMN farmer_ready_at TIMESTAMP NULL,
    ADD COLUMN farmer_notes TEXT NULL;

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

CREATE TABLE wishlists (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE (user_id, product_id)
);

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

ALTER TABLE users
    ADD COLUMN phone VARCHAR(255) NULL,
    ADD COLUMN address TEXT NULL,
    ADD COLUMN profile_picture VARCHAR(255) NULL,
    ADD COLUMN business_name VARCHAR(255) NULL,
    ADD COLUMN business_type VARCHAR(255) NULL,
    ADD COLUMN business_description TEXT NULL,
    ADD COLUMN business_license VARCHAR(255) NULL,
    ADD COLUMN tax_id VARCHAR(255) NULL,
    ADD COLUMN farm_address TEXT NULL,
    ADD COLUMN city VARCHAR(255) NULL,
    ADD COLUMN state VARCHAR(255) NULL,
    ADD COLUMN zip_code VARCHAR(255) NULL,
    ADD COLUMN country VARCHAR(255) NULL,
    ADD COLUMN delivery_radius INTEGER NULL,
    ADD COLUMN coordinates VARCHAR(255) NULL,
    ADD COLUMN bank_name VARCHAR(255) NULL,
    ADD COLUMN account_number VARCHAR(255) NULL,
    ADD COLUMN routing_number VARCHAR(255) NULL,
    ADD COLUMN paypal_email VARCHAR(255) NULL,
    ADD COLUMN preferred_payment_method VARCHAR(255) NULL,
    ADD COLUMN profile_visibility VARCHAR(255) NOT NULL DEFAULT 'public',
    ADD COLUMN show_contact_info BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN show_business_info BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN allow_messages BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN data_sharing BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN email_notifications BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN sms_notifications BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN order_notifications BOOLEAN NOT NULL DEFAULT TRUE,
    ADD COLUMN marketing_notifications BOOLEAN NOT NULL DEFAULT FALSE;

ALTER TABLE orders
    ADD COLUMN delivery_option VARCHAR(50) NOT NULL DEFAULT 'pickup' CHECK (delivery_option IN ('pickup', 'delivery')),
    ADD COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'cash' CHECK (payment_method IN ('cash', 'gcash', 'bank_transfer')),
    ADD COLUMN special_instructions TEXT NULL,
    ADD COLUMN pickup_date TIMESTAMP NULL,
    ADD COLUMN delivery_date TIMESTAMP NULL,
    ADD COLUMN farmer_notes VARCHAR(255) NULL;

ALTER TABLE users
    ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT TRUE;

ALTER TABLE forums
    ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active',
    ADD COLUMN is_flagged BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN moderation_notes TEXT NULL,
    ADD COLUMN moderated_by BIGINT NULL,
    ADD COLUMN moderated_at TIMESTAMP NULL;

ALTER TABLE forums
    ADD CONSTRAINT forums_moderated_by_fkey FOREIGN KEY (moderated_by) REFERENCES users(id) ON DELETE SET NULL;

ALTER TABLE forum_replies
    ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active',
    ADD COLUMN is_flagged BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN moderation_notes TEXT NULL,
    ADD COLUMN moderated_by BIGINT NULL,
    ADD COLUMN moderated_at TIMESTAMP NULL;

ALTER TABLE forum_replies
    ADD CONSTRAINT forum_replies_moderated_by_fkey FOREIGN KEY (moderated_by) REFERENCES users(id) ON DELETE SET NULL;

