<?php
declare(strict_types=1);
/** @var string $page_title */
/** @var string $crumb_current */
?>
<header class="page-hero">
    <div class="shell" data-reveal>
        <nav class="page-hero__crumb" aria-label="Breadcrumb">
            <a href="<?= e(app_url('')) ?>">Home</a>
            <span aria-hidden="true"> / </span>
            <span><?= e($crumb_current) ?></span>
        </nav>
        <h1 class="page-hero__title"><?= e($page_title) ?></h1>
    </div>
</header>
