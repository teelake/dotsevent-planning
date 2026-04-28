<?php
declare(strict_types=1);
/** @var array<string, string> $settings */
$settings = $settings ?? [];
function s(array $settings, string $k, string $fallback = ''): string {
    $v = $settings[$k] ?? '';
    return $v !== '' ? $v : $fallback;
}
$cfg = app_config();
?>
<section class="admin-cms">
    <header class="admin-cms__header">
        <p class="admin-cms__eyebrow">Website</p>
        <h2 class="admin-cms__title">Site settings</h2>
        <p class="admin-cms__lead">Brand assets, contact info, map, and social URLs. Page copy and media live under <a class="admin-inline-link" href="<?= e(app_url('admin/cms/pages')) ?>">Pages &amp; content</a>; homepage slides under <a class="admin-inline-link" href="<?= e(app_url('admin/cms/slides')) ?>">Hero carousel</a>.</p>
        <div class="admin-cms__actions">
            <a class="btn btn--secondary" href="<?= e(app_url('')) ?>" target="_blank" rel="noopener noreferrer">View live site</a>
            <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/pages')) ?>">Pages &amp; content</a>
            <a class="btn btn--primary" href="<?= e(app_url('admin/cms/slides')) ?>">Hero carousel</a>
        </div>
    </header>

    <div class="admin-grid admin-grid--cms-stats">
        <div class="admin-stat admin-stat--lift">
            <div class="admin-stat__l">Configuration</div>
            <div class="admin-stat__v admin-stat__v--sm">DB overrides <code>config/app.php</code></div>
        </div>
        <div class="admin-stat admin-stat--lift">
            <div class="admin-stat__l">Map &amp; footer</div>
            <div class="admin-stat__v admin-stat__v--sm">Embed + address</div>
        </div>
        <div class="admin-stat admin-stat--lift">
            <div class="admin-stat__l">Identity</div>
            <div class="admin-stat__v admin-stat__v--sm">Logo &amp; favicon</div>
        </div>
    </div>

    <div class="admin-panel admin-panel--cms">
        <h3 class="admin-panel__title">Global settings</h3>
        <p class="admin-panel__subtitle">These values power the public footer, contact page, and browser chrome when the database is connected.</p>

        <form class="admin-form" method="post" action="<?= e(app_url('admin/cms/settings')) ?>" enctype="multipart/form-data">
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
                        <input class="input" id="cms-logo" name="logo_path" type="text" placeholder="assets/images/logo/brand-logo-….jpg" value="<?= e(s($settings, 'logo_path', 'assets/images/logo-dots.svg')) ?>">
                        <span class="text-muted admin-settings-hint">Saves under <code>public/assets/images/logo/</code>. Or paste a path from <strong>Recent</strong> on <a class="admin-inline-link" href="<?= e(app_url('admin/cms/pages')) ?>">Pages &amp; content</a>.</span>
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
                        <input class="input" id="cms-favicon" name="favicon_path" type="text" placeholder="assets/images/favicon/brand-favicon-….png" value="<?= e(s($settings, 'favicon_path', '')) ?>">
                        <span class="text-muted admin-settings-hint">Saves under <code>public/assets/images/favicon/</code>.</span>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <p class="admin-form__section-label">Contact &amp; location</p>
                <p class="text-muted" style="margin:0 0 0.5rem; font-size:0.82rem;">Shown in the footer and contact page. Map embed is used when no override is saved.</p>
                <label>Contact</label>
                <div class="placeholder-grid" style="grid-template-columns:1fr; gap:0.75rem; margin:0;">
                    <input class="input" name="email" type="email" placeholder="Email" value="<?= e(s($settings, 'email', (string) ($cfg['email'] ?? ''))) ?>">
                    <input class="input" name="phone_display" type="text" placeholder="Phone (display)" value="<?= e(s($settings, 'phone_display', (string) ($cfg['phone_display'] ?? ''))) ?>">
                    <input class="input" name="phone_tel" type="text" placeholder="Phone for tel: link (e.g. +15065550100)" value="<?= e(s($settings, 'phone_tel', (string) ($cfg['phone_tel'] ?? ''))) ?>">
                    <input class="input" name="address_line1" id="cms-address-line1" type="text" placeholder="Address line 1 (e.g. street & suite)" value="<?= e(s($settings, 'address_line1', (string) ($cfg['address_line1'] ?? ''))) ?>">
                    <input class="input" name="address_line2" id="cms-address-line2" type="text" placeholder="Address line 2 (city, province, postal)" value="<?= e(s($settings, 'address_line2', (string) ($cfg['address_line2'] ?? ''))) ?>">
                </div>
            </div>
            <div class="form-row">
                <label for="cms-map">Google Maps embed URL</label>
                <input class="input" id="cms-map" name="map_embed_url" type="text" value="<?= e(s($settings, 'map_embed_url', (string) ($cfg['map_embed_url'] ?? ''))) ?>">
                <span class="text-muted admin-settings-hint">Google Maps → Share → Embed a map → paste the iframe <code>src</code>.</span>
            </div>

            <div class="form-row">
                <label>Social</label>
                <div class="placeholder-grid" style="grid-template-columns:1fr; gap:0.75rem; margin:0;">
                    <input class="input" name="social_facebook" type="url" placeholder="Facebook URL" value="<?= e(s($settings, 'social_facebook', (string) ($cfg['social_facebook'] ?? ''))) ?>">
                    <input class="input" name="social_instagram" type="url" placeholder="Instagram URL" value="<?= e(s($settings, 'social_instagram', (string) ($cfg['social_instagram'] ?? ''))) ?>">
                    <input class="input" name="social_youtube" type="url" placeholder="YouTube URL" value="<?= e(s($settings, 'social_youtube', (string) ($cfg['social_youtube'] ?? ''))) ?>">
                </div>
            </div>

            <button class="btn btn--primary" type="submit">Save settings</button>
        </form>
    </div>
</section>
