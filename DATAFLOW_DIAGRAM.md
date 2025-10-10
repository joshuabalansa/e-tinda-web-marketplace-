# E-Tinda Marketplace - Dataflow Diagram

## Overview
This document illustrates the data flow patterns and system interactions within the E-Tinda Marketplace platform. The system supports three main user types (Admin, Farmer, Buyer) with distinct workflows and data interactions.

## System Architecture Overview

```mermaid
graph TB
    subgraph "Frontend Layer"
        UI[User Interface]
        Auth[Authentication]
        Cart[Shopping Cart]
    end

    subgraph "Application Layer"
        Controllers[Controllers]
        Middleware[Middleware]
        Services[Services]
    end

    subgraph "Data Layer"
        Models[Eloquent Models]
        Database[(MySQL Database)]
        Storage[File Storage]
    end

    subgraph "External Services"
        Email[Email Service]
        SMS[SMS Service]
        Payment[Payment Gateway]
    end

    UI --> Controllers
    Auth --> Middleware
    Cart --> Controllers
    Controllers --> Models
    Models --> Database
    Controllers --> Storage
    Controllers --> Email
    Controllers --> SMS
    Controllers --> Payment
```

## User Registration & Authentication Flow

```mermaid
sequenceDiagram
    participant User
    participant AuthController
    participant UserModel
    participant Database
    participant Session

    User->>AuthController: Register Request
    AuthController->>AuthController: Validate Input
    AuthController->>UserModel: Create User
    UserModel->>Database: Insert User Record
    Database-->>UserModel: User Created
    UserModel-->>AuthController: User Object
    AuthController->>Session: Create Session
    AuthController-->>User: Redirect to Dashboard

    Note over User,Session: Role-based redirect:<br/>Admin → Admin Dashboard<br/>Farmer → Farmer Dashboard<br/>Buyer → Buyer Dashboard
```

## Product Management Flow (Farmer)

```mermaid
flowchart TD
    A[Farmer Login] --> B[Farmer Dashboard]
    B --> C{Action?}

    C -->|Add Product| D[Product Creation Form]
    C -->|Manage Inventory| E[Inventory Management]
    C -->|View Orders| F[Order Management]

    D --> D1[Validate Product Data]
    D1 --> D2[Upload Product Image]
    D2 --> D3[Save to Products Table]
    D3 --> D4[Update Inventory Table]
    D4 --> B

    E --> E1[View Current Inventory]
    E1 --> E2{Transaction Type?}
    E2 -->|Adjustment| E3[Stock Adjustment]
    E2 -->|Purchase| E4[New Stock Purchase]
    E2 -->|Sale| E5[Record Sale]
    E2 -->|Loss| E6[Record Loss]
    E3 --> E7[Update Product Stock]
    E4 --> E7
    E5 --> E7
    E6 --> E7
    E7 --> B

    F --> F1[View Incoming Orders]
    F1 --> F2[Update Order Status]
    F2 --> F3[Add Farmer Notes]
    F3 --> B
```

## Shopping & Order Flow (Buyer)

```mermaid
flowchart TD
    A[Buyer Login] --> B[Browse Products]
    B --> C[Add to Cart]
    C --> D{Cart Actions}

    D -->|Continue Shopping| B
    D -->|Checkout| E[Checkout Process]

    E --> E1[Validate Cart Items]
    E1 --> E2[Group Items by Farmer]
    E2 --> E3[Calculate Totals]
    E3 --> E4[Create Order Record]
    E4 --> E5[Create Order Items]
    E5 --> E6[Update Product Stock]
    E6 --> E7[Clear Cart]
    E7 --> E8[Order Confirmation]

    E8 --> F[Order Tracking]
    F --> F1[View Order Status]
    F1 --> F2[Receive Products]
    F2 --> F3[Leave Review]
    F3 --> G[Review Processing]

    G --> G1[Save Review to Database]
    G1 --> G2[Update Product Ratings]
    G2 --> G3[Update Farmer Ratings]
```

## Multi-Vendor Order Processing

```mermaid
sequenceDiagram
    participant Buyer
    participant CheckoutController
    participant OrderModel
    participant OrderItemModel
    participant ProductModel
    participant Farmer1
    participant Farmer2

    Buyer->>CheckoutController: Submit Order
    CheckoutController->>CheckoutController: Group Items by Farmer

    Note over CheckoutController: Detect Multi-Vendor Order

    CheckoutController->>OrderModel: Create Main Order
    OrderModel->>OrderModel: Set is_multi_vendor = true

    loop For Each Product
        CheckoutController->>OrderItemModel: Create Order Item
        OrderItemModel->>OrderItemModel: Set farmer_delivery_status = 'pending'
        OrderItemModel->>ProductModel: Update Stock
    end

    CheckoutController-->>Buyer: Order Confirmation

    par Farmer 1 Processing
        Farmer1->>OrderItemModel: Update farmer_delivery_status
        Farmer1->>OrderItemModel: Set farmer_ready_at
        Farmer1->>OrderItemModel: Add farmer_notes
    and Farmer 2 Processing
        Farmer2->>OrderItemModel: Update farmer_delivery_status
        Farmer2->>OrderItemModel: Set farmer_ready_at
        Farmer2->>OrderItemModel: Add farmer_notes
    end
```

## Forum System Flow

```mermaid
flowchart TD
    A[User Login] --> B[Forum Index]
    B --> C{Action?}

    C -->|Create Post| D[Create Forum Post]
    C -->|Reply to Post| E[Create Reply]
    C -->|View Post| F[View Forum Post]

    D --> D1[Upload Video Optional]
    D1 --> D2[Save to Forums Table]
    D2 --> D3[Set Status = 'active']
    D3 --> B

    E --> E1[Upload Video Optional]
    E1 --> E2[Save to Forum Replies Table]
    E2 --> E3[Set Status = 'active']
    E3 --> E4[Increment Helpful Votes]
    E4 --> B

    F --> F1[Increment View Count]
    F1 --> F2[Display Post & Replies]
    F2 --> F3[Show Moderation Status]

    G[Admin/Moderator] --> H[Moderation Actions]
    H --> H1[Flag Content]
    H1 --> H2[Set Status]
    H2 --> H3[Add Moderation Notes]
    H3 --> H4[Assign Moderator]
```

## Review & Rating System Flow

```mermaid
sequenceDiagram
    participant Buyer
    participant OrderController
    participant ReviewModel
    participant ProductModel
    participant FarmerModel
    participant Database

    Buyer->>OrderController: Complete Order
    OrderController->>OrderController: Order Status = 'completed'

    Buyer->>ReviewModel: Submit Review
    ReviewModel->>ReviewModel: Validate Rating (1-5)
    ReviewModel->>Database: Save Review

    ReviewModel->>ProductModel: Update Product Ratings
    ProductModel->>Database: Calculate Average Rating

    ReviewModel->>FarmerModel: Update Farmer Ratings
    FarmerModel->>Database: Calculate Farmer Average

    Database-->>Buyer: Review Confirmed
```

## Inventory Management Flow

```mermaid
flowchart TD
    A[Farmer Login] --> B[Inventory Dashboard]
    B --> C{Action?}

    C -->|Add Stock| D[Stock Addition]
    C -->|Remove Stock| E[Stock Removal]
    C -->|Adjust Stock| F[Stock Adjustment]
    C -->|Record Loss| G[Loss Recording]

    D --> D1[Enter Quantity In]
    D1 --> D2[Enter Unit Cost]
    D2 --> D3[Calculate Total Value]
    D3 --> D4[Save to Inventory Table]
    D4 --> D5[Update Product Stock]
    D5 --> B

    E --> E1[Enter Quantity Out]
    E1 --> E2[Enter Reference Number]
    E2 --> E3[Save to Inventory Table]
    E3 --> E4[Update Product Stock]
    E4 --> B

    F --> F1[Enter Adjustment Amount]
    F1 --> F2[Enter Reason/Notes]
    F2 --> F3[Save to Inventory Table]
    F3 --> F4[Update Product Stock]
    F4 --> B

    G --> G1[Enter Loss Amount]
    G1 --> G2[Enter Loss Reason]
    G2 --> G3[Save to Inventory Table]
    G3 --> G4[Update Product Stock]
    G4 --> B
```

## Admin Management Flow

```mermaid
flowchart TD
    A[Admin Login] --> B[Admin Dashboard]
    B --> C{Management Area}

    C -->|User Management| D[User Administration]
    C -->|Content Moderation| E[Forum Moderation]
    C -->|Analytics| F[System Analytics]
    C -->|Reports| G[Generate Reports]

    D --> D1[View All Users]
    D1 --> D2{User Action?}
    D2 -->|Activate/Deactivate| D3[Update User Status]
    D2 -->|View Profile| D4[User Details]
    D2 -->|Delete User| D5[Soft Delete User]
    D3 --> B
    D4 --> B
    D5 --> B

    E --> E1[View Flagged Content]
    E1 --> E2{Moderation Action?}
    E2 -->|Approve| E3[Set Status = 'active']
    E2 -->|Hide| E4[Set Status = 'hidden']
    E2 -->|Delete| E5[Set Status = 'deleted']
    E3 --> E6[Add Moderation Notes]
    E4 --> E6
    E5 --> E6
    E6 --> B

    F --> F1[Sales Analytics]
    F1 --> F2[User Analytics]
    F2 --> F3[Product Analytics]
    F3 --> F4[Revenue Reports]
    F4 --> B

    G --> G1[Export User Data]
    G1 --> G2[Export Order Data]
    G2 --> G3[Export Product Data]
    G3 --> B
```

## Data Flow Patterns

### 1. User Registration Flow
```
User Input → Validation → Password Hashing → Database Insert → Session Creation → Role-based Redirect
```

### 2. Product Creation Flow
```
Farmer Input → Image Upload → Validation → Database Insert → Inventory Record → Stock Update
```

### 3. Order Processing Flow
```
Cart Items → Farmer Grouping → Order Creation → Order Items → Stock Deduction → Confirmation
```

### 4. Multi-Vendor Order Flow
```
Mixed Cart → Farmer Detection → Main Order → Individual Items → Farmer Notifications → Status Tracking
```

### 5. Review Submission Flow
```
Order Completion → Review Form → Rating Validation → Database Insert → Rating Calculations → Notifications
```

### 6. Inventory Transaction Flow
```
Transaction Input → Validation → Inventory Record → Stock Calculation → Product Update → Reporting
```

### 7. Forum Content Flow
```
User Post → Content Validation → Video Upload → Database Insert → Moderation Queue → Publication
```

### 8. Content Moderation Flow
```
Flagged Content → Admin Review → Status Decision → Moderation Notes → User Notification
```

## Key Data Relationships

### Primary Data Flows
1. **User → Products → Orders → Reviews**
2. **Farmer → Inventory → Stock Updates → Reports**
3. **Buyer → Cart → Orders → Reviews → Ratings**
4. **User → Forums → Replies → Moderation**

### Cross-Entity Flows
1. **Order Items → Farmer Status Updates**
2. **Reviews → Product/Farmer Rating Updates**
3. **Inventory → Product Stock Synchronization**
4. **Forum Posts → User Activity Tracking**

## System Integration Points

### External Service Integrations
- **Email Service**: Order confirmations, notifications
- **SMS Service**: Delivery updates, alerts
- **Payment Gateway**: Order processing, refunds
- **File Storage**: Product images, forum videos

### Internal Service Dependencies
- **Authentication Service**: All user interactions
- **Session Management**: User state persistence
- **File Management**: Image/video uploads
- **Notification Service**: User communications

## Performance Considerations

### Database Optimization
- Indexed foreign keys for fast lookups
- Composite indexes for multi-column queries
- Date-based indexes for time-series data

### Caching Strategies
- Session-based cart storage
- Product image caching
- User profile caching

### Scalability Patterns
- Pagination for large datasets
- Lazy loading for related data
- Background job processing for heavy operations

This dataflow diagram provides a comprehensive view of how data moves through the E-Tinda Marketplace system, showing the interactions between different user types, system components, and data entities.

