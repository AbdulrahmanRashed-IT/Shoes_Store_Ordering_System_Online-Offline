Shoe Store E-Commerce Platform 👟🛒

🌟 Overview
A complete PHP-based e-commerce solution for selling shoes online with:

👨‍💻 Admin dashboard for inventory management

🛍️ User-friendly shopping interface

🔍 Product search and category browsing

🛒 Order processing system

✨ Features
Frontend
🏠 Responsive homepage with featured products

🔍 Advanced product search functionality

🗂️ Category-based product browsing

👟 Detailed product pages

👤 User registration/login system

📞 Contact page

Admin Panel
📊 Dashboard with sales analytics

👟 Product management (add/edit/delete)

🏷️ Category management

👥 User management

📦 Order processing

🛠️ Installation
Requirements
PHP 7.4+ (Tested on PHP 8.1)

MySQL 5.7+

Apache/Nginx web server

Composer (for potential dependencies)

Setup Instructions
Clone the repository:

bash
git clone https://github.com/yourusername/shoe-store.git
Import the database:

bash
mysql -u username -p shoe_store < shoes_store.sql
Configure database connection in config/constant.php

Set proper permissions for uploads folder:

bash
chmod -R 755 images/ category/ shoes/
📂 Project Structure
text
shoe-store/
├── admin/                # Admin panel
│   ├── dashboard.php     # Admin dashboard
│   ├── login_admin.php   # Admin login
│   └── logout.php        # Admin logout
├── config/
│   └── constant.php      # Configuration file
├── css/                  # CSS files
├── images/               # Store images
├── category/             # Category images
├── shoes/                # Product images
└── partials_front/       # Frontend components
    ├── footer.php        # Site footer
    ├── menu.php          # Navigation menu
    └── ...               # Other partials
🔧 Configuration
Edit config/constant.php with your database credentials:

php
<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'shoe_store');
?>
🌐 Usage
Admin Access
Navigate to /admin/login_admin.php

Use default credentials (change after first login):

Username: admin

Password: admin123

Customer Features
Browse products by category

Search for specific shoes

Register account

Place orders

📊 Database
The system uses MySQL with tables for:

Users (admin/customers)

Products

Categories
Orders

(See full schema in shoes_store.sql)
