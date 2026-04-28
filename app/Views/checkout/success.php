<?php
declare(strict_types=1);
/** @var string $order_message */
/** @var int|null $order_id */
$msg = $order_message ?? 'Thank you!';
$orderId = $order_id ?? null;
$page_title = 'Order complete';
$crumb_current = 'Order';
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <?php include dirname(__DIR__) . '/partials/app-rail.php'; ?>
    <div class="app-shell__main">
        <div class="shell shell--wide page-pad checkout-success app-panel checkout-success-panel" data-reveal>
            <?php if ($orderId !== null): ?>
            <p class="section__lead checkout-success__ref">Order reference: <strong>#<?= (int) $orderId ?></strong></p>
            <?php endif; ?>
            <p class="section__lead"><?= e($msg) ?></p>
            <a class="btn btn--primary" href="<?= e(app_url('rentals')) ?>">Continue shopping</a>
        </div>
    </div>
</div>
