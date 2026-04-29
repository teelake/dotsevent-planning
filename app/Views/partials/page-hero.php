<?php
declare(strict_types=1);
/** @var string $page_title */
/** @var string $crumb_current */
$hero_kicker = $hero_kicker ?? '';
$show_breadcrumbs = $show_breadcrumbs ?? true;
?>
<div class="app-page-hero app-page-hero--inner" data-reveal>
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
    </div>
</div>
