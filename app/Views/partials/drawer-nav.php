<?php
declare(strict_types=1);
/** @var string $activeNav */
$activeNav = $activeNav ?? '';
$items = [
    'home' => ['Home', app_url('')],
    'about' => ['About us', app_url('about')],
    'services' => ['Services', app_url('services')],
    'kids' => ['Kids party', app_url('kids')],
    'rentals' => ['Rentals', app_url('rentals')],
    'portfolio' => ['Portfolio', app_url('portfolio')],
    'book' => ['Book', app_url('book')],
    'contact' => ['Contact', app_url('contact')],
    'cart' => ['Cart', app_url('cart')],
];
$cn = (int) cart_count();
?>
<div class="app-drawer" id="app-drawer" aria-hidden="true" data-drawer>
    <div class="app-drawer__backdrop" data-drawer-backdrop></div>
    <div class="app-drawer__panel" role="dialog" aria-modal="true" aria-label="Site navigation">
        <div class="app-drawer__head">
            <span class="app-drawer__title">Navigate</span>
            <button class="app-drawer__close" type="button" data-drawer-close aria-label="Close menu">×</button>
        </div>

        <nav class="app-drawer__nav" aria-label="Primary">
            <ul class="app-drawer__list">
                <?php foreach ($items as $key => $item):
                    $isCurrent = $activeNav === $key;
                ?>
                <li class="app-drawer__item">
                    <a class="app-drawer__link<?= $isCurrent ? ' is-active' : '' ?>" href="<?= e($item[1]) ?>"<?= $isCurrent ? ' aria-current="page"' : '' ?>>
                        <span class="app-drawer__label"><?= e($item[0]) ?></span>
                        <?php if ($key === 'cart' && $cn > 0): ?>
                            <span class="app-drawer__badge" aria-label="<?= (int) $cn ?> items in cart"><?= (int) $cn ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</div>

