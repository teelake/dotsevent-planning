<?php
declare(strict_types=1);
/** @var string $user_email */
/** @var int $lead_count */
/** @var int $order_count */
/** @var int $product_count */
?>
<h1 class="section__title" style="margin-bottom: 0.35rem;">Dashboard</h1>
<p class="section__lead" style="margin-bottom: 1.5rem;">Signed in as <strong><?= e($user_email) ?></strong></p>
<div class="admin-grid">
    <div class="admin-stat">
        <div class="admin-stat__v"><?= (int) $product_count ?></div>
        <div class="admin-stat__l">Products</div>
        <a class="text-link" href="<?= e(app_url('admin/products')) ?>">Manage</a>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__v"><?= (int) $lead_count ?></div>
        <div class="admin-stat__l">Leads</div>
        <a class="text-link" href="<?= e(app_url('admin/leads')) ?>">View</a>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__v"><?= (int) $order_count ?></div>
        <div class="admin-stat__l">Orders</div>
        <a class="text-link" href="<?= e(app_url('admin/orders')) ?>">View</a>
    </div>
</div>
