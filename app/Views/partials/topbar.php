<?php
declare(strict_types=1);
/** @var string $activeNav */
/** @var array $app */
$activeNav = $activeNav ?? '';
$siteName = $app['name'] ?? 'DOTS Event Planning';
$logoPath = trim(site_setting('logo_path', 'assets/images/logo-dots.svg'));
$primaryCtaHref = app_url('book');
$primaryCtaLabel = 'Book Your Event';
$social = site_social_urls();
$headerSocial = array_filter([
    'facebook' => $social['facebook'] ?? '',
    'instagram' => $social['instagram'] ?? '',
    'whatsapp' => $social['whatsapp'] ?? '',
], static fn (string $url): bool => trim($url) !== '');
$headerIcons = [
    'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.5 8.4V6.7c0-.8.3-1.2 1.3-1.2h1.6V2.8c-.8-.1-1.5-.2-2.3-.2-2.5 0-4.2 1.5-4.2 4.3v1.5H8.1v3h2.8v9.9h3.4v-9.9h2.8l.4-3h-3z"/></svg>',
    'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3.2" y="3.2" width="17.6" height="17.6" rx="5.1"/><circle cx="12" cy="12" r="4.1"/><circle cx="17.2" cy="6.8" r="1.2"/></svg>',
    'whatsapp' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20.5 11.8a8.3 8.3 0 0 1-12.2 7.3L3.5 20.5l1.5-4.6a8.3 8.3 0 1 1 15.5-4.1z"/><path d="M8.8 8.1c.2-.5.4-.5.7-.5h.5c.2 0 .4.1.5.4l.7 1.7c.1.3 0 .5-.1.7l-.4.5c-.1.1-.2.3-.1.5.4.8 1 1.5 1.7 2 .5.3.9.5 1.1.6.2.1.4.1.5-.1l.8-.9c.2-.2.4-.2.6-.1l1.8.9c.3.1.4.3.4.5 0 .4-.3 1.2-.9 1.6-.5.4-1.1.5-1.8.4-1.1-.2-2.5-.8-4-2.1-1.8-1.6-2.8-3.5-3-4.6-.1-.5 0-1 .2-1.5z"/></svg>',
];
$headerLabels = [
    'facebook' => 'Facebook',
    'instagram' => 'Instagram',
    'whatsapp' => 'Chat on WhatsApp',
];
$navItems = [
    'home' => ['Home', app_url('')],
    'about' => ['About Us', app_url('about')],
    'services' => ['Services', app_url('services')],
    'kids' => ['Kids Party', app_url('kids')],
    'rentals' => ['Rentals', app_url('rentals')],
    'portfolio' => ['Portfolio', app_url('portfolio')],
    'contact' => ['Contact', app_url('contact')],
];
$cartHref = app_url('cart');
$cn = (int) cart_count();
$cartAriaLabel = $cn > 0 ? ('Shopping cart, ' . $cn . ' items') : 'Shopping cart';
?>
<header class="app-topbar" data-topbar>
    <div class="app-topbar__inner shell">
        <a class="app-brand" href="<?= e(app_url('')) ?>" aria-label="<?= e($siteName) ?> — home">
            <img
                class="app-brand__mark"
                src="<?= e(public_file_url($logoPath)) ?>"
                alt="<?= e($siteName) ?>"
                width="320"
                height="96"
                decoding="sync"
                loading="eager"
                fetchpriority="high"
            >
        </a>

        <nav class="app-site-nav" aria-label="Primary">
            <ul class="app-site-nav__list">
                <?php foreach ($navItems as $key => $item):
                    $isCurrent = $activeNav === $key;
                    ?>
                <li class="app-site-nav__item">
                    <a class="app-site-nav__link<?= $isCurrent ? ' is-active' : '' ?>" href="<?= e($item[1]) ?>"<?= $isCurrent ? ' aria-current="page"' : '' ?>>
                        <?= e($item[0]) ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <li class="app-site-nav__item">
                    <a
                        class="app-site-nav__link app-site-nav__link--cart<?= $activeNav === 'cart' ? ' is-active' : '' ?>"
                        href="<?= e($cartHref) ?>"
                        <?= $activeNav === 'cart' ? 'aria-current="page"' : '' ?>
                        aria-label="<?= e($cartAriaLabel) ?>"
                    >
                        <svg class="app-site-nav__cart-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <?php if ($cn > 0): ?>
                        <span class="app-site-nav__badge"><?= (int) $cn ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="app-topbar__end">
            <?php if ($headerSocial !== []): ?>
            <ul class="app-topbar-social" aria-label="Quick social links">
                <?php foreach ($headerSocial as $key => $url): ?>
                <li>
                    <a class="app-topbar-social__link app-topbar-social__link--<?= e($key) ?>" href="<?= e($url) ?>" rel="noopener noreferrer" target="_blank" aria-label="<?= e($headerLabels[$key] ?? ucfirst($key)) ?>">
                        <?= $headerIcons[$key] ?? '' ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <a class="btn btn--primary app-topbar__cta" href="<?= e($primaryCtaHref) ?>"<?= $activeNav === 'book' ? ' aria-current="page"' : '' ?>><?= e($primaryCtaLabel) ?></a>
        </div>
    </div>
</header>
