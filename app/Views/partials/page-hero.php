<?php
declare(strict_types=1);
/** @var string $page_title */
/** @var string $crumb_current */
?>
<div class="app-page-hero" data-reveal>
    <div class="shell shell--wide">
        <nav class="app-page-hero__crumb" aria-label="Breadcrumb">
            <a href="<?= e(app_url('')) ?>">Home</a>
            <span class="app-page-hero__sep" aria-hidden="true">/</span>
            <span><?= e($crumb_current) ?></span>
        </nav>
        <h1 class="app-page-hero__title"><?= e($page_title) ?></h1>
    </div>
</div>
