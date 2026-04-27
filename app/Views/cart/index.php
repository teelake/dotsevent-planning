<?php
declare(strict_types=1);
/** @var list<array<string, mixed>> $lines */
/** @var int $subtotal_cents */
/** @var string $currency */
$lines = $lines ?? [];
$subtotal_cents = (int) ($subtotal_cents ?? 0);
$currency = (string) ($currency ?? 'CAD');
$page_title = 'Cart';
$crumb_current = 'Cart';
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="shell page-pad">
    <?php if ($lines === []): ?>
        <p class="section__lead">Your cart is empty.</p>
        <a class="btn btn--primary" href="<?= e(app_url('rentals')) ?>">Browse rentals</a>
    <?php else: ?>
    <div class="cart-table-wrap">
    <table class="cart-table">
        <caption class="visually-hidden">Items in your cart</caption>
        <thead>
            <tr>
                <th scope="col">Item</th>
                <th scope="col">Price</th>
                <th scope="col">Qty</th>
                <th scope="col">Subtotal</th>
                <th><span class="visually-hidden">Remove</span></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($lines as $row):
            $pid = (int) $row['id'];
            $qty = (int) $row['quantity'];
            $lineTotal = (int) $row['line_total_cents'];
            $pc = (int) $row['price_cents'];
        ?>
            <tr>
                <td><a href="<?= e(app_url('product/' . $pid)) ?>"><?= e((string) $row['name']) ?></a></td>
                <td><?= e(money_format_cents($pc, $currency)) ?></td>
                <td>
                    <form class="inline-form" method="post" action="<?= e(app_url('cart/update')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= $pid ?>">
                        <input class="input input--num" type="number" name="quantity" min="0" value="<?= (int) $qty ?>">
                        <button class="btn btn--ghost" type="submit" style="color:var(--color-ink);">Update</button>
                    </form>
                </td>
                <td><?= e(money_format_cents($lineTotal, $currency)) ?></td>
                <td>
                    <form method="post" action="<?= e(app_url('cart/remove')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= $pid ?>">
                        <button class="text-link" type="submit" style="border:0;background:none;cursor:pointer;">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <p class="cart-total">Total: <strong><?= e(money_format_cents($subtotal_cents, $currency)) ?></strong> <?= e($currency) ?></p>
    <div class="cart-actions">
        <a class="btn btn--secondary" href="<?= e(app_url('checkout')) ?>">Checkout with Square</a>
        <a class="text-link" href="<?= e(app_url('rentals')) ?>">Continue shopping</a>
    </div>
    <?php endif; ?>
</div>
