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
<div class="shell page-pad checkout-success" data-reveal>
    <?php if ($orderId !== null): ?>
    <p class="section__lead" style="margin-bottom: 0.75rem;">Order reference: <strong>#<?= (int) $orderId ?></strong></p>
    <?php endif; ?>
    <p class="section__lead"><?= e($msg) ?></p>
    <a class="btn btn--primary" href="<?= e(app_url('rentals')) ?>">Continue shopping</a>
</div>
