<?php
declare(strict_types=1);
/** @var array<string, mixed> $mergedPortfolioBlocks */
$b = isset($mergedPortfolioBlocks) && is_array($mergedPortfolioBlocks) ? $mergedPortfolioBlocks : [];
$hero = is_array($b['hero'] ?? null) ? $b['hero'] : [];
$intro = is_array($b['intro'] ?? null) ? $b['intro'] : [];
$controls = is_array($b['controls'] ?? null) ? $b['controls'] : [];
$featured = is_array($b['featured'] ?? null) ? $b['featured'] : [];
$gallery = is_array($b['gallery'] ?? null) ? $b['gallery'] : [];
$nw = is_array($b['newsletter_cta'] ?? null) ? $b['newsletter_cta'] : [];
$featuredItems = is_array($featured['items'] ?? null) ? $featured['items'] : [];
$galleryItems = is_array($gallery['items'] ?? null) ? $gallery['items'] : [];
?>
<div class="home-blocks-editor" id="portfolio-blocks-editor">
    <details class="home-blocks-editor__details" open>
        <summary class="home-blocks-editor__summary">Hero + intro</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="pf-hero-en" <?= (($hero['enabled'] ?? true) !== false) ? 'checked' : '' ?>><span>Enable hero overrides</span></label>
            <label class="home-blocks-editor__check"><input type="checkbox" id="pf-hero-bc" <?= (!isset($hero['show_breadcrumbs']) || $hero['show_breadcrumbs'] !== false) ? 'checked' : '' ?>><span>Show breadcrumbs</span></label>
            <div class="form-row"><label for="pf-hero-kicker">Hero kicker</label><input id="pf-hero-kicker" class="input" type="text" value="<?= e((string) ($hero['kicker'] ?? '')) ?>"></div>
            <div class="form-row"><label for="pf-intro-eye">Intro eyebrow</label><input id="pf-intro-eye" class="input" type="text" value="<?= e((string) ($intro['eyebrow'] ?? '')) ?>"></div>
            <div class="form-row"><label for="pf-intro-title">Intro title</label><input id="pf-intro-title" class="input" type="text" value="<?= e((string) ($intro['title'] ?? '')) ?>"></div>
            <div class="form-row"><label for="pf-intro-lead">Intro lead (HTML)</label><textarea id="pf-intro-lead" class="input input--textarea" rows="4"><?= e((string) ($intro['lead_html'] ?? '')) ?></textarea></div>
        </div>
    </details>

    <details class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Controls</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="pf-ctrl-en" <?= (($controls['enabled'] ?? true) !== false) ? 'checked' : '' ?>><span>Show controls bar</span></label>
            <label class="home-blocks-editor__check"><input type="checkbox" id="pf-ctrl-search" <?= !empty($controls['show_search']) ? 'checked' : '' ?>><span>Show search</span></label>
            <label class="home-blocks-editor__check"><input type="checkbox" id="pf-ctrl-sort" <?= !empty($controls['show_sort']) ? 'checked' : '' ?>><span>Show sort</span></label>
            <div class="form-row"><label for="pf-ctrl-default">Default sort</label><input id="pf-ctrl-default" class="input" type="text" value="<?= e((string) ($controls['default_sort'] ?? 'featured')) ?>"></div>
        </div>
    </details>

    <details class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Featured projects</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="pf-ft-en" <?= (($featured['enabled'] ?? true) !== false) ? 'checked' : '' ?>><span>Show featured section</span></label>
            <div class="form-row"><label for="pf-ft-title">Section title</label><input id="pf-ft-title" class="input" type="text" value="<?= e((string) ($featured['title'] ?? '')) ?>"></div>
            <div class="form-row"><label for="pf-ft-subtitle">Section subtitle</label><input id="pf-ft-subtitle" class="input" type="text" value="<?= e((string) ($featured['subtitle'] ?? '')) ?>"></div>
            <div id="pf-featured-items" class="hb-repeat-list">
                <?php foreach ($featuredItems as $it): if (!is_array($it)) { continue; } ?>
                <div class="hb-repeat-row pf-repeat-row js-pf-featured-item">
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;"><label>Title</label><input class="input js-pf-title" type="text" value="<?= e((string) ($it['title'] ?? '')) ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Tag</label><input class="input js-pf-tag" type="text" value="<?= e((string) ($it['tag'] ?? '')) ?>"></div>
                    </div>
                    <div class="form-row"><label>Summary</label><textarea class="input input--textarea js-pf-summary" rows="2"><?= e((string) ($it['summary'] ?? '')) ?></textarea></div>
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;"><label>Image path (under /public)</label><input class="input js-pf-image" type="text" value="<?= e((string) ($it['image_path'] ?? '')) ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Alt text</label><input class="input js-pf-alt" type="text" value="<?= e((string) ($it['alt'] ?? '')) ?>"></div>
                    </div>
                    <button type="button" class="btn btn--ghost pf-row-remove">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="pf-add-featured">Add featured item</button></p>
        </div>
    </details>

    <details class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Gallery + newsletter</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check"><input type="checkbox" id="pf-gal-en" <?= (($gallery['enabled'] ?? true) !== false) ? 'checked' : '' ?>><span>Show gallery</span></label>
            <div class="form-row"><label for="pf-gal-title">Gallery title</label><input id="pf-gal-title" class="input" type="text" value="<?= e((string) ($gallery['title'] ?? '')) ?>"></div>
            <div id="pf-gallery-items" class="hb-repeat-list">
                <?php foreach ($galleryItems as $it): if (!is_array($it)) { continue; } ?>
                <div class="hb-repeat-row pf-repeat-row js-pf-gallery-item">
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;"><label>Title</label><input class="input js-pf-title" type="text" value="<?= e((string) ($it['title'] ?? '')) ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Tag</label><input class="input js-pf-tag" type="text" value="<?= e((string) ($it['tag'] ?? '')) ?>"></div>
                    </div>
                    <div class="form-row"><label>Summary</label><textarea class="input input--textarea js-pf-summary" rows="2"><?= e((string) ($it['summary'] ?? '')) ?></textarea></div>
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;"><label>Image path (under /public)</label><input class="input js-pf-image" type="text" value="<?= e((string) ($it['image_path'] ?? '')) ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Alt text</label><input class="input js-pf-alt" type="text" value="<?= e((string) ($it['alt'] ?? '')) ?>"></div>
                    </div>
                    <button type="button" class="btn btn--ghost pf-row-remove">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="pf-add-gallery">Add gallery item</button></p>

            <label class="home-blocks-editor__check"><input type="checkbox" id="pf-nw-en" <?= (($nw['enabled'] ?? true) !== false) ? 'checked' : '' ?>><span>Show newsletter band</span></label>
            <div class="form-row"><label for="pf-nw-title">Newsletter title</label><input id="pf-nw-title" class="input" type="text" value="<?= e((string) ($nw['title'] ?? '')) ?>"></div>
            <div class="form-row"><label for="pf-nw-text">Newsletter text HTML</label><textarea id="pf-nw-text" class="input input--textarea" rows="3"><?= e((string) ($nw['text_html'] ?? '')) ?></textarea></div>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="pf-nw-btn">Button label</label><input id="pf-nw-btn" class="input" type="text" value="<?= e((string) ($nw['button_label'] ?? 'Submit')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="pf-nw-ph">Placeholder</label><input id="pf-nw-ph" class="input" type="text" value="<?= e((string) ($nw['placeholder'] ?? 'Your email address')) ?>"></div>
            </div>
        </div>
    </details>
</div>

<template id="pf-tpl-item">
    <div class="hb-repeat-row pf-repeat-row js-pf-item">
        <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
            <div class="form-row" style="margin:0;"><label>Title</label><input class="input js-pf-title" type="text" value=""></div>
            <div class="form-row" style="margin:0;"><label>Tag</label><input class="input js-pf-tag" type="text" value=""></div>
        </div>
        <div class="form-row"><label>Summary</label><textarea class="input input--textarea js-pf-summary" rows="2"></textarea></div>
        <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
            <div class="form-row" style="margin:0;"><label>Image path (under /public)</label><input class="input js-pf-image" type="text" value=""></div>
            <div class="form-row" style="margin:0;"><label>Alt text</label><input class="input js-pf-alt" type="text" value=""></div>
        </div>
        <button type="button" class="btn btn--ghost pf-row-remove">Remove</button>
    </div>
</template>

