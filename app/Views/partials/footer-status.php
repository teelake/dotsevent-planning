<?php
declare(strict_types=1);
/** @var array $app */
$siteName = $app['name'] ?? 'DOTS Event Planning';
$footerLogoPath = trim(site_setting('logo_path', 'assets/images/logo-dots.svg'));
$social = site_social_urls();
$hasSocial = ($social['facebook'] ?? '') !== '' || (($social['instagram'] ?? '') !== '') || (($social['youtube'] ?? '') !== '');
$footerEmail = trim(site_setting('email', (string) ($app['email'] ?? 'info@dotseventplanning.com')));
$footerPhoneDisplay = trim(site_setting('phone_display', (string) ($app['phone_display'] ?? '')));
$footerPhoneTel = trim(site_setting('phone_tel', (string) ($app['phone_tel'] ?? '')));
$footerLine1 = trim(site_setting('address_line1', (string) ($app['address_line1'] ?? '')));
$footerLine2 = trim(site_setting('address_line2', (string) ($app['address_line2'] ?? '')));
$mapEmbed = site_map_embed_url();
$cartCountFooter = (int) cart_count();
$socialIcons = [
    'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.5 8.4V6.7c0-.8.3-1.2 1.3-1.2h1.6V2.8c-.8-.1-1.5-.2-2.3-.2-2.5 0-4.2 1.5-4.2 4.3v1.5H8.1v3h2.8v9.9h3.4v-9.9h2.8l.4-3h-3z"/></svg>',
    'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3.2" y="3.2" width="17.6" height="17.6" rx="5.1"/><circle cx="12" cy="12" r="4.1"/><circle cx="17.2" cy="6.8" r="1.2"/></svg>',
    'youtube' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M21.6 7.2a3 3 0 0 0-2.1-2.1C17.7 4.6 12 4.6 12 4.6s-5.7 0-7.5.5a3 3 0 0 0-2.1 2.1C2 9 2 12 2 12s0 3 .4 4.8a3 3 0 0 0 2.1 2.1c1.8.5 7.5.5 7.5.5s5.7 0 7.5-.5a3 3 0 0 0 2.1-2.1C22 15 22 12 22 12s0-3-.4-4.8z"/><path d="M10 15.3V8.7l5.8 3.3L10 15.3z"/></svg>',
];
?>
<footer class="app-footer">
    <div class="shell app-footer__grid">
        <div class="app-footer__col app-footer__col--brand">
            <a class="app-footer__lockup" href="<?= e(app_url('')) ?>" aria-label="<?= e($siteName) ?> — home">
                <img class="app-footer__mark" src="<?= e(public_file_url($footerLogoPath)) ?>" alt="<?= e($siteName) ?>" width="48" height="48">
            </a>
            <p class="app-footer__tagline">Event planning in Saint John—straight answers, on-site hustle, and decor that still looks good in your uncle’s phone photos.</p>
            <address class="app-footer__meta">
                <?php if ($footerEmail !== ''): ?>
                <p><a href="mailto:<?= e($footerEmail) ?>"><?= e($footerEmail) ?></a></p>
                <?php endif; ?>
                <?php if ($footerPhoneTel !== '' && $footerPhoneDisplay !== ''): ?>
                <p><a href="tel:<?= e(preg_replace('/\s+/', '', $footerPhoneTel)) ?>"><?= e($footerPhoneDisplay) ?></a></p>
                <?php endif; ?>
                <?php if ($footerLine1 !== '' || $footerLine2 !== ''): ?>
                <p class="app-footer__addr"><?= e(trim($footerLine1 . ', ' . $footerLine2, ' ,')) ?></p>
                <?php endif; ?>
            </address>
            <?php if ($hasSocial): ?>
            <ul class="app-social" aria-label="Social links">
                <?php if ($social['facebook'] !== ''): ?>
                <li><a class="app-social__link" href="<?= e($social['facebook']) ?>" rel="noopener noreferrer" target="_blank" aria-label="Facebook"><?= $socialIcons['facebook'] ?></a></li>
                <?php endif; ?>
                <?php if (($social['instagram'] ?? '') !== ''): ?>
                <li><a class="app-social__link" href="<?= e($social['instagram']) ?>" rel="noopener noreferrer" target="_blank" aria-label="Instagram"><?= $socialIcons['instagram'] ?></a></li>
                <?php endif; ?>
                <?php if (($social['youtube'] ?? '') !== ''): ?>
                <li><a class="app-social__link" href="<?= e($social['youtube']) ?>" rel="noopener noreferrer" target="_blank" aria-label="YouTube"><?= $socialIcons['youtube'] ?></a></li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </div>

        <nav class="app-footer__col app-footer__col--menus" aria-labelledby="footer-explore-heading">
            <h2 id="footer-explore-heading" class="app-footer__heading">Explore</h2>
            <ul class="app-footer__links" role="list">
                <li><a href="<?= e(app_url('')) ?>">Home</a></li>
                <li><a href="<?= e(app_url('about')) ?>">About Us</a></li>
                <li><a href="<?= e(app_url('services')) ?>">Services</a></li>
                <li><a href="<?= e(app_url('portfolio')) ?>">Portfolio</a></li>
            </ul>
        </nav>

        <nav class="app-footer__col app-footer__col--menus" aria-labelledby="footer-shop-heading">
            <h2 id="footer-shop-heading" class="app-footer__heading">Events &amp; Shop</h2>
            <ul class="app-footer__links" role="list">
                <li><a href="<?= e(app_url('kids')) ?>">Kids Party</a></li>
                <li><a href="<?= e(app_url('rentals')) ?>">Rentals</a></li>
                <li><a href="<?= e(app_url('cart')) ?>">Cart<?php if ($cartCountFooter > 0): ?> <span class="app-footer__cart-dot" aria-label="<?= $cartCountFooter ?> in cart"><?= $cartCountFooter ?></span><?php endif; ?></a></li>
                <li><a href="<?= e(app_url('book')) ?>">Book</a></li>
            </ul>
        </nav>

        <div class="app-footer__col app-footer__col--visit">
            <h2 class="app-footer__heading">Visit</h2>
            <ul class="app-footer__links" role="list">
                <li><a href="<?= e(app_url('contact')) ?>">Contact</a></li>
            </ul>
            <div class="app-footer__map-embed">
                <iframe class="app-footer__map-frame" title="Map: <?= e($siteName) ?> area" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= e($mapEmbed) ?>"></iframe>
            </div>
        </div>
    </div>
    <div class="app-footer__legal shell">
        <div class="app-footer__legal-inner">
            <p class="app-footer__copyright">&copy; <?= (int) date('Y') ?> <?= e($siteName) ?>. All rights reserved.</p>
            <p class="app-footer__credit">
                Designed by <a class="app-footer__credit-link" href="https://www.webspace.ng/" rel="noopener noreferrer" target="_blank">Webspace</a>
            </p>
        </div>
    </div>
</footer>
