<?php
declare(strict_types=1);
/** @var array<string, mixed> $mergedAboutBlocks */
$b = isset($mergedAboutBlocks) && is_array($mergedAboutBlocks) ? $mergedAboutBlocks : [];
$ver = isset($b['version']) ? (int) $b['version'] : 1;
$hero = is_array($b['hero'] ?? null) ? $b['hero'] : [];
$story = is_array($b['story'] ?? null) ? $b['story'] : [];
$ap = is_array($b['approach'] ?? null) ? $b['approach'] : [];
$vals = is_array($b['values'] ?? null) ? $b['values'] : [];
$team = is_array($b['team'] ?? null) ? $b['team'] : [];
$nw = is_array($b['newsletter_cta'] ?? null) ? $b['newsletter_cta'] : [];

$hEn = (($hero['enabled'] ?? true) !== false);
$bc = !isset($hero['show_breadcrumbs']) || ($hero['show_breadcrumbs'] !== false);
$stEn = (($story['enabled'] ?? true) !== false);
$apEn = (($ap['enabled'] ?? true) !== false);
$vEn = (($vals['enabled'] ?? true) !== false);
$tEn = (($team['enabled'] ?? true) !== false);
$nEn = (($nw['enabled'] ?? true) !== false);

$chapters = isset($story['chapters']) && is_array($story['chapters']) ? $story['chapters'] : [];
$metrics = isset($story['metrics']) && is_array($story['metrics']) ? $story['metrics'] : [];
$imgs = isset($ap['images']) && is_array($ap['images']) ? $ap['images'] : [];
$valueItems = isset($vals['items']) && is_array($vals['items']) ? $vals['items'] : [];
$members = isset($team['members']) && is_array($team['members']) ? $team['members'] : [];
?>

<div class="home-blocks-editor" id="about-blocks-editor">
    <input type="hidden" id="ab-version" value="<?= (int) $ver ?>">

    <details id="cms-sec-ab-hero" class="home-blocks-editor__details" open>
        <summary class="home-blocks-editor__summary">Hero title &amp; breadcrumb</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="ab-hero-en" <?= $hEn ? 'checked' : '' ?>>
                <span>Use structured hero overrides</span>
            </label>
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="ab-hero-bc" <?= $bc ? 'checked' : '' ?>>
                <span>Show breadcrumbs</span>
            </label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;">
                    <label for="ab-hero-kicker">Kicker / eyebrow (optional)</label>
                    <input class="input" id="ab-hero-kicker" type="text" value="<?= e((string) ($hero['kicker'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="ab-hero-title">H1 headline</label>
                    <input class="input" id="ab-hero-title" type="text" value="<?= e((string) ($hero['title'] ?? '')) ?>">
                </div>
            </div>
        </div>
    </details>

    <details id="cms-sec-ab-story" class="home-blocks-editor__details" open>
        <summary class="home-blocks-editor__summary">Story &amp; figures</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="ab-story-en" <?= $stEn ? 'checked' : '' ?>>
                <span>Show section</span>
            </label>
            <div class="form-row">
                <label for="ab-story-eye">Section eyebrow</label>
                <input class="input" id="ab-story-eye" type="text" value="<?= e((string) ($story['eyebrow'] ?? '')) ?>">
            </div>
            <div class="form-row">
                <label for="ab-story-quote">Pull quote</label>
                <textarea class="input input--textarea" id="ab-story-quote" rows="2"><?= e((string) ($story['pull_quote'] ?? '')) ?></textarea>
            </div>
            <p class="text-muted" style="font-size:0.85rem;margin:0.5rem 0 0.25rem;">Chapters (HTML snippets allowed)</p>
            <div id="ab-story-chapters" class="hb-repeat-list">
                <?php foreach ($chapters as $ch): ?>
                <?php if (!is_array($ch)) {
                    continue;
                } ?>
                <div class="hb-repeat-row ab-repeat-row js-ab-ch-row">
                    <div class="form-row"><label>Heading</label><input type="text" class="input js-ab-ch-head" value="<?= e((string) ($ch['heading'] ?? '')) ?>"></div>
                    <div class="form-row"><label>Body HTML</label><textarea class="input input--textarea js-ab-ch-body" rows="4"><?= e((string) ($ch['body_html'] ?? '')) ?></textarea></div>
                    <button type="button" class="btn btn--ghost ab-row-remove">Remove chapter</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="ab-add-chapter">Add chapter</button></p>
            <p class="text-muted" style="font-size:0.85rem;margin:0.75rem 0 0.35rem;">Metrics</p>
            <div id="ab-metrics" class="hb-repeat-list">
                <?php foreach ($metrics as $m): ?>
                <?php if (!is_array($m)) {
                    continue;
                } ?>
                <div class="hb-repeat-row ab-repeat-row js-ab-metric-row">
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--4">
                        <div class="form-row" style="margin:0;"><label>Label</label><input type="text" class="input js-abm-label" value="<?= e((string) ($m['label'] ?? '')) ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Display</label><input type="text" class="input js-abm-display" value="<?= e((string) ($m['display'] ?? '')) ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Target</label><input type="text" class="input js-abm-target" inputmode="numeric" value="<?= isset($m['target']) ? (string) (int) $m['target'] : '' ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Suffix</label><input type="text" class="input js-abm-suffix" value="<?= e((string) ($m['suffix'] ?? '+')) ?>"></div>
                    </div>
                    <button type="button" class="btn btn--ghost ab-row-remove">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="ab-add-metric">Add metric</button></p>
        </div>
    </details>

    <details id="cms-sec-ab-approach" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Approach</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="ab-ap-en" <?= $apEn ? 'checked' : '' ?>>
                <span>Show section</span>
            </label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;">
                    <label for="ab-ap-eye">Eyebrow</label>
                    <input class="input" id="ab-ap-eye" type="text" value="<?= e((string) ($ap['eyebrow'] ?? '')) ?>">
                </div>
                <div class="form-row" style="margin:0;">
                    <label for="ab-ap-title">Title</label>
                    <input class="input" id="ab-ap-title" type="text" value="<?= e((string) ($ap['title'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-row">
                <label for="ab-ap-lead">Lead copy (HTML)</label>
                <textarea class="input input--textarea" id="ab-ap-lead" rows="6"><?= e((string) ($ap['lead_html'] ?? '')) ?></textarea>
            </div>
            <p class="text-muted" style="font-size:0.85rem;margin:0">Stack images — paths under <code>/public</code>, e.g. <code>uploads/photo.jpg</code></p>
            <div id="ab-img-rows" class="hb-repeat-list">
                <?php foreach ($imgs as $im): ?>
                <?php if (!is_array($im)) {
                    continue;
                } ?>
                <div class="hb-repeat-row ab-repeat-row js-ab-img-row">
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;"><label>Image path</label><input type="text" class="input js-abi-src" value="<?= e((string) ($im['src'] ?? '')) ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Alt text</label><input type="text" class="input js-abi-alt" value="<?= e((string) ($im['alt'] ?? '')) ?>"></div>
                    </div>
                    <button type="button" class="btn btn--ghost ab-row-remove">Remove image</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="ab-add-img">Add image slot</button></p>
        </div>
    </details>

    <details id="cms-sec-ab-values" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Core values</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="ab-val-en" <?= $vEn ? 'checked' : '' ?>>
                <span>Show section</span>
            </label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="ab-val-eye">Eyebrow</label><input class="input" id="ab-val-eye" type="text" value="<?= e((string) ($vals['eyebrow'] ?? '')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="ab-val-title">Title</label><input class="input" id="ab-val-title" type="text" value="<?= e((string) ($vals['title'] ?? '')) ?>"></div>
            </div>
            <div id="ab-values" class="hb-repeat-list">
                <?php foreach ($valueItems as $vi): ?>
                <?php if (!is_array($vi)) {
                    continue;
                } ?>
                <div class="hb-repeat-row ab-repeat-row js-ab-val-row">
                    <div class="form-row"><label>Value title</label><input type="text" class="input js-abv-title" value="<?= e((string) ($vi['title'] ?? '')) ?>"></div>
                    <div class="form-row"><label>Summary (HTML)</label><textarea class="input input--textarea js-abv-sum" rows="3"><?= e((string) ($vi['summary_html'] ?? '')) ?></textarea></div>
                    <button type="button" class="btn btn--ghost ab-row-remove">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="ab-add-value">Add value</button></p>
        </div>
    </details>

    <details id="cms-sec-ab-team" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Team</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="ab-team-en" <?= $tEn ? 'checked' : '' ?>>
                <span>Show section</span>
            </label>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="ab-team-eye">Eyebrow</label><input class="input" id="ab-team-eye" type="text" value="<?= e((string) ($team['eyebrow'] ?? '')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="ab-team-title">Title</label><input class="input" id="ab-team-title" type="text" value="<?= e((string) ($team['title'] ?? '')) ?>"></div>
            </div>
            <div class="form-row">
                <label for="ab-team-intro">Intro HTML</label>
                <textarea class="input input--textarea" id="ab-team-intro" rows="3"><?= e((string) ($team['intro_html'] ?? '')) ?></textarea>
            </div>
            <div id="ab-members" class="hb-repeat-list">
                <?php foreach ($members as $mem): ?>
                <?php if (!is_array($mem)) {
                    continue;
                } ?>
                <div class="hb-repeat-row ab-repeat-row js-ab-mem-row">
                    <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                        <div class="form-row" style="margin:0;"><label>Name</label><input type="text" class="input js-abm-name" value="<?= e((string) ($mem['name'] ?? '')) ?>"></div>
                        <div class="form-row" style="margin:0;"><label>Role</label><input type="text" class="input js-abm-role" value="<?= e((string) ($mem['role'] ?? '')) ?>"></div>
                    </div>
                    <div class="form-row"><label>Photo path</label><input type="text" class="input js-abm-photo" value="<?= e((string) ($mem['photo'] ?? '')) ?>"></div>
                    <div class="form-row"><label>Bio (HTML)</label><textarea class="input input--textarea js-abm-bio" rows="2"><?= e((string) ($mem['bio_html'] ?? '')) ?></textarea></div>
                    <button type="button" class="btn btn--ghost ab-row-remove">Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <p><button type="button" class="btn btn--secondary" id="ab-add-member">Add teammate</button></p>
        </div>
    </details>

    <details id="cms-sec-ab-newsletter" class="home-blocks-editor__details">
        <summary class="home-blocks-editor__summary">Newsletter strip</summary>
        <div class="home-blocks-editor__body">
            <label class="home-blocks-editor__check">
                <input type="checkbox" id="ab-nw-en" <?= $nEn ? 'checked' : '' ?>>
                <span>Show band</span>
            </label>
            <div class="form-row"><label for="ab-nw-title">Heading</label><input class="input" id="ab-nw-title" type="text" value="<?= e((string) ($nw['title'] ?? '')) ?>"></div>
            <div class="form-row"><label for="ab-nw-text">Supporting HTML</label><textarea class="input input--textarea" id="ab-nw-text" rows="3"><?= e((string) ($nw['text_html'] ?? '')) ?></textarea></div>
            <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
                <div class="form-row" style="margin:0;"><label for="ab-nw-btn">Button label</label><input class="input" id="ab-nw-btn" type="text" value="<?= e((string) ($nw['button_label'] ?? '')) ?>"></div>
                <div class="form-row" style="margin:0;"><label for="ab-nw-ph">Placeholder</label><input class="input" id="ab-nw-ph" type="text" value="<?= e((string) ($nw['placeholder'] ?? '')) ?>"></div>
            </div>
        </div>
    </details>
</div>

<template id="ab-tpl-chapter">
    <div class="hb-repeat-row ab-repeat-row js-ab-ch-row">
        <div class="form-row"><label>Heading</label><input type="text" class="input js-ab-ch-head" value=""></div>
        <div class="form-row"><label>Body HTML</label><textarea class="input input--textarea js-ab-ch-body" rows="4"></textarea></div>
        <button type="button" class="btn btn--ghost ab-row-remove">Remove chapter</button>
    </div>
</template>

<template id="ab-tpl-metric">
    <div class="hb-repeat-row ab-repeat-row js-ab-metric-row">
        <div class="home-blocks-editor__grid home-blocks-editor__grid--4">
            <div class="form-row" style="margin:0;"><label>Label</label><input type="text" class="input js-abm-label" value=""></div>
            <div class="form-row" style="margin:0;"><label>Display</label><input type="text" class="input js-abm-display" value=""></div>
            <div class="form-row" style="margin:0;"><label>Target</label><input type="text" class="input js-abm-target" inputmode="numeric" value=""></div>
            <div class="form-row" style="margin:0;"><label>Suffix</label><input type="text" class="input js-abm-suffix" value="+"></div>
        </div>
        <button type="button" class="btn btn--ghost ab-row-remove">Remove</button>
    </div>
</template>

<template id="ab-tpl-img">
    <div class="hb-repeat-row ab-repeat-row js-ab-img-row">
        <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
            <div class="form-row" style="margin:0;"><label>Image path</label><input type="text" class="input js-abi-src" value=""></div>
            <div class="form-row" style="margin:0;"><label>Alt text</label><input type="text" class="input js-abi-alt" value=""></div>
        </div>
        <button type="button" class="btn btn--ghost ab-row-remove">Remove image</button>
    </div>
</template>

<template id="ab-tpl-value">
    <div class="hb-repeat-row ab-repeat-row js-ab-val-row">
        <div class="form-row"><label>Value title</label><input type="text" class="input js-abv-title" value=""></div>
        <div class="form-row"><label>Summary (HTML)</label><textarea class="input input--textarea js-abv-sum" rows="3"></textarea></div>
        <button type="button" class="btn btn--ghost ab-row-remove">Remove</button>
    </div>
</template>

<template id="ab-tpl-member">
    <div class="hb-repeat-row ab-repeat-row js-ab-mem-row">
        <div class="home-blocks-editor__grid home-blocks-editor__grid--2">
            <div class="form-row" style="margin:0;"><label>Name</label><input type="text" class="input js-abm-name" value=""></div>
            <div class="form-row" style="margin:0;"><label>Role</label><input type="text" class="input js-abm-role" value=""></div>
        </div>
        <div class="form-row"><label>Photo path</label><input type="text" class="input js-abm-photo" value=""></div>
        <div class="form-row"><label>Bio (HTML)</label><textarea class="input input--textarea js-abm-bio" rows="2"></textarea></div>
        <button type="button" class="btn btn--ghost ab-row-remove">Remove</button>
    </div>
</template>
