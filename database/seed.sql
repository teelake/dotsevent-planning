-- Sample rental products (run once after schema.sql; replace images with your assets)
INSERT INTO products (slug, name, description, price_cents, currency, image_url, stock, has_options, is_active, sort_order) VALUES
('adult-chiavari-chairs', 'Adult Chiavari Chairs', 'Elegant seating for weddings and galas.', 900, 'CAD', NULL, 200, 0, 1, 10),
('barbie-theme-arch-backdrop', 'Barbie Theme Arch Backdrop Set', 'Themed backdrop set for birthdays and celebrations.', 25000, 'CAD', NULL, 5, 0, 1, 20),
('charger-plates', 'Charger Plates', 'Premium charger plates for table settings.', 200, 'CAD', NULL, 80, 0, 1, 30),
('pipe-drape-backdrop', 'Pipe and Drape Backdrop', 'Versatile pipe and drape for stage and photo areas.', 30000, 'CAD', NULL, 3, 0, 1, 40);
