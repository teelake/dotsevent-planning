<?php
declare(strict_types=1);
/** @var list<array<string, mixed>> $products */
/** @var bool $db_ready */
$products = $products ?? [];
$db_ready = $db_ready ?? false;
$page_title = 'Rentals';
$crumb_current = 'Rentals';
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="shell page-pad" data-reveal>
    <?php if (!$db_ready): ?>
        <p class="banner banner--warn" role="status">Database is not connected. Add <code>config/database.php</code> and import <code>database/schema.sql</code> to show products.</p>
    <?php elseif ($products === []): ?>
        <p class="section__lead">No rental products yet. Add rows in the admin (coming soon) or import <code>database/seed.sql</code>.</p>
    <?php else: ?>
    <div class="shop-toolbar">
        <p class="section__lead" style="margin:0;">Chairs, backdrops, and finishing touches. Secure checkout is powered by <strong>Square</strong>.</p>
        <a class="text-link" href="<?= e(app_url('cart')) ?>">View cart<?php if (cart_count() > 0): ?> (<?= (int) cart_count() ?>)<?php endif; ?></a>
    </div>
    <ul class="product-grid reveal-stagger">
        <?php foreach ($products as $p):
            $id = (int) $p['id'];
            $name = (string) $p['name'];
            $cents = (int) $p['price_cents'];
            $cur = (string) ($p['currency'] ?? 'CAD');
            $img = $p['image_url'] ? (string) $p['image_url'] : null;
        ?>
        <li class="product-card">
            <a class="product-card__media" href="<?= e(app_url('product/' . $id)) ?>">
                <?php if ($img): ?>
                    <img src="<?= e($img) ?>" alt="" loading="lazy" width="400" height="400">
                <?php else: ?>
                    <div class="product-card__ph" aria-hidden="true"></div>
                <?php endif; ?>
            </a>
            <div class="product-card__body">
                <h2 class="product-card__title"><a href="<?= e(app_url('product/' . $id)) ?>"><?= e($name) ?></a></h2>
                <p class="product-card__price"><?= e(money_format_cents($cents, $cur)) ?></p>
                <a class="btn btn--secondary" href="<?= e(app_url('product/' . $id)) ?>">View</a>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
