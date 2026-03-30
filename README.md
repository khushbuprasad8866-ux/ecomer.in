# POPA E-commerce Website

A high-quality, colorful e-commerce website built with HTML, CSS, JavaScript, and PHP.

## Features

### 🛍️ Customer Features
- **Modern UI/UX**: Beautiful, colorful design with smooth animations
- **User Authentication**: Secure login and registration system
- **Product Catalog**: Browse products with categories, search, and filters
- **Shopping Cart**: Add to cart with quantity management
- **Checkout Process**: Complete order placement with multiple payment options
- **Order Tracking**: View order history and status
- **Responsive Design**: Works perfectly on all devices

### 💳 Payment Methods
- **Cash on Delivery (COD)**: Traditional payment method
- **UPI Integration**: Modern digital payments with QR code support
- **Credit/Debit Cards**: Ready for integration
- **Net Banking**: Banking payment options

### 🎨 Admin Panel Features
- **Dashboard**: Complete overview with statistics
- **Product Management**: Add, edit, delete products with inventory tracking
- **Order Management**: View and manage customer orders
- **User Management**: Customer database management
- **Settings**: Configure store settings and payment methods
- **Low Stock Alerts**: Automatic notifications for inventory management

### 🚀 Technical Features
- **Secure Architecture**: SQL injection protection, XSS prevention
- **Database Management**: MySQL with optimized queries
- **Session Management**: Secure user sessions
- **File Uploads**: Product image management
- **Error Handling**: Comprehensive error management
- **SEO Friendly**: Clean URLs and meta tags

## Installation

### Prerequisites
- XAMPP/WAMP/MAMP (or similar web server)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

### Setup Instructions

1. **Extract the Files**
   ```
   Place the 'popa-website' folder in your htdocs directory
   ```

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `popa_ecommerce`
   - Import the SQL file: `config/database.sql`

3. **Configuration**
   - Open `config/database.php`
   - Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'popa_ecommerce');
   ```

4. **File Permissions**
   - Ensure the `uploads/` folder is writable
   - Set permissions to 755 for uploads directory

5. **Access the Website**
   - Frontend: http://localhost/other/popa-website/
   - Admin Panel: http://localhost/other/popa-website/admin/

## Default Login

### Admin Access
- **Email**: admin@popa.com
- **Password**: password

### Customer Registration
- Click "Register" on the homepage to create a new account

## Project Structure

```
popa-website/
├── admin/                  # Admin panel files
│   ├── index.php          # Admin dashboard
│   ├── products.php       # Product management
│   ├── settings.php       # Store settings
│   └── ...
├── assets/                # Static assets
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   ├── js/
│   │   └── script.js      # JavaScript functions
│   └── images/            # Product images
├── config/                # Configuration files
│   ├── database.php       # Database connection
│   └── database.sql       # Database schema
├── includes/              # Helper functions
│   └── functions.php      # Common functions
├── uploads/               # File upload directory
├── index.php              # Homepage
├── login.php              # User login
├── register.php           # User registration
├── products.php           # Product listing
├── cart.php               # Shopping cart
├── checkout.php           # Checkout process
├── upi_payment.php        # UPI payment page
└── ...
```

## Key Features Explained

### 🎨 Modern Design
- **Colorful UI**: Purple and pink gradient theme
- **Smooth Animations**: CSS transitions and JavaScript effects
- **Responsive Layout**: Mobile-first design approach
- **Interactive Elements**: Hover effects and micro-interactions

### 🛒 E-commerce Functionality
- **Product Management**: Full CRUD operations for products
- **Inventory Tracking**: Stock quantity management
- **Category System**: Organized product categories
- **Search & Filter**: Advanced product search capabilities
- **Shopping Cart**: Session-based cart management
- **Order Processing**: Complete order workflow

### 💰 Payment Integration
- **UPI Support**: QR code generation and UPI ID configuration
- **COD Option**: Traditional cash on delivery
- **Payment Tracking**: Transaction ID verification
- **Settings Panel**: Easy payment method configuration

### 🔧 Admin Features
- **Dashboard Statistics**: Real-time data overview
- **Product CRUD**: Complete product management
- **Order Management**: View and update order status
- **User Management**: Customer database
- **Settings Control**: Store configuration options
- **Low Stock Alerts**: Inventory management warnings

## Security Features

- **SQL Injection Protection**: Prepared statements
- **XSS Prevention**: Input sanitization
- **CSRF Protection**: Token-based security
- **Password Hashing**: Secure password storage
- **Session Security**: Secure session management
- **Input Validation**: Comprehensive form validation

## Customization

### Branding
- Update site name in `config/database.php` settings table
- Modify colors in `assets/css/style.css` CSS variables
- Replace logo in header sections

### Payment Methods
- Configure UPI settings in admin panel
- Add new payment methods via settings
- Integrate payment gateways as needed

### Products
- Add sample products via admin panel
- Upload product images
- Set pricing and inventory

## Support

For technical support or questions:
- 📧 Email: support@popa.com
- 📞 Phone: +91 9876543210
- 🌐 Website: www.popa.com

## License

This project is for educational and demonstration purposes. Feel free to modify and use according to your needs.

---

**Note**: This is a complete e-commerce solution with all essential features. The code is well-structured, secure, and ready for production use with proper hosting setup.
