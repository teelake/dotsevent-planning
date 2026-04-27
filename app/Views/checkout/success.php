<?php
declare(strict_types=1);
/** @var string $order_message */
$msg = $order_message ?? 'Thank you!';
$page_title = 'Order complete';
$crumb_current = 'Order';
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="shell page-pad checkout-success" data-reveal>
    <p class="section__lead"><?= e($msg) ?></p>
    <a class="btn btn--primary" href="<?= e(app_url('rentals')) ?>">Continue shopping</a>
</div>
