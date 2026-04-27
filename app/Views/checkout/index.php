<?php
declare(strict_types=1);
/** @var list<array<string, mixed>> $lines */
/** @var int $subtotal_cents */
/** @var string $currency */
/** @var array<string, mixed>|null $square */
$lines = $lines ?? [];
$subtotal_cents = (int) ($subtotal_cents ?? 0);
$currency = (string) ($currency ?? 'CAD');
$square = $square ?? null;
$appId = $square ? (string) ($square['application_id'] ?? '') : '';
$locId = $square ? (string) ($square['location_id'] ?? '') : '';
$env = $square ? (string) ($square['environment'] ?? 'sandbox') : '';
$page_title = 'Checkout';
$crumb_current = 'Checkout';
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="shell page-pad checkout" data-reveal>
    <div class="checkout__summary">
        <h2 class="section__title checkout__heading">Order summary</h2>
        <ul class="checkout__lines">
            <?php foreach ($lines as $row): ?>
            <li>
                <span><?= e((string) $row['name']) ?> × <?= (int) $row['quantity'] ?></span>
                <span><?= e(money_format_cents((int) $row['line_total_cents'], $currency)) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        <p class="checkout__total">Total <strong><?= e(money_format_cents($subtotal_cents, $currency)) ?></strong> <?= e($currency) ?></p>
    </div>
    <div class="checkout__pay">
        <?php if ($square === null || $appId === '' || $locId === ''): ?>
        <p class="banner banner--warn" role="status">Payment is not configured. Copy <code>config/square.php.example</code> to <code>config/square.php</code> and add your Square application ID, location ID, and access token.</p>
        <a class="btn btn--secondary" href="<?= e(app_url('cart')) ?>">Back to cart</a>
        <?php else: ?>
        <form
            id="checkout-form"
            class="checkout-form"
            method="post"
            action="<?= e(app_url('checkout/pay')) ?>"
            data-app-id="<?= e($appId) ?>"
            data-location-id="<?= e($locId) ?>"
            data-currency="<?= e($currency) ?>"
            data-env="<?= e($env) ?>"
        >
            <?= csrf_field() ?>
            <input type="hidden" name="source_id" id="source_id" value="">
            <h2 class="section__title" style="font-size:1.4rem;">Contact &amp; payment</h2>
            <div class="form-row">
                <label for="co-email">Email <span class="req">*</span></label>
                <input class="input" id="co-email" name="email" type="email" required autocomplete="email" placeholder="you@example.com">
            </div>
            <div class="form-row">
                <label for="co-name">Name on receipt</label>
                <input class="input" id="co-name" name="name" type="text" autocomplete="name">
            </div>
            <div class="form-row">
                <label for="co-phone">Phone</label>
                <input class="input" id="co-phone" name="phone" type="tel" autocomplete="tel">
            </div>
            <p class="text-muted" style="font-size:0.9rem;">Card information is entered securely in the fields below. We never store your full card number.</p>
            <div id="card-container" class="sq-card"></div>
            <p class="form-error" id="card-errors" role="alert" hidden></p>
            <button class="btn btn--primary" type="submit" id="pay-button">Pay <?= e(money_format_cents($subtotal_cents, $currency)) ?></button>
        </form>
        <?php endif; ?>
    </div>
</div>
