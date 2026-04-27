-- DOTS Event Planning — MySQL 8+
-- Run: mysql -u root -p dots_event < database/schema.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug VARCHAR(160) NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NULL,
  price_cents INT UNSIGNED NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'CAD',
  image_url VARCHAR(512) NULL,
  stock INT NULL,
  has_options TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_products_slug (slug),
  KEY idx_products_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_email VARCHAR(255) NULL,
  customer_name VARCHAR(255) NULL,
  phone VARCHAR(64) NULL,
  total_cents INT UNSIGNED NOT NULL,
  currency CHAR(3) NOT NULL,
  status VARCHAR(32) NOT NULL DEFAULT 'pending',
  square_payment_id VARCHAR(255) NULL,
  idempotency_key VARCHAR(100) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_orders_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit_price_cents INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY idx_order_items_order (order_id),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS leads (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  type VARCHAR(32) NOT NULL,
  email VARCHAR(255) NOT NULL,
  name VARCHAR(255) NULL,
  phone VARCHAR(64) NULL,
  message TEXT NULL,
  extra JSON NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_leads_type (type),
  KEY idx_leads_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('system_admin','admin') NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CMS settings (logo, favicon, map embed, social, etc.)
CREATE TABLE IF NOT EXISTS cms_settings (
  `key` VARCHAR(120) NOT NULL,
  `value` TEXT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CMS page content by slug (home/about/services/etc.) stored as JSON
CREATE TABLE IF NOT EXISTS cms_pages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug VARCHAR(80) NOT NULL,
  title VARCHAR(255) NOT NULL DEFAULT '',
  content_json JSON NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cms_pages_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Uploaded media for CMS (images/videos). Stored under /public/uploads/
CREATE TABLE IF NOT EXISTS cms_media (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  file_path VARCHAR(512) NOT NULL,
  mime VARCHAR(120) NOT NULL,
  size_bytes INT UNSIGNED NOT NULL DEFAULT 0,
  original_name VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_cms_media_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Homepage hero carousel slides (managed in admin: CMS → Hero carousel)
CREATE TABLE IF NOT EXISTS cms_slides (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  sort_order INT NOT NULL DEFAULT 0,
  is_live TINYINT(1) NOT NULL DEFAULT 1,
  badge VARCHAR(24) NOT NULL DEFAULT '',
  headline VARCHAR(160) NOT NULL,
  supporting VARCHAR(200) NOT NULL DEFAULT '',
  btn_primary_label VARCHAR(24) NOT NULL DEFAULT '',
  btn_primary_href VARCHAR(512) NOT NULL DEFAULT '',
  btn_secondary_label VARCHAR(24) NOT NULL DEFAULT '',
  btn_secondary_href VARCHAR(512) NOT NULL DEFAULT '',
  image_desktop_path VARCHAR(512) NOT NULL,
  image_mobile_path VARCHAR(512) NULL,
  image_alt VARCHAR(255) NOT NULL DEFAULT '',
  starts_at DATETIME NULL,
  ends_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_cms_slides_live_order (is_live, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
