<?php
declare(strict_types=1);
/** @var list<array<string, mixed>> $media */
$media = $media ?? [];

/** Page slug => label */
$cmsPages = [
    'home' => 'Home',
    'about' => 'About Us',
    'services' => 'Services',
    'portfolio' => 'Portfolio',
    'contact' => 'Contact',
    'book' => 'Book your event',
    'kids-party' => 'Kids Party',
    'rentals' => 'Rentals',
];
?>
<section class="admin-cms">
    <header class="admin-cms__header">
        <p class="admin-cms__eyebrow">Website</p>
        <h2 class="admin-cms__title">Pages &amp; content</h2>
        <p class="admin-cms__lead">Edit public page copy with the rich-text editor (Quill). Slides above the homepage hero are managed separately in <a class="admin-inline-link" href="<?= e(app_url('admin/cms/slides')) ?>">Hero carousel</a>.</p>
        <div class="admin-cms__actions">
            <a class="btn btn--secondary" href="<?= e(app_url('admin/cms')) ?>">Site settings</a>
            <a class="btn btn--secondary" href="<?= e(app_url('')) ?>" target="_blank" rel="noopener noreferrer">View live site</a>
        </div>
    </header>

    <div class="admin-page-hub">
        <?php foreach ($cmsPages as $slug => $label): ?>
        <a class="admin-page-hub__card" href="<?= e(app_url('admin/cms/page/' . $slug)) ?>">
            <span class="admin-page-hub__slug"><?= e(str_replace('-', ' · ', $slug)) ?></span>
            <span class="admin-page-hub__title"><?= e($label) ?></span>
            <span class="admin-page-hub__cta">Edit page →</span>
        </a>
        <?php endforeach; ?>
    </div>

    <div class="admin-panel admin-panel--cms admin-panel--media">
        <h3 class="admin-panel__title">Media library</h3>
        <p class="admin-panel__subtitle">Upload images or short video for page content and sliders. Paths appear in uploads; you can paste a path into fields that accept an image URL.</p>
        <form class="admin-form" method="post" action="<?= e(app_url('admin/media/upload')) ?>" enctype="multipart/form-data" data-cms-upload-form>
            <?= csrf_field() ?>
            <label class="visually-hidden" for="cms-media-file">Choose file</label>
            <input id="cms-media-file" class="input" type="file" name="file" accept="image/*,video/mp4,video/webm" required>
            <button class="btn btn--primary admin-media-upload-btn" type="submit">Upload</button>
            <p class="text-muted" style="font-size:0.85rem;margin:0.5rem 0 0;">JPG · PNG · WEBP · GIF · MP4 · WEBM</p>
        </form>

        <?php if (!empty($media)): ?>
        <div class="admin-media-recent">
            <p class="admin-form__section-label" style="margin-top: 1.25rem;">Recent uploads</p>
            <div class="admin-media-grid">
                <?php foreach ($media as $m): ?>
                    <?php $p = (string) ($m['file_path'] ?? ''); ?>
                    <a class="admin-media-thumb" href="<?= e(app_url($p)) ?>" target="_blank" rel="noopener noreferrer">
                        <?php if (str_starts_with((string) ($m['mime'] ?? ''), 'image/')): ?>
                            <img src="<?= e(app_url($p)) ?>" alt="">
                        <?php else: ?>
                            <span class="admin-media-thumb__badge">VIDEO</span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
  const form = document.querySelector('[data-cms-upload-form]');
  if (!form) return;
  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const fd = new FormData(form);
    const res = await fetch(form.action, { method: 'POST', body: fd, credentials: 'same-origin' });
    const data = await res.json().catch(() => null);
    if (!data || !data.ok) {
      alert((data && data.error) ? data.error : 'Upload failed');
      return;
    }
    window.location.reload();
  });
})();
</script>
