<?php
declare(strict_types=1);
/** @var string $content */
/** @var string $title */
$siteName = $app['name'] ?? 'DOTS Event Planning';
$t = $title ?? 'Admin';
$pageTitle = e($t) . ' | ' . e($siteName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/layout.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/pages.css')) ?>">
    <style>
        .admin-bar { background: var(--color-brand-black); color: #e7e2db; padding: 0.65rem var(--space-lg); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; }
        .admin-bar a { color: var(--color-gold-light); text-decoration: none; font-weight: 600; font-size: 0.95rem; }
        .admin-bar a:hover { text-decoration: underline; color: #fff; }
        .admin-nav { display: flex; flex-wrap: wrap; gap: 0.5rem 1rem; align-items: center; }
        .admin-main { padding: var(--space-xl) 0 var(--space-2xl); min-height: 50vh; }
        .admin-table { width: 100%; border-collapse: collapse; font-size: 0.92rem; background: var(--color-surface); border: 1px solid var(--color-line); border-radius: var(--radius-md); overflow: hidden; }
        .admin-table th, .admin-table td { padding: 0.6rem 0.75rem; text-align: left; border-bottom: 1px solid var(--color-line); vertical-align: top; }
        .admin-table th { background: #f3efe8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-ink-soft); }
        .admin-table tr:last-child td { border-bottom: 0; }
        .admin-form .form-row { margin-bottom: 1rem; }
        .admin-form label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.25rem; }
        .admin-form .input { max-width: 100%; width: 100%; }
        .admin-grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: var(--space-xl); }
        .admin-stat { background: var(--color-surface); border: 1px solid var(--color-line); border-radius: var(--radius-md); padding: 1rem 1.1rem; }
        .admin-stat__v { font-family: var(--font-display); font-size: 1.75rem; font-weight: 600; color: var(--color-ink); }
        .admin-stat__l { font-size: 0.78rem; color: var(--color-ink-soft); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 0.2rem; }
    </style>
</head>
<body class="page-admin">
<a class="skip-link" href="#admin-main">Skip to content</a>
<header class="admin-bar">
    <span><strong><?= e($siteName) ?></strong> — Admin</span>
    <nav class="admin-nav" aria-label="Admin">
        <?php if (!empty($admin_authed)): ?>
        <a href="<?= e(app_url('admin/dashboard')) ?>">Dashboard</a>
        <a href="<?= e(app_url('admin/products')) ?>">Products</a>
        <a href="<?= e(app_url('admin/leads')) ?>">Leads</a>
        <a href="<?= e(app_url('admin/orders')) ?>">Orders</a>
        <a href="<?= e(app_url('admin/logout')) ?>">Sign out</a>
        <?php endif; ?>
        <a href="<?= e(app_url('')) ?>">View site</a>
    </nav>
</header>
<main id="admin-main" class="shell admin-main" tabindex="-1">
    <?php
    $flashErr = \App\Core\Flash::get(\App\Core\Flash::ERROR);
    $flashOk = \App\Core\Flash::get(\App\Core\Flash::SUCCESS);
    $flashNote = \App\Core\Flash::get(\App\Core\Flash::NOTICE);
    ?>
    <?php if ($flashErr !== null): ?>
        <div class="flash flash--error" role="alert"><?= e($flashErr) ?></div>
    <?php endif; ?>
    <?php if ($flashOk !== null): ?>
        <div class="flash flash--success" role="status"><?= e($flashOk) ?></div>
    <?php endif; ?>
    <?php if ($flashNote !== null): ?>
        <div class="flash flash--notice" role="status"><?= e($flashNote) ?></div>
    <?php endif; ?>
    <?= $content ?>
</main>
</body>
</html>
