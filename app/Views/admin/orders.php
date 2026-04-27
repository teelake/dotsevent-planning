<?php
declare(strict_types=1);
/** @var list<array<string, mixed>> $orders */
/** @var int $page */
/** @var int $pages */
/** @var int $total */
?>
<p class="section__lead" style="margin-bottom: 1rem;">Checkout history (Square).</p>
<p class="text-muted" style="margin-bottom:1rem;">Total: <?= (int) $total ?> · Page <?= (int) $page ?> of <?= (int) $pages ?></p>
<div style="overflow-x:auto;">
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Total</th>
            <th>Status</th>
            <th>Square ID</th>
            <th>When</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td><a class="text-link" href="<?= e(app_url('admin/order/' . (int) $o['id'])) ?>"><?= (int) $o['id'] ?></a></td>
            <td><?= e((string) ($o['customer_email'] ?? '')) ?></td>
            <td><?= e(money_format_cents((int) ($o['total_cents'] ?? 0), (string) ($o['currency'] ?? 'CAD'))) ?></td>
            <td><code><?= e((string) ($o['status'] ?? '')) ?></code></td>
            <td style="font-size:0.8rem;word-break:break-all;max-width:10rem;"><?= e((string) ($o['square_payment_id'] ?? '')) ?></td>
            <td style="white-space:nowrap;"><?= e((string) ($o['created_at'] ?? '')) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php if ($orders === []): ?>
<p class="section__lead">No orders yet.</p>
<?php endif; ?>
<nav class="text-muted" style="margin-top:1rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
    <?php if ($page > 1): ?><a class="text-link" href="?page=<?= (int) $page - 1 ?>">← Previous</a><?php endif; ?>
    <?php if ($page < $pages): ?><a class="text-link" href="?page=<?= (int) $page + 1 ?>">Next →</a><?php endif; ?>
</nav>
