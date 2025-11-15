# 🌱 Etinda Marketplace

**A Direct Farmer-to-Buyer Agricultural Marketplace Platform**

Etinda is a comprehensive web-based marketplace that connects local farmers directly with buyers, eliminating middlemen and creating an efficient agricultural supply chain. Built with Laravel 12, it supports multi-vendor operations, community knowledge sharing, and complete inventory management.

## 🎯 Project Objectives

- **Direct Marketplace**: Connect farmers directly with buyers, eliminating intermediaries
- **Community Building**: Enable knowledge sharing through forums and educational content
- **Multi-Vendor Support**: Handle products from multiple farmers in single orders
- **Complete Supply Chain**: Track inventory from farm to customer
- **Local Support**: Cater to Philippine agricultural communities with local language and payment methods

## ✨ Key Features

### 🛒 **Multi-Vendor Shopping Experience**
- Browse products from multiple farmers
- Single cart with products from different vendors
- Unified checkout process
- Coordinated delivery management

### 👥 **Three User Roles**

#### **🌾 Farmers**
- Product management (CRUD operations)
- Inventory tracking and management
- Order processing and status updates
- Sales analytics and reporting
- Profile and business settings
- Community participation in forums

#### **🛍️ Buyers**
- Product browsing and search
- Shopping cart and wishlist management
- Order history and tracking
- Multi-vendor order handling
- Community forum participation

#### **⚙️ Administrators**
- User management and role assignment
- Forum moderation and content control
- System analytics and reporting
- Platform oversight and maintenance

### 🏪 **Core Marketplace Features**
- **Product Catalog**: Categorized products with images and descriptions
- **Shopping Cart**: Session-based cart with multi-vendor support
- **Order Management**: Complete order lifecycle tracking
- **Inventory System**: Real-time stock management
- **Review System**: Product ratings and feedback
- **Wishlist**: Save products for later purchase

### 🌐 **Community Features**
- **Forums**: Knowledge sharing and discussions
- **Video Support**: Educational content uploads
- **Reply System**: Community-driven discussions
- **Moderation**: Admin content oversight

### 🌍 **Localization**
- **Languages**: English and Hiligaynon support
- **Payment Methods**: Cash on delivery, GCash, bank transfer
- **Delivery Options**: Farm pickup or home delivery
- **Local Focus**: Philippine agricultural community support

## 🏗️ Technical Architecture

### **Technology Stack**
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite (development), MySQL/PostgreSQL (production)
- **Frontend**: Blade templates with Bootstrap 5
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage system

### **Database Schema**

#### **Core Tables**
- `users` - User accounts with role-based access
- `products` - Product catalog with farmer associations
- `orders` - Order management with multi-vendor support
- `order_items` - Order-product relationships
- `forums` - Community discussion topics
- `forum_replies` - Forum discussion replies
- `wishlists` - User product wishlists
- `inventory` - Stock tracking and management
- `reviews` - Product ratings and feedback

#### **Key Relationships**
- Users → Products (One-to-Many)
- Orders → Order Items (One-to-Many)
- Products → Order Items (One-to-Many)
- Forums → Forum Replies (One-to-Many)
- Users → Wishlists (One-to-Many)

## 🚀 Installation & Setup

### **Prerequisites**
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL

### **Installation Steps**

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd e-tinda-marketplace
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Storage setup**
   ```bash
   php artisan storage:link
   ```

7. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

## 📁 Project Structure

```
e-tinda-marketplace/
├── app/
│   ├── Enums/                 # User role definitions
│   ├── Http/
│   │   ├── Controllers/       # Application controllers
│   │   │   ├── Auth/         # Authentication controllers
│   │   │   ├── Farmer/       # Farmer-specific controllers
│   │   │   └── ...
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form request validation
│   ├── Models/               # Eloquent models
│   ├── Policies/            # Authorization policies
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database schema migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   │   ├── admin/          # Admin interface
│   │   ├── farmer/         # Farmer interface
│   │   ├── buyer/          # Buyer interface
│   │   ├── shop/           # Shopping interface
│   │   └── forums/         # Forum interface
│   ├── lang/               # Localization files
│   │   ├── en/             # English translations
│   │   └── hil/            # Hiligaynon translations
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   ├── web.php             # Web routes
│   └── auth.php            # Authentication routes
└── public/                 # Public assets
```

## 🔐 User Roles & Permissions

### **Admin Role**
- Full system access
- User management (create, edit, delete, activate/deactivate)
- Forum moderation and content control
- System analytics and reporting
- Platform configuration

### **Farmer Role**
- Product management (CRUD operations)
- Inventory tracking and management
- Order processing and status updates
- Sales analytics and reports
- Profile and business settings
- Forum participation

### **Buyer Role**
- Product browsing and purchasing
- Order history and tracking
- Wishlist management
- Forum participation
- Profile management

## 🛠️ Key Features Implementation

### **Multi-Vendor Order System**
- Single order creation for products from multiple farmers
- Individual farmer order views showing only their products
- Shared order status updates affecting entire order
- Coordinated delivery management

### **Inventory Management**
- Real-time stock tracking
- Transaction history with audit trail
- Cost management and value tracking
- Reference number linking to orders

### **Forum System**
- Community knowledge sharing
- Video content support
- Reply system with helpful voting
- Admin moderation capabilities

### **Localization System**
- Dynamic language switching
- Persistent language preferences
- Complete interface translation
- Local payment method integration

## 📊 Database Schema Details

### **Users Table**
```sql
- id (Primary Key)
- name, email, password
- role (admin/farmer/buyer)
- phone, address, profile_picture
- business_name, business_type, business_description
- farm_address, city, state, zip_code
- delivery_radius, coordinates
- payment_methods, privacy_settings
- notification_preferences
- is_active (boolean)
```

### **Products Table**
```sql
- id (Primary Key)
- user_id (Foreign Key to Users)
- name, description, price_per_unit
- unit_type, stock_quantity
- harvest_date, image_url
- category, status (available/unavailable/out_of_stock)
```

### **Orders Table**
```sql
- id (Primary Key)
- user_id (Foreign Key to Users - Buyer)
- customer_information (name, email, phone, address)
- financial_details (subtotal, shipping, total)
- order_management (status, delivery_option, payment_method)
- special_instructions, pickup_date, delivery_date
```

## 🌐 API Endpoints

### **Authentication Routes**
- `POST /register` - User registration
- `POST /login` - User login
- `POST /logout` - User logout

### **Product Routes**
- `GET /shop` - Browse products
- `GET /shop/product/{id}` - View product details
- `POST /cart/add` - Add to cart
- `GET /cart` - View cart

### **Order Routes**
- `GET /checkout` - Checkout page
- `POST /checkout/process` - Process order
- `GET /buyer/orders` - View order history

### **Farmer Routes**
- `GET /farmer/dashboard` - Farmer dashboard
- `GET /farmer/products` - Manage products
- `GET /farmer/orders` - Manage orders
- `GET /farmer/inventory` - Inventory management

### **Admin Routes**
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - User management
- `GET /admin/forums` - Forum moderation

## 🎨 User Interface

### **Design System**
- **Framework**: Bootstrap 5
- **Icons**: Font Awesome
- **Color Scheme**: Green-based agricultural theme
- **Responsive**: Mobile-first design approach

### **Key Pages**
- **Welcome Page**: Hero section with featured products and categories
- **Shop**: Product catalog with filtering and search
- **Checkout**: Multi-step checkout process
- **Dashboards**: Role-specific dashboard interfaces
- **Forums**: Community discussion interface

## 🔧 Configuration

### **Environment Variables**
```env
APP_NAME=Etinda
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

### **Localization Configuration**
- Supported locales: `en` (English), `hil` (Hiligaynon)
- Default locale: `en`
- Fallback locale: `en`
- Language switching via route: `/language/{locale}`

## 🧪 Testing

### **Running Tests**
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### **Test Coverage**
- Authentication tests
- Product management tests
- Order processing tests
- Forum functionality tests
- User role permission tests

## 📈 Performance & Optimization

### **Database Optimization**
- Proper indexing on foreign keys
- Query optimization with Eloquent relationships
- Database connection pooling

### **Frontend Optimization**
- Asset compilation and minification
- Image optimization for product photos
- Lazy loading for product catalogs

### **Caching Strategy**
- Route caching for production
- View caching for static content
- Database query caching

## 🚀 Deployment

### **Production Deployment**
1. Set up production environment variables
2. Configure database connection
3. Run migrations and seeders
4. Set up file storage (local or cloud)
5. Configure web server (Apache/Nginx)
6. Set up SSL certificates
7. Configure email services

### **Docker Deployment**
```bash
# Build Docker image
docker build -t etinda-marketplace .

# Run container
docker run -p 8000:8000 etinda-marketplace
```

## 🤝 Contributing

### **Development Workflow**
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write tests for new functionality
5. Submit a pull request

### **Code Standards**
- Follow PSR-12 coding standards
- Use Laravel best practices
- Write comprehensive tests
- Document new features

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Team & Support

### **Development Team**
- **Lead Developer**: [Your Name]
- **Backend Developer**: [Team Member]
- **Frontend Developer**: [Team Member]
- **UI/UX Designer**: [Team Member]

### **Support**
- **Email**: support@etinda.com
- **Documentation**: [Link to documentation]
- **Issues**: [GitHub Issues](https://github.com/your-repo/issues)

## 🔮 Future Enhancements

### **Planned Features**
- Mobile application (React Native/Flutter)
- Advanced analytics dashboard
- Payment gateway integration
- SMS notifications
- Multi-language support expansion
- API for third-party integrations
- Advanced search with filters
- Recommendation system
- Social media integration

### **Technical Improvements**
- Microservices architecture
- Redis caching implementation
- Queue system for background jobs
- Real-time notifications
- Advanced security features
- Performance monitoring

---

**Built with ❤️ for the agricultural community**

*Connecting local farmers with the community, one harvest at a time.*