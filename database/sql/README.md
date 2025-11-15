# E-Tinda Database SQL Files

This directory contains PostgreSQL-compatible SQL migration files for the E-Tinda application, optimized for **Supabase**.

## 📁 Contents

### Individual Migration Files
Each migration file corresponds to a Laravel migration and can be run independently (in order):

1. `0001_01_01_000000_create_users_table.sql` - Core user authentication tables
2. `0001_01_01_000001_create_cache_table.sql` - Cache system tables
3. `0001_01_01_000002_create_jobs_table.sql` - Queue and job processing tables
4. `2024_03_20_000000_create_products_table.sql` - Products catalog
5. `2024_03_21_000000_create_orders_table.sql` - Order management
6. `2024_03_21_000001_create_order_items_table.sql` - Order line items
7. `2024_03_22_000000_create_related_tables.sql` - Associations, buyers, farmers, inventory reports, reviews
8. `2025_01_15_000000_enhance_multi_vendor_orders.sql` - Multi-vendor support
9. `2025_05_22_131124_create_forums_table.sql` - Community forums
10. `2025_05_22_131128_create_forum_replies_table.sql` - Forum discussions
11. `2025_08_23_150638_create_wishlist_table.sql` - User wishlists
12. `2025_09_13_143406_create_inventory_table.sql` - Inventory tracking
13. `2025_09_21_132210_add_profile_fields_to_users_table.sql` - Extended user profiles
14. `2025_09_21_143809_add_farmer_fields_to_orders_table.sql` - Farmer-specific order fields
15. `2025_09_27_145952_add_is_active_to_users_table.sql` - User account status
16. `2025_09_27_150401_add_moderation_fields_to_forums_table.sql` - Forum moderation
17. `2025_09_27_150404_add_moderation_fields_to_forum_replies_table.sql` - Reply moderation

### Complete Database File
- `complete_database.sql` - **All migrations combined in the correct order** - run this single file to set up the entire database schema

## 🚀 Usage

### Option 1: Run Complete Database File (Recommended)

In Supabase SQL Editor or via `psql`:

```sql
-- Connect to your database
\i /path/to/complete_database.sql
```

Or copy the contents of `complete_database.sql` and paste into the Supabase SQL Editor.

### Option 2: Run Individual Migration Files

Run each file in numerical/chronological order:

```bash
psql -h your-supabase-host -U postgres -d your-database -f 0001_01_01_000000_create_users_table.sql
psql -h your-supabase-host -U postgres -d your-database -f 0001_01_01_000001_create_cache_table.sql
# ... continue with all files in order
```

## 🔑 Key PostgreSQL Features Used

- **BIGSERIAL** - Auto-incrementing primary keys (replaces MySQL AUTO_INCREMENT)
- **CHECK constraints** - Enum-like constraints (replaces MySQL ENUM)
- **JSONB** - Binary JSON storage for flexible data structures
- **Standard indexes** - Created separately after table definitions
- **TEXT** - Flexible text storage (replaces MySQL LONGTEXT, MEDIUMTEXT)
- **INTEGER/SMALLINT** - Standard integer types (replaces MySQL INT, TINYINT)

## ⚙️ Supabase Compatibility

All SQL files are fully compatible with **Supabase** PostgreSQL. Key considerations:

1. ✅ Uses PostgreSQL-native data types
2. ✅ CHECK constraints instead of ENUM for better flexibility
3. ✅ JSONB for JSON data (faster and more efficient than JSON)
4. ✅ Standard foreign key constraints with ON DELETE actions
5. ✅ Proper index creation for query optimization

## 📊 Database Schema Overview

### Core Tables
- **users** - User accounts (admin, farmer, buyer)
- **products** - Product catalog
- **orders** - Customer orders
- **order_items** - Order line items

### Supporting Tables
- **associations** - Farmer associations
- **buyers** - Buyer profiles
- **farmers** - Farmer profiles
- **inventory** - Inventory tracking
- **inventory_reports** - Inventory reports

### Community Features
- **forums** - Discussion forums
- **forum_replies** - Forum responses
- **reviews** - Product reviews
- **wishlists** - User wishlists

### System Tables
- **sessions** - User sessions
- **password_reset_tokens** - Password recovery
- **cache** / **cache_locks** - Application caching
- **jobs** / **job_batches** / **failed_jobs** - Queue system

## 🔐 Security Notes

- All foreign keys have proper CASCADE/SET NULL constraints
- User roles are validated with CHECK constraints
- Sensitive data fields (password, tokens) use appropriate VARCHAR lengths
- Indexes created on frequently queried columns

## 📝 Migration from Laravel

These SQL files are direct conversions from Laravel migrations and maintain the same structure and relationships. The main differences:

| Laravel/MySQL | PostgreSQL |
|--------------|------------|
| `BIGINT UNSIGNED AUTO_INCREMENT` | `BIGSERIAL` |
| `ENUM('val1', 'val2')` | `VARCHAR(50) CHECK (col IN ('val1', 'val2'))` |
| `LONGTEXT` | `TEXT` |
| `INT` | `INTEGER` |
| Inline `INDEX` | Separate `CREATE INDEX` |
| `JSON` | `JSONB` |

## 🆘 Support

For issues or questions about the database schema, please refer to the main E-Tinda documentation.

---

**Last Updated:** October 26, 2025

