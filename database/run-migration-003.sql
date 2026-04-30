-- ============================================================
-- DOTS Event Planning — Migration 003
-- Rentals page: new product columns + product_options table
--
-- HOW TO RUN:
--   Option A (phpMyAdmin) — paste everything below into the
--                           SQL tab and click Go.
--   Option B (SSH / CLI) — mysql -u USER -p DBNAME < run-migration-003.sql
-- ============================================================

SET NAMES utf8mb4;

-- Step 1: Add new plain columns to the products table
ALTER TABLE products
  ADD COLUMN IF NOT EXISTS price_max_cents INT UNSIGNED NULL
    COMMENT 'Highest option price in cents. NULL = single price.',
  ADD COLUMN IF NOT EXISTS category_key VARCHAR(60) NULL
    COMMENT 'Category slug for front-end filter (chairs, tables, backdrops…)',
  ADD COLUMN IF NOT EXISTS badge_label VARCHAR(40) NULL
    COMMENT 'Optional card badge text (Popular, New, Great for Kids…)',
  ADD COLUMN IF NOT EXISTS details TEXT NULL
    COMMENT 'Product detail bullet points, one per line',
  ADD COLUMN IF NOT EXISTS ideal_for TEXT NULL
    COMMENT 'Ideal-for bullet points, one per line (e.g. Weddings, Birthday parties)',
  ADD COLUMN IF NOT EXISTS policy_note TEXT NULL
    COMMENT 'Short rental policy / return note shown on the detail page';

-- Step 2: Index for category filter queries
ALTER TABLE products
  ADD KEY IF NOT EXISTS idx_products_category (category_key);

-- Step 3: Per-product options table (for items with multiple variants / price tiers)
CREATE TABLE IF NOT EXISTS product_options (
  id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED  NOT NULL,
  label      VARCHAR(255)  NOT NULL,
  price_cents INT UNSIGNED NOT NULL DEFAULT 0,
  sort_order INT           NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_product_options_product (product_id),
  CONSTRAINT fk_product_options_product
    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 4: Seed the rentals CMS page row
INSERT INTO cms_pages (slug, title, content_json)
VALUES ('rentals', 'Rentals', NULL)
ON DUPLICATE KEY UPDATE updated_at = updated_at;
