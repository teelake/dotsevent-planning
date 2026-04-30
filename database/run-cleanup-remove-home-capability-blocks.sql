-- Remove obsolete homepage "capability clusters" CMS keys (clusters are now driven by Services → Offerings).
-- Run once after deploying the unified services teaser. Requires backup first.

DELETE f FROM cms_page_fields AS f
INNER JOIN cms_pages AS p ON p.id = f.page_id
WHERE p.slug = 'home'
  AND f.field_key LIKE 'blocks.clusters%';
