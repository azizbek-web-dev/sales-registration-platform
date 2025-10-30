-- MySQL schema for Sales Platform
CREATE DATABASE IF NOT EXISTS `sales_platform` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sales_platform`;

-- Users table
CREATE TABLE IF NOT EXISTS users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	email VARCHAR(150) NOT NULL UNIQUE,
	password_hash VARCHAR(255) NOT NULL,
	role ENUM('admin','seller','customer') NOT NULL DEFAULT 'customer',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products table
CREATE TABLE IF NOT EXISTS products (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(150) NOT NULL,
	sku VARCHAR(80) NOT NULL UNIQUE,
	price DECIMAL(10,2) NOT NULL,
	stock INT NOT NULL DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Sales table
CREATE TABLE IF NOT EXISTS sales (
	id INT AUTO_INCREMENT PRIMARY KEY,
	seller_id INT NOT NULL,
	customer_id INT NULL,
	total_amount DECIMAL(12,2) NOT NULL,
	paid_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (seller_id) REFERENCES users(id),
	FOREIGN KEY (customer_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- Sale items
CREATE TABLE IF NOT EXISTS sale_items (
	id INT AUTO_INCREMENT PRIMARY KEY,
	sale_id INT NOT NULL,
	product_id INT NOT NULL,
	quantity INT NOT NULL,
	unit_price DECIMAL(10,2) NOT NULL,
	FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- Seed: admin and seller
INSERT INTO users (name, email, password_hash, role) VALUES
	('Admin', 'admin@example.com', '$2y$10$54t8kF3yM8o6Jc6xw8ZlV.L1T1QfL9z8y9X8cQkU0i2i2Zl6m3w5a', 'admin'),
	('Seller One', 'seller@example.com', '$2y$10$54t8kF3yM8o6Jc6xw8ZlV.L1T1QfL9z8y9X8cQkU0i2i2Zl6m3w5a', 'seller')
ON DUPLICATE KEY UPDATE email = VALUES(email);

-- Seed: products
INSERT INTO products (name, sku, price, stock) VALUES
	('Sample Product A', 'SKU-A', 10.00, 100),
	('Sample Product B', 'SKU-B', 20.00, 50)
ON DUPLICATE KEY UPDATE sku = VALUES(sku);
