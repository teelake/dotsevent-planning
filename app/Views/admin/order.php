<?php
declare(strict_types=1);
/** @var array<string, mixed> $o */
/** @var list<array<string, mixed>> $items */
$cur = (string) ($o['currency'] ?? 'CAD');
?>
<p style="margin-bottom: 1.25rem;"><a class="text-link" href="<?= e(app_url('admin/orders')) ?>">← All orders</a></p>
<dl style="display:grid; gap:0.5rem; margin-bottom:1.5rem; max-width: 32rem;">
    <div><strong>Status</strong> <code><?= e((string) ($o['status'] ?? '')) ?></code></div>
    <div><strong>Email</strong> <?= e((string) ($o['customer_email'] ?? '')) ?></div>
    <div><strong>Name</strong> <?= e((string) ($o['customer_name'] ?? '')) ?></div>
    <div><strong>Phone</strong> <?= e((string) ($o['phone'] ?? '')) ?></div>
    <div><strong>Total</strong> <?= e(money_format_cents((int) ($o['total_cents'] ?? 0), $cur)) ?> <?= e($cur) ?></div>
    <div><strong>Created</strong> <?= e((string) ($o['created_at'] ?? '')) ?></div>
    <div><strong>Square payment</strong> <code style="word-break:break-all;"><?= e((string) ($o['square_payment_id'] ?? '')) ?></code></div>
</dl>
<h2 class="section__title" style="font-size:1.2rem; margin-bottom:0.75rem;">Line items</h2>
<table class="admin-table" style="max-width: 40rem;">
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Line</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $li): ?>
        <tr>
            <td><?= e((string) ($li['product_name'] ?? '—')) ?> <span class="text-muted">(ID <?= (int) $li['product_id'] ?>)</span></td>
            <td><?= (int) $li['quantity'] ?></td>
            <td><?= e(money_format_cents((int) $li['unit_price_cents'], $cur)) ?></td>
            <td><?= e(money_format_cents((int) $li['unit_price_cents'] * (int) $li['quantity'], $cur)) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
