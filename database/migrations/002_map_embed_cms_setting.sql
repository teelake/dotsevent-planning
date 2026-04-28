-- Default map + address rows in cms_settings (matches config/app.php; Admin CMS can edit).
-- Run on existing DBs: mysql -u USER -p DB_NAME < database/migrations/002_map_embed_cms_setting.sql

SET NAMES utf8mb4;

INSERT INTO cms_settings (`key`, `value`) VALUES
  ('map_embed_url', 'https://maps.google.com/maps?q=473+Millidge+Avenue+Suite+E+Saint+John+NB+Canada&hl=en&z=16&output=embed'),
  ('address_line1', '473 Suite E, Millidge Avenue'),
  ('address_line2', 'Saint John, NB')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
