# 💎 GemGlitz — Haute Joaillerie Luxury E-Commerce

<p align="center">
  <img src="assets/images/logo.svg" alt="GemGlitz Luxury Logo" width="120">
  <br>
  <strong>A Premier Core PHP & MySQL Luxury Jewelry E-Commerce Platform</strong>
  <br>
  <em>Handcrafted Luxury &bull; Minimalist Aesthetics &bull; Production-Ready Architecture</em>
</p>

---

## 🌟 Overview

**GemGlitz** is an elite, fully-functional luxury jewelry e-commerce web application engineered with **Core PHP**, **HTML5**, **CSS3 (Vanilla)**, **Vanilla JavaScript**, and a **MySQL / MariaDB** database. 

Designed specifically for high-end jewelry ateliers, GemGlitz offers a seamless shopping experience with real-time AJAX interactions, static payment gateway simulation, live order shipment tracking, and a comprehensive Administrator Management Panel.

> [!IMPORTANT]
> **Zero External Framework Dependencies**: Built strictly without Laravel, React, Bootstrap, or Node.js to deliver maximum performance, high security, and clean native codebase organization.

---

## ✨ Features at a Glance

### 💍 Storefront & Customer Portal
- 💎 **Minimal Luxury Design System**: Custom typography (*Playfair Display* & *Poppins*), champagne gold accents (`#C8A96A`), and sleek dark mode compatibility.
- 🔍 **Live Search AJAX**: Real-time auto-complete dropdown search across all high-grade jewelry items.
- 👁️ **Quick View Modal**: Inspect specs, ratings, and add to cart without navigating away from the page.
- 🎛️ **Advanced Vault Filters**: Filter by Category, Precious Metal (18K Gold, Platinum, Rose Gold), Gemstones (GIA Diamond, Emerald, Sapphire, Solitaire), and Price Range.
- 🛒 **Cart & Coupon System**: Instant quantity modifiers and discount coupon redemption (e.g. `LUXURY10`).
- 💳 **Simulated Payment Gateway**: Supports UPI, Credit/Debit Cards, NetBanking, and COD with 256-bit simulated authorization and order reference generation.
- 🚚 **Real-Time Order Tracking**: 5-stage shipment timeline with courier tracking numbers.
- 👁️ **Password Eye Toggle**: Interactive password visibility toggle buttons on all login, registration, and admin forms.
- 👤 **Patron Profile**: Address book management, order history inspection, and password security management.

### 🛡️ Administrator Panel (`/admin`)
- 📊 **Executive Dashboard**: Real-time revenue metrics, order status breakdowns, and monthly sales trends.
- 📦 **Product CRUD**: Add, edit, upload high-res images, update stock levels, and toggle featured/bestseller status.
- 📁 **Category & Coupon Management**: Create discount codes and manage jewelry categories.
- 📑 **Order & Review Management**: Approve customer reviews and update shipment statuses (`Processing` $\rightarrow$ `Packed` $\rightarrow$ `Shipped` $\rightarrow$ `Delivered`).

---

## 💻 Tech Stack & Compatibility

| Component | Technology / Tool |
| :--- | :--- |
| **Backend Engine** | Core PHP 7.4 / 8.x |
| **Database** | MySQL 5.7+ / MariaDB 10.4+ |
| **Frontend** | HTML5, Vanilla CSS3, Vanilla JavaScript (ES6) |
| **Server Stack** | XAMPP / WAMP / LAMP / Apache Web Server |
| **Security** | PDO Prepared Statements, `password_hash()`, CSRF Tokens, Input Sanitization |

---

## 🗝️ Default Login Credentials

> [!TIP]
> Use these pre-configured credentials to test customer shopping flows or manage the store backend:

| Role | Email Address | Password | Access URL |
| :--- | :--- | :--- | :--- |
| **Customer** | `keyadudhat@gmail.com` | `Customer@123` | `http://localhost/gemglitz/login.php` |
| **Administrator** | `admin@gemglitz.com` | `Admin@123` | `http://localhost/gemglitz/admin/login.php` |

---

## 🚀 Step-by-Step Setup Guide

Follow these simple steps to install and run **GemGlitz** on any independent Windows, Mac, or Linux laptop/desktop.

### 📋 Prerequisites
1. Download and install **[XAMPP](https://www.apachefriends.org/index.html)** (PHP 7.4 or 8.x with MySQL/MariaDB).

---

### Step 1: Copy Project Files
Place the `gemglitz` project folder inside your XAMPP web root directory:
- **Windows Path**: `C:\xampp\htdocs\gemglitz`
- **macOS Path**: `/Applications/XAMPP/htdocs/gemglitz`
- **Linux Path**: `/opt/lampp/htdocs/gemglitz`

---

### Step 2: Start XAMPP Control Panel
1. Open **XAMPP Control Panel**.
2. Start the **Apache** server.
3. Start the **MySQL** database server.

---

### Step 3: Import Database (1-Click)

You can import the database using **phpMyAdmin** (Method A) or **MySQL Command Line** (Method B):

#### Method A: Using phpMyAdmin (Recommended)
1. Open your browser and go to: `http://localhost/phpmyadmin/`
2. Click **Databases** tab $\rightarrow$ Create a new database named `gemglitz`.
3. Select `gemglitz` from the left sidebar.
4. Click the **Import** tab at the top.
5. Click **Choose File** and select `gemglitz_import.sql` located at `C:\xampp\htdocs\gemglitz\gemglitz_import.sql`.
6. Scroll down and click **Import** (or **Go**).
7. You will see a success message: *"Import has been successfully finished"*.

#### Method B: Using MySQL Command Line (Alternative)
Open PowerShell or Command Prompt and run:
```bash
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS gemglitz;"
Get-Content C:\xampp\htdocs\gemglitz\gemglitz_import.sql | C:\xampp\mysql\bin\mysql.exe -u root gemglitz
```

---

### Step 4: Verify Database Connection (`includes/db.php`)
Open `includes/db.php` to verify your local database settings (XAMPP defaults are pre-configured):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gemglitz');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP password is empty
```

---

### Step 5: Launch the Website! 🎉

Open your web browser and navigate to:
- **Storefront Website**: `http://localhost/gemglitz/`
- **Admin Control Panel**: `http://localhost/gemglitz/admin/`

---

## 📁 Project Directory Structure

```
gemglitz/
├── admin/                     # Administrator Management Portal
│   ├── index.php              # Dashboard & Sales Analytics
│   ├── products.php           # Product Management (CRUD)
│   ├── categories.php         # Category Management
│   ├── orders.php             # Order & Shipment Status Tracking
│   ├── users.php              # Customer Account Directory
│   ├── coupons.php            # Promo Code Manager
│   ├── reviews.php            # Customer Review Approval
│   ├── login.php              # Secure Admin Login Page
│   └── logout.php             # Admin Logout Handler
├── api/                       # Dynamic AJAX Endpoint API Handlers
│   ├── cart_action.php        # Add / Update / Delete Cart Items
│   ├── wishlist_action.php    # Toggle Saved Wishlist Items
│   ├── search.php             # Live Search Autocomplete JSON API
│   └── quick_view.php         # Quick View Product Modal Data API
├── assets/                    # Static Web Assets
│   ├── css/
│   │   ├── style.css          # Master Luxury & Dark Mode CSS Design System
│   │   └── admin.css          # Admin Portal Dashboard Styles
│   ├── js/
│   │   ├── main.js            # Customer Interactions & AJAX Logic
│   │   └── admin.js           # Admin Dashboard Logic & Charts
│   └── images/                # High-Res Product & Category Photographs
├── database/                  # Database Schema Archives
│   └── gemglitz.sql           # Database Backup File
├── includes/                  # Reusable Modular PHP Components
│   ├── db.php                 # PDO Database Connection
│   ├── functions.php          # Security, CSRF, Cart, & Helper Functions
│   ├── header.php             # HTML Head & Meta Tags
│   ├── navbar.php             # Navigation Bar & Live Search Component
│   └── footer.php             # Footer & Floating Action Widgets
├── index.php                  # Storefront Homepage
├── shop.php                   # Jewelry Catalog & Multi-Filter Vault
├── product.php                # Individual Product Detail Page & Reviews
├── cart.php                   # Shopping Cart & Coupon Redemption
├── checkout.php               # Delivery Address & Billing Form
├── payment.php                # Simulated Payment Gateway Authorization Portal
├── order_success.php          # Invoice & Downloadable Receipt
├── order_tracking.php         # Real-time Shipment Tracking Page
├── login.php                  # Customer Authentication Sign-in
├── signup.php                 # Customer Account Registration
├── profile.php                # Patron Account Dashboard & Orders
├── gemglitz_import.sql        # 1-Click Database Setup File
└── README.md                  # Comprehensive Documentation
```

---

## ❓ Troubleshooting & Tips

> [!NOTE]
> **Images Not Showing?**
> All high-resolution product photographs (`ring_1.jpg`, `necklace_1.jpg`, etc.) are located in `assets/images/`. Ensure file permissions permit Apache to read files inside `assets/images/`.

> [!NOTE]
> **Headers Already Sent Warning?**
> Output buffering (`ob_start()`) is enabled globally in `includes/functions.php`. If you make custom edits, ensure no whitespace or HTML tags precede `<?php` tags.

---

## 📜 License & Credits

Developed for **GemGlitz Haute Joaillerie**. Built with pure craft and precision.

*Crafted with excellence by Senior Full Stack Engineering Team.*