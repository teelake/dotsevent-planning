<?php
declare(strict_types=1);
/** @var string $content */
/** @var string $title */
/** @var string $activeNav */
/** @var string $bodyClass */
/** @var string $extraHeader */
/** @var array $app */
/** @var string $metaDescription */
/** @var string $metaOgType */
$bodyClass = $bodyClass ?? '';
$extraHeader = $extraHeader ?? '';
$extraFooter = $extraFooter ?? '';
$activeNav = $activeNav ?? '';
$siteName = $app['name'] ?? 'DOTS Event Planning';
$t = $title ?? 'Home';
$pageTitlePlain = ($t === '' || $t === 'Home') ? $siteName : $t . ' | ' . $siteName;
$pageTitle = e($pageTitlePlain);
$metaDescription = $metaDescription ?? (string) ($app['meta_description'] ?? '');
$metaOgType = $metaOgType ?? 'website';
$canonicalUrl = current_canonical_url();
$ogImagePath = trim((string) ($app['og_image'] ?? ''));
$ogImageAbsolute = $ogImagePath !== '' ? absolute_public_url($ogImagePath) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ebe6dc" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0c0b09" media="(prefers-color-scheme: dark)">
    <title><?= $pageTitle ?></title>
    <?php if ($metaDescription !== ''): ?>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta property="og:type" content="<?= e($metaOgType) ?>">
    <meta property="og:title" content="<?= e($pageTitlePlain) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta name="twitter:card" content="<?= $ogImageAbsolute !== '' ? 'summary_large_image' : 'summary' ?>">
    <meta name="twitter:title" content="<?= e($pageTitlePlain) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <?php if ($canonicalUrl !== ''): ?>
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <?php endif; ?>
    <?php if ($ogImageAbsolute !== ''): ?>
    <meta property="og:image" content="<?= e($ogImageAbsolute) ?>">
    <meta name="twitter:image" content="<?= e($ogImageAbsolute) ?>">
    <?php endif; ?>
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/layout.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/pages.css')) ?>">
    <?= $extraHeader ?>
</head>
<body class="<?= e($bodyClass) ?>">
<a class="skip-link" href="#main">Skip to main content</a>

<header class="site-header" data-header data-header-scroll>
    <div class="site-header__inner shell">
        <a class="site-logo" href="<?= e(app_url('')) ?>" aria-label="<?= e($siteName) ?> home">
            <img class="site-logo__mark" src="<?= e(asset('images/logo-dots.svg')) ?>" alt="" width="44" height="44">
            <span class="site-logo__text">DOTS <span>Event</span></span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
            <span class="nav-toggle__bar" aria-hidden="true"></span>
            <span class="visually-hidden">Menu</span>
        </button>
        <nav id="site-nav" class="site-nav" aria-label="Primary" data-nav>
            <ul class="site-nav__list">
                <?php
                $items = [
                    'home' => ['Home', app_url('')],
                    'about' => ['About us', app_url('about')],
                    'services' => ['Services', app_url('services')],
                    'kids' => ['Kids party', app_url('kids')],
                    'rentals' => ['Rentals', app_url('rentals')],
                    'portfolio' => ['Portfolio', app_url('portfolio')],
                    'book' => ['Book', app_url('book')],
                    'contact' => ['Contact', app_url('contact')],
                ];
                foreach ($items as $key => $item):
                    $isCurrent = $activeNav === $key;
                ?>
                <li>
                    <a class="site-nav__link<?= $isCurrent ? ' is-active' : '' ?>" href="<?= e($item[1]) ?>"<?= $isCurrent ? ' aria-current="page"' : '' ?>><?= e($item[0]) ?></a>
                </li>
                <?php endforeach; ?>
                <?php
                $cn = (int) cart_count();
                $cartCurrent = $activeNav === 'cart';
                ?>
                <li>
                    <a class="site-nav__link site-nav__link--cart<?= $cartCurrent ? ' is-active' : '' ?>"
                       href="<?= e(app_url('cart')) ?>"
                       aria-label="<?= $cn > 0 ? 'Shopping cart, ' . (int) $cn . ' items' : 'Shopping cart' ?>"<?= $cartCurrent ? ' aria-current="page"' : '' ?>>
                        <svg class="site-nav__cart-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                            <path d="M9 22a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zM1 2h2l.6 2M5 2h14l-1.5 9.2A2 2 0 0 1 15.5 13H8.4a2 2 0 0 1-1.9-1.4L4.2 6H19" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="site-nav__cart-text" aria-hidden="true">Cart</span>
                        <?php if ($cn > 0): ?>
                        <span class="cart-badge" aria-hidden="true"><?= (int) $cn ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<main id="main" class="site-main" tabindex="-1">
    <?php
    $flashErr = \App\Core\Flash::get(\App\Core\Flash::ERROR);
    $flashOk = \App\Core\Flash::get(\App\Core\Flash::SUCCESS);
    $flashNote = \App\Core\Flash::get(\App\Core\Flash::NOTICE);
    ?>
    <?php if ($flashErr !== null): ?>
        <div class="flash flash--error" role="alert" data-flash>
            <div class="flash__inner">
                <span class="flash__text"><?= e($flashErr) ?></span>
                <button type="button" class="flash__dismiss" aria-label="Dismiss message" data-flash-dismiss>&times;</button>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($flashOk !== null): ?>
        <div class="flash flash--success" role="status" data-flash>
            <div class="flash__inner">
                <span class="flash__text"><?= e($flashOk) ?></span>
                <button type="button" class="flash__dismiss" aria-label="Dismiss message" data-flash-dismiss>&times;</button>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($flashNote !== null): ?>
        <div class="flash flash--notice" role="status" data-flash>
            <div class="flash__inner">
                <span class="flash__text"><?= e($flashNote) ?></span>
                <button type="button" class="flash__dismiss" aria-label="Dismiss message" data-flash-dismiss>&times;</button>
            </div>
        </div>
    <?php endif; ?>
    <?= $content ?>
</main>

<?php
$social = site_social_urls();
$hasSocial = ($social['facebook'] ?? '') !== '' || ($social['instagram'] ?? '') !== '' || ($social['youtube'] ?? '') !== '';
$footerEmail = (string) ($app['email'] ?? 'info@dotseventplanning.com');
$footerPhoneDisplay = (string) ($app['phone_display'] ?? '');
$footerPhoneTel = (string) ($app['phone_tel'] ?? '');
$footerLine1 = (string) ($app['address_line1'] ?? '');
$footerLine2 = (string) ($app['address_line2'] ?? '');
$mapEmbed = site_map_embed_url();
?>
<footer class="site-footer">
    <div class="shell site-footer__grid">
        <div class="site-footer__brand">
            <a class="site-footer__logo" href="<?= e(app_url('')) ?>"><?= e($siteName) ?></a>
            <p class="site-footer__tagline">Event planning in Saint John—straight answers, on-site hustle, and decor that still looks good in your uncle’s phone photos.</p>
            <?php if ($hasSocial): ?>
            <ul class="social" aria-label="Social links">
                <?php if ($social['facebook'] !== ''): ?>
                <li>
                    <a class="social__link social__link--icon" href="<?= e($social['facebook']) ?>" rel="noopener noreferrer" target="_blank" aria-label="Facebook">
                        <svg class="social__svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M13.5 22v-8.3h2.8l.4-3.3h-3.2V8.8c0-.9.3-1.6 1.7-1.6h1.7V4.2c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.5-4 4.2v2.4H7.5v3.3H11V22h2.5z"/></svg>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($social['instagram'] !== ''): ?>
                <li>
                    <a class="social__link social__link--icon" href="<?= e($social['instagram']) ?>" rel="noopener noreferrer" target="_blank" aria-label="Instagram">
                        <svg class="social__svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($social['youtube'] !== ''): ?>
                <li>
                    <a class="social__link social__link--icon" href="<?= e($social['youtube']) ?>" rel="noopener noreferrer" target="_blank" aria-label="YouTube">
                        <svg class="social__svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </div>
        <div class="site-footer__contact">
            <h2 class="site-footer__heading">Contact</h2>
            <p><a href="mailto:<?= e($footerEmail) ?>"><?= e($footerEmail) ?></a></p>
            <?php if ($footerLine1 !== '' || $footerLine2 !== ''): ?>
            <p><?= e(trim($footerLine1 . ', ' . $footerLine2, ' ,')) ?></p>
            <?php endif; ?>
            <?php if ($footerPhoneTel !== '' && $footerPhoneDisplay !== ''): ?>
            <p><a href="tel:<?= e(preg_replace('/\s+/', '', $footerPhoneTel)) ?>"><?= e($footerPhoneDisplay) ?></a></p>
            <?php endif; ?>
        </div>
        <div class="site-footer__map">
            <h2 class="site-footer__heading">Location</h2>
            <div class="site-footer__map-embed map-embed">
                <iframe class="map-embed__frame" title="Map: <?= e($siteName) ?> area" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= e($mapEmbed) ?>"></iframe>
            </div>
        </div>
    </div>
    <div class="site-footer__legal">
        <p>&copy; <?= (int) date('Y') ?> <?= e($siteName) ?>. All rights reserved.</p>
    </div>
</footer>

<script src="<?= e(asset('js/main.js')) ?>" defer></script>
<?= $extraFooter ?? '' ?>
</body>
</html>
