# E-Tinda Marketplace - Gane and Sarson Data Flow Diagram (DFD)

## Overview
This document presents the E-Tinda Marketplace system using Gane and Sarson Data Flow Diagram notation, showing processes, data stores, external entities, and data flows.

## DFD Symbols Legend
- **Process**: Rectangle with rounded corners
- **Data Store**: Rectangle with open right side
- **External Entity**: Rectangle
- **Data Flow**: Arrow with label

## Context Diagram (Level 0)

```
┌─────────────┐    Order Request    ┌─────────────────┐
│             │ ──────────────────► │                 │
│   Farmer    │                     │                 │
│             │ ◄────────────────── │  E-Tinda        │
│             │    Product Updates  │  Marketplace    │
└─────────────┘                     │                 │
                                     │                 │
┌─────────────┐    Product Search   │                 │
│             │ ──────────────────► │                 │
│   Buyer     │                     │                 │
│             │ ◄────────────────── │                 │
│             │    Order Confirmation│                 │
└─────────────┘                     │                 │
                                     │                 │
┌─────────────┐    System Reports   │                 │
│             │ ◄────────────────── │                 │
│   Admin     │                     │                 │
│             │ ──────────────────► │                 │
│             │    Management Commands│                 │
└─────────────┘                     └─────────────────┘
```

## Level 1 DFD - Main Processes

```
┌─────────────┐
│   Farmer    │
└─────────────┘
       │
       │ Product Data
       ▼
┌─────────────────┐    Product Info    ┌─────────────┐
│  1.0 Manage     │ ─────────────────► │    D1       │
│     Products    │                    │  Products   │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Inventory Data                         │ Product Data
       ▼                                        ▼
┌─────────────────┐    Inventory Records ┌─────────────┐
│  2.0 Manage     │ ──────────────────► │    D2       │
│     Inventory   │                    │ Inventory   │
└─────────────────┘                    └─────────────┘
       │
       │ Stock Updates
       ▼
┌─────────────┐
│   Buyer     │
└─────────────┘
       │
       │ Order Request
       ▼
┌─────────────────┐    Order Data     ┌─────────────┐
│  3.0 Process    │ ─────────────────► │    D3       │
│     Orders      │                    │   Orders    │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Order Items                            │ Order Info
       ▼                                        ▼
┌─────────────────┐    Order Items    ┌─────────────┐
│  4.0 Manage     │ ─────────────────► │    D4       │
│   Order Items   │                    │Order Items  │
└─────────────────┘                    └─────────────┘
       │
       │ Order Status
       ▼
┌─────────────────┐    Review Data    ┌─────────────┐
│  5.0 Process    │ ─────────────────► │    D5       │
│     Reviews     │                    │   Reviews   │
└─────────────────┘                    └─────────────┘
       │
       │ Rating Updates
       ▼
┌─────────────┐
│   Admin     │
└─────────────┘
       │
       │ Management Data
       ▼
┌─────────────────┐    User Data      ┌─────────────┐
│  6.0 Manage     │ ─────────────────► │    D6       │
│     Users       │                    │   Users    │
└─────────────────┘                    └─────────────┘
```

## Level 2 DFD - Detailed Process Breakdown

### Process 1.0: Manage Products

```
┌─────────────┐
│   Farmer    │
└─────────────┘
       │
       │ Product Details
       ▼
┌─────────────────┐    Product Info    ┌─────────────┐
│  1.1 Create     │ ─────────────────► │    D1       │
│     Product     │                    │  Products   │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Product Updates                        │ Product Data
       ▼                                        ▼
┌─────────────────┐    Updated Info   ┌─────────────┐
│  1.2 Update     │ ─────────────────► │    D1       │
│     Product     │                    │  Products   │
└─────────────────┘                    └─────────────┘
       │
       │ Image Data
       ▼
┌─────────────────┐    Image Files    ┌─────────────┐
│  1.3 Upload     │ ─────────────────► │    D7       │
│     Images      │                    │   Images   │
└─────────────────┘                    └─────────────┘
```

### Process 2.0: Manage Inventory

```
┌─────────────┐
│   Farmer    │
└─────────────┘
       │
       │ Transaction Data
       ▼
┌─────────────────┐    Inventory Records ┌─────────────┐
│  2.1 Record     │ ──────────────────► │    D2       │
│   Transaction   │                    │ Inventory   │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Stock Updates                          │ Current Stock
       ▼                                        ▼
┌─────────────────┐    Updated Stock  ┌─────────────┐
│  2.2 Update     │ ─────────────────► │    D1       │
│     Stock       │                    │  Products   │
└─────────────────┘                    └─────────────┘
       │
       │ Report Data
       ▼
┌─────────────────┐    Reports        ┌─────────────┐
│  2.3 Generate   │ ─────────────────► │    D8       │
│     Reports     │                    │  Reports   │
└─────────────────┘                    └─────────────┘
```

### Process 3.0: Process Orders

```
┌─────────────┐
│   Buyer     │
└─────────────┘
       │
       │ Cart Data
       ▼
┌─────────────────┐    Order Data     ┌─────────────┐
│  3.1 Create     │ ─────────────────► │    D3       │
│     Order       │                    │   Orders    │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Order Items                            │ Order Info
       ▼                                        ▼
┌─────────────────┐    Order Items    ┌─────────────┐
│  3.2 Create     │ ─────────────────► │    D4       │
│   Order Items   │                    │Order Items  │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Stock Deduction                        │ Product Data
       ▼                                        ▼
┌─────────────────┐    Stock Updates  ┌─────────────┐
│  3.3 Update     │ ─────────────────► │    D1       │
│     Stock       │                    │  Products   │
└─────────────────┘                    └─────────────┘
       │
       │ Confirmation
       ▼
┌─────────────┐
│   Buyer     │
└─────────────┘
```

### Process 4.0: Manage Order Items (Multi-Vendor)

```
┌─────────────┐
│   Farmer    │
└─────────────┘
       │
       │ Status Updates
       ▼
┌─────────────────┐    Updated Status ┌─────────────┐
│  4.1 Update     │ ─────────────────► │    D4       │
│   Item Status   │                    │Order Items  │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Ready Notifications                    │ Order Info
       ▼                                        ▼
┌─────────────────┐    Notifications ┌─────────────┐
│  4.2 Send       │ ─────────────────► │    D9       │
│   Notifications │                    │Notifications│
└─────────────────┘                    └─────────────┘
       │
       │ Pickup Schedule
       ▼
┌─────────────────┐    Schedule Data  ┌─────────────┐
│  4.3 Manage     │ ─────────────────► │    D3       │
│   Pickup        │                    │   Orders    │
└─────────────────┘                    └─────────────┘
```

### Process 5.0: Process Reviews

```
┌─────────────┐
│   Buyer     │
└─────────────┘
       │
       │ Review Data
       ▼
┌─────────────────┐    Review Info    ┌─────────────┐
│  5.1 Submit     │ ─────────────────► │    D5       │
│     Review      │                    │   Reviews   │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Rating Calculations                    │ Review Data
       ▼                                        ▼
┌─────────────────┐    Updated Ratings ┌─────────────┐
│  5.2 Calculate  │ ─────────────────► │    D1       │
│     Ratings     │                    │  Products   │
└─────────────────┘                    └─────────────┘
       │
       │ Farmer Ratings
       ▼
┌─────────────────┐    Farmer Ratings ┌─────────────┐
│  5.3 Update     │ ─────────────────► │    D6       │
│   Farmer Rating │                    │   Users    │
└─────────────────┘                    └─────────────┘
```

### Process 6.0: Manage Users

```
┌─────────────┐
│   Admin     │
└─────────────┘
       │
       │ User Management
       ▼
┌─────────────────┐    User Data      ┌─────────────┐
│  6.1 Manage    │ ─────────────────► │    D6       │
│   User Accounts │                    │   Users    │
└─────────────────┘                    └─────────────┘
       │                                        │
       │ Profile Updates                        │ User Info
       ▼                                        ▼
┌─────────────────┐    Profile Data   ┌─────────────┐
│  6.2 Update     │ ─────────────────► │    D6       │
│   Profiles      │                    │   Users    │
└─────────────────┘                    └─────────────┘
       │
       │ Association Data
       ▼
┌─────────────────┐    Association   ┌─────────────┐
│  6.3 Manage     │ ─────────────────► │    D10      │
│   Associations  │                    │Associations │
└─────────────────┘                    └─────────────┘
```

## Data Stores (D1-D10)

| Data Store | Description | Contents |
|------------|-------------|----------|
| **D1** | Products | Product information, pricing, stock levels, images |
| **D2** | Inventory | Transaction records, stock movements, costs |
| **D3** | Orders | Order details, customer info, totals, status |
| **D4** | Order Items | Individual items, quantities, farmer status |
| **D5** | Reviews | Customer reviews, ratings, comments |
| **D6** | Users | User accounts, profiles, roles, preferences |
| **D7** | Images | Product images, forum videos, profile pictures |
| **D8** | Reports | Inventory reports, sales analytics, summaries |
| **D9** | Notifications | System notifications, alerts, messages |
| **D10** | Associations | Farmer associations, cooperatives, groups |

## External Entities

| Entity | Description | Interactions |
|--------|-------------|--------------|
| **Farmer** | Agricultural producers | Creates products, manages inventory, fulfills orders |
| **Buyer** | Customers purchasing products | Browses products, places orders, leaves reviews |
| **Admin** | System administrators | Manages users, moderates content, generates reports |

## Data Flow Descriptions

### Primary Data Flows

1. **Product Data Flow**
   - Farmer → Product Creation → Products Store
   - Products Store → Product Updates → Inventory Store

2. **Order Processing Flow**
   - Buyer → Order Request → Orders Store
   - Orders Store → Order Items → Order Items Store
   - Order Items Store → Stock Updates → Products Store

3. **Multi-Vendor Order Flow**
   - Order Items Store → Farmer Status Updates → Order Items Store
   - Order Items Store → Pickup Schedule → Orders Store

4. **Review Processing Flow**
   - Buyer → Review Submission → Reviews Store
   - Reviews Store → Rating Calculations → Products Store
   - Reviews Store → Farmer Rating Updates → Users Store

5. **Inventory Management Flow**
   - Farmer → Transaction Data → Inventory Store
   - Inventory Store → Stock Updates → Products Store
   - Inventory Store → Report Generation → Reports Store

### Secondary Data Flows

1. **User Management Flow**
   - Admin → User Management → Users Store
   - Users Store → Profile Updates → Users Store

2. **Content Management Flow**
   - Users → Forum Posts → Forums Store
   - Admin → Content Moderation → Forums Store

3. **Notification Flow**
   - System Processes → Notifications → Notifications Store
   - Notifications Store → User Alerts → External Entities

## Process Specifications

### Process 1.0: Manage Products
- **Input**: Product details, images, pricing
- **Output**: Product records, inventory updates
- **Logic**: Validate product data, store in database, update inventory

### Process 2.0: Manage Inventory
- **Input**: Transaction data, quantities, costs
- **Output**: Inventory records, stock updates, reports
- **Logic**: Record transactions, calculate stock levels, generate reports

### Process 3.0: Process Orders
- **Input**: Cart data, customer information
- **Output**: Order records, order items, stock updates
- **Logic**: Create order, process items, update stock, send confirmation

### Process 4.0: Manage Order Items
- **Input**: Farmer status updates, pickup schedules
- **Output**: Updated order items, notifications
- **Logic**: Update item status, manage pickup schedules, send notifications

### Process 5.0: Process Reviews
- **Input**: Review data, ratings
- **Output**: Review records, updated ratings
- **Logic**: Store reviews, calculate average ratings, update product/farmer ratings

### Process 6.0: Manage Users
- **Input**: User management commands, profile updates
- **Output**: Updated user records, association data
- **Logic**: Manage user accounts, update profiles, handle associations

## System Boundaries

The E-Tinda Marketplace system includes:
- **Internal Processes**: All business logic and data processing
- **Data Stores**: All database tables and file storage
- **External Entities**: Users (Farmers, Buyers, Admins)

The system interfaces with external services for:
- Email notifications
- SMS alerts
- Payment processing
- File storage

This Gane and Sarson DFD provides a structured view of the E-Tinda Marketplace system, showing how data flows between processes, data stores, and external entities in a clear, hierarchical manner.

