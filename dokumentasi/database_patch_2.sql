-- Database Patch 2: API Simulation Tables & Subscriptions

USE `umkm_insight`;

-- 1. SmartBank Accounts
CREATE TABLE IF NOT EXISTS `smartbank_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smartbank_id` varchar(50) NOT NULL UNIQUE,
  `owner_name` varchar(100) NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. SmartBank Transactions
CREATE TABLE IF NOT EXISTS `smartbank_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smartbank_id` varchar(50) NOT NULL,
  `type` enum('Income','Expense') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`smartbank_id`) REFERENCES `smartbank_accounts`(`smartbank_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. POS & Marketplace Sales (Unified Shadow Table for simplicity)
CREATE TABLE IF NOT EXISTS `external_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smartbank_id` varchar(50) NOT NULL, -- To link back to user
  `source` enum('POS','Marketplace') NOT NULL,
  `platform_name` varchar(50) DEFAULT NULL, -- e.g., 'Tokopedia', 'Shopee'
  `product_name` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Subscription Payments (For Langganan feature)
CREATE TABLE IF NOT EXISTS `subscription_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `proof_image` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Dummy Data Seeding
-- Dummy SmartBank Account for testing (assuming the user 'budi' has smartbank_id 'SB-8829-102')
INSERT IGNORE INTO `smartbank_accounts` (`smartbank_id`, `owner_name`, `balance`) VALUES
('SB-8829-102', 'Budi Santoso', 15000000.00),
('SB-9938-204', 'Sari Melati', 25000000.00);

-- Dummy SmartBank Transactions
INSERT INTO `smartbank_transactions` (`smartbank_id`, `type`, `amount`, `description`, `transaction_date`) VALUES
('SB-8829-102', 'Income', 500000.00, 'Transfer masuk - Penjualan Kasir', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('SB-8829-102', 'Expense', 150000.00, 'Pembayaran Listrik', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('SB-9938-204', 'Income', 1200000.00, 'Pencairan Dana Tokopedia', DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Dummy External Sales
INSERT INTO `external_sales` (`smartbank_id`, `source`, `platform_name`, `product_name`, `amount`, `transaction_date`) VALUES
('SB-8829-102', 'POS', 'Lokal', 'Kopi Susu Gula Aren x 5', 100000.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('SB-8829-102', 'Marketplace', 'Tokopedia', 'Biji Kopi Arabica 1kg', 250000.00, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('SB-9938-204', 'Marketplace', 'Shopee', 'Kain Batik Motif Mega Mendung', 450000.00, DATE_SUB(NOW(), INTERVAL 2 DAY));

-- 6. Market Trends Cache (Data tren global PasarKita yang di-cache oleh UMKM Insight)
CREATE TABLE IF NOT EXISTS `market_trends_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `total_sold_global` int(11) NOT NULL DEFAULT 0,
  `avg_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `trend_direction` enum('up','down','stable') NOT NULL DEFAULT 'stable',
  `synced_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Global PasarKita Trending Products (Simulasi data seluruh marketplace)
-- Data ini merepresentasikan produk-produk yang laku keras di PasarKita secara GLOBAL
-- (bukan milik UMKM tertentu, tapi tren pasar seluruh platform)
INSERT INTO `external_sales` (`smartbank_id`, `source`, `platform_name`, `product_name`, `amount`, `transaction_date`) VALUES
-- Penjualan global dari berbagai penjual di PasarKita
('GLOBAL', 'Marketplace', 'PasarKita', 'Kopi Susu Gula Aren', 25000.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Kopi Susu Gula Aren', 25000.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Kopi Susu Gula Aren', 25000.00, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Biji Kopi Arabica 1kg', 120000.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Biji Kopi Arabica 1kg', 120000.00, DATE_SUB(NOW(), INTERVAL 4 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Keripik Singkong Pedas', 15000.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Keripik Singkong Pedas', 15000.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Keripik Singkong Pedas', 15000.00, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Keripik Singkong Pedas', 15000.00, DATE_SUB(NOW(), INTERVAL 5 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Kain Batik Motif Mega Mendung', 350000.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Kain Batik Motif Mega Mendung', 350000.00, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Sambal Roa Manado', 45000.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Sambal Roa Manado', 45000.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Sambal Roa Manado', 45000.00, DATE_SUB(NOW(), INTERVAL 4 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Teh Pucuk Harum', 8000.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('GLOBAL', 'Marketplace', 'PasarKita', 'Teh Pucuk Harum', 8000.00, DATE_SUB(NOW(), INTERVAL 2 DAY));
