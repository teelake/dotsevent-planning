<?php
declare(strict_types=1);
/** @var string $page_title */
/** @var string $crumb_current */
$hero_kicker = $hero_kicker ?? '';
$show_breadcrumbs = $show_breadcrumbs ?? true;
$hero_subtitle = trim((string) ($hero_subtitle ?? ''));
$hero_primary_cta_label = trim((string) ($hero_primary_cta_label ?? ''));
$hero_primary_cta_href = trim((string) ($hero_primary_cta_href ?? ''));
$hero_secondary_cta_label = trim((string) ($hero_secondary_cta_label ?? ''));
$hero_secondary_cta_href = trim((string) ($hero_secondary_cta_href ?? ''));
$hero_bg_url = trim((string) ($hero_bg_url ?? ''));
$heroWrapClass = 'app-page-hero app-page-hero--inner';
if ($hero_bg_url !== '') {
    $heroWrapClass .= ' app-page-hero--photo';
}
$heroPhotoStyle = '';
if ($hero_bg_url !== '') {
    $u = str_replace(['"', "'", '(', ')', '\\', "\n", "\r"], '', $hero_bg_url);
    $heroPhotoStyle = '--page-hero-photo: url("' . $u . '");';
}
?>
<div class="<?= e($heroWrapClass) ?>" data-reveal<?= $heroPhotoStyle !== '' ? ' style="' . e($heroPhotoStyle) . '"' : '' ?>>
    <div class="shell shell--wide">
        <?php if (!empty($show_breadcrumbs)): ?>
        <nav class="app-page-hero__crumb" aria-label="Breadcrumb">
            <a href="<?= e(app_url('')) ?>">Home</a>
            <span class="app-page-hero__sep" aria-hidden="true">/</span>
            <span><?= e($crumb_current) ?></span>
        </nav>
        <?php endif; ?>
        <?php if (trim((string) $hero_kicker) !== ''): ?>
        <p class="eyebrow app-page-hero__kicker"><?= e($hero_kicker) ?></p>
        <?php endif; ?>
        <h1 class="app-page-hero__title"><?= e($page_title) ?></h1>
        <?php if ($hero_subtitle !== ''): ?>
        <p class="app-page-hero__lead"><?= e($hero_subtitle) ?></p>
        <?php endif; ?>
        <?php if (($hero_primary_cta_label !== '' && $hero_primary_cta_href !== '')
            || ($hero_secondary_cta_label !== '' && $hero_secondary_cta_href !== '')): ?>
        <div class="app-page-hero__actions">
            <?php if ($hero_primary_cta_label !== '' && $hero_primary_cta_href !== ''): ?>
            <a class="btn btn--primary app-page-hero__cta app-page-hero__cta--primary" href="<?= e($hero_primary_cta_href) ?>"><?= e($hero_primary_cta_label) ?></a>
            <?php endif; ?>
            <?php if ($hero_secondary_cta_label !== '' && $hero_secondary_cta_href !== ''): ?>
            <a class="btn btn--secondary app-page-hero__cta app-page-hero__cta--ghost" href="<?= e($hero_secondary_cta_href) ?>"><?= e($hero_secondary_cta_label) ?></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
