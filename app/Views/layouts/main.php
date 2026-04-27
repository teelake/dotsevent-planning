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
$pageTitle = ($t === '' || $t === 'Home') ? e($siteName) : e($t) . ' | ' . e($siteName);
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
    <meta property="og:title" content="<?= $pageTitle ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta name="twitter:card" content="<?= $ogImageAbsolute !== '' ? 'summary_large_image' : 'summary' ?>">
    <meta name="twitter:title" content="<?= $pageTitle ?>">
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
<body class="<?= e($bodyClass) ?>" data-site-header>
<a class="skip-link" href="#main">Skip to main content</a>

<header class="site-header" data-header data-header-scroll>
    <div class="shell site-header__inner">
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
                    'kids-party' => ['Kids party', app_url('kids-party')],
                    'rentals' => ['Rentals', app_url('rentals')],
                    'portfolio' => ['Portfolio', app_url('portfolio')],
                    'book-your-event' => ['Book your event', app_url('book-your-event')],
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
                        <svg class="social__svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9A5.5 5.5 0 0 1 16.5 22h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9zm4.5 2.5a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm5.25-3.25a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5z"/></svg>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($social['youtube'] !== ''): ?>
                <li>
                    <a class="social__link social__link--icon" href="<?= e($social['youtube']) ?>" rel="noopener noreferrer" target="_blank" aria-label="YouTube">
                        <svg class="social__svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M10 15.5l6-3.5-6-3.5v7zm12.2-5.2c.2 1.1.2 3.2.2 3.2s0 2.1-.2 3.2c-.1.7-.4 1.2-.8 1.6-.5.4-1.1.5-1.5.5-1.1.1-4.7.1-4.7.1h-.1s-3.6 0-4.7-.1c-.4 0-1.1-.1-1.5-.5-.4-.4-.7-.9-.8-1.6C4 19.1 4 17 4 17s0-2.1.2-3.2c.1-.7.3-1.2.7-1.6.4-.3 1-.5 1.5-.5C7.3 12 12 12 12 12h.1s4.6 0 4.7.1c.4 0 1.1.2 1.5.5.4.4.6.9.7 1.6z"/></svg>
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
