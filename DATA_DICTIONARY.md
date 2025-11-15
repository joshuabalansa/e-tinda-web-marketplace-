# E-Tinda Marketplace - Data Dictionary

## Overview
This document provides a comprehensive data dictionary for the E-Tinda Marketplace database schema. The system supports a multi-vendor agricultural marketplace with farmers, buyers, and administrators.

## Database Tables

### 1. users
**Purpose**: Core user authentication and profile management
**Primary Key**: id

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| name | varchar(255) | NOT NULL | User's full name |
| email | varchar(255) | UNIQUE, NOT NULL | User's email address |
| email_verified_at | timestamp | NULLABLE | Email verification timestamp |
| password | varchar(255) | NOT NULL | Hashed password |
| role | enum | DEFAULT 'buyer' | User role: admin, farmer, buyer |
| is_active | boolean | DEFAULT true | Account activation status |
| remember_token | varchar(100) | NULLABLE | Remember me token |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

**Extended Profile Fields** (Added via migration):
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| phone | varchar(255) | NULLABLE | Contact phone number |
| address | text | NULLABLE | Physical address |
| profile_picture | varchar(255) | NULLABLE | Profile image URL |
| business_name | varchar(255) | NULLABLE | Business/farm name |
| business_type | varchar(255) | NULLABLE | Type of business |
| business_description | text | NULLABLE | Business description |
| business_license | varchar(255) | NULLABLE | Business license number |
| tax_id | varchar(255) | NULLABLE | Tax identification number |
| farm_address | text | NULLABLE | Farm location address |
| city | varchar(255) | NULLABLE | City |
| state | varchar(255) | NULLABLE | State/Province |
| zip_code | varchar(255) | NULLABLE | Postal/ZIP code |
| country | varchar(255) | NULLABLE | Country |
| delivery_radius | integer | NULLABLE | Delivery radius in km |
| coordinates | varchar(255) | NULLABLE | GPS coordinates |
| bank_name | varchar(255) | NULLABLE | Bank name |
| account_number | varchar(255) | NULLABLE | Bank account number |
| routing_number | varchar(255) | NULLABLE | Bank routing number |
| paypal_email | varchar(255) | NULLABLE | PayPal email |
| preferred_payment_method | varchar(255) | NULLABLE | Preferred payment method |
| profile_visibility | varchar(255) | DEFAULT 'public' | Profile visibility setting |
| show_contact_info | boolean | DEFAULT true | Show contact information |
| show_business_info | boolean | DEFAULT true | Show business information |
| allow_messages | boolean | DEFAULT true | Allow messages from others |
| data_sharing | boolean | DEFAULT false | Data sharing consent |
| email_notifications | boolean | DEFAULT true | Email notification preference |
| sms_notifications | boolean | DEFAULT false | SMS notification preference |
| order_notifications | boolean | DEFAULT true | Order notification preference |
| marketing_notifications | boolean | DEFAULT false | Marketing notification preference |

### 2. products
**Purpose**: Product catalog management
**Primary Key**: id
**Foreign Keys**: user_id → users(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique product identifier |
| user_id | bigint | FOREIGN KEY, NOT NULL | Farmer who owns the product |
| name | varchar(255) | NOT NULL | Product name |
| description | text | NOT NULL | Product description |
| price_per_unit | decimal(10,2) | NOT NULL | Price per unit |
| unit_type | varchar(255) | NOT NULL | Unit of measurement (kg, lb, piece, etc.) |
| stock_quantity | integer | NOT NULL | Available stock quantity |
| harvest_date | date | NOT NULL | Date of harvest |
| image_url | varchar(255) | NULLABLE | Product image URL |
| category | varchar(255) | NOT NULL | Product category |
| status | enum | DEFAULT 'available' | Product status: available, unavailable, out_of_stock |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

### 3. orders
**Purpose**: Order management and tracking
**Primary Key**: id
**Foreign Keys**: user_id → users(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique order identifier |
| user_id | bigint | FOREIGN KEY, NOT NULL | Buyer who placed the order |
| first_name | varchar(255) | NOT NULL | Buyer's first name |
| last_name | varchar(255) | NOT NULL | Buyer's last name |
| email | varchar(255) | NOT NULL | Buyer's email |
| phone | varchar(255) | NOT NULL | Buyer's phone number |
| address | varchar(255) | NOT NULL | Delivery address |
| city | varchar(255) | NOT NULL | Delivery city |
| state | varchar(255) | NOT NULL | Delivery state |
| zip | varchar(255) | NOT NULL | Delivery ZIP code |
| subtotal | decimal(10,2) | NOT NULL | Order subtotal |
| shipping | decimal(10,2) | NOT NULL | Shipping cost |
| total | decimal(10,2) | NOT NULL | Total order amount |
| status | varchar(255) | DEFAULT 'pending' | Order status |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

**Extended Order Fields** (Added via migrations):
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| delivery_option | enum | DEFAULT 'pickup' | Delivery option: pickup, delivery |
| payment_method | enum | DEFAULT 'cash' | Payment method: cash, gcash, bank_transfer |
| special_instructions | text | NULLABLE | Special delivery instructions |
| pickup_date | timestamp | NULLABLE | Scheduled pickup date |
| delivery_date | timestamp | NULLABLE | Scheduled delivery date |
| farmer_notes | varchar(255) | NULLABLE | Notes from farmer |
| pickup_schedule | json | NULLABLE | Pickup times per farmer (multi-vendor) |
| delivery_notes | text | NULLABLE | Additional delivery instructions |
| is_multi_vendor | boolean | DEFAULT false | Multi-vendor order flag |

### 4. order_items
**Purpose**: Individual items within orders
**Primary Key**: id
**Foreign Keys**: order_id → orders(id), product_id → products(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique order item identifier |
| order_id | bigint | FOREIGN KEY, NOT NULL | Associated order |
| product_id | bigint | FOREIGN KEY, NOT NULL | Product being ordered |
| quantity | integer | NOT NULL | Quantity ordered |
| price | decimal(10,2) | NOT NULL | Price at time of order |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

**Extended Order Item Fields** (Added via migration):
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| farmer_delivery_status | varchar(255) | DEFAULT 'pending' | Individual item delivery status |
| farmer_ready_at | timestamp | NULLABLE | When farmer has item ready |
| farmer_notes | text | NULLABLE | Farmer-specific notes |

### 5. associations
**Purpose**: Farmer associations and cooperatives
**Primary Key**: association_id

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| association_id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique association identifier |
| name | varchar(100) | NOT NULL | Association name |
| location | text | NOT NULL | Association location |
| total_members | integer | DEFAULT 0 | Number of members |
| date_established | date | NOT NULL | Establishment date |
| contact_person | varchar(100) | NOT NULL | Contact person name |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

### 6. buyers
**Purpose**: Buyer-specific information
**Primary Key**: buyer_id

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| buyer_id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique buyer identifier |
| preferred_payment_method | enum | NOT NULL | Payment preference: GCash, Palawan Pay, COD |
| buyer_rating | decimal(3,2) | DEFAULT 0.00 | Buyer rating (0.00-5.00) |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

### 7. farmers
**Purpose**: Farmer-specific information
**Primary Key**: farmer_id
**Foreign Keys**: association_id → associations(association_id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| farmer_id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique farmer identifier |
| association_id | bigint | FOREIGN KEY, NULLABLE | Associated farmer association |
| farm_location | text | NOT NULL | Farm location details |
| land_size | decimal(10,2) | NOT NULL | Farm land size |
| years_of_experience | integer | DEFAULT 0 | Years of farming experience |
| government_id_url | varchar(255) | NULLABLE | Government ID document URL |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

### 8. inventory_reports
**Purpose**: Inventory tracking and reporting
**Primary Key**: report_id
**Foreign Keys**: farmer_id → farmers(farmer_id), product_id → products(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| report_id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique report identifier |
| farmer_id | bigint | FOREIGN KEY, NOT NULL | Farmer who owns the inventory |
| product_id | bigint | FOREIGN KEY, NOT NULL | Product being tracked |
| quantity_sold | integer | NOT NULL | Quantity sold |
| remaining_stock | integer | NOT NULL | Remaining stock |
| report_date | timestamp | DEFAULT CURRENT_TIMESTAMP | Report date |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

### 9. reviews
**Purpose**: Product and seller reviews
**Primary Key**: review_id
**Foreign Keys**: order_id → orders(id), buyer_id → buyers(buyer_id), product_id → products(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| review_id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique review identifier |
| order_id | bigint | FOREIGN KEY, NOT NULL | Associated order |
| buyer_id | bigint | FOREIGN KEY, NOT NULL | Buyer who wrote the review |
| product_id | bigint | FOREIGN KEY, NOT NULL | Product being reviewed |
| rating | tinyint | NOT NULL | Rating (1-5 stars) |
| comment | text | NULLABLE | Review comment |
| review_date | timestamp | DEFAULT CURRENT_TIMESTAMP | Review date |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

### 10. forums
**Purpose**: Community discussion forums
**Primary Key**: id
**Foreign Keys**: user_id → users(id), moderated_by → users(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique forum post identifier |
| user_id | bigint | FOREIGN KEY, NOT NULL | User who created the post |
| title | varchar(255) | NOT NULL | Post title |
| content | text | NOT NULL | Post content |
| category | varchar(255) | NOT NULL | Post category |
| video_path | varchar(255) | NULLABLE | Video file path |
| video_original_name | varchar(255) | NULLABLE | Original video filename |
| views | integer | DEFAULT 0 | Number of views |
| status | enum | DEFAULT 'active' | Post status: active, pending, hidden, deleted |
| is_flagged | boolean | DEFAULT false | Flagged for moderation |
| moderation_notes | text | NULLABLE | Moderation notes |
| moderated_by | bigint | FOREIGN KEY, NULLABLE | Moderator user ID |
| moderated_at | timestamp | NULLABLE | Moderation timestamp |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

### 11. forum_replies
**Purpose**: Replies to forum posts
**Primary Key**: id
**Foreign Keys**: forum_id → forums(id), user_id → users(id), moderated_by → users(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique reply identifier |
| forum_id | bigint | FOREIGN KEY, NOT NULL | Associated forum post |
| user_id | bigint | FOREIGN KEY, NOT NULL | User who wrote the reply |
| content | text | NOT NULL | Reply content |
| video_path | varchar(255) | NULLABLE | Video file path |
| video_original_name | varchar(255) | NULLABLE | Original video filename |
| helpful_votes | integer | DEFAULT 0 | Number of helpful votes |
| status | enum | DEFAULT 'active' | Reply status: active, pending, hidden, deleted |
| is_flagged | boolean | DEFAULT false | Flagged for moderation |
| moderation_notes | text | NULLABLE | Moderation notes |
| moderated_by | bigint | FOREIGN KEY, NULLABLE | Moderator user ID |
| moderated_at | timestamp | NULLABLE | Moderation timestamp |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

### 12. wishlists
**Purpose**: User wishlist management
**Primary Key**: id
**Foreign Keys**: user_id → users(id), product_id → products(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique wishlist item identifier |
| user_id | bigint | FOREIGN KEY, NOT NULL | User who added to wishlist |
| product_id | bigint | FOREIGN KEY, NOT NULL | Product in wishlist |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

**Unique Constraint**: (user_id, product_id) - Ensures a user can only add a product to wishlist once

### 13. inventory
**Purpose**: Detailed inventory tracking and transactions
**Primary Key**: id
**Foreign Keys**: farmer_id → users(id), product_id → products(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT | Unique inventory record identifier |
| farmer_id | bigint | FOREIGN KEY, NOT NULL | Farmer who owns the inventory |
| product_id | bigint | FOREIGN KEY, NOT NULL | Product being tracked |
| quantity_in | integer | DEFAULT 0 | Quantity added to inventory |
| quantity_out | integer | DEFAULT 0 | Quantity removed from inventory |
| current_stock | integer | DEFAULT 0 | Current stock level |
| unit_cost | decimal(10,2) | NULLABLE | Cost per unit |
| total_value | decimal(10,2) | DEFAULT 0 | Total inventory value |
| transaction_type | varchar(255) | DEFAULT 'adjustment' | Transaction type: adjustment, purchase, sale, loss |
| notes | text | NULLABLE | Transaction notes |
| transaction_date | date | NOT NULL | Transaction date |
| reference_number | varchar(255) | NULLABLE | Reference number for tracking |
| created_at | timestamp | NOT NULL | Record creation timestamp |
| updated_at | timestamp | NOT NULL | Record update timestamp |

**Indexes**:
- (farmer_id, product_id) - Composite index for farmer-product lookups
- transaction_date - Index for date-based queries
- transaction_type - Index for transaction type filtering

### 14. password_reset_tokens
**Purpose**: Password reset token management
**Primary Key**: email

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| email | varchar(255) | PRIMARY KEY | User's email address |
| token | varchar(255) | NOT NULL | Reset token |
| created_at | timestamp | NULLABLE | Token creation timestamp |

### 15. sessions
**Purpose**: User session management
**Primary Key**: id
**Foreign Keys**: user_id → users(id)

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | varchar(255) | PRIMARY KEY | Session identifier |
| user_id | bigint | FOREIGN KEY, NULLABLE | Associated user |
| ip_address | varchar(45) | NULLABLE | User's IP address |
| user_agent | text | NULLABLE | User's browser agent |
| payload | longtext | NOT NULL | Session data |
| last_activity | integer | NOT NULL | Last activity timestamp |

**Indexes**:
- user_id - Index for user-based session lookups
- last_activity - Index for session cleanup

## Database Relationships

### Primary Relationships
1. **users** → **products** (1:many) - Farmers can have multiple products
2. **users** → **orders** (1:many) - Buyers can have multiple orders
3. **orders** → **order_items** (1:many) - Orders contain multiple items
4. **products** → **order_items** (1:many) - Products can be in multiple order items
5. **users** → **forums** (1:many) - Users can create multiple forum posts
6. **forums** → **forum_replies** (1:many) - Forum posts can have multiple replies
7. **users** → **forum_replies** (1:many) - Users can write multiple replies
8. **users** → **wishlists** (1:many) - Users can have multiple wishlist items
9. **products** → **wishlists** (1:many) - Products can be in multiple wishlists
10. **users** → **inventory** (1:many) - Farmers can have multiple inventory records
11. **products** → **inventory** (1:many) - Products can have multiple inventory records

### Secondary Relationships
1. **associations** → **farmers** (1:many) - Associations can have multiple farmers
2. **orders** → **reviews** (1:many) - Orders can have multiple reviews
3. **buyers** → **reviews** (1:many) - Buyers can write multiple reviews
4. **products** → **reviews** (1:many) - Products can have multiple reviews
5. **farmers** → **inventory_reports** (1:many) - Farmers can have multiple inventory reports
6. **products** → **inventory_reports** (1:many) - Products can have multiple inventory reports

## Key Features

### Multi-Vendor Support
- Orders can contain items from multiple farmers
- `is_multi_vendor` flag identifies multi-vendor orders
- `pickup_schedule` JSON field stores pickup times per farmer
- Individual `farmer_delivery_status` for each order item

### Content Moderation
- Forum posts and replies support moderation workflow
- Status tracking: active, pending, hidden, deleted
- Flagging system for inappropriate content
- Moderator assignment and notes

### Inventory Management
- Detailed transaction tracking with `inventory` table
- Support for different transaction types: adjustment, purchase, sale, loss
- Value tracking with unit costs and total values
- Reference number system for transaction tracking

### User Profiles
- Comprehensive profile fields for personal and business information
- Privacy and notification preferences
- Payment method preferences
- Location and delivery radius settings

### Review System
- Product and seller reviews linked to orders
- Rating system (1-5 stars)
- Comment support
- Buyer rating tracking

## Data Integrity

### Constraints
- Foreign key constraints ensure referential integrity
- Unique constraints prevent duplicate entries where appropriate
- Enum constraints limit values to predefined options
- Default values provide sensible defaults

### Indexes
- Primary keys on all tables
- Foreign key indexes for performance
- Composite indexes for common query patterns
- Date-based indexes for time-series queries

## Security Considerations

### Sensitive Data
- Passwords are hashed
- Payment information is stored separately
- Government IDs are stored as URLs (not direct files)
- Profile visibility controls

### Access Control
- Role-based access (admin, farmer, buyer)
- Account activation status
- Content moderation controls
- Privacy settings for user information

This data dictionary provides a comprehensive overview of the E-Tinda Marketplace database schema, supporting a full-featured agricultural marketplace with multi-vendor capabilities, content management, and detailed tracking systems.
