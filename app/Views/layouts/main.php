<?php
declare(strict_types=1);
/** @var string $content */
/** @var string $title */
/** @var string $activeNav */
/** @var string $bodyClass */
/** @var string $extraHeader */
/** @var array $app */
/** @var string $metaDescription */
/** @var string $metaOgType */
$bodyClass = $bodyClass ?? '';
$extraHeader = $extraHeader ?? '';
$extraFooter = $extraFooter ?? '';
$activeNav = $activeNav ?? '';
$siteName = $app['name'] ?? 'DOTS Event Planning';
$t = $title ?? 'Home';
$pageTitlePlain = ($t === '' || $t === 'Home') ? $siteName : $t . ' | ' . $siteName;
$pageTitle = e($pageTitlePlain);
$metaDescription = $metaDescription ?? (string) ($app['meta_description'] ?? '');
$metaOgType = $metaOgType ?? 'website';
$canonicalUrl = current_canonical_url();
$ogImagePath = trim((string) ($app['og_image'] ?? ''));
$ogImageAbsolute = $ogImagePath !== '' ? absolute_public_url($ogImagePath) : '';
$faviconPath = trim(site_setting('favicon_path', ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#e8e2d9" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0c0b09" media="(prefers-color-scheme: dark)">
    <title><?= $pageTitle ?></title>
    <?php if ($faviconPath !== ''): ?>
    <link rel="icon" href="<?= e(app_url(ltrim($faviconPath, '/'))) ?>">
    <?php endif; ?>
    <?php if ($metaDescription !== ''): ?>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta property="og:type" content="<?= e($metaOgType) ?>">
    <meta property="og:title" content="<?= e($pageTitlePlain) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta name="twitter:card" content="<?= $ogImageAbsolute !== '' ? 'summary_large_image' : 'summary' ?>">
    <meta name="twitter:title" content="<?= e($pageTitlePlain) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <?php if ($canonicalUrl !== ''): ?>
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <?php endif; ?>
    <?php if ($ogImageAbsolute !== ''): ?>
    <meta property="og:image" content="<?= e($ogImageAbsolute) ?>">
    <meta name="twitter:image" content="<?= e($ogImageAbsolute) ?>">
    <?php endif; ?>
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/layout.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/pages.css')) ?>">
    <?= $extraHeader ?>
</head>
<body class="<?= e($bodyClass) ?>">
<a class="skip-link" href="#main">Skip to main content</a>
<?php include dirname(__DIR__) . '/partials/topbar.php'; ?>
<?php include dirname(__DIR__) . '/partials/drawer-nav.php'; ?>

<main id="main" class="app-main" tabindex="-1">
    <?php
    $flashErr = \App\Core\Flash::get(\App\Core\Flash::ERROR);
    $flashOk = \App\Core\Flash::get(\App\Core\Flash::SUCCESS);
    $flashNote = \App\Core\Flash::get(\App\Core\Flash::NOTICE);
    ?>
    <?php if ($flashErr !== null): ?>
        <div class="flash flash--error" role="alert" data-flash>
            <div class="flash__inner">
                <span class="flash__text"><?= e($flashErr) ?></span>
                <button type="button" class="flash__dismiss" aria-label="Dismiss message" data-flash-dismiss>&times;</button>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($flashOk !== null): ?>
        <div class="flash flash--success" role="status" data-flash>
            <div class="flash__inner">
                <span class="flash__text"><?= e($flashOk) ?></span>
                <button type="button" class="flash__dismiss" aria-label="Dismiss message" data-flash-dismiss>&times;</button>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($flashNote !== null): ?>
        <div class="flash flash--notice" role="status" data-flash>
            <div class="flash__inner">
                <span class="flash__text"><?= e($flashNote) ?></span>
                <button type="button" class="flash__dismiss" aria-label="Dismiss message" data-flash-dismiss>&times;</button>
            </div>
        </div>
    <?php endif; ?>
    <?= $content ?>
</main>

<?php include dirname(__DIR__) . '/partials/footer-status.php'; ?>

<script src="<?= e(asset('js/main.js')) ?>" defer></script>
<?= $extraFooter ?? '' ?>
</body>
</html>
