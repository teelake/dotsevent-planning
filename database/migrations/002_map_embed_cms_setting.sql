-- Ensures cms_settings has a map_embed_url row (sources map from DB like other CMS globals).
-- Run on existing DBs: mysql -u USER -p DB_NAME < database/migrations/002_map_embed_cms_setting.sql
-- Value matches config/app.php default; Admin → CMS can edit anytime.

SET NAMES utf8mb4;

INSERT INTO cms_settings (`key`, `value`) VALUES (
  'map_embed_url',
  'https://maps.google.com/maps?q=473+Millidge+Avenue+Suite+E+Saint+John+NB+Canada&hl=en&z=16&output=embed'
)
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
