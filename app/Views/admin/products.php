<?php
declare(strict_types=1);
/** @var list<array<string, mixed>> $products */
?>
<h1 class="section__title" style="margin-bottom: 1rem;">Products</h1>
<p style="margin-bottom: 1rem;"><a class="btn btn--primary" href="<?= e(app_url('admin/product/new')) ?>">New product</a></p>
<div style="overflow-x: auto;">
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Price</th>
            <th>Active</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= (int) $p['id'] ?></td>
            <td><?= e((string) $p['name']) ?></td>
            <td><code><?= e((string) $p['slug']) ?></code></td>
            <td><?= e(money_format_cents((int) $p['price_cents'], (string) ($p['currency'] ?? 'CAD'))) ?></td>
            <td><?= !empty($p['is_active']) ? 'Yes' : 'No' ?></td>
            <td>
                <a class="text-link" href="<?= e(app_url('admin/product/' . (int) $p['id'] . '/edit')) ?>">Edit</a>
                <?php if (!empty($p['is_active'])): ?>
                <form method="post" action="<?= e(app_url('admin/product/delete')) ?>" style="display:inline;" onsubmit="return confirm('Hide this product from the shop?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
                    <button type="submit" class="text-link" style="background:none;border:none;padding:0;cursor:pointer;color:var(--color-gold-deep);font-weight:600;">Deactivate</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php if ($products === []): ?>
<p class="section__lead">No products yet. Add one or import <code>database/seed.sql</code>.</p>
<?php endif; ?>
