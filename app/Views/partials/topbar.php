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
    'about' => ['About us', app_url('about')],
    'services' => ['Services', app_url('services')],
    'kids' => ['Kids party', app_url('kids')],
    'rentals' => ['Rentals', app_url('rentals')],
    'portfolio' => ['Portfolio', app_url('portfolio')],
    'contact' => ['Contact', app_url('contact')],
    'cart' => ['Cart', app_url('cart')],
];
$cn = (int) cart_count();
$nameParts = preg_split('/\s+/', $siteName, 2, PREG_SPLIT_NO_EMPTY);
$brandPrimary = $nameParts[0] ?? $siteName;
$brandSecondary = $nameParts[1] ?? '';
?>
<header class="app-topbar" data-topbar>
    <div class="app-topbar__inner shell shell--wide">
        <a class="app-brand" href="<?= e(app_url('')) ?>" aria-label="<?= e($siteName) ?> home">
            <img class="app-brand__mark" src="<?= e(public_file_url($logoPath)) ?>" alt="" width="40" height="40">
            <span class="app-brand__text">
                <?= e($brandPrimary) ?><?php if ($brandSecondary !== ''): ?> <span><?= e($brandSecondary) ?></span><?php endif; ?>
            </span>
        </a>

        <nav class="app-site-nav" aria-label="Primary">
            <ul class="app-site-nav__list">
                <?php foreach ($navItems as $key => $item):
                    $isCurrent = $activeNav === $key;
                ?>
                <li class="app-site-nav__item">
                    <a class="app-site-nav__link<?= $isCurrent ? ' is-active' : '' ?>" href="<?= e($item[1]) ?>"<?= $isCurrent ? ' aria-current="page"' : '' ?>>
                        <?= e($item[0]) ?>
                        <?php if ($key === 'cart' && $cn > 0): ?>
                            <span class="app-site-nav__badge" aria-label="<?= (int) $cn ?> items in cart"><?= (int) $cn ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="app-topbar__end">
            <a class="btn btn--primary app-topbar__cta" href="<?= e($primaryCtaHref) ?>"<?= $activeNav === 'book' ? ' aria-current="page"' : '' ?>><?= e($primaryCtaLabel) ?></a>
        </div>
    </div>
</header>
