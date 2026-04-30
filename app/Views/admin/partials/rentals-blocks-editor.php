<?php
declare(strict_types=1);
/** @var array<string, mixed> $mergedRentalsBlocks */
$rb         = $mergedRentalsBlocks ?? [];
$hero       = $rb['hero']           ?? [];
$categories = $rb['categories']     ?? [];
$controls   = $rb['controls']       ?? [];
$hiw        = $rb['how_it_works']   ?? [];
$logistics  = $rb['logistics']      ?? [];
$newsletter = $rb['newsletter_cta'] ?? [];

$catItems   = is_array($categories['items'] ?? null) ? $categories['items'] : [];
$hiwSteps   = is_array($hiw['steps']   ?? null) ? $hiw['steps']   : [];
$logItems   = is_array($logistics['items'] ?? null) ? $logistics['items'] : [];
$sortOpts   = is_array($controls['sort_options'] ?? null) ? $controls['sort_options'] : [];
?>
<div id="rentals-blocks-editor" class="rentals-blocks-editor">


    <?php /* ── HERO ── */ ?>
    <details id="cms-sec-rb-hero" class="hb-section" open>
        <summary class="hb-section__summary">Hero banner</summary>
        <div class="hb-section__body">
            <label class="hb-label">Enabled
                <input type="checkbox" id="rb-hero-enabled" <?= !empty($hero['enabled']) ? 'checked' : '' ?>>
            </label>
            <label class="hb-label">Kicker (eyebrow)
                <input class="input" id="rb-hero-kicker" type="text" value="<?= e((string) ($hero['kicker'] ?? '')) ?>">
            </label>
            <label class="hb-label">Headline
                <input class="input" id="rb-hero-title" type="text" value="<?= e((string) ($hero['title'] ?? '')) ?>">
            </label>
            <label class="hb-label">Subtitle
                <input class="input" id="rb-hero-subtitle" type="text" value="<?= e((string) ($hero['subtitle'] ?? '')) ?>">
            </label>
            <label class="hb-label">Primary CTA label
                <input class="input" id="rb-hero-cta-label" type="text" value="<?= e((string) ($hero['cta_primary_label'] ?? '')) ?>">
            </label>
            <label class="hb-label">Primary CTA href
                <input class="input" id="rb-hero-cta-href" type="text" value="<?= e((string) ($hero['cta_primary_href'] ?? '')) ?>">
            </label>
            <label class="hb-label">Secondary CTA label
                <input class="input" id="rb-hero-cta2-label" type="text" value="<?= e((string) ($hero['cta_secondary_label'] ?? '')) ?>">
            </label>
            <label class="hb-label">Secondary CTA href
                <input class="input" id="rb-hero-cta2-href" type="text" value="<?= e((string) ($hero['cta_secondary_href'] ?? '')) ?>">
            </label>
            <label class="hb-label">Background image path
                <input class="input" id="rb-hero-bg" type="text" value="<?= e((string) ($hero['bg_image_path'] ?? '')) ?>" placeholder="/assets/images/…">
            </label>
        </div>
    </details>

    <?php /* ── CATEGORY FILTER ITEMS ── */ ?>
    <details id="cms-sec-rb-categories" class="hb-section">
        <div class="hb-section__body">
            <label class="hb-label">Enabled
                <input type="checkbox" id="rb-cat-enabled" <?= !empty($categories['enabled']) ? 'checked' : '' ?>>
            </label>
            <label class="hb-label">"All" pill label
                <input class="input" id="rb-cat-all-label" type="text" value="<?= e((string) ($categories['all_label'] ?? 'All items')) ?>">
            </label>
            <p class="text-muted" style="font-size:0.85rem;margin:0.5rem 0 0.25rem;">Categories (key · label pairs):</p>
            <ul class="hb-repeat-list" id="rb-cat-items">
                <?php foreach ($catItems as $cat): ?>
                <li class="hb-repeat-row" style="display:grid;grid-template-columns:1fr 1fr auto;gap:0.5rem;align-items:center;margin-bottom:0.5rem;">
                    <input class="input rb-cat-key" type="text" placeholder="Key (e.g. chairs)" value="<?= e((string) ($cat['key'] ?? '')) ?>">
                    <input class="input rb-cat-label" type="text" placeholder="Label (e.g. Chairs)" value="<?= e((string) ($cat['label'] ?? '')) ?>">
                    <button class="btn btn--ghost hb-row-remove" type="button" aria-label="Remove">✕</button>
                </li>
                <?php endforeach; ?>
            </ul>
            <button class="btn btn--ghost" id="rb-add-cat" type="button" style="margin-top:0.35rem;">+ Add category</button>
            <template id="rb-tpl-cat">
                <li class="hb-repeat-row" style="display:grid;grid-template-columns:1fr 1fr auto;gap:0.5rem;align-items:center;margin-bottom:0.5rem;">
                    <input class="input rb-cat-key" type="text" placeholder="Key (e.g. linens)">
                    <input class="input rb-cat-label" type="text" placeholder="Label (e.g. Linens)">
                    <button class="btn btn--ghost hb-row-remove" type="button" aria-label="Remove">✕</button>
                </li>
            </template>
        </div>
    </details>

    <?php /* ── CONTROLS BAR ── */ ?>
    <details id="cms-sec-rb-controls" class="hb-section">
        <div class="hb-section__body">
            <label class="hb-label">Search placeholder
                <input class="input" id="rb-ctrl-search-ph" type="text" value="<?= e((string) ($controls['search_placeholder'] ?? 'Search rentals…')) ?>">
            </label>
            <label class="hb-label">Result label (plural)
                <input class="input" id="rb-ctrl-result-label" type="text" value="<?= e((string) ($controls['result_label_plural'] ?? 'rentals')) ?>">
            </label>
        </div>
    </details>

    <?php /* ── HOW IT WORKS ── */ ?>
    <details id="cms-sec-rb-how-it-works" class="hb-section">
        <div class="hb-section__body">
            <label class="hb-label">Enabled
                <input type="checkbox" id="rb-hiw-enabled" <?= !empty($hiw['enabled']) ? 'checked' : '' ?>>
            </label>
            <label class="hb-label">Section title
                <input class="input" id="rb-hiw-title" type="text" value="<?= e((string) ($hiw['title'] ?? '')) ?>">
            </label>
            <label class="hb-label">CTA label
                <input class="input" id="rb-hiw-cta-label" type="text" value="<?= e((string) ($hiw['cta_label'] ?? '')) ?>">
            </label>
            <label class="hb-label">CTA href
                <input class="input" id="rb-hiw-cta-href" type="text" value="<?= e((string) ($hiw['cta_href'] ?? '')) ?>">
            </label>
            <p class="text-muted" style="font-size:0.85rem;margin:0.5rem 0 0.25rem;">Steps:</p>
            <ul class="hb-repeat-list" id="rb-hiw-steps">
                <?php foreach ($hiwSteps as $step): ?>
                <li class="hb-repeat-row" style="border:1px solid var(--color-line);border-radius:8px;padding:0.75rem;margin-bottom:0.65rem;">
                    <div style="display:grid;grid-template-columns:6rem 1fr;gap:0.5rem;margin-bottom:0.35rem;">
                        <input class="input rb-hiw-num" type="text" placeholder="01" value="<?= e((string) ($step['number'] ?? '')) ?>">
                        <input class="input rb-hiw-step-title" type="text" placeholder="Step title" value="<?= e((string) ($step['title'] ?? '')) ?>">
                    </div>
                    <textarea class="input rb-hiw-step-desc" rows="2" placeholder="Short description" style="width:100%;"><?= e((string) ($step['description'] ?? '')) ?></textarea>
                    <button class="btn btn--ghost hb-row-remove" type="button" style="margin-top:0.35rem;">Remove step</button>
                </li>
                <?php endforeach; ?>
            </ul>
            <button class="btn btn--ghost" id="rb-add-hiw-step" type="button">+ Add step</button>
            <template id="rb-tpl-hiw-step">
                <li class="hb-repeat-row" style="border:1px solid var(--color-line);border-radius:8px;padding:0.75rem;margin-bottom:0.65rem;">
                    <div style="display:grid;grid-template-columns:6rem 1fr;gap:0.5rem;margin-bottom:0.35rem;">
                        <input class="input rb-hiw-num" type="text" placeholder="04">
                        <input class="input rb-hiw-step-title" type="text" placeholder="Step title">
                    </div>
                    <textarea class="input rb-hiw-step-desc" rows="2" placeholder="Short description" style="width:100%;"></textarea>
                    <button class="btn btn--ghost hb-row-remove" type="button" style="margin-top:0.35rem;">Remove step</button>
                </li>
            </template>
        </div>
    </details>

    <?php /* ── LOGISTICS BAR ── */ ?>
    <details id="cms-sec-rb-logistics" class="hb-section">
        <div class="hb-section__body">
            <label class="hb-label">Enabled
                <input type="checkbox" id="rb-log-enabled" <?= !empty($logistics['enabled']) ? 'checked' : '' ?>>
            </label>
            <p class="text-muted" style="font-size:0.85rem;margin:0.5rem 0 0.25rem;">Items (icon key · label):</p>
            <ul class="hb-repeat-list" id="rb-log-items">
                <?php foreach ($logItems as $item): ?>
                <li class="hb-repeat-row" style="display:grid;grid-template-columns:8rem 1fr auto;gap:0.5rem;align-items:center;margin-bottom:0.5rem;">
                    <input class="input rb-log-icon" type="text" placeholder="truck" value="<?= e((string) ($item['icon'] ?? '')) ?>">
                    <input class="input rb-log-label" type="text" placeholder="Label text" value="<?= e((string) ($item['label'] ?? '')) ?>">
                    <button class="btn btn--ghost hb-row-remove" type="button" aria-label="Remove">✕</button>
                </li>
                <?php endforeach; ?>
            </ul>
            <p class="text-muted" style="font-size:0.8rem;margin:0.25rem 0 0.5rem;">Icon keys: truck · sparkle · shield · lock</p>
            <button class="btn btn--ghost" id="rb-add-log" type="button">+ Add item</button>
            <template id="rb-tpl-log">
                <li class="hb-repeat-row" style="display:grid;grid-template-columns:8rem 1fr auto;gap:0.5rem;align-items:center;margin-bottom:0.5rem;">
                    <input class="input rb-log-icon" type="text" placeholder="truck">
                    <input class="input rb-log-label" type="text" placeholder="Label text">
                    <button class="btn btn--ghost hb-row-remove" type="button" aria-label="Remove">✕</button>
                </li>
            </template>
        </div>
    </details>

    <?php /* ── NEWSLETTER ── */ ?>
    <details id="cms-sec-rb-newsletter" class="hb-section">
        <div class="hb-section__body">
            <label class="hb-label">Enabled
                <input type="checkbox" id="rb-nw-enabled" <?= !empty($newsletter['enabled']) ? 'checked' : '' ?>>
            </label>
            <label class="hb-label">Title
                <input class="input" id="rb-nw-title" type="text" value="<?= e((string) ($newsletter['title'] ?? '')) ?>">
            </label>
            <label class="hb-label">Supporting text HTML
                <textarea class="input" id="rb-nw-text" rows="2"><?= e((string) ($newsletter['text_html'] ?? '')) ?></textarea>
            </label>
            <label class="hb-label">Button label
                <input class="input" id="rb-nw-btn" type="text" value="<?= e((string) ($newsletter['button_label'] ?? 'Submit')) ?>">
            </label>
            <label class="hb-label">Input placeholder
                <input class="input" id="rb-nw-ph" type="text" value="<?= e((string) ($newsletter['placeholder'] ?? 'Your email address')) ?>">
            </label>
        </div>
    </details>

</div>
