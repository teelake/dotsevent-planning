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
$layoutSocial = site_social_urls();
$whatsappUrl = trim((string) ($layoutSocial['whatsapp'] ?? ''));
$whatsappIcon = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20.5 11.8a8.3 8.3 0 0 1-12.2 7.3L3.5 20.5l1.5-4.6a8.3 8.3 0 1 1 15.5-4.1z"/><path d="M8.8 8.1c.2-.5.4-.5.7-.5h.5c.2 0 .4.1.5.4l.7 1.7c.1.3 0 .5-.1.7l-.4.5c-.1.1-.2.3-.1.5.4.8 1 1.5 1.7 2 .5.3.9.5 1.1.6.2.1.4.1.5-.1l.8-.9c.2-.2.4-.2.6-.1l1.8.9c.3.1.4.3.4.5 0 .4-.3 1.2-.9 1.6-.5.4-1.1.5-1.8.4-1.1-.2-2.5-.8-4-2.1-1.8-1.6-2.8-3.5-3-4.6-.1-.5 0-1 .2-1.5z"/></svg>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#010101" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#010101" media="(prefers-color-scheme: dark)">
    <title><?= $pageTitle ?></title>
    <?php if ($faviconPath !== ''): ?>
    <link rel="icon" href="<?= e(public_file_url($faviconPath)) ?>">
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
    <link rel="stylesheet" href="<?= e(asset('css/app-shell.css')) ?>">
    <?= $extraHeader ?>
</head>
<body class="<?= e($bodyClass) ?>">
<a class="skip-link" href="#main">Skip to main content</a>
<?php include dirname(__DIR__) . '/partials/topbar.php'; ?>

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

<?php if ($whatsappUrl !== ''): ?>
<a class="floating-whatsapp" href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Chat with DOTS on WhatsApp">
    <?= $whatsappIcon ?>
    <span class="floating-whatsapp__label">Chat</span>
</a>
<?php endif; ?>

<script src="<?= e(asset('js/main.js')) ?>" defer></script>
<?= $extraFooter ?? '' ?>
</body>
</html>
