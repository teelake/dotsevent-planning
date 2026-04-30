<?php
declare(strict_types=1);
/** @var array<string, mixed> $mergedContactBlocks */
$b = isset($mergedContactBlocks) && is_array($mergedContactBlocks) ? $mergedContactBlocks : [];
$hero = is_array($b['hero'] ?? null) ? $b['hero'] : [];
$intro = is_array($b['intro'] ?? null) ? $b['intro'] : [];
$form = is_array($b['contact_form'] ?? null) ? $b['contact_form'] : [];
$nw = is_array($b['newsletter_cta'] ?? null) ? $b['newsletter_cta'] : [];
$trust = is_array($b['trust'] ?? null) ? $b['trust'] : [];
?>
<div class="home-blocks-editor" id="contact-blocks-editor">
    <details id="cms-sec-ct-hero" class="home-blocks-editor__details" open>
        <summary class="home-blocks-editor__summary">Hero</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="ct-hero-en" <?= (($hero['enabled'] ?? true) !== false) ? 'checked' : '' ?>><span>Enable hero overrides</span></label>
            <label class="home-blocks-editor__check"><input type="checkbox" id="ct-hero-bc" <?= (!isset($hero['show_breadcrumbs']) || $hero['show_breadcrumbs'] !== false) ? 'checked' : '' ?>><span>Show breadcrumbs</span></label>
            <div class="form-row"><label for="ct-hero-kicker">Kicker</label><input id="ct-hero-kicker" class="input" type="text" value="<?= e((string) ($hero['kicker'] ?? '')) ?>"></div>
        </div>
    </details>
    <details id="cms-sec-ct-intro" class="home-blocks-editor__details" open>
        <summary class="home-blocks-editor__summary">Intro</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="ct-intro-en" <?= (($intro['enabled'] ?? true) !== false) ? 'checked' : '' ?>><span>Show intro</span></label>
            <div class="form-row"><label for="ct-intro-title">Title</label><input id="ct-intro-title" class="input" type="text" value="<?= e((string) ($intro['title'] ?? '')) ?>"></div>
            <div class="form-row"><label for="ct-intro-lead">Lead HTML</label><textarea id="ct-intro-lead" class="input input--textarea" rows="3"><?= e((string) ($intro['lead_html'] ?? '')) ?></textarea></div>
        </div>
    </details>
    <details id="cms-sec-ct-form" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Form + newsletter + trust</summary>
        <div class="home-blocks-editor__body">
            <div class="form-row"><label for="ct-form-heading">Form heading</label><input id="ct-form-heading" class="input" type="text" value="<?= e((string) ($form['heading'] ?? '')) ?>"></div>
            <div class="form-row"><label for="ct-form-submit">Form submit label</label><input id="ct-form-submit" class="input" type="text" value="<?= e((string) ($form['submit_label'] ?? 'Send')) ?>"></div>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="ct-nw-title">Newsletter title</label><input id="ct-nw-title" class="input" type="text" value="<?= e((string) ($nw['title'] ?? '')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="ct-nw-btn">Newsletter button</label><input id="ct-nw-btn" class="input" type="text" value="<?= e((string) ($nw['button_label'] ?? 'Submit')) ?>"></div>
            </div>
            <div class="form-row"><label for="ct-nw-desc">Newsletter description HTML</label><textarea id="ct-nw-desc" class="input input--textarea" rows="3"><?= e((string) ($nw['description_html'] ?? '')) ?></textarea></div>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="ct-trust-stars">Trust dots count</label><input id="ct-trust-stars" class="input" type="number" min="1" max="8" value="<?= (int) ($trust['star_count'] ?? 5) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="ct-trust-copy">Trust microcopy</label><input id="ct-trust-copy" class="input" type="text" value="<?= e((string) ($trust['microcopy'] ?? '')) ?>"></div>
            </div>
        </div>
    </details>
</div>

