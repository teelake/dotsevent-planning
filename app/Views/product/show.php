<?php
declare(strict_types=1);
/** @var array<string, mixed> $product */
$p = $product;
$id = (int) $p['id'];
$cents = (int) $p['price_cents'];
$cur = (string) ($p['currency'] ?? 'CAD');
$desc = (string) ($p['description'] ?? '');
$name = (string) $p['name'];
$img = !empty($p['image_url']) ? (string) $p['image_url'] : null;
$page_title = $name;
$crumb_current = $name;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="shell page-pad product-layout">
    <div class="product-layout__media">
        <?php if ($img): ?>
            <img class="product-layout__img" src="<?= e($img) ?>" alt="" width="800" height="800">
        <?php else: ?>
            <div class="product-layout__ph" role="img" aria-label="Product image"></div>
        <?php endif; ?>
    </div>
    <div class="product-layout__info">
        <h1 class="product-layout__title"><?= e($name) ?></h1>
        <p class="product-layout__price"><?= e(money_format_cents($cents, $cur)) ?></p>
        <?php if ($desc !== ''): ?><p class="prose"><?= nl2br(e($desc), false) ?></p><?php endif; ?>
        <form class="add-form" method="post" action="<?= e(app_url('cart/add')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <input type="hidden" name="return" value="<?= e(app_url('product/' . $id)) ?>">
            <label for="pqty">Quantity</label>
            <div class="add-form__row">
                <input id="pqty" class="input input--num" name="quantity" type="number" min="1" value="1">
                <button class="btn btn--primary" type="submit">Add to cart</button>
            </div>
        </form>
        <p class="text-muted" style="margin-top:1rem;">Checkout uses Square. Taxes and final pricing follow at checkout as configured in Square.</p>
    </div>
</div>
