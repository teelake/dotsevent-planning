<?php
declare(strict_types=1);
/** @var array<string, mixed>|null $p */
/** @var list<array<string, mixed>> $options */
$isNew = $p === null;
$cur = (string) ($p['currency'] ?? 'CAD');
$priceDollars = $p ? number_format((int) $p['price_cents'] / 100, 2, '.', '') : '';
$priceMaxDollars = (!$isNew && isset($p['price_max_cents']) && $p['price_max_cents'] !== null) ? number_format((int) $p['price_max_cents'] / 100, 2, '.', '') : '';
$options = $options ?? [];
?>
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<p class="section__lead" style="margin-bottom: 1rem;"><?= $isNew ? 'Add a rental product to the catalog.' : 'Update details and visibility.' ?></p>
<p style="margin-bottom: 1rem;"><a class="text-link" href="<?= e(app_url('admin/products')) ?>">← Back to products</a></p>
<form class="admin-form" id="product-form" method="post" action="<?= e(app_url('admin/product/save')) ?>" style="max-width: 48rem;">
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
        <div id="p-desc-editor" style="min-height: 130px; background:#fff; border:1px solid var(--color-line); border-radius:12px;"></div>
        <textarea class="input input--textarea" id="p-desc" name="description" rows="4" hidden><?= $isNew ? '' : e((string) ($p['description'] ?? '')) ?></textarea>
    </div>
    <div class="form-row">
        <label for="p-price">Price (<?= e($cur) ?>) <span class="req">*</span></label>
        <input class="input" id="p-price" name="price" type="text" inputmode="decimal" required placeholder="0.00" value="<?= e($priceDollars) ?>" style="max-width: 12rem;">
    </div>
    <div class="form-row">
        <label for="p-price-max">Max price / price range end (optional)</label>
        <input class="input" id="p-price-max" name="price_max" type="text" inputmode="decimal" placeholder="8.00" value="<?= e($priceMaxDollars) ?>" style="max-width: 12rem;">
        <span class="text-muted" style="font-size:0.85rem;">Leave blank for a single-price product.</span>
    </div>
    <div class="form-row">
        <label for="p-cur">Currency</label>
        <input class="input" id="p-cur" name="currency" type="text" maxlength="3" value="<?= e($cur) ?>" style="max-width: 6rem;">
    </div>
    <div class="form-row">
        <label for="p-img">Image URL</label>
        <input class="input" id="p-img" name="image_url" type="text" value="<?= $isNew || empty($p['image_url']) ? '' : e((string) $p['image_url']) ?>" placeholder="uploads/example.jpg or https://…">
    </div>
    <div class="form-row">
        <label for="p-category">Category key</label>
        <input class="input" id="p-category" name="category_key" type="text" maxlength="60" value="<?= $isNew ? '' : e((string) ($p['category_key'] ?? '')) ?>" placeholder="chairs, tables, backdrops, linens, decor">
    </div>
    <div class="form-row">
        <label for="p-badge">Badge label</label>
        <input class="input" id="p-badge" name="badge_label" type="text" maxlength="40" value="<?= $isNew ? '' : e((string) ($p['badge_label'] ?? '')) ?>" placeholder="Popular, New, Great for Kids">
    </div>
    <div class="form-row">
        <label for="p-details">Details (one bullet per line)</label>
        <textarea class="input input--textarea" id="p-details" name="details" rows="5"><?= $isNew ? '' : e((string) ($p['details'] ?? '')) ?></textarea>
    </div>
    <div class="form-row">
        <label for="p-ideal">Ideal for (one item per line)</label>
        <textarea class="input input--textarea" id="p-ideal" name="ideal_for" rows="4"><?= $isNew ? '' : e((string) ($p['ideal_for'] ?? '')) ?></textarea>
    </div>
    <div class="form-row">
        <label for="p-policy-editor">Rental policy note</label>
        <div id="p-policy-editor" style="min-height: 100px; background:#fff; border:1px solid var(--color-line); border-radius:12px;"></div>
        <textarea class="input input--textarea" id="p-policy" name="policy_note" rows="3" hidden><?= $isNew ? '' : e((string) ($p['policy_note'] ?? '')) ?></textarea>
    </div>
    <div class="form-row">
        <span class="admin-form__section-label">Product options / variants</span>
        <p class="text-muted" style="font-size:0.85rem;margin:0 0 0.5rem;">Use this when a product has multiple rentable items or prices, e.g. Folding Table $8 and Folding Chair $3.</p>
        <div id="product-options-list" style="display:grid;gap:0.65rem;">
            <?php foreach ($options as $i => $opt): ?>
            <div class="product-option-row" style="display:grid;grid-template-columns:1fr 8rem 6rem auto;gap:0.5rem;align-items:end;">
                <label>Label
                    <input class="input" name="options[<?= (int) $i ?>][label]" type="text" value="<?= e((string) ($opt['label'] ?? '')) ?>" maxlength="255">
                </label>
                <label>Price
                    <input class="input" name="options[<?= (int) $i ?>][price]" type="text" inputmode="decimal" value="<?= e(number_format((int) ($opt['price_cents'] ?? 0) / 100, 2, '.', '')) ?>">
                </label>
                <label>Sort
                    <input class="input" name="options[<?= (int) $i ?>][sort_order]" type="number" min="0" value="<?= (int) ($opt['sort_order'] ?? $i) ?>">
                </label>
                <button class="btn btn--ghost product-option-remove" type="button">Remove</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="btn btn--secondary" id="add-product-option" type="button" style="margin-top:0.65rem;">+ Add option</button>
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
        <label><input type="checkbox" name="has_options" value="1" <?= (!$isNew && !empty($p['has_options'])) ? ' checked' : '' ?>> Has options / variants</label>
    </div>
    <div class="form-row">
        <label><input type="checkbox" name="is_active" value="1" <?= ($isNew || !empty($p['is_active'])) ? ' checked' : '' ?>> Visible in shop</label>
    </div>
    <button class="btn btn--primary" type="submit"><?= $isNew ? 'Create' : 'Save' ?></button>
</form>

<template id="product-option-template">
    <div class="product-option-row" style="display:grid;grid-template-columns:1fr 8rem 6rem auto;gap:0.5rem;align-items:end;">
        <label>Label
            <input class="input js-opt-label" type="text" maxlength="255" placeholder="Folding Chair">
        </label>
        <label>Price
            <input class="input js-opt-price" type="text" inputmode="decimal" placeholder="3.00">
        </label>
        <label>Sort
            <input class="input js-opt-sort" type="number" min="0" value="0">
        </label>
        <button class="btn btn--ghost product-option-remove" type="button">Remove</button>
    </div>
</template>

<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
(function () {
  var desc = new Quill('#p-desc-editor', { theme: 'snow', modules: { toolbar: [['bold', 'italic', 'underline'], [{ list: 'bullet' }], ['clean']] } });
  var policy = new Quill('#p-policy-editor', { theme: 'snow', modules: { toolbar: [['bold', 'italic'], ['clean']] } });
  var descEl = document.getElementById('p-desc');
  var policyEl = document.getElementById('p-policy');
  desc.root.innerHTML = descEl.value || '';
  policy.root.innerHTML = policyEl.value || '';

  var list = document.getElementById('product-options-list');
  var tpl = document.getElementById('product-option-template');
  var add = document.getElementById('add-product-option');
  function renumber() {
    list.querySelectorAll('.product-option-row').forEach(function (row, i) {
      var label = row.querySelector('input[name$="[label]"], .js-opt-label');
      var price = row.querySelector('input[name$="[price]"], .js-opt-price');
      var sort = row.querySelector('input[name$="[sort_order]"], .js-opt-sort');
      if (label) label.name = 'options[' + i + '][label]';
      if (price) price.name = 'options[' + i + '][price]';
      if (sort) sort.name = 'options[' + i + '][sort_order]';
    });
  }
  add.addEventListener('click', function () {
    list.appendChild(document.importNode(tpl.content, true));
    renumber();
  });
  list.addEventListener('click', function (ev) {
    var btn = ev.target.closest('.product-option-remove');
    if (!btn) return;
    var row = btn.closest('.product-option-row');
    if (row) row.remove();
    renumber();
  });
  document.getElementById('product-form').addEventListener('submit', function () {
    descEl.value = desc.root.innerHTML.trim();
    policyEl.value = policy.root.innerHTML.trim();
    renumber();
  });
  renumber();
})();
</script>
