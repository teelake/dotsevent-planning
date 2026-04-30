-- ============================================================
-- DOTS Event Planning - Migration 004
-- Remove all remaining database JSON columns
--
-- HOW TO RUN:
--   phpMyAdmin - paste this whole file into the SQL tab and click Go.
--
-- IMPORTANT:
--   Run this once after migration 003.
--   If you already added one of these columns manually, remove that line first.
-- ============================================================

SET NAMES utf8mb4;

ALTER TABLE leads
  ADD COLUMN subject VARCHAR(255) NULL,
  ADD COLUMN package_key VARCHAR(64) NULL,
  ADD COLUMN event_date VARCHAR(64) NULL,
  ADD COLUMN guest_count VARCHAR(64) NULL,
  ADD COLUMN venue_city VARCHAR(255) NULL;

ALTER TABLE leads
  DROP COLUMN extra;

CREATE TABLE IF NOT EXISTS cms_page_fields (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  page_id INT UNSIGNED NOT NULL,
  field_key VARCHAR(255) NOT NULL,
  field_type VARCHAR(24) NOT NULL DEFAULT 'string',
  field_value MEDIUMTEXT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cms_page_fields_key (page_id, field_key),
  KEY idx_cms_page_fields_page (page_id),
  CONSTRAINT fk_cms_page_fields_page
    FOREIGN KEY (page_id) REFERENCES cms_pages (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE cms_pages
  DROP COLUMN content_json;
