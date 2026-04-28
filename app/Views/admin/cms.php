<?php
declare(strict_types=1);
/** @var array<string, string> $settings */
/** @var list<array<string, mixed>> $media */
$settings = $settings ?? [];
$media = $media ?? [];
function s(array $settings, string $k, string $fallback = ''): string {
    $v = $settings[$k] ?? '';
    return $v !== '' ? $v : $fallback;
}
$cfg = app_config();
?>

<section class="section--tight" style="padding-top: 0;">
    <div class="card card--folio" style="margin-bottom: 1.25rem; padding: 1.25rem 1.35rem; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem;">
        <div>
            <p class="text-muted" style="margin:0 0 0.35rem; font-size:0.7rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">Homepage experience</p>
            <h2 class="card__title" style="margin:0; font-size:1.15rem;">Hero carousel</h2>
            <p class="card__text" style="margin:0.35rem 0 0; max-width:42rem;">Manage homepage slides, imagery, buttons, and scheduling—separate from page copy.</p>
        </div>
        <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
            <a class="btn btn--secondary" href="<?= e(app_url('')) ?>" target="_blank" rel="noopener noreferrer">View storefront</a>
            <a class="btn btn--primary" href="<?= e(app_url('admin/cms/slides')) ?>">Open hero carousel</a>
        </div>
    </div>

    <div class="admin-grid" style="margin-bottom: 1rem;">
        <div class="admin-stat">
            <div class="admin-stat__l">Brand assets</div>
            <div class="admin-stat__v" style="font-size:1.1rem;">Logo + favicon</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__l">Pages</div>
            <div class="admin-stat__v" style="font-size:1.1rem;">Home / About / Services…</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat__l">Media</div>
            <div class="admin-stat__v" style="font-size:1.1rem;">Uploads library</div>
        </div>
    </div>

    <div class="card-grid" style="grid-template-columns: 1.1fr 0.9fr; align-items:start;">
        <div class="card card--folio" style="min-height:auto;">
            <h2 class="card__title" style="margin:0 0 0.75rem;">Global settings</h2>
            <p class="card__text" style="margin:0 0 1rem;">These override <code>config/app.php</code> when the database is connected.</p>

            <form class="admin-form" method="post" action="<?= e(app_url('admin/cms/settings')) ?>" enctype="multipart/form-data" style="max-width:none;">
                <?= csrf_field() ?>

                <div class="admin-settings-brand">
                    <div class="admin-settings-brand__preview">
                        <p class="admin-settings-brand__caption">Logo preview</p>
                        <div class="admin-settings-brand__thumb-wrap">
                            <img class="admin-settings-brand__thumb"
                                 src="<?= e(public_file_url(s($settings, 'logo_path', 'assets/images/logo-dots.svg'))) ?>"
                                 alt=""
                                 width="120"
                                 height="120">
                        </div>
                    </div>
                    <div class="admin-settings-brand__fields">
                        <div class="form-row">
                            <label for="cms-logo-upload">Upload logo image</label>
                            <input class="input" id="cms-logo-upload" name="logo_upload" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                            <span class="text-muted admin-settings-hint">PNG or SVG-ready PNG recommended. SVG upload not supported · max 5 MB.</span>
                        </div>
                        <div class="form-row">
                            <label for="cms-logo">Logo path override (relative to <code>/public</code>)</label>
                            <input class="input" id="cms-logo" name="logo_path" type="text" placeholder="assets/image/logo/brand-logo-….jpg" value="<?= e(s($settings, 'logo_path', 'assets/images/logo-dots.svg')) ?>">
                            <span class="text-muted admin-settings-hint">Uploads from here are saved under <code>public/assets/image/logo/</code>. Or paste a path from <strong>Recent</strong> uploads below.</span>
                        </div>
                    </div>
                </div>

                <div class="admin-settings-brand">
                    <div class="admin-settings-brand__preview">
                        <p class="admin-settings-brand__caption">Favicon preview</p>
                        <div class="admin-settings-brand__thumb-wrap admin-settings-brand__thumb-wrap--small">
                            <?php $fv = trim(s($settings, 'favicon_path', '')); ?>
                            <?php if ($fv !== ''): ?>
                            <img class="admin-settings-brand__thumb admin-settings-brand__thumb--tiny" src="<?= e(public_file_url($fv)) ?>" alt="" width="48" height="48">
                            <?php else: ?>
                            <span class="admin-settings-brand__noop">Default browser icon until you upload one.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="admin-settings-brand__fields">
                        <div class="form-row">
                            <label for="cms-favicon-upload">Upload favicon</label>
                            <input class="input" id="cms-favicon-upload" name="favicon_upload" type="file" accept="image/jpeg,image/png,image/webp,image/gif,.ico">
                            <span class="text-muted admin-settings-hint">ICO, PNG, or JPG · max 2 MB.</span>
                        </div>
                        <div class="form-row">
                            <label for="cms-favicon">Favicon path override (relative to <code>/public</code>)</label>
                            <input class="input" id="cms-favicon" name="favicon_path" type="text" placeholder="uploads/favicon.png" value="<?= e(s($settings, 'favicon_path', '')) ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <label for="cms-map">Google maps embed URL</label>
                    <input class="input" id="cms-map" name="map_embed_url" type="text" value="<?= e(s($settings, 'map_embed_url', (string) ($cfg['map_embed_url'] ?? ''))) ?>">
                </div>

                <div class="form-row">
                    <label>Social</label>
                    <div class="placeholder-grid" style="grid-template-columns:1fr; gap:0.75rem; margin:0;">
                        <input class="input" name="social_facebook" type="url" placeholder="Facebook URL" value="<?= e(s($settings, 'social_facebook', (string) ($cfg['social_facebook'] ?? ''))) ?>">
                        <input class="input" name="social_instagram" type="url" placeholder="Instagram URL" value="<?= e(s($settings, 'social_instagram', (string) ($cfg['social_instagram'] ?? ''))) ?>">
                        <input class="input" name="social_youtube" type="url" placeholder="YouTube URL" value="<?= e(s($settings, 'social_youtube', (string) ($cfg['social_youtube'] ?? ''))) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <label>Contact</label>
                    <div class="placeholder-grid" style="grid-template-columns:1fr; gap:0.75rem; margin:0;">
                        <input class="input" name="email" type="email" placeholder="Email" value="<?= e(s($settings, 'email', (string) ($cfg['email'] ?? ''))) ?>">
                        <input class="input" name="phone_display" type="text" placeholder="Phone (display)" value="<?= e(s($settings, 'phone_display', (string) ($cfg['phone_display'] ?? ''))) ?>">
                        <input class="input" name="phone_tel" type="text" placeholder="Phone (tel:)" value="<?= e(s($settings, 'phone_tel', (string) ($cfg['phone_tel'] ?? ''))) ?>">
                        <input class="input" name="address_line1" type="text" placeholder="Address line 1" value="<?= e(s($settings, 'address_line1', (string) ($cfg['address_line1'] ?? ''))) ?>">
                        <input class="input" name="address_line2" type="text" placeholder="Address line 2" value="<?= e(s($settings, 'address_line2', (string) ($cfg['address_line2'] ?? ''))) ?>">
                    </div>
                </div>

                <button class="btn btn--primary" type="submit">Save settings</button>
            </form>
        </div>

        <div class="card card--frame" style="min-height:auto;">
            <h2 class="card__title" style="margin:0 0 0.75rem;">Pages</h2>
            <p class="card__text" style="margin:0 0 1rem;">Edit content blocks with Quill.</p>
            <div class="placeholder-grid" style="margin:0;">
                <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/page/home')) ?>">Edit Home</a>
                <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/page/about')) ?>">Edit About</a>
                <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/page/services')) ?>">Edit Services</a>
                <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/page/portfolio')) ?>">Edit Portfolio</a>
                <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/page/contact')) ?>">Edit Contact</a>
                <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/page/book')) ?>">Edit Book</a>
                <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/page/kids-party')) ?>">Edit Kids party</a>
            </div>

            <hr style="border:0;border-top:1px solid var(--color-line); margin:1.25rem 0;">
            <h3 class="card__title" style="font-size:1.05rem;margin:0 0 0.75rem;">Upload media</h3>
            <form class="admin-form" method="post" action="<?= e(app_url('admin/media/upload')) ?>" enctype="multipart/form-data" data-cms-upload-form>
                <?= csrf_field() ?>
                <input class="input" type="file" name="file" accept="image/*,video/mp4,video/webm" required>
                <button class="btn btn--primary" type="submit" style="margin-top:0.75rem;">Upload</button>
                <p class="text-muted" style="font-size:0.85rem;margin:0.75rem 0 0;">Images: JPG/PNG/WEBP/GIF · Video: MP4/WEBM</p>
            </form>

            <?php if (!empty($media)): ?>
            <div style="margin-top: 1rem;">
                <p class="text-muted" style="font-size:0.8rem;margin:0 0 0.6rem;">Recent</p>
                <div class="placeholder-grid" style="grid-template-columns: repeat(3, 1fr); gap: 0.6rem; margin:0;">
                    <?php foreach ($media as $m): ?>
                        <?php $p = (string) ($m['file_path'] ?? ''); ?>
                        <a href="<?= e(app_url($p)) ?>" target="_blank" rel="noopener noreferrer" style="display:block; border:1px solid var(--color-line); border-radius:12px; overflow:hidden; background:#fff;">
                            <?php if (str_starts_with((string) ($m['mime'] ?? ''), 'image/')): ?>
                                <img src="<?= e(app_url($p)) ?>" alt="" style="width:100%; height:72px; object-fit:cover;">
                            <?php else: ?>
                                <div style="height:72px; display:flex; align-items:center; justify-content:center; color:var(--color-ink-soft); font-weight:700;">VIDEO</div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// Enhance media upload form: show JSON errors nicely
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

