-- Adds rental-specific columns to the products table.
-- Run: mysql -u USER -p DB_NAME < database/migrations/003_products_rental_extensions.sql

SET NAMES utf8mb4;

ALTER TABLE products
  ADD COLUMN IF NOT EXISTS price_max_cents INT UNSIGNED  NULL    COMMENT 'Set for price-range products (e.g. $3–$8); NULL = single price',
  ADD COLUMN IF NOT EXISTS category_key   VARCHAR(60)   NULL    COMMENT 'Slug used by the front-end category filter (chairs, tables, backdrops…)',
  ADD COLUMN IF NOT EXISTS badge_label    VARCHAR(40)   NULL    COMMENT 'Optional card badge shown in the catalog (Popular, New, Great for Kids…)',
  ADD COLUMN IF NOT EXISTS meta_json      JSON          NULL    COMMENT 'Per-product structured data: options[], details[], ideal_for[], policy_note';

-- Index for category filter queries
ALTER TABLE products
  ADD KEY IF NOT EXISTS idx_products_category (category_key);

-- Seed the cms_pages row for the rentals landing page (blocks only — products come from DB)
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
