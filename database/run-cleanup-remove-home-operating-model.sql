-- Remove obsolete homepage "operating model / Our Approach" structured-block rows from relational CMS storage.
-- Run once after deploying the removal. Requires backup first.

DELETE f FROM cms_page_fields AS f
INNER JOIN cms_pages AS p ON p.id = f.page_id
WHERE p.slug = 'home'
  AND (
    f.field_key LIKE 'blocks.operating_model%'
    OR f.field_key REGEXP '^blocks\\.operating_model\\.[0-9]+\\.'
  );
