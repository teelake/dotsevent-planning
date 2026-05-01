<?php
declare(strict_types=1);
/** @var array<string, mixed> $mergedBlocks */
$b = isset($mergedBlocks) && is_array($mergedBlocks) ? $mergedBlocks : [];
$version = isset($b['version']) ? (int) $b['version'] : 1;

$cf = is_array($b['confidence'] ?? null) ? $b['confidence'] : [];
$cfEn = (($cf['enabled'] ?? true) !== false);
$metrics = isset($cf['metrics']) && is_array($cf['metrics']) ? $cf['metrics'] : [];

$pa = is_array($b['partnership'] ?? null) ? $b['partnership'] : [];
$paEn = (($pa['enabled'] ?? true) !== false);

$pk = is_array($b['packages'] ?? null) ? $b['packages'] : [];
$pkEn = (($pk['enabled'] ?? true) !== false);
$pkItems = isset($pk['items']) && is_array($pk['items']) ? $pk['items'] : [];

$ts = is_array($b['testimonials'] ?? null) ? $b['testimonials'] : [];
$tsEn = (($ts['enabled'] ?? true) !== false);
$quotes = isset($ts['quotes']) && is_array($ts['quotes']) ? $ts['quotes'] : [];

$nw = is_array($b['newsletter'] ?? null) ? $b['newsletter'] : [];
$nwEn = (($nw['enabled'] ?? true) !== false);
?>
<div class="home-blocks-editor" id="home-blocks-editor" data-hb-editor>
    <input type="hidden" id="hb-version" value="<?= (int) $version ?>">

    <details id="cms-sec-hb-confidence" class="home-blocks-editor__details" open>
        <summary class="home-blocks-editor__summary">Confidence &amp; metrics</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="hb-cf-enabled" <?= $cfEn ? 'checked' : '' ?>>
                <span>Show this section</span>
            </label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;">
                    <label for="hb-cf-eyebrow">Eyebrow</label>
                    <input class="input" type="text" id="hb-cf-eyebrow" value="<?= e((string) ($cf['eyebrow'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="hb-cf-title">Title</label>
                    <input class="input" type="text" id="hb-cf-title" value="<?= e((string) ($cf['title'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-row" style="margin-top:0.65rem;">
                <label for="hb-cf-lead">Lead</label>
                <textarea class="input input--textarea" id="hb-cf-lead" rows="3"><?= e((string) ($cf['lead'] ?? '')) ?></textarea>
            </div>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;">
                    <label for="hb-cf-cta-label">CTA label</label>
                    <input class="input" type="text" id="hb-cf-cta-label" value="<?= e((string) ($cf['cta_label'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="hb-cf-cta-href">CTA link (optional)</label>
                    <input class="input" type="text" id="hb-cf-cta-href" value="<?= e((string) ($cf['cta_href'] ?? '')) ?>" placeholder="Leave empty for default">
                </div>
            </div>
            <p class="text-muted" style="font-size:0.85rem; margin:0.75rem 0 0.35rem;">Metrics (count-up uses target + suffix when target &gt; 0)</p>
            <div id="hb-metrics" class="hb-repeat-list">
                <?php foreach ($metrics as $m): ?>
                <?php
                if (!is_array($m)) {
                    continue;
                }
                ?>
                <div class="hb-repeat-row js-hb-metric-row">
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--4">
                        <div class="form-row" style="margin:0;">
                            <label>Label</label>
                            <input class="input js-metric-label" type="text" value="<?= e((string) ($m['label'] ?? '')) ?>">
                        </div>
                        <div class="form-row" style="margin:0;">
                            <label>Display (fallback)</label>
                            <input class="input js-metric-display" type="text" value="<?= e((string) ($m['display'] ?? '')) ?>">
                        </div>
                        <div class="form-row" style="margin:0;">
                            <label>Target (number)</label>
                            <input class="input js-metric-target" type="text" inputmode="numeric" value="<?= isset($m['target']) ? (string) (int) $m['target'] : '' ?>">
                        </div>
                        <div class="form-row" style="margin:0;">
                            <label>Suffix</label>
                            <input class="input js-metric-suffix" type="text" value="<?= e((string) ($m['suffix'] ?? '+')) ?>">
                        </div>
                    </div>
                    <button type="button" class="hb-row-remove hb-row-remove--danger" aria-label="Remove metric">Remove metric</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p style="margin-top:0.5rem;">
                <button type="button" class="btn btn--secondary" id="hb-add-metric">Add metric</button>
            </p>
        </div>
    </details>

    <details id="cms-sec-hb-partnership" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Partnership</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="hb-pa-enabled" <?= $paEn ? 'checked' : '' ?>>
                <span>Show this section</span>
            </label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;">
                    <label for="hb-pa-kicker">Kicker</label>
                    <input class="input" type="text" id="hb-pa-kicker" value="<?= e((string) ($pa['kicker'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="hb-pa-title">Title</label>
                    <input class="input" type="text" id="hb-pa-title" value="<?= e((string) ($pa['title'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-row" style="margin-top:0.65rem;">
                <label for="hb-pa-lead">Lead</label>
                <textarea class="input input--textarea" id="hb-pa-lead" rows="4"><?= e((string) ($pa['lead'] ?? '')) ?></textarea>
            </div>
            <div class="form-row">
                <label for="hb-pa-pull">Pull quote</label>
                <textarea class="input input--textarea" id="hb-pa-pull" rows="3"><?= e((string) ($pa['pull_quote'] ?? '')) ?></textarea>
            </div>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;">
                    <label for="hb-pa-cta-label">CTA label</label>
                    <input class="input" type="text" id="hb-pa-cta-label" value="<?= e((string) ($pa['cta_label'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="hb-pa-cta-href">CTA link (optional)</label>
                    <input class="input" type="text" id="hb-pa-cta-href" value="<?= e((string) ($pa['cta_href'] ?? '')) ?>">
                </div>
            </div>
        </div>
    </details>

    <details id="cms-sec-hb-packages" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Packages &amp; investment</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="hb-pk-enabled" <?= $pkEn ? 'checked' : '' ?>>
                <span>Show this section</span>
            </label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--3">
                <div class="form-row" style="margin:0;">
                    <label for="hb-pk-eyebrow">Eyebrow</label>
                    <input class="input" type="text" id="hb-pk-eyebrow" value="<?= e((string) ($pk['eyebrow'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="hb-pk-title">Title</label>
                    <input class="input" type="text" id="hb-pk-title" value="<?= e((string) ($pk['title'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="hb-pk-subtitle">Subtitle</label>
                    <input class="input" type="text" id="hb-pk-subtitle" value="<?= e((string) ($pk['subtitle'] ?? '')) ?>">
                </div>
            </div>
            <div id="hb-pk-items" class="hb-repeat-list">
                <?php foreach ($pkItems as $pkg): ?>
                <?php if (!is_array($pkg)) { continue; } ?>
                <?php $feats = isset($pkg['features']) && is_array($pkg['features']) ? $pkg['features'] : []; ?>
                <?php
                $pkgFeatSeedHtml = '';
                if (isset($pkg['features_html']) && is_string($pkg['features_html']) && trim($pkg['features_html']) !== '') {
                    $pkgFeatSeedHtml = $pkg['features_html'];
                } elseif ($feats !== []) {
                    $lis = [];
                    foreach ($feats as $line) {
                        if (! is_string($line)) {
                            continue;
                        }
                        $t = trim($line);
                        if ($t === '') {
                            continue;
                        }
                        $lis[] = '<li>' . htmlspecialchars($t, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</li>';
                    }
                    if ($lis !== []) {
                        $pkgFeatSeedHtml = '<ul>' . implode('', $lis) . '</ul>';
                    }
                }
                ?>
                <div class="hb-pkg-card hb-repeat-row js-hb-pkg-row">
                    <label class="home-blocks-editor__check" style="margin-bottom:0.5rem;"><input type="checkbox" class="js-pkg-featured" <?= !empty($pkg['featured']) ? 'checked' : '' ?>><span>Featured tier</span></label>
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;">
                            <label>Name</label>
                            <input class="input js-pkg-name" type="text" value="<?= e((string) ($pkg['name'] ?? '')) ?>">
                        </div>
                        <div class="form-row" style="margin:0;">
                            <label>Price line</label>
                            <input class="input js-pkg-price" type="text" value="<?= e((string) ($pkg['price_display'] ?? '')) ?>">
                        </div>
                    </div>
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;">
                            <label>CTA label</label>
                            <input class="input js-pkg-cta-l" type="text" value="<?= e((string) ($pkg['cta_label'] ?? '')) ?>">
                        </div>
                        <div class="form-row" style="margin:0;">
                            <label>CTA link (optional)</label>
                            <input class="input js-pkg-cta-h" type="text" value="<?= e((string) ($pkg['cta_href'] ?? '')) ?>">
                        </div>
                    </div>
                    <?php
                    $bpSel = normalize_booking_package_slug($pkg['booking_package'] ?? null);
                    ?>
                    <div class="form-row" style="margin:0;">
                        <label>Book form tier</label>
                        <select class="input js-pkg-booking">
                            <option value=""<?= $bpSel === null ? ' selected' : '' ?>>None (visitor chooses)</option>
                            <option value="basic"<?= $bpSel === 'basic' ? ' selected' : '' ?>>Basic</option>
                            <option value="premium"<?= $bpSel === 'premium' ? ' selected' : '' ?>>Premium</option>
                            <option value="vip"<?= $bpSel === 'vip' ? ' selected' : '' ?>>VIP</option>
                            <option value="not_sure"<?= $bpSel === 'not_sure' ? ' selected' : '' ?>>Not sure yet</option>
                        </select>
                        <span class="text-muted" style="font-size:0.82rem;display:block;margin-top:0.35rem;">When the CTA targets the booking page, we append <code>?package=…</code> so this tier pre-selects.</span>
                    </div>
                    <div class="form-row hb-pkg-feats-editor-wrap">
                        <label>Features</label>
                        <textarea class="js-pkg-feats-html" rows="1" hidden aria-hidden="true"><?= htmlspecialchars($pkgFeatSeedHtml, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
                        <div class="cms-edit-quill hb-pkg-feats-quill js-pkg-feats-quill" aria-label="Package features editor"></div>
                    </div>
                    <button type="button" class="btn btn--ghost hb-row-remove">Remove package</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="hb-add-pkg">Add package</button></p>
        </div>
    </details>

    <details id="cms-sec-hb-testimonials" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Testimonials</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="hb-ts-enabled" <?= $tsEn ? 'checked' : '' ?>>
                <span>Show this section</span>
            </label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;">
                    <label for="hb-ts-title">Title</label>
                    <input class="input" type="text" id="hb-ts-title" value="<?= e((string) ($ts['title'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="hb-ts-subtitle">Subtitle</label>
                    <input class="input" type="text" id="hb-ts-subtitle" value="<?= e((string) ($ts['subtitle'] ?? '')) ?>">
                </div>
            </div>
            <div id="hb-quotes" class="hb-repeat-list">
                <?php foreach ($quotes as $q): ?>
                <?php if (!is_array($q)) { continue; } ?>
                <div class="hb-quote-block hb-repeat-row js-hb-quote-row">
                    <div class="form-row">
                        <label>Quote</label>
                        <textarea class="input input--textarea js-quote-text" rows="3"><?= e((string) ($q['quote'] ?? '')) ?></textarea>
                    </div>
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;">
                            <label>Name</label>
                            <input class="input js-quote-name" type="text" value="<?= e((string) ($q['name'] ?? '')) ?>">
                        </div>
                        <div class="form-row" style="margin:0;">
                            <label>Role / context</label>
                            <input class="input js-quote-role" type="text" value="<?= e((string) ($q['role'] ?? '')) ?>">
                        </div>
                    </div>
                    <button type="button" class="btn btn--ghost hb-row-remove">Remove quote</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="hb-add-quote">Add quote</button></p>
        </div>
    </details>

    <details id="cms-sec-hb-newsletter" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Newsletter band</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="hb-nw-enabled" <?= $nwEn ? 'checked' : '' ?>>
                <span>Show this section</span>
            </label>
            <div class="form-row">
                <label for="hb-nw-title">Heading</label>
                <input class="input" type="text" id="hb-nw-title" value="<?= e((string) ($nw['title'] ?? '')) ?>">
            </div>
            <div class="form-row">
                <label for="hb-nw-text">Supporting text</label>
                <textarea class="input input--textarea" id="hb-nw-text" rows="3"><?= e((string) ($nw['text'] ?? '')) ?></textarea>
            </div>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;">
                    <label for="hb-nw-btn">Button label</label>
                    <input class="input" type="text" id="hb-nw-btn" value="<?= e((string) ($nw['button_label'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="hb-nw-ph">Email placeholder</label>
                    <input class="input" type="text" id="hb-nw-ph" value="<?= e((string) ($nw['placeholder'] ?? '')) ?>">
                </div>
            </div>
        </div>
    </details>
</div>

<template id="hb-tpl-metric">
    <div class="hb-repeat-row js-hb-metric-row">
        <div class="home-blocks-editor__grid home-blocks-editor__grid--4">
            <div class="form-row" style="margin:0;">
                <label>Label</label>
                <input class="input js-metric-label" type="text" value="">
            </div>
            <div class="form-row" style="margin:0;">
                <label>Display (fallback)</label>
                <input class="input js-metric-display" type="text" value="">
            </div>
            <div class="form-row" style="margin:0;">
                <label>Target (number)</label>
                <input class="input js-metric-target" type="text" inputmode="numeric" value="">
            </div>
            <div class="form-row" style="margin:0;">
                <label>Suffix</label>
                <input class="input js-metric-suffix" type="text" value="+">
            </div>
        </div>
        <button type="button" class="hb-row-remove hb-row-remove--danger" aria-label="Remove metric">Remove metric</button>
    </div>
</template>

<template id="hb-tpl-pkg">
    <div class="hb-pkg-card hb-repeat-row js-hb-pkg-row">
        <label class="home-blocks-editor__check" style="margin-bottom:0.5rem;"><input type="checkbox" class="js-pkg-featured"><span>Featured tier</span></label>
        <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
            <div class="form-row" style="margin:0;">
                <label>Name</label>
                <input class="input js-pkg-name" type="text" value="">
            </div>
            <div class="form-row" style="margin:0;">
                <label>Price line</label>
                <input class="input js-pkg-price" type="text" value="">
            </div>
        </div>
        <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
            <div class="form-row" style="margin:0;">
                <label>CTA label</label>
                <input class="input js-pkg-cta-l" type="text" value="">
            </div>
            <div class="form-row" style="margin:0;">
                <label>CTA link (optional)</label>
                <input class="input js-pkg-cta-h" type="text" value="">
            </div>
        </div>
        <div class="form-row" style="margin:0;">
            <label>Book form tier</label>
            <select class="input js-pkg-booking">
                <option value="" selected>None (visitor chooses)</option>
                <option value="basic">Basic</option>
                <option value="premium">Premium</option>
                <option value="vip">VIP</option>
                <option value="not_sure">Not sure yet</option>
            </select>
            <span class="text-muted" style="font-size:0.82rem;display:block;margin-top:0.35rem;">When the CTA targets the booking page, we append <code>?package=…</code> so this tier pre-selects.</span>
        </div>
        <div class="form-row hb-pkg-feats-editor-wrap">
            <label>Features</label>
            <textarea class="js-pkg-feats-html" rows="1" hidden aria-hidden="true"></textarea>
            <div class="cms-edit-quill hb-pkg-feats-quill js-pkg-feats-quill" aria-label="Package features editor"></div>
        </div>
        <button type="button" class="btn btn--ghost hb-row-remove">Remove package</button>
    </div>
</template>

<template id="hb-tpl-quote">
    <div class="hb-quote-block hb-repeat-row js-hb-quote-row">
        <div class="form-row">
            <label>Quote</label>
            <textarea class="input input--textarea js-quote-text" rows="3"></textarea>
        </div>
        <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
            <div class="form-row" style="margin:0;">
                <label>Name</label>
                <input class="input js-quote-name" type="text" value="">
            </div>
            <div class="form-row" style="margin:0;">
                <label>Role / context</label>
                <input class="input js-quote-role" type="text" value="">
            </div>
        </div>
        <button type="button" class="btn btn--ghost hb-row-remove">Remove quote</button>
    </div>
</template>
