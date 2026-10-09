# 💎 GemGlitz — Haute Joaillerie Luxury E-Commerce Website

<p align="center">
  <img src="assets/images/logo.svg" alt="GemGlitz Luxury Logo" width="130">
  <br>
  <strong>A Premier Core PHP & MySQL Luxury Jewelry E-Commerce Platform</strong>
  <br>
  <em>Handcrafted Luxury &bull; Minimalist Aesthetics &bull; Production-Quality Native Codebase</em>
</p>

---

## 📋 Table of Contents
1. [🌟 Project Overview](#-project-overview)
2. [✨ Key Features](#-key-features)
3. [💻 System Requirements](#-system-requirements)
4. [🛠️ In-Depth Step-by-Step Installation Guide](#%EF%B8%8F-in-depth-step-by-step-installation-guide)
   - [Stage 1: Installing & Starting XAMPP](#stage-1-installing--starting-xampp)
   - [Stage 2: Placing Project Files in `htdocs`](#stage-2-placing-project-files-in-htdocs)
   - [Stage 3: Creating & Importing the Database (Click-by-Click)](#stage-3-creating--importing-the-database-click-by-click)
   - [Stage 4: Verifying Connection Settings (`includes/db.php`)](#stage-4-verifying-connection-settings-includesdbphp)
   - [Stage 5: Accessing the Storefront & Admin Panel](#stage-5-accessing-the-storefront--admin-panel)
5. [🔑 Demo Credentials & Test Data Matrix](#-demo-credentials--test-data-matrix)
6. [🧪 Feature Testing Walkthrough](#-feature-testing-walkthrough)
7. [❓ Comprehensive Troubleshooting & FAQ](#-comprehensive-troubleshooting--faq)
8. [📁 Complete Directory & File Architecture](#-complete-directory--file-architecture)
9. [🔒 Security & Performance Features](#-security--performance-features)

---

## 🌟 Project Overview

**GemGlitz** is a complete, production-quality luxury jewelry e-commerce website engineered using **Core PHP**, **HTML5**, **Vanilla CSS3**, **Vanilla JavaScript**, and **MySQL Database**.

It features an ultra-sleek, Apple-like minimal luxury design system with champagne gold accents (`#C8A96A`), dark mode support, real-time AJAX features, an interactive 5-stage order shipment tracker, static payment gateway simulation, and an executive Administrator Control Panel.

> [!IMPORTANT]
> **No Frameworks Used**: Built 100% natively without Laravel, React, Bootstrap, or Node.js. It runs out-of-the-box inside any standard XAMPP environment.

---

## ✨ Key Features

### 💍 Storefront & Customer Experience
- 🎨 **Minimal Luxury Aesthetics**: Elegant typography (*Playfair Display* headings & *Poppins* body font), generous whitespace, refined cards, and smooth transitions.
- 🌓 **High-Contrast Dark / Light Mode**: Instant mode toggle with crisp, fully readable text across all backgrounds.
- 🔍 **Live Search AJAX**: Instant drop-down search suggestions as you type product names, categories, or prices.
- 👁️ **Product Quick View**: Inspect product details, rating breakdown, and add to cart directly from catalog grids.
- 🎛️ **Multi-Attribute Filters**: Filter jewelry by Category, Metal Type (*18K Gold, Rose Gold, Platinum, White Gold*), Gemstone (*Diamond, Emerald, Sapphire, Solitaire*), and Price Range.
- 🛒 **Cart & Coupon Engine**: Real-time cart calculations with promo code redemption (e.g., `LUXURY10` for 10% off).
- 💳 **Static Payment Gateway**: Beautiful UI simulation for UPI, Credit/Debit Cards, NetBanking, and Cash on Delivery with automatic transaction reference generation (`TXN_GG_...`).
- 🚚 **Order Shipment Tracker**: Search by Order Number (e.g., `GG-ORD-88291`) to view a live 5-stage shipment timeline with courier tracking IDs.
- 👁️ **Password Eye Toggle**: Interactive eye icon buttons on all password fields for easy visibility switching.
- 👤 **Customer Portal**: Manage personal shipping address, order history, saved wishlist items, and password changes.

### 🛡️ Administrator Panel (`/admin`)
- 📊 **Analytics Dashboard**: Real-time sales metrics, revenue totals, recent orders table, and monthly sales trends.
- 📦 **Product Management (CRUD)**: Create, view, edit, upload product photos, manage stock levels, and toggle featured/bestseller badges.
- 📁 **Category & Coupon Management**: Add new categories and create promotional discount codes with expiration dates.
- 📦 **Order Fulfillment**: Update order statuses (`Processing` $\rightarrow$ `Packed` $\rightarrow$ `Shipped` $\rightarrow$ `Delivered` $\rightarrow$ `Cancelled`).
- 💬 **Review Moderation**: Approve or delete customer product reviews.

---

## 💻 System Requirements

Before you start, ensure your computer meets the following basic requirements:

| Component | Minimum Requirement | Recommended |
| :--- | :--- | :--- |
| **Operating System** | Windows 10/11, macOS 10.14+, or Linux | Any modern OS |
| **Web Server** | Apache 2.4 (via XAMPP / WAMP / MAMP) | XAMPP 8.1 / 8.2 |
| **PHP Version** | PHP 7.4 | **PHP 8.0 or 8.2+** |
| **Database Server** | MySQL 5.7 or MariaDB 10.4 | MariaDB 10.4+ (included with XAMPP) |
| **Web Browser** | Google Chrome, Microsoft Edge, Firefox, or Safari | Any modern browser with JS enabled |

---

## 🛠️ In-Depth Step-by-Step Installation Guide

Follow this beginner-friendly, foolproof guide to set up **GemGlitz** on any independent desktop or laptop computer.

---

### Stage 1: Installing & Starting XAMPP

If you already have XAMPP installed and running, you can skip to [Stage 2](#stage-2-placing-project-files-in-htdocs).

1. **Download XAMPP**:
   - Go to the official Apache Friends website: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
   - Download **XAMPP for Windows** (or macOS / Linux) with **PHP 8.0 or 8.2**.
2. **Install XAMPP**:
   - Run the downloaded installer (`xampp-windows-x64-...-installer.exe`).
   - Leave all default components checked (Apache, MySQL, phpMyAdmin, PHP).
   - Install to the default directory: `C:\xampp` on Windows (or `/Applications/XAMPP` on Mac).
3. **Launch XAMPP Control Panel**:
   - Open **XAMPP Control Panel** from your Start Menu or desktop shortcut.
4. **Start Web Server & Database Services**:
   - Click the **Start** button next to **Apache**. (Status indicator will turn GREEN).
   - Click the **Start** button next to **MySQL**. (Status indicator will turn GREEN).

---

### Stage 2: Placing Project Files in `htdocs`

1. Locate your downloaded **`gemglitz`** project folder.
2. Copy or move the entire **`gemglitz`** folder into the XAMPP `htdocs` directory:
   - **Windows**: `C:\xampp\htdocs\gemglitz`
   - **macOS**: `/Applications/XAMPP/htdocs/gemglitz`
   - **Linux**: `/opt/lampp/htdocs/gemglitz`
3. Verify that the files are structured properly inside `C:\xampp\htdocs\gemglitz`:
   - `index.php` should be directly inside `C:\xampp\htdocs\gemglitz\index.php`.
   - `gemglitz_import.sql` should be inside `C:\xampp\htdocs\gemglitz\gemglitz_import.sql`.

---

### Stage 3: Creating & Importing the Database (Click-by-Click)

You can import the database using **Method A (phpMyAdmin Interface)** or **Method B (Command Line)**.

#### 🔹 Method A: Using phpMyAdmin (Recommended for Beginners)

1. Open your web browser (Chrome, Edge, Firefox).
2. Type the following URL into your address bar and press Enter:
   ```text
   http://localhost/phpmyadmin/
   ```
3. **Create the Database**:
   - Click on the **Databases** tab on the top menu bar.
   - In the **Database name** field, type exactly: `gemglitz`
   - Leave the encoding dropdown as `utf8mb4_general_ci` (or default).
   - Click the **Create** button.
4. **Select the New Database**:
   - On the left sidebar list of databases, click on **`gemglitz`**.
5. **Import the SQL File**:
   - Click on the **Import** tab located on the top navigation bar.
   - Click the **Choose File** (or **Browse**) button under *File to import*.
   - Navigate to your project folder: `C:\xampp\htdocs\gemglitz\`
   - Select the file named **`gemglitz_import.sql`** and click **Open**.
   - Scroll down to the bottom of the page and click the **Import** (or **Go**) button.
6. **Confirmation**:
   - After 3–5 seconds, you will see a green success banner:
     > *"Import has been successfully finished, queries executed."*
   - You will see 12 database tables created under `gemglitz` (`users`, `products`, `categories`, `orders`, `cart`, etc.).

#### 🔹 Method B: Using Command Line / Terminal (For Advanced Users)

Open **PowerShell** or **Command Prompt** as Administrator and run:
```cmd
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS gemglitz;"
Get-Content C:\xampp\htdocs\gemglitz\gemglitz_import.sql | C:\xampp\mysql\bin\mysql.exe -u root gemglitz
```

---

### Stage 4: Verifying Connection Settings (`includes/db.php`)

Open `C:\xampp\htdocs\gemglitz\includes\db.php` in your text editor (VS Code, Notepad++, Notepad) and confirm the settings:

```php
<?php
// Database Credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'gemglitz');
define('DB_USER', 'root');
define('DB_PASS', ''); // Leave empty for default XAMPP setup
```

> [!NOTE]
> If your local MySQL setup has a custom root password, set `define('DB_PASS', 'your_password_here');`.

---

### Stage 5: Accessing the Storefront & Admin Panel

Your website setup is now **100% Complete**! You can access it immediately in your browser:

- 🛍️ **Customer Storefront Website**:
  [http://localhost/gemglitz/](http://localhost/gemglitz/)

- 🛡️ **Administrator Control Panel**:
  [http://localhost/gemglitz/admin/login.php](http://localhost/gemglitz/admin/login.php)

---

## 🔑 Demo Credentials & Test Data Matrix

The database comes pre-seeded with sample users, products, categories, orders, and promotional coupons:

### 👤 User Accounts

| Account Type | Email Address | Password | Privileges / Features |
| :--- | :--- | :--- | :--- |
| **Default Customer** | `keyadudhat@gmail.com` | `Customer@123` | Full shopping access, order tracking, address book, profile management |
| **Administrator** | `admin@gemglitz.com` | `Admin@123` | Full access to `/admin` dashboard, product CRUD, order updates, sales reports |

---

### 🎟️ Active Test Promo Coupons

| Coupon Code | Discount | Minimum Order Value | Expiry |
| :--- | :--- | :--- | :--- |
| **`LUXURY10`** | **10% OFF** | $1,000.00 | Dec 2030 |
| **`GEMGLITZ20`** | **20% OFF** | $5,000.00 | Dec 2030 |
| **`WELCOME100`** | **5% OFF** | $500.00 | Dec 2030 |

---

### 📦 Sample Trackable Orders

| Order Number | Customer | Order Total | Status | Sample Tracking Number |
| :--- | :--- | :--- | :--- | :--- |
| **`GG-ORD-88291`** | Keya Dudhat | $11,812.50 | Shipped | `RGE-99021884-NY` |

---

## 🧪 Feature Testing Walkthrough

Follow these quick walkthroughs to test every feature of the website:

### 1. Test Customer Shopping & Checkout Flow
1. Visit `http://localhost/gemglitz/`.
2. Click on **Catalog** in the top navigation bar.
3. Use the sidebar filters to select **Metal: 18K Yellow Gold** and click **Apply Filters**.
4. Hover over **"The Empress Royal Solitaire Ring"** and click the **Eye icon** for Quick View, or click **Add to Cart**.
5. Click the **Shopping Bag icon** in the navbar to open your Cart.
6. Type coupon code `LUXURY10` into the coupon box and click **Apply Coupon**. You will see a 10% discount applied!
7. Click **Proceed to Checkout**.
8. Fill in or review shipping details and click **Proceed to Payment**.
9. Select your preferred payment method (e.g., Credit Card or UPI) and click **Authorize & Complete Order**.
10. You will be redirected to the **Order Success Page** with a printable invoice!

### 2. Test Real-Time Order Shipment Tracking
1. Click **Track Order** in the navbar.
2. Enter Order ID: `GG-ORD-88291` (or your newly generated order number).
3. Click **Track Order**. You will see an interactive 5-stage shipment timeline with courier details!

### 3. Test Admin Control Panel
1. Go to `http://localhost/gemglitz/admin/login.php`.
2. Log in with Email: `admin@gemglitz.com` / Password: `Admin@123`.
3. View executive sales metrics and recent orders on the **Dashboard**.
4. Click **Manage Products** in the admin sidebar $\rightarrow$ Add a new luxury item with photos and stock.
5. Click **Manage Orders** $\rightarrow$ Change an order status from `Processing` to `Shipped`.

---

## ❓ Comprehensive Troubleshooting & FAQ

### Q1: Apache or MySQL fails to start in XAMPP (Port Conflict)?
- **Cause**: Skype, VMware, or IIS might be using Port 80 or 3306.
- **Solution**:
  - In XAMPP Control Panel, click **Netstat** to check which app is using Port 80.
  - Close Skype or VMware, then click **Start** on Apache/MySQL again.
  - Alternatively, change Apache port to `8080` via `Config` $\rightarrow$ `httpd.conf` (Access via `http://localhost:8080/gemglitz/`).

### Q2: "Database Connection Failed: SQLSTATE[HY000] [2002]" Error?
- **Cause**: MySQL service is not running in XAMPP.
- **Solution**: Open XAMPP Control Panel and ensure the **MySQL** module is started (GREEN indicator).

### Q3: "Warning: Cannot modify header information - headers already sent"?
- **Cause**: PHP output buffer was flushed prematurely before a `header('Location: ...')` redirect.
- **Solution**: Output buffering (`ob_start()`) is enabled globally in `includes/functions.php`. Make sure you do not add extra spaces or HTML code above `<?php` tags in custom files.

### Q4: "Error #1451: Cannot delete or update a parent row: foreign key constraint fails"?
- **Cause**: Importing database without disabling foreign key checks.
- **Solution**: Always import `gemglitz_import.sql` which includes `@OLD_FOREIGN_KEY_CHECKS` rules to prevent foreign key errors during table creation.

---

## 📁 Complete Directory & File Architecture

```
c:\xampp\htdocs\gemglitz\
├── admin/                     # Administrator Management Portal
│   ├── index.php              # Admin Dashboard & Metric Cards
│   ├── products.php           # Product Management CRUD (Add/Edit/Delete)
│   ├── categories.php         # Category Management
│   ├── orders.php             # Order Fulfillment & Tracking Updates
│   ├── users.php              # Registered User Directory
│   ├── coupons.php            # Promotional Coupon Code Manager
│   ├── reviews.php            # Customer Review Moderation
│   ├── login.php              # Admin Authentication Login
│   └── logout.php             # Admin Logout Handler
├── api/                       # Asynchronous AJAX Endpoint APIs
│   ├── cart_action.php        # AJAX Add/Remove/Update Cart Items
│   ├── wishlist_action.php    # AJAX Wishlist Toggle
│   ├── search.php             # Live Search Autocomplete JSON API
│   └── quick_view.php         # Quick View Modal Data Endpoint
├── assets/                    # Static Web Assets
│   ├── css/
│   │   ├── style.css          # Master Luxury & Dark Mode Stylesheet
│   │   └── admin.css          # Admin Dashboard Stylesheet
│   ├── js/
│   │   ├── main.js            # Storefront Interactivity & AJAX Handlers
│   │   └── admin.js           # Admin Dashboard Interactivity & Charts
│   └── images/                # High-Res Product & Category Photographs
├── database/                  # SQL Schema Archives
│   └── gemglitz.sql           # Standard Database Backup
├── includes/                  # Modular Server-Side PHP Components
│   ├── db.php                 # PDO Database Connection
│   ├── functions.php          # Security, Sessions, CSRF, Cart Helpers
│   ├── header.php             # HTML Head, Fonts & Meta Tags
│   ├── navbar.php             # Navigation Bar & Live Search UI
│   ├── footer.php             # Footer & Floating Contact Widgets
│   └── auth.php               # Auth Guard Middleware Helpers
├── index.php                  # Storefront Homepage
├── shop.php                   # Jewelry Catalog & Multi-Attribute Vault
├── product.php                # Product Details Page & Customer Reviews
├── cart.php                   # Shopping Cart & Coupon Discount Form
├── checkout.php               # Shipping Address & Order Summary
├── payment.php                # Static Payment Gateway Simulation
├── order_success.php          # Invoice Receipt & Confirmation Page
├── order_tracking.php         # Real-time Order Shipment Tracking
├── login.php                  # Customer Account Sign-in
├── signup.php                 # Customer Account Registration
├── profile.php                # Customer Account Dashboard & Orders
├── gemglitz_import.sql        # 1-Click Database Setup File
└── README.md                  # Detailed Setup & Documentation Guide
```

---

## 🔒 Security & Performance Features

- **Prepared Statements**: All SQL queries use PDO prepared statements with parameterized inputs to **100% prevent SQL Injection**.
- **Password Hashing**: User passwords are encrypted using native PHP `password_hash()` (BCRYPT) and verified via `password_verify()`.
- **Input Sanitization**: Every user input is sanitized using `htmlspecialchars()`, `strip_tags()`, and `trim()` to prevent Cross-Site Scripting (**XSS**).
- **CSRF Token Protection**: All forms utilize session-bound anti-CSRF validation tokens.
- **Session Security**: Session cookies are configured securely to prevent session fixation and hijacking.

---

<p align="center">
  <strong>GemGlitz Haute Joaillerie E-Commerce Project</strong><br>
  Built with precision for high-end luxury e-commerce experiences.
</p>