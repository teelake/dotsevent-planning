<?php
declare(strict_types=1);
/** @var array $app */
$siteName = $app['name'] ?? 'DOTS Event Planning';
$footerLogoPath = trim(site_setting('logo_path', 'assets/images/logo-dots.svg'));
$social = site_social_urls();
$hasSocial = ($social['facebook'] ?? '') !== '' || ($social['instagram'] ?? '') !== '' || ($social['youtube'] ?? '') !== '';
$footerEmail = trim(site_setting('email', (string) ($app['email'] ?? 'info@dotseventplanning.com')));
$footerPhoneDisplay = trim(site_setting('phone_display', (string) ($app['phone_display'] ?? '')));
$footerPhoneTel = trim(site_setting('phone_tel', (string) ($app['phone_tel'] ?? '')));
$footerLine1 = trim(site_setting('address_line1', (string) ($app['address_line1'] ?? '')));
$footerLine2 = trim(site_setting('address_line2', (string) ($app['address_line2'] ?? '')));
$mapEmbed = site_map_embed_url();
?>
<footer class="app-footer">
    <div class="shell shell--wide app-footer__grid">
        <div class="app-footer__brand">
            <a class="app-footer__lockup" href="<?= e(app_url('')) ?>">
                <img class="app-footer__mark" src="<?= e(app_url(ltrim($footerLogoPath, '/'))) ?>" alt="" width="48" height="48">
                <span class="app-footer__name"><?= e($siteName) ?></span>
            </a>
            <p class="app-footer__tagline">Event planning in Saint John—straight answers, on-site hustle, and decor that still looks good in your uncle’s phone photos.</p>
            <?php if ($hasSocial): ?>
            <ul class="app-social" aria-label="Social links">
                <?php if ($social['facebook'] !== ''): ?>
                <li><a class="app-social__link" href="<?= e($social['facebook']) ?>" rel="noopener noreferrer" target="_blank" aria-label="Facebook">Facebook</a></li>
                <?php endif; ?>
                <?php if ($social['instagram'] !== ''): ?>
                <li><a class="app-social__link" href="<?= e($social['instagram']) ?>" rel="noopener noreferrer" target="_blank" aria-label="Instagram">Instagram</a></li>
                <?php endif; ?>
                <?php if ($social['youtube'] !== ''): ?>
                <li><a class="app-social__link" href="<?= e($social['youtube']) ?>" rel="noopener noreferrer" target="_blank" aria-label="YouTube">YouTube</a></li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div class="app-footer__contact">
            <h2 class="app-footer__heading">Contact</h2>
            <p><a href="mailto:<?= e($footerEmail) ?>"><?= e($footerEmail) ?></a></p>
            <?php if ($footerLine1 !== '' || $footerLine2 !== ''): ?>
            <p><?= e(trim($footerLine1 . ', ' . $footerLine2, ' ,')) ?></p>
            <?php endif; ?>
            <?php if ($footerPhoneTel !== '' && $footerPhoneDisplay !== ''): ?>
            <p><a href="tel:<?= e(preg_replace('/\s+/', '', $footerPhoneTel)) ?>"><?= e($footerPhoneDisplay) ?></a></p>
            <?php endif; ?>
        </div>

        <div class="app-footer__map">
            <h2 class="app-footer__heading">Location</h2>
            <div class="app-footer__map-embed">
                <iframe class="app-footer__map-frame" title="Map: <?= e($siteName) ?> area" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= e($mapEmbed) ?>"></iframe>
            </div>
        </div>
    </div>
    <div class="app-footer__legal">
        <p>&copy; <?= (int) date('Y') ?> <?= e($siteName) ?>. All rights reserved.</p>
    </div>
</footer>

