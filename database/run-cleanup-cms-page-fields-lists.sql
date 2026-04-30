-- CMS relational field cleanup: normalize list rows that used blocks.<section>.<N>.field
-- into blocks.<section>.<list_key>.<N>.field so they match admin save flattening.
--
-- Run after backup. Requires MySQL 8+ (REGEXP_REPLACE).
-- Default scope: slug = 'home' (capability clusters, packages, steps, quotes, metrics).
--
-- Preview first (STEP 1), then uncomment and run TRANSACTION block (STEP 2).

SET NAMES utf8mb4;

-- ── STEP 1: Preview rows that would be renamed (adjust slug if needed) ─────────────
SELECT f.id,
       p.slug,
       f.field_key AS old_key,
       REGEXP_REPLACE(
         f.field_key,
         '^blocks\\.clusters\\.([0-9]+)\\.',
         'blocks.clusters.items.\\1.'
       ) AS new_key_clusters,
       REGEXP_REPLACE(
         f.field_key,
         '^blocks\\.packages\\.([0-9]+)\\.',
         'blocks.packages.items.\\1.'
       ) AS new_key_packages,
       REGEXP_REPLACE(
         f.field_key,
         '^blocks\\.operating_model\\.([0-9]+)\\.',
         'blocks.operating_model.steps.\\1.'
       ) AS new_key_steps,
       REGEXP_REPLACE(
         f.field_key,
         '^blocks\\.testimonials\\.([0-9]+)\\.',
         'blocks.testimonials.quotes.\\1.'
       ) AS new_key_quotes,
       REGEXP_REPLACE(
         f.field_key,
         '^blocks\\.confidence\\.([0-9]+)\\.',
         'blocks.confidence.metrics.\\1.'
       ) AS new_key_metrics
FROM cms_page_fields f
JOIN cms_pages p ON p.id = f.page_id
WHERE p.slug = 'home'
  AND (
       f.field_key REGEXP '^blocks\\.clusters\\.[0-9]+\\.'
    OR f.field_key REGEXP '^blocks\\.packages\\.[0-9]+\\.'
    OR f.field_key REGEXP '^blocks\\.operating_model\\.[0-9]+\\.'
    OR f.field_key REGEXP '^blocks\\.testimonials\\.[0-9]+\\.'
    OR f.field_key REGEXP '^blocks\\.confidence\\.[0-9]+\\.'
  );


-- ── Duplicate check (clusters): UPDATE would collide if canonical key exists ─────────
SELECT f.id AS old_row_id,
       f.field_key AS old_key,
       ex.id AS conflicting_row_id,
       ex.field_key AS conflicting_key
FROM cms_page_fields f
JOIN cms_pages p ON p.id = f.page_id
JOIN cms_page_fields ex
  ON ex.page_id = f.page_id
 AND ex.field_key = REGEXP_REPLACE(
       f.field_key,
       '^blocks\\.clusters\\.([0-9]+)\\.',
       'blocks.clusters.items.\\1.'
     )
 AND ex.id <> f.id
WHERE p.slug = 'home'
  AND f.field_key REGEXP '^blocks\\.clusters\\.[0-9]+\\.';
-- Empty result = safe cluster renames.


/*
-- ── STEP 2: Apply renames inside a transaction (review STEP 1 first) ────────────────

START TRANSACTION;

-- Clusters → blocks.clusters.items.N.*
UPDATE cms_page_fields f
JOIN cms_pages p ON p.id = f.page_id
SET f.field_key = REGEXP_REPLACE(
    f.field_key,
    '^blocks\\.clusters\\.([0-9]+)\\.',
    'blocks.clusters.items.\\1.'
  ),
  f.updated_at = CURRENT_TIMESTAMP
WHERE p.slug = 'home'
  AND f.field_key REGEXP '^blocks\\.clusters\\.[0-9]+\\.';

-- Packages → blocks.packages.items.N.*
UPDATE cms_page_fields f
JOIN cms_pages p ON p.id = f.page_id
SET f.field_key = REGEXP_REPLACE(
    f.field_key,
    '^blocks\\.packages\\.([0-9]+)\\.',
    'blocks.packages.items.\\1.'
  ),
  f.updated_at = CURRENT_TIMESTAMP
WHERE p.slug = 'home'
  AND f.field_key REGEXP '^blocks\\.packages\\.[0-9]+\\.';

-- Operating model steps
UPDATE cms_page_fields f
JOIN cms_pages p ON p.id = f.page_id
SET f.field_key = REGEXP_REPLACE(
    f.field_key,
    '^blocks\\.operating_model\\.([0-9]+)\\.',
    'blocks.operating_model.steps.\\1.'
  ),
  f.updated_at = CURRENT_TIMESTAMP
WHERE p.slug = 'home'
  AND f.field_key REGEXP '^blocks\\.operating_model\\.[0-9]+\\.';

-- Testimonials quotes
UPDATE cms_page_fields f
JOIN cms_pages p ON p.id = f.page_id
SET f.field_key = REGEXP_REPLACE(
    f.field_key,
    '^blocks\\.testimonials\\.([0-9]+)\\.',
    'blocks.testimonials.quotes.\\1.'
  ),
  f.updated_at = CURRENT_TIMESTAMP
WHERE p.slug = 'home'
  AND f.field_key REGEXP '^blocks\\.testimonials\\.[0-9]+\\.';

-- Confidence KPI metrics
UPDATE cms_page_fields f
JOIN cms_pages p ON p.id = f.page_id
SET f.field_key = REGEXP_REPLACE(
    f.field_key,
    '^blocks\\.confidence\\.([0-9]+)\\.',
    'blocks.confidence.metrics.\\1.'
  ),
  f.updated_at = CURRENT_TIMESTAMP
WHERE p.slug = 'home'
  AND f.field_key REGEXP '^blocks\\.confidence\\.[0-9]+\\.';

COMMIT;

-- ── OPTIONAL: remove stale empty-array root rows after list children exist ─────────────
-- Run one uncommented DELETE at a time; only safe if numbered children already exist under that prefix.

-- DELETE f FROM cms_page_fields f
-- JOIN cms_pages p ON p.id = f.page_id
-- WHERE p.slug = 'home' AND f.field_key = 'blocks.clusters.items' AND f.field_type = 'empty_array'
-- AND EXISTS (
--   SELECT 1 FROM cms_page_fields c
--   WHERE c.page_id = f.page_id AND c.field_key REGEXP '^blocks\\.clusters\\.items\\.[0-9]'
-- );

*/
