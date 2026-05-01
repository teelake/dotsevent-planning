<?php
declare(strict_types=1);
/** @var array<string, mixed> $mergedServicesBlocks */
$b = isset($mergedServicesBlocks) && is_array($mergedServicesBlocks) ? $mergedServicesBlocks : [];
$ver = isset($b['version']) ? (int) $b['version'] : 1;
$h = is_array($b['hero'] ?? null) ? $b['hero'] : [];
$of = is_array($b['offerings'] ?? null) ? $b['offerings'] : [];
$f = is_array($b['faq'] ?? null) ? $b['faq'] : [];
$nw = is_array($b['newsletter_cta'] ?? null) ? $b['newsletter_cta'] : [];

$hEn = (($h['enabled'] ?? true) !== false);
$bc = !isset($h['show_breadcrumbs']) || $h['show_breadcrumbs'] !== false;
$ofEn = (($of['enabled'] ?? true) !== false);
$ofHomeTeaser = (($of['home_teaser_enabled'] ?? true) !== false);
$fEn = (($f['enabled'] ?? true) !== false);
$nEn = (($nw['enabled'] ?? true) !== false);
$faqOpenFirst = !empty($f['open_first']);

$offItems = isset($of['items']) && is_array($of['items']) ? $of['items'] : [];
$faqs = isset($f['items']) && is_array($f['items']) ? $f['items'] : [];
?>

<div class="home-blocks-editor" id="services-blocks-editor">
    <input type="hidden" id="svc-version" value="<?= (int) $ver ?>">

    <details id="cms-sec-svc-hero" class="home-blocks-editor__details" open>
        <summary class="home-blocks-editor__summary">Hero</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="svc-hero-en" <?= $hEn ? 'checked' : '' ?>><span>Use title overrides</span></label>
            <label class="home-blocks-editor__check"><input type="checkbox" id="svc-hero-bc" <?= $bc ? 'checked' : '' ?>><span>Show breadcrumbs</span></label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="svc-hero-kicker">Kicker</label><input class="input" id="svc-hero-kicker" type="text" value="<?= e((string) ($h['kicker'] ?? '')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="svc-hero-title">H1</label><input class="input" id="svc-hero-title" type="text" value="<?= e((string) ($h['title'] ?? '')) ?>"></div>
            </div>
        </div>
    </details>

    <details id="cms-sec-svc-offerings" class="home-blocks-editor__details" open>
        <summary class="home-blocks-editor__summary">Services catalogue</summary>
        <div class="home-blocks-editor__body">
            <p class="text-muted" style="font-size:0.85rem; margin:0 0 0.65rem;">Each row is one service card. Use <strong>Remove</strong> on a row to delete it and <strong>Add service</strong> to create a new one, then save.</p>
            <label class="home-blocks-editor__check"><input type="checkbox" id="svc-of-en" <?= $ofEn ? 'checked' : '' ?>><span>Show catalogue on Services page</span></label>
            <label class="home-blocks-editor__check"><input type="checkbox" id="svc-of-home-teaser-en" <?= $ofHomeTeaser ? 'checked' : '' ?>><span>Show teaser on Home page</span></label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="svc-of-eye">Section eyebrow</label><input class="input" id="svc-of-eye" type="text" value="<?= e((string) ($of['eyebrow'] ?? '')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="svc-of-stitle">Section title</label><input class="input" id="svc-of-stitle" type="text" value="<?= e((string) ($of['section_title'] ?? '')) ?>"></div>
            </div>
            <p class="text-muted" style="font-size:0.85rem; margin:0 0 0.5rem;">You can hide the catalogue on the Services page but still promote the same list on Home (or the reverse), using the two toggles above. Heading and CTA for Home are below.</p>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="svc-of-home-cta-label">Home CTA label</label><input class="input" id="svc-of-home-cta-label" type="text" value="<?= e((string) ($of['home_teaser_cta_label'] ?? '')) ?>" placeholder="Explore services"></div>
                <div class="form-row" style="margin:0;"><label for="svc-of-home-cta-href">Home CTA URL</label><input class="input" id="svc-of-home-cta-href" type="text" value="<?= e((string) ($of['home_teaser_cta_href'] ?? '')) ?>" placeholder="/services if empty"></div>
            </div>
            <div id="svc-offer-rows" class="hb-repeat-list">
                <?php foreach ($offItems as $it): ?>
                <?php if (!is_array($it)) {
                    continue;
                } ?>
                <div class="hb-repeat-row svc-repeat-row js-svc-off-row">
                    <div class="form-row"><label>Title</label><input type="text" class="input js-svc-off-title" value="<?= e((string) ($it['title'] ?? '')) ?>"></div>
                    <div class="form-row"><label>Summary HTML</label><textarea class="input input--textarea js-svc-off-sum" rows="3"><?= e((string) ($it['summary_html'] ?? '')) ?></textarea></div>
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;"><label>Optional link</label><input type="text" class="input js-svc-off-href" value="<?= e((string) ($it['href'] ?? '')) ?>" placeholder="https:// or /path"></div>
                        <div class="form-row hb-cluster-checks">
                            <label class="home-blocks-editor__check"><input type="checkbox" class="js-svc-off-accent" <?= !empty($it['accent']) ? 'checked' : '' ?>><span>Accent</span></label>
                            <label class="home-blocks-editor__check"><input type="checkbox" class="js-svc-off-muted" <?= !empty($it['muted']) ? 'checked' : '' ?>><span>Muted</span></label>
                        </div>
                    </div>
                    <button type="button" class="btn btn--ghost hb-row-remove svc-row-remove">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="svc-add-offer">Add service</button></p>
        </div>
    </details>

    <details id="cms-sec-svc-faq" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">FAQ</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="svc-faq-en" <?= $fEn ? 'checked' : '' ?>><span>Show FAQ</span></label>
            <label class="home-blocks-editor__check"><input type="checkbox" id="svc-faq-openfirst" <?= $faqOpenFirst ? 'checked' : '' ?>><span>Open first item by default</span></label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="svc-faq-eye">Eyebrow</label><input class="input" id="svc-faq-eye" type="text" value="<?= e((string) ($f['eyebrow'] ?? '')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="svc-faq-title">Title</label><input class="input" id="svc-faq-title" type="text" value="<?= e((string) ($f['title'] ?? '')) ?>"></div>
            </div>
            <div class="form-row"><label for="svc-faq-lead">Intro HTML</label><textarea class="input input--textarea" id="svc-faq-lead" rows="2"><?= e((string) ($f['lead_html'] ?? '')) ?></textarea></div>
            <div id="svc-faq-rows" class="hb-repeat-list">
                <?php foreach ($faqs as $q): ?>
                <?php if (!is_array($q)) {
                    continue;
                } ?>
                <div class="hb-repeat-row svc-repeat-row js-svc-faq-row">
                    <div class="form-row"><label>Question</label><input type="text" class="input js-scf-q" value="<?= e((string) ($q['question'] ?? '')) ?>"></div>
                    <div class="form-row"><label>Answer HTML</label><textarea class="input input--textarea js-scf-a" rows="3"><?= e((string) ($q['answer_html'] ?? '')) ?></textarea></div>
                    <button type="button" class="btn btn--ghost hb-row-remove svc-row-remove">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="svc-add-faq">Add FAQ</button></p>
        </div>
    </details>

    <details id="cms-sec-svc-newsletter" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Newsletter</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="svc-nw-en" <?= $nEn ? 'checked' : '' ?>><span>Show band</span></label>
            <div class="form-row"><label for="svc-nw-title">Heading</label><input class="input" id="svc-nw-title" type="text" value="<?= e((string) ($nw['title'] ?? '')) ?>"></div>
            <div class="form-row"><label for="svc-nw-text">Supporting HTML</label><textarea class="input input--textarea" id="svc-nw-text" rows="3"><?= e((string) ($nw['text_html'] ?? '')) ?></textarea></div>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="svc-nw-btn">Button</label><input class="input" id="svc-nw-btn" type="text" value="<?= e((string) ($nw['button_label'] ?? '')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="svc-nw-ph">Placeholder</label><input class="input" id="svc-nw-ph" type="text" value="<?= e((string) ($nw['placeholder'] ?? '')) ?>"></div>
            </div>
        </div>
    </details>
</div>

<template id="svc-tpl-offer">
    <div class="hb-repeat-row svc-repeat-row js-svc-off-row">
        <div class="form-row"><label>Title</label><input type="text" class="input js-svc-off-title" value=""></div>
        <div class="form-row"><label>Summary HTML</label><textarea class="input input--textarea js-svc-off-sum" rows="3"></textarea></div>
        <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
            <div class="form-row" style="margin:0;"><label>Optional link</label><input type="text" class="input js-svc-off-href" value=""></div>
            <div class="form-row hb-cluster-checks">
                <label class="home-blocks-editor__check"><input type="checkbox" class="js-svc-off-accent"><span>Accent</span></label>
                <label class="home-blocks-editor__check"><input type="checkbox" class="js-svc-off-muted"><span>Muted</span></label>
            </div>
        </div>
        <button type="button" class="btn btn--ghost hb-row-remove svc-row-remove">Remove</button>
    </div>
</template>

<template id="svc-tpl-faq">
    <div class="hb-repeat-row svc-repeat-row js-svc-faq-row">
        <div class="form-row"><label>Question</label><input type="text" class="input js-scf-q" value=""></div>
        <div class="form-row"><label>Answer HTML</label><textarea class="input input--textarea js-scf-a" rows="3"></textarea></div>
        <button type="button" class="btn btn--ghost hb-row-remove svc-row-remove">Remove</button>
    </div>
</template>
