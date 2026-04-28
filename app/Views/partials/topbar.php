<?php
declare(strict_types=1);
/** @var string $activeNav */
/** @var array $app */
$activeNav = $activeNav ?? '';
$siteName = $app['name'] ?? 'DOTS Event Planning';
$logoPath = trim(site_setting('logo_path', 'assets/images/logo-dots.svg'));
$primaryCtaHref = app_url('book');
$primaryCtaLabel = 'Book';
?>
<header class="app-topbar" data-topbar>
    <div class="app-topbar__inner shell shell--wide">
        <a class="app-brand" href="<?= e(app_url('')) ?>" aria-label="<?= e($siteName) ?> home">
            <img class="app-brand__mark" src="<?= e(app_url(ltrim($logoPath, '/'))) ?>" alt="" width="40" height="40">
            <span class="app-brand__text">DOTS <span>Event</span></span>
        </a>

        <div class="app-topbar__actions">
            <button class="app-pages-btn" type="button" aria-expanded="false" aria-controls="app-drawer" data-drawer-toggle>
                <span class="app-pages-btn__label">Pages</span>
                <span class="app-pages-btn__chev" aria-hidden="true">▾</span>
            </button>
            <a class="btn btn--primary app-topbar__cta" href="<?= e($primaryCtaHref) ?>"<?= $activeNav === 'book' ? ' aria-current="page"' : '' ?>><?= e($primaryCtaLabel) ?></a>
        </div>
    </div>
</header>

