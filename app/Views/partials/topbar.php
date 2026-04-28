<?php
declare(strict_types=1);
/** @var string $activeNav */
/** @var array $app */
$activeNav = $activeNav ?? '';
$siteName = $app['name'] ?? 'DOTS Event Planning';
$logoPath = trim(site_setting('logo_path', 'assets/images/logo-dots.svg'));
$primaryCtaHref = app_url('book');
$primaryCtaLabel = 'Book';
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
            <a class="btn btn--primary app-topbar__cta" href="<?= e($primaryCtaHref) ?>"<?= $activeNav === 'book' ? ' aria-current="page"' : '' ?>><?= e($primaryCtaLabel) ?></a>
        </div>
    </div>
</header>
