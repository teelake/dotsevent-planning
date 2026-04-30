-- Adds rental-specific columns and the product_options table.
-- Run: mysql -u USER -p DB_NAME < database/migrations/003_products_rental_extensions.sql
--
-- This version avoids JSON_OBJECT(), JSON columns, and ADD COLUMN IF NOT EXISTS.
-- Run it once. If one of these columns already exists, skip that ADD COLUMN line.

SET NAMES utf8mb4;

ALTER TABLE products
  ADD COLUMN price_max_cents INT UNSIGNED NULL
    COMMENT 'Highest option price in cents. NULL = single price.',
  ADD COLUMN category_key VARCHAR(60) NULL
    COMMENT 'Category slug for front-end filter',
  ADD COLUMN badge_label VARCHAR(40) NULL
    COMMENT 'Optional card badge text',
  ADD COLUMN details TEXT NULL
    COMMENT 'Product detail bullet points, one per line',
  ADD COLUMN ideal_for TEXT NULL
    COMMENT 'Ideal-for bullet points, one per line',
  ADD COLUMN policy_note TEXT NULL
    COMMENT 'Short rental policy note shown on the detail page';

ALTER TABLE products
  ADD KEY idx_products_category (category_key);

CREATE TABLE IF NOT EXISTS product_options (
  id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  product_id  INT UNSIGNED  NOT NULL,
  label       VARCHAR(255)  NOT NULL,
  price_cents INT UNSIGNED  NOT NULL DEFAULT 0,
  sort_order  INT           NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_product_options_product (product_id),
  CONSTRAINT fk_product_options_product
    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO cms_pages (slug, title, content_json)
VALUES ('rentals', 'Rentals', NULL)
ON DUPLICATE KEY UPDATE updated_at = updated_at;
