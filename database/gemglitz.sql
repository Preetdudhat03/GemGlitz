-- GemGlitz Luxury Jewelry E-Commerce Database Schema
-- Compatible with MySQL / MariaDB (XAMPP)

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(50) DEFAULT NULL,
  `state` VARCHAR(50) DEFAULT NULL,
  `zip` VARCHAR(20) DEFAULT NULL,
  `role` ENUM('admin', 'customer') DEFAULT 'customer',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) UNIQUE NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) UNIQUE NOT NULL,
  `sku` VARCHAR(50) UNIQUE NOT NULL,
  `short_description` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `discount_price` DECIMAL(10, 2) DEFAULT NULL,
  `stock` INT DEFAULT 10,
  `metal_type` VARCHAR(50) DEFAULT '18K Yellow Gold',
  `gemstone_type` VARCHAR(50) DEFAULT 'Diamond',
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_bestseller` TINYINT(1) DEFAULT 0,
  `is_trending` TINYINT(1) DEFAULT 0,
  `main_image` TEXT DEFAULT NULL,
  `gallery_images` TEXT DEFAULT NULL,
  `rating` DECIMAL(3, 2) DEFAULT 5.00,
  `review_count` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_user_product` (`user_id`, `product_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `session_id` VARCHAR(100) DEFAULT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) UNIQUE NOT NULL,
  `user_id` INT NOT NULL,
  `total_amount` DECIMAL(10, 2) NOT NULL,
  `discount_amount` DECIMAL(10, 2) DEFAULT 0.00,
  `shipping_fee` DECIMAL(10, 2) DEFAULT 0.00,
  `tax_amount` DECIMAL(10, 2) DEFAULT 0.00,
  `grand_total` DECIMAL(10, 2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `payment_status` ENUM('Pending', 'Paid', 'Failed', 'Refunded') DEFAULT 'Paid',
  `order_status` ENUM('Pending', 'Processing', 'Packed', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Processing',
  `shipping_name` VARCHAR(100) NOT NULL,
  `shipping_email` VARCHAR(100) NOT NULL,
  `shipping_phone` VARCHAR(20) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `shipping_city` VARCHAR(50) NOT NULL,
  `shipping_zip` VARCHAR(20) NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_name` VARCHAR(150) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `quantity` INT NOT NULL,
  `total` DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `transaction_id` VARCHAR(100) UNIQUE NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Success',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tracking`;
CREATE TABLE `tracking` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `status_title` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `courier_name` VARCHAR(100) DEFAULT 'Royal Gold Express',
  `tracking_number` VARCHAR(100) DEFAULT NULL,
  `estimated_delivery` DATE DEFAULT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `rating` INT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `review_title` VARCHAR(150) DEFAULT NULL,
  `comment` TEXT NOT NULL,
  `status` ENUM('approved', 'pending') DEFAULT 'approved',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) UNIQUE NOT NULL,
  `discount_percent` DECIMAL(5, 2) NOT NULL,
  `min_order_amount` DECIMAL(10, 2) DEFAULT 0.00,
  `max_discount` DECIMAL(10, 2) DEFAULT 1000.00,
  `expiry_date` DATE NOT NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE `newsletter` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================
-- INITIAL SEED DATA
-- =========================================================

-- USERS
-- Admin password: Admin@123
-- Customer password: Customer@123
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `city`, `state`, `zip`, `role`) VALUES
(1, 'GemGlitz', 'Admin', 'admin@gemglitz.com', '$2y$10$frjvNhekaAG3dnNMTwBDnumPY86BG1JohWE8BuEtkR4JG9P.EioKm', '+1 800 555 0199', '740 5th Avenue, Suite 1200', 'New York', 'NY', '10019', 'admin'),
(2, 'Sophia', 'Vanderbilt', 'customer@gemglitz.com', '$2y$10$qlDl/IVTVhHBwyGXDbwctucKB.FbN.tlOJlPXMM3c6euv4.GoJ9tG', '+1 212 555 0148', '432 Park Avenue, Apt 64A', 'New York', 'NY', '10022', 'customer');

-- CATEGORIES
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_url`) VALUES
(1, 'Diamond Rings', 'diamond-rings', 'Exquisite handcrafted solitaire & halo diamond rings forged in 18K gold and platinum.', 'ring_cat.svg'),
(2, 'Luxury Necklaces', 'luxury-necklaces', 'Statement gold pendants, diamond chokers, and royal sapphire necklaces.', 'necklace_cat.svg'),
(3, 'Royal Bracelets', 'royal-bracelets', 'Bangles, tennis bracelets, and diamond cuffs designed for royalty.', 'bracelet_cat.svg'),
(4, 'Elegant Earrings', 'elegant-earrings', 'Graceful studs, drop earrings, and diamond hoops crafted to shine.', 'earring_cat.svg'),
(5, 'Luxury Watches', 'luxury-watches', 'Timeless mechanical horology featuring Swiss movements and diamond dials.', 'watch_cat.svg'),
(6, 'High Solitaires', 'high-solitaires', 'Rare GIA certified colorless diamond single stone creations.', 'solitaire_cat.svg');

-- PRODUCTS
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `sku`, `short_description`, `description`, `price`, `discount_price`, `stock`, `metal_type`, `gemstone_type`, `is_featured`, `is_bestseller`, `is_trending`, `main_image`, `gallery_images`, `rating`, `review_count`) VALUES
(1, 1, 'The Empress Royal Solitaire Ring', 'empress-royal-solitaire-ring', 'GG-RNG-001', '3.5 Carat D-Flawless Cushion Diamond Ring in 18K Yellow Gold', 'Forged by our master jewelers in Paris, The Empress Ring features a transcendent 3.5 carat D-Flawless cushion-cut center diamond enveloped by micro-pave diamonds along an 18K yellow gold band. A statement of pure luxury.', 12500.00, 11200.00, 5, '18K Yellow Gold', 'Diamond', 1, 1, 1, 'product_ring_1.svg', '["product_ring_1.svg", "product_ring_2.svg"]', 5.00, 12),

(2, 1, 'Elysian Eternity Halo Ring', 'elysian-eternity-halo-ring', 'GG-RNG-002', '2.0 Carat Round Brilliant Diamond in Platinum Band', 'Surrounded by a double halo of brilliant pave diamonds, this platinum ring captivates with effortless brilliance and timeless romantic allure.', 8900.00, NULL, 8, 'Platinum', 'Diamond', 1, 0, 1, 'product_ring_2.svg', '["product_ring_2.svg", "product_ring_1.svg"]', 4.90, 8),

(3, 2, 'The Celestia Diamond Pendant', 'celestia-diamond-pendant', 'GG-NCK-001', 'Cascade 18K White Gold Necklace with Pear Diamond Drop', 'Inspired by celestial constellations, this 18K white gold necklace features a graduating line of brilliant round diamonds leading to a breathtaking 2.2 Carat pear-cut focal gem.', 14800.00, 13500.00, 3, 'White Gold', 'Diamond', 1, 1, 0, 'product_necklace_1.svg', '["product_necklace_1.svg", "product_necklace_2.svg"]', 5.00, 19),

(4, 2, 'Royal Emerald Riviera Choker', 'royal-emerald-riviera-choker', 'GG-NCK-002', 'Deep Colombian Emeralds surrounded by marquise diamonds', 'An extraordinary riviera necklace displaying 15 carats of vivid green Colombian emeralds paired with marquise cut diamonds set in 18K yellow gold.', 28000.00, NULL, 2, '18K Yellow Gold', 'Emerald', 1, 0, 1, 'product_necklace_2.svg', '["product_necklace_2.svg", "product_necklace_1.svg"]', 5.00, 6),

(5, 3, 'Majestic Imperial Tennis Bracelet', 'majestic-imperial-tennis-bracelet', 'GG-BRC-001', '10.0 Carats Total Weight Round Cut Diamonds in Platinum', 'A iconic tennis bracelet boasting 42 perfectly matched VVS clarity diamonds seamlessly linked in solid platinum. Fluid, comfortable, and scintillating.', 16500.00, 14999.00, 6, 'Platinum', 'Diamond', 1, 1, 1, 'product_bracelet_1.svg', '["product_bracelet_1.svg", "product_bracelet_2.svg"]', 4.95, 24),

(6, 3, 'Rose Gold Aurora Bangle Cuff', 'rose-gold-aurora-bangle-cuff', 'GG-BRC-002', '18K Rose Gold Bangle set with Pink Diamonds', 'Crafted in luminous 18K rose gold, this hinged bangle features pave pink and white diamonds arranged in an elegant geometric aurora motif.', 9200.00, NULL, 7, 'Rose Gold', 'Diamond', 0, 0, 1, 'product_bracelet_2.svg', '["product_bracelet_2.svg", "product_bracelet_1.svg"]', 4.80, 5),

(7, 4, 'Aura Sapphire Drop Earrings', 'aura-sapphire-drop-earrings', 'GG-ERG-001', 'Royal Blue Ceylon Sapphires with Diamond Halo in White Gold', 'Featuring 4 carats of vivid blue Ceylon sapphires encased in diamond halos, suspended from delicate white gold drop hooks.', 11000.00, 9800.00, 4, 'White Gold', 'Sapphire', 1, 1, 0, 'product_earring_1.svg', '["product_earring_1.svg", "product_earring_2.svg"]', 5.00, 15),

(8, 4, 'Radiant Marquise Diamond Studs', 'radiant-marquise-diamond-studs', 'GG-ERG-002', 'Classic 1.5 CT TW Marquise Diamonds in 18K Yellow Gold', 'Sophisticated marquise-cut solitaire diamond studs in classic 4-prong 18K gold settings. A essential staple for refined collections.', 6400.00, NULL, 12, '18K Yellow Gold', 'Diamond', 0, 1, 0, 'product_earring_2.svg', '["product_earring_2.svg", "product_earring_1.svg"]', 4.88, 11),

(9, 5, 'Chronos Tourbillon Diamond Watch', 'chronos-tourbillon-diamond-watch', 'GG-WTC-001', 'Swiss Automatic Skeleton Watch with Baguette Diamond Bezel', 'Masterpiece of fine watchmaking. Automatic flying tourbillon movement with sapphire crystal back and 48 baguette diamonds set in platinum casing.', 45000.00, 39900.00, 1, 'Platinum', 'Diamond', 1, 1, 1, 'product_watch_1.svg', '["product_watch_1.svg", "product_watch_2.svg"]', 5.00, 4),

(10, 5, 'Gilded Heritage Moonphase Watch', 'gilded-heritage-moonphase-watch', 'GG-WTC-002', '18K Rose Gold Mechanical Watch with Alligator Leather Strap', 'Classic precision moonphase timepiece housed in polished 18K rose gold with a hand-stitched Italian alligator leather strap.', 22500.00, NULL, 3, 'Rose Gold', 'Solitaire', 1, 0, 0, 'product_watch_2.svg', '["product_watch_2.svg", "product_watch_1.svg"]', 4.90, 7),

(11, 6, 'Crown Jewel 5ct Round Solitaire', 'crown-jewel-5ct-round-solitaire', 'GG-SLT-001', 'GIA Certified 5.0 Carat D Flawless Solitaire Ring', 'The pinnacle of fine gemology. A single 5-carat round brilliant cut diamond mounted on an ultra-slim 18K gold band.', 65000.00, 59000.00, 1, '18K Yellow Gold', 'Solitaire', 1, 1, 1, 'product_solitaire_1.svg', '["product_solitaire_1.svg", "product_solitaire_1.svg"]', 5.00, 9),

(12, 6, 'Ocean Heart Blue Diamond Solitaire', 'ocean-heart-blue-diamond-solitaire', 'GG-SLT-002', 'Rare 3.0 CT Fancy Deep Blue Diamond in Platinum', 'Extremely rare natural blue heart-shaped diamond surrounded by micropave platinum setting. A collector item of extraordinary distinction.', 85000.00, NULL, 1, 'Platinum', 'Diamond', 1, 0, 1, 'product_solitaire_2.svg', '["product_solitaire_2.svg", "product_solitaire_1.svg"]', 5.00, 3);

-- COUPONS
INSERT INTO `coupons` (`id`, `code`, `discount_percent`, `min_order_amount`, `max_discount`, `expiry_date`, `status`) VALUES
(1, 'LUXURY10', 10.00, 1000.00, 2000.00, '2030-12-31', 1),
(2, 'GEMGLITZ20', 20.00, 5000.00, 5000.00, '2030-12-31', 1),
(3, 'WELCOME100', 5.00, 500.00, 500.00, '2030-12-31', 1);

-- ORDERS
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total_amount`, `discount_amount`, `shipping_fee`, `tax_amount`, `grand_total`, `payment_method`, `payment_status`, `order_status`, `shipping_name`, `shipping_email`, `shipping_phone`, `shipping_address`, `shipping_city`, `shipping_zip`, `created_at`) VALUES
(1, 'GG-ORD-88291', 2, 12500.00, 1250.00, 0.00, 562.50, 11812.50, 'Credit Card', 'Paid', 'Shipped', 'Sophia Vanderbilt', 'customer@gemglitz.com', '+1 212 555 0148', '432 Park Avenue, Apt 64A', 'New York', '10022', '2026-08-01 10:15:00');

-- ORDER ITEMS
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `total`) VALUES
(1, 1, 1, 'The Empress Royal Solitaire Ring', 12500.00, 1, 12500.00);

-- PAYMENTS
INSERT INTO `payments` (`id`, `order_id`, `transaction_id`, `payment_method`, `amount`, `status`, `created_at`) VALUES
(1, 1, 'TXN_GG_992104882', 'Credit Card', 11812.50, 'Success', '2026-08-01 10:15:20');

-- TRACKING
INSERT INTO `tracking` (`id`, `order_id`, `status_title`, `description`, `courier_name`, `tracking_number`, `estimated_delivery`) VALUES
(1, 1, 'In Transit', 'Package has left the master vault and is en route via armored transport.', 'Royal Gold Express', 'RGE-99021884-NY', '2026-08-06');

-- REVIEWS
INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `review_title`, `comment`, `status`, `created_at`) VALUES
(1, 1, 2, 5, 'Unparalleled Perfection!', 'The Empress Ring exceeded every expectation. The brilliance under light is breathtaking and the custom packaging felt like opening a royal crown.', 'approved', '2026-08-02 14:22:00'),
(2, 3, 2, 5, 'Exquisite Craftsmanship', 'The pear diamond drop captures light from every angle. GemGlitz white-glove delivery was flawless.', 'approved', '2026-08-03 09:10:00');
