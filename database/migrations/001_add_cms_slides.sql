-- Run on existing databases that already have schema.sql applied before cms_slides existed:
-- mysql -u USER -p DB_NAME < database/migrations/001_add_cms_slides.sql

SET NAMES utf8mb4;

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
