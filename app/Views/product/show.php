<?php
declare(strict_types=1);
/**
 * @var array<string, mixed>       $product
 * @var list<array<string, mixed>> $options          Rows from product_options table
 * @var list<array<string, mixed>> $related_products
 */
$p               = $product          ?? [];
$options         = $options          ?? [];
$relatedProducts = $related_products ?? [];

$id         = (int)    ($p['id']            ?? 0);
$name       = (string) ($p['name']          ?? '');
$cents      = (int)    ($p['price_cents']   ?? 0);
$maxCents   = isset($p['price_max_cents']) && $p['price_max_cents'] !== null ? (int) $p['price_max_cents'] : null;
$cur        = (string) ($p['currency']      ?? 'CAD');
$desc       = (string) ($p['description']   ?? '');
$img        = !empty($p['image_url']) ? (string) $p['image_url'] : null;
$catKey     = (string) ($p['category_key']  ?? '');
$badge      = (string) ($p['badge_label']   ?? '');
$hasOptions = (bool)   ($p['has_options']   ?? false);

// Plain TEXT columns — split on newline into arrays
$detailsRaw  = trim((string) ($p['details']     ?? ''));
$idealForRaw = trim((string) ($p['ideal_for']   ?? ''));
$policyNote  = trim((string) ($p['policy_note'] ?? ''));

$details = $detailsRaw  !== '' ? array_filter(array_map('trim', explode("\n", $detailsRaw)))  : [];
$idealFor= $idealForRaw !== '' ? array_filter(array_map('trim', explode("\n", $idealForRaw))) : [];

$priceMin   = money_format_cents($cents, $cur);
$priceLabel = $maxCents !== null
    ? e($priceMin) . ' – ' . e(money_format_cents($maxCents, $cur))
    : e($priceMin);

$catLabel = $catKey !== '' ? ucfirst(str_replace('-', ' ', $catKey)) : 'Rental';

include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <div class="app-shell__main">
        <div class="shell shell--wide page-pad">

            <?php /* ── PRODUCT SPLIT PANEL ─────────────────────── */ ?>
            <div class="product-split" data-reveal>

                <?php /* LEFT: Image */ ?>
                <div class="product-split__media">
                    <?php if ($img): ?>
                        <figure class="product-split__figure">
                            <img
                                class="product-split__img"
                                src="<?= e($img) ?>"
                                alt="<?= e($name) ?>"
                                width="800" height="800"
                            >
                        </figure>
                    <?php else: ?>
                        <div class="product-split__ph" role="img" aria-label="<?= e($name) ?>"></div>
                    <?php endif; ?>
                </div>

                <?php /* RIGHT: Info panel */ ?>
                <div class="product-split__info app-panel">

                    <div class="product-split__meta-row">
                        <span class="product-card__eyebrow"><?= e($catLabel) ?></span>
                        <?php if ($badge !== ''): ?>
                            <span class="product-split__badge"><?= e($badge) ?></span>
                        <?php endif; ?>
                    </div>

                    <h1 class="product-split__title"><?= e($name) ?></h1>

                    <p class="product-split__price">
                        <?= $priceLabel ?>
                        <span class="product-split__per">per item</span>
                    </p>

                    <?php if ($desc !== ''): ?>
                        <div class="product-split__desc prose"><?= nl2br(e($desc), false) ?></div>
                    <?php endif; ?>

                    <?php /* Accordion — Details / Ideal For / Policy */ ?>
                    <?php if ($details !== [] || $idealFor !== [] || $policyNote !== ''): ?>
                    <div class="product-split__accordions">

                        <?php if ($details !== []): ?>
                        <details class="product-acc" open>
                            <summary class="product-acc__summary">Details</summary>
                            <ul class="product-acc__list">
                                <?php foreach ($details as $line): ?>
                                    <li><?= e($line) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                        <?php endif; ?>

                        <?php if ($idealFor !== []): ?>
                        <details class="product-acc">
                            <summary class="product-acc__summary">Ideal for</summary>
                            <ul class="product-acc__list product-acc__list--tags">
                                <?php foreach ($idealFor as $use): ?>
                                    <li><?= e($use) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                        <?php endif; ?>

                        <?php if ($policyNote !== ''): ?>
                        <details class="product-acc">
                            <summary class="product-acc__summary">Rental policy</summary>
                            <p class="product-acc__body"><?= e($policyNote) ?></p>
                        </details>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                    <?php /* Add to cart — options table or simple qty */ ?>
                    <?php if ($hasOptions && $options !== []): ?>
                        <form class="add-form product-options-form" method="post" action="<?= e(app_url('cart/add')) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="return" value="<?= e(app_url('product/' . $id)) ?>">
                            <table class="product-options-table">
                                <thead>
                                    <tr>
                                        <th class="product-options-table__col-label">Item</th>
                                        <th class="product-options-table__col-price">Price</th>
                                        <th class="product-options-table__col-qty">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($options as $i => $opt):
                                        $optId    = (int)    ($opt['id']          ?? 0);
                                        $optLabel = (string) ($opt['label']       ?? '');
                                        $optCents = (int)    ($opt['price_cents'] ?? $cents);
                                    ?>
                                    <tr class="product-options-table__row">
                                        <td class="product-options-table__label">
                                            <input type="hidden" name="items[<?= $i ?>][option_id]" value="<?= $optId ?>">
                                            <input type="hidden" name="items[<?= $i ?>][product_id]" value="<?= $id ?>">
                                            <?= e($optLabel) ?>
                                        </td>
                                        <td class="product-options-table__price">
                                            <?= e(money_format_cents($optCents, $cur)) ?>
                                        </td>
                                        <td class="product-options-table__qty">
                                            <input
                                                class="input input--num"
                                                type="number"
                                                name="items[<?= $i ?>][qty]"
                                                min="0"
                                                value="0"
                                                aria-label="Quantity for <?= e($optLabel) ?>"
                                            >
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="product-split__actions">
                                <button class="btn btn--primary" type="submit">Add to cart</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <form class="add-form" method="post" action="<?= e(app_url('cart/add')) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= $id ?>">
                            <input type="hidden" name="return" value="<?= e(app_url('product/' . $id)) ?>">
                            <div class="product-split__actions">
                                <div class="add-form__row">
                                    <label for="pqty" class="visually-hidden">Quantity</label>
                                    <input id="pqty" class="input input--num" name="quantity" type="number" min="1" value="1" aria-label="Quantity">
                                    <button class="btn btn--primary" type="submit">Add to cart</button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>

                    <p class="text-muted product-split__pay-note">
                        Checkout uses Square. Taxes and final pricing follow at checkout.
                    </p>

                </div>
            </div>

            <?php /* ── RELATED PRODUCTS ──────────────────────────── */ ?>
            <?php if ($relatedProducts !== []): ?>
            <section class="product-related" data-reveal>
                <h2 class="product-related__title">You might also need</h2>
                <ul class="product-strip">
                    <?php foreach ($relatedProducts as $rp):
                        $rpId    = (int)    $rp['id'];
                        $rpName  = (string) $rp['name'];
                        $rpCents = (int)    $rp['price_cents'];
                        $rpMax   = isset($rp['price_max_cents']) && $rp['price_max_cents'] !== null ? (int) $rp['price_max_cents'] : null;
                        $rpCur   = (string) ($rp['currency'] ?? 'CAD');
                        $rpImg   = !empty($rp['image_url']) ? (string) $rp['image_url'] : null;
                        $rpPrice = $rpMax !== null
                            ? e(money_format_cents($rpCents, $rpCur)) . ' – ' . e(money_format_cents($rpMax, $rpCur))
                            : e(money_format_cents($rpCents, $rpCur));
                    ?>
                    <li class="product-strip__item">
                        <a class="product-strip__media" href="<?= e(app_url('product/' . $rpId)) ?>" tabindex="-1" aria-hidden="true">
                            <?php if ($rpImg): ?>
                                <img src="<?= e($rpImg) ?>" alt="" loading="lazy" width="300" height="300">
                            <?php else: ?>
                                <div class="product-card__ph"></div>
                            <?php endif; ?>
                        </a>
                        <div class="product-strip__body">
                            <h3 class="product-strip__name">
                                <a href="<?= e(app_url('product/' . $rpId)) ?>"><?= e($rpName) ?></a>
                            </h3>
                            <p class="product-strip__price"><?= $rpPrice ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>

        </div>
    </div>
</div>
