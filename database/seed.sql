-- Sample rental products (run once after schema.sql; replace images with your assets)
INSERT INTO products (slug, name, description, price_cents, currency, image_url, stock, has_options, is_active, sort_order) VALUES
('adult-chiavari-chairs', 'Adult Chiavari Chairs', 'Elegant seating for weddings and galas.', 900, 'CAD', NULL, 200, 0, 1, 10),
('barbie-theme-arch-backdrop', 'Barbie Theme Arch Backdrop Set', 'Themed backdrop set for birthdays and celebrations.', 25000, 'CAD', NULL, 5, 0, 1, 20),
('charger-plates', 'Charger Plates', 'Premium charger plates for table settings.', 200, 'CAD', NULL, 80, 0, 1, 30),
('pipe-drape-backdrop', 'Pipe and Drape Backdrop', 'Versatile pipe and drape for stage and photo areas.', 30000, 'CAD', NULL, 3, 0, 1, 40);

-- Admin (ignored if email already exists). Change password in production.
-- Password: ChangeMe!Admin2024
INSERT IGNORE INTO users (email, password_hash, role) VALUES (
  'admin@dotseventplanning.com',
  '$2y$10$BBXLDf5iaz3b.b.az3fkcu6NfvBlO7VmaKSieSfsO9XAOtQh7NYeO',
  'admin'
);

-- Map embed URL in DB so it is sourced from cms_settings like other CMS globals (defaults match config/app.php).
INSERT INTO cms_settings (`key`, `value`) VALUES (
  'map_embed_url',
  'https://maps.google.com/maps?q=473+Millidge+Avenue+Suite+E+Saint+John+NB+Canada&hl=en&z=16&output=embed'
)
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
