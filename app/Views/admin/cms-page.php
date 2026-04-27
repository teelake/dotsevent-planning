<?php
declare(strict_types=1);
/** @var string $slug */
/** @var string $page_title */
/** @var string $content_json */
$slug = $slug ?? '';
$page_title = $page_title ?? '';
$content_json = trim((string) ($content_json ?? ''));
if ($content_json === '') {
    $content_json = '{}';
}
$dataPreview = json_decode($content_json, true);
$meta_description_field = '';
if (is_array($dataPreview) && isset($dataPreview['meta_description']) && is_string($dataPreview['meta_description'])) {
    $meta_description_field = $dataPreview['meta_description'];
}
?>
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<section class="section--tight" style="padding-top: 0;">
    <p class="text-muted" style="margin: 0 0 1rem; font-size: 0.9rem;">
        <a class="text-link" href="<?= e(app_url('admin/cms')) ?>">← CMS</a>
        <span aria-hidden="true"> · </span>
        <span>Slug: <code><?= e($slug) ?></code></span>
    </p>

    <div class="card card--folio" style="max-width: none;">
        <h2 class="card__title" style="margin: 0 0 1rem;">Edit page</h2>
        <?php if ($slug === 'home'): ?>
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Homepage hero slides are managed in <a class="text-link" href="<?= e(app_url('admin/cms/slides')) ?>">Hero carousel</a>. This page edits intro copy below the hero (and meta).</p>
        <?php endif; ?>

        <form class="admin-form" method="post" action="<?= e(app_url('admin/cms/page/' . $slug . '/save')) ?>" id="cms-page-form">
            <?= csrf_field() ?>
            <div class="form-row">
                <label for="cms-page-title">Page title</label>
                <input class="input" id="cms-page-title" name="title" type="text" value="<?= e($page_title) ?>" placeholder="Optional display title">
            </div>
            <div class="form-row">
                <label for="cms-meta-description">Meta description (SEO)</label>
                <textarea class="input input--textarea" id="cms-meta-description" rows="2" placeholder="Shown in search results when set"><?= e($meta_description_field) ?></textarea>
                <span class="text-muted" style="font-size:0.85rem;">Stored in page JSON; overrides the default for this route.</span>
            </div>
            <div class="form-row">
                <label for="editor">Body</label>
                <div id="editor" style="min-height: 280px; background: #fff; border: 1px solid var(--color-line); border-radius: 12px;"></div>
                <input type="hidden" name="content_json" id="cms-content-json" value="">
            </div>
            <button class="btn btn--primary" type="submit">Save page</button>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
(function () {
  const initial = <?= json_encode($content_json, JSON_THROW_ON_ERROR) ?>;
  let data = {};
  try {
    data = JSON.parse(initial || '{}');
  } catch (e) {
    data = {};
  }
  const quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
      toolbar: {
        container: [
          [{ header: [1, 2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['blockquote', 'link', 'image', 'video'],
          ['clean'],
        ],
        handlers: {
          image: function () {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            input.onchange = async function () {
              const file = input.files && input.files[0];
              if (!file) return;
              const fd = new FormData();
              fd.append('file', file);
              fd.append('_csrf', <?= json_encode(\App\Core\Csrf::token(), JSON_THROW_ON_ERROR) ?>);
              const res = await fetch(<?= json_encode(app_url('admin/media/upload'), JSON_THROW_ON_ERROR) ?>, {
                method: 'POST',
                body: fd,
                credentials: 'same-origin',
              });
              const out = await res.json().catch(function () { return null; });
              if (!out || !out.ok || !out.url) {
                alert((out && out.error) ? out.error : 'Upload failed');
                return;
              }
              const range = quill.getSelection(true);
              quill.insertEmbed(range.index, 'image', out.url);
            };
          },
        },
      },
    },
  });
  const html = typeof data.html === 'string' ? data.html : '';
  if (html) {
    quill.root.innerHTML = html;
  }

  const metaEl = document.getElementById('cms-meta-description');
  document.getElementById('cms-page-form').addEventListener('submit', function () {
    const payload = { html: quill.root.innerHTML };
    if (metaEl) {
      payload.meta_description = metaEl.value.trim();
    }
    if (Array.isArray(data.slides)) {
      payload.slides = data.slides;
    }
    document.getElementById('cms-content-json').value = JSON.stringify(payload);
  });
})();
</script>
