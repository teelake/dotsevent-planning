<?php
declare(strict_types=1);
/** @var string $content */
/** @var string $title */
/** @var string $activeNav */
/** @var string $bodyClass */
/** @var string $extraHeader */
/** @var array $app */
$bodyClass = $bodyClass ?? '';
$extraHeader = $extraHeader ?? '';
$extraFooter = $extraFooter ?? '';
$activeNav = $activeNav ?? '';
$siteName = $app['name'] ?? 'DOTS Event Planning';
$t = $title ?? 'Home';
$pageTitle = ($t === '' || $t === 'Home') ? e($siteName) : e($t) . ' | ' . e($siteName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ebe6dc" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0c0b09" media="(prefers-color-scheme: dark)">
    <title><?= $pageTitle ?></title>
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
                    <a class="site-nav__link site-nav__link--cart<?= $cartCurrent ? ' is-active' : '' ?>" href="<?= e(app_url('cart')) ?>"<?= $cartCurrent ? ' aria-current="page"' : '' ?>>
                        Cart<?php if ($cn > 0): ?> <span class="cart-badge" aria-label="<?= (int) $cn ?> items"><?= (int) $cn ?></span><?php endif; ?>
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
        <div class="flash flash--error" role="alert"><?= e($flashErr) ?></div>
    <?php endif; ?>
    <?php if ($flashOk !== null): ?>
        <div class="flash flash--success" role="status"><?= e($flashOk) ?></div>
    <?php endif; ?>
    <?php if ($flashNote !== null): ?>
        <div class="flash flash--notice" role="status"><?= e($flashNote) ?></div>
    <?php endif; ?>
    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="shell site-footer__grid">
        <div class="site-footer__brand">
            <a class="site-footer__logo" href="<?= e(app_url('')) ?>"><?= e($siteName) ?></a>
            <p class="site-footer__tagline">Event planning in Saint John—straight answers, on-site hustle, and decor that still looks good in your uncle’s phone photos.</p>
            <ul class="social" aria-label="Social links">
                <li><a class="social__link" href="#" aria-label="Facebook">f</a></li>
                <li><a class="social__link" href="#" aria-label="Instagram">in</a></li>
                <li><a class="social__link" href="#" aria-label="YouTube">▶</a></li>
            </ul>
        </div>
        <div class="site-footer__contact">
            <h2 class="site-footer__heading">Contact</h2>
            <p><a href="mailto:info@dotseventplanning.com">info@dotseventplanning.com</a></p>
            <p>181 McNamara Drive, Saint John, NB</p>
            <p><a href="tel:+1">+1 (506) 000-0000</a></p>
        </div>
        <div class="site-footer__map">
            <h2 class="site-footer__heading">Location</h2>
            <div class="site-footer__map-embed" role="img" aria-label="Map area placeholder">
                <span class="visually-hidden">Map embed to be added</span>
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
