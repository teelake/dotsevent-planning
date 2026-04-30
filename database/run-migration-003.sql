-- ============================================================
-- DOTS Event Planning — Migration 003
-- Rentals page: new product columns + cms_pages seed row
--
-- HOW TO RUN:
--   Option A (phpMyAdmin) — paste everything below into the
--                           SQL tab and click Go.
--   Option B (SSH / CLI) — mysql -u USER -p DBNAME < run-migration-003.sql
-- ============================================================

SET NAMES utf8mb4;

-- Step 1: Add new columns to the products table
ALTER TABLE products
  ADD COLUMN IF NOT EXISTS price_max_cents INT UNSIGNED NULL
    COMMENT 'Set for price-range products (e.g. $3–$8). NULL = single price.',
  ADD COLUMN IF NOT EXISTS category_key VARCHAR(60) NULL
    COMMENT 'Category slug used by the front-end filter (chairs, tables, backdrops…)',
  ADD COLUMN IF NOT EXISTS badge_label VARCHAR(40) NULL
    COMMENT 'Optional card badge (Popular, New, Great for Kids…)',
  ADD COLUMN IF NOT EXISTS meta_json JSON NULL
    COMMENT 'Structured product data: options[], details[], ideal_for[], policy_note';

-- Step 2: Add index for category filter queries
ALTER TABLE products
  ADD KEY IF NOT EXISTS idx_products_category (category_key);

-- Step 3: Seed the rentals CMS page row (safe to run multiple times)
INSERT INTO cms_pages (slug, title, content_json)
VALUES (
  'rentals',
  'Rentals',
  JSON_OBJECT(
    'meta_description', 'Browse decor and event rentals from DOTS in Saint John — chairs, backdrops, linens and finishing pieces. Add to cart and check out online.',
    'blocks', JSON_OBJECT()
  )
)
ON DUPLICATE KEY UPDATE updated_at = updated_at;
