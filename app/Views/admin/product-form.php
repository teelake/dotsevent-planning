<?php
declare(strict_types=1);
/** @var array<string, mixed>|null $p */
$isNew = $p === null;
$cur = (string) ($p['currency'] ?? 'CAD');
$priceDollars = $p ? number_format((int) $p['price_cents'] / 100, 2, '.', '') : '';
?>
<p class="section__lead" style="margin-bottom: 1rem;"><?= $isNew ? 'Add a rental product to the catalog.' : 'Update details and visibility.' ?></p>
<p style="margin-bottom: 1rem;"><a class="text-link" href="<?= e(app_url('admin/products')) ?>">← Back to products</a></p>
<form class="admin-form" method="post" action="<?= e(app_url('admin/product/save')) ?>" style="max-width: 36rem;">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $isNew ? 0 : (int) $p['id'] ?>">
    <div class="form-row">
        <label for="p-name">Name <span class="req">*</span></label>
        <input class="input" id="p-name" name="name" type="text" required value="<?= $isNew ? '' : e((string) $p['name']) ?>">
    </div>
    <div class="form-row">
        <label for="p-slug">Slug</label>
        <input class="input" id="p-slug" name="slug" type="text" placeholder="auto from name" value="<?= $isNew ? '' : e((string) $p['slug']) ?>">
        <span class="text-muted" style="font-size:0.85rem;">URL segment; leave blank to generate from the name.</span>
    </div>
    <div class="form-row">
        <label for="p-desc">Description</label>
        <textarea class="input input--textarea" id="p-desc" name="description" rows="4"><?= $isNew ? '' : e((string) ($p['description'] ?? '')) ?></textarea>
    </div>
    <div class="form-row">
        <label for="p-price">Price (<?= e($cur) ?>) <span class="req">*</span></label>
        <input class="input" id="p-price" name="price" type="text" inputmode="decimal" required placeholder="0.00" value="<?= e($priceDollars) ?>" style="max-width: 12rem;">
    </div>
    <div class="form-row">
        <label for="p-cur">Currency</label>
        <input class="input" id="p-cur" name="currency" type="text" maxlength="3" value="<?= e($cur) ?>" style="max-width: 6rem;">
    </div>
    <div class="form-row">
        <label for="p-img">Image URL</label>
        <input class="input" id="p-img" name="image_url" type="url" value="<?= $isNew || empty($p['image_url']) ? '' : e((string) $p['image_url']) ?>" placeholder="https://…">
    </div>
    <div class="form-row">
        <label for="p-stock">Stock (optional)</label>
        <input class="input" id="p-stock" name="stock" type="number" min="0" value="<?= $isNew || !isset($p['stock']) || $p['stock'] === null || $p['stock'] === '' ? '' : (string) (int) $p['stock'] ?>" style="max-width: 8rem;">
    </div>
    <div class="form-row">
        <label for="p-sort">Sort order</label>
        <input class="input" id="p-sort" name="sort_order" type="number" value="<?= $isNew ? 0 : (int) ($p['sort_order'] ?? 0) ?>" style="max-width: 8rem;">
    </div>
    <div class="form-row">
        <label><input type="checkbox" name="has_options" value="1" <?= (!$isNew && !empty($p['has_options'])) ? ' checked' : '' ?>> Has options (future use)</label>
    </div>
    <div class="form-row">
        <label><input type="checkbox" name="is_active" value="1" <?= ($isNew || !empty($p['is_active'])) ? ' checked' : '' ?>> Visible in shop</label>
    </div>
    <button class="btn btn--primary" type="submit"><?= $isNew ? 'Create' : 'Save' ?></button>
</form>
