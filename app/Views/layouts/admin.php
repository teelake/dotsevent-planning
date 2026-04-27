<?php
declare(strict_types=1);
/** @var string $content */
/** @var string $title */
$siteName = $app['name'] ?? 'DOTS Event Planning';
$t = $title ?? 'Admin';
$pageTitle = e($t) . ' | ' . e($siteName) . ' Admin';
$bodyClass = trim((string) ($bodyClass ?? ''));
$adminEmail = current_admin_user_email();
$logoPath = trim(site_setting('logo_path', 'assets/images/logo-dots.svg'));
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
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="page-admin<?= $adminAuthed ? ' page-admin--authed' : ' page-admin--login' ?><?= $bodyClass !== '' ? ' ' . e($bodyClass) : '' ?>">
<a class="skip-link" href="#admin-main">Skip to content</a>

<?php if (!$adminAuthed): ?>
<main id="admin-main" class="admin-login" tabindex="-1">
    <div class="admin-login__inner">
        <a class="admin-login__logo" href="<?= e(app_url('')) ?>" aria-label="<?= e($siteName) ?> home">
            <img src="<?= e(app_url(ltrim($logoPath, '/'))) ?>" alt="" width="40" height="40" class="admin-login__logo-img">
        </a>
        <h1 class="admin-login__heading">Welcome back</h1>
        <p class="admin-login__tagline">Sign in to <?= e($siteName) ?> — staff access only.</p>

        <div class="admin-login__card">
        <?php
        $flashErr = \App\Core\Flash::get(\App\Core\Flash::ERROR);
        $flashOk = \App\Core\Flash::get(\App\Core\Flash::SUCCESS);
        $flashNote = \App\Core\Flash::get(\App\Core\Flash::NOTICE);
        ?>
        <?php if ($flashErr !== null): ?><div class="admin-login__flash admin-login__flash--error" role="alert"><?= e($flashErr) ?></div><?php endif; ?>
        <?php if ($flashOk !== null): ?><div class="admin-login__flash admin-login__flash--success" role="status"><?= e($flashOk) ?></div><?php endif; ?>
        <?php if ($flashNote !== null): ?><div class="admin-login__flash admin-login__flash--notice" role="status"><?= e($flashNote) ?></div><?php endif; ?>
        <?= $content ?>
        </div>

        <p class="admin-login__hint">Need an account? Contact your <strong>site administrator</strong>.</p>
        <p class="admin-login__public">
            <a class="admin-login__public-link" href="<?= e(app_url('')) ?>">View public website</a>
        </p>
    </div>
</main>
<?php else: ?>
<div class="admin-app">
    <aside class="admin-sidebar" id="admin-sidebar" data-admin-sidebar aria-label="Admin navigation">
        <div class="admin-sidebar__brand">
            <p class="admin-sidebar__brand-name"><?= e($siteName) ?></p>
            <p class="admin-sidebar__brand-sub">Control panel</p>
        </div>
        <nav class="admin-sidebar__nav" aria-label="Main">
            <a class="admin-sidebar__link<?= $activeAdminNav === 'dashboard' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/dashboard')) ?>"<?= $activeAdminNav === 'dashboard' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M4 10.5L12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-9.5z"/></svg>
                Dashboard
            </a>
            <a class="admin-sidebar__link<?= $activeAdminNav === 'analytics' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/analytics')) ?>"<?= $activeAdminNav === 'analytics' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M4 20V10M10 20V4M16 20v-6M22 20V14"/></svg>
                Analytics
            </a>
            <a class="admin-sidebar__link<?= $activeAdminNav === 'cms' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/cms')) ?>"<?= $activeAdminNav === 'cms' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M7 3h10a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2z"/><path d="M9 7h6M9 11h6"/></svg>
                CMS
            </a>
            <a class="admin-sidebar__link<?= $activeAdminNav === 'cms-slides' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/cms/slides')) ?>"<?= $activeAdminNav === 'cms-slides' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h6M7 13h4"/></svg>
                Hero carousel
            </a>
            <a class="admin-sidebar__link<?= $activeAdminNav === 'products' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/products')) ?>"<?= $activeAdminNav === 'products' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M6 8h12l1 3H5l1-3zM6 8V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/><path d="M5 11h14v8a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-8z"/></svg>
                Products
            </a>
            <a class="admin-sidebar__link<?= $activeAdminNav === 'leads' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/leads')) ?>"<?= $activeAdminNav === 'leads' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M16 11a4 4 0 1 0-8 0M4 20a8 8 0 0 1 16 0"/></svg>
                Leads
            </a>
            <a class="admin-sidebar__link<?= $activeAdminNav === 'orders' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/orders')) ?>"<?= $activeAdminNav === 'orders' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M9 2h6l1 2H8l1-2zM6 6h12l-1.5 9.2A2 2 0 0 1 15.5 17H8.4A2 2 0 0 1 6.9 15.2L4.2 4H2"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                Orders
            </a>
            <p class="admin-sidebar__section-label" style="margin:1.25rem 0 0.35rem;padding:0 0.75rem;font-size:0.7rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--color-ink-soft);">Account</p>
            <a class="admin-sidebar__link<?= $activeAdminNav === 'profile' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/profile')) ?>"<?= $activeAdminNav === 'profile' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c1.5-4 6.5-5 8-5s6.5 1 8 5"/></svg>
                Profile
            </a>
            <a class="admin-sidebar__link<?= $activeAdminNav === 'password' ? ' is-active' : '' ?>" href="<?= e(app_url('admin/password')) ?>"<?= $activeAdminNav === 'password' ? ' aria-current="page"' : '' ?>>
                <svg class="admin-sidebar__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                Password
            </a>
        </nav>
    </aside>
    <button type="button" class="admin-backdrop" data-admin-backdrop tabindex="-1" aria-label="Close menu"></button>
    <div class="admin-app__main">
        <header class="admin-topbar">
            <button type="button" class="admin-sidebar-toggle" data-admin-sidebar-toggle aria-controls="admin-sidebar" aria-expanded="false" aria-label="Open navigation">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="admin-topbar__title"><?= e($t) ?></h1>
            <div class="admin-user-menu" data-admin-user-menu>
                <button type="button" class="admin-user-menu__btn" id="admin-user-menu-btn" aria-expanded="false" aria-haspopup="true" aria-controls="admin-user-menu-panel" aria-label="Account menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                </button>
                <div class="admin-user-menu__panel" id="admin-user-menu-panel" role="menu" hidden>
                    <p class="admin-user-menu__email"><?= e($adminEmail !== '' ? $adminEmail : 'Signed in') ?></p>
                    <a class="admin-user-menu__action" role="menuitem" href="<?= e(app_url('admin/profile')) ?>">Profile</a>
                    <a class="admin-user-menu__action" role="menuitem" href="<?= e(app_url('admin/password')) ?>">Password</a>
                    <a class="admin-user-menu__action" role="menuitem" href="<?= e(app_url('')) ?>">View site</a>
                    <a class="admin-user-menu__action admin-user-menu__action--danger" role="menuitem" href="<?= e(app_url('admin/logout')) ?>">Sign out</a>
                </div>
            </div>
        </header>
        <main id="admin-main" class="admin-content" tabindex="-1">
        <?php
        $flashErr = \App\Core\Flash::get(\App\Core\Flash::ERROR);
        $flashOk = \App\Core\Flash::get(\App\Core\Flash::SUCCESS);
        $flashNote = \App\Core\Flash::get(\App\Core\Flash::NOTICE);
        ?>
        <?php if ($flashErr !== null): ?><div class="flash flash--error" role="alert" style="margin-bottom:1rem;"><?= e($flashErr) ?></div><?php endif; ?>
        <?php if ($flashOk !== null): ?><div class="flash flash--success" role="status" style="margin-bottom:1rem;"><?= e($flashOk) ?></div><?php endif; ?>
        <?php if ($flashNote !== null): ?><div class="flash flash--notice" role="status" style="margin-bottom:1rem;"><?= e($flashNote) ?></div><?php endif; ?>
        <?= $content ?>
        </main>
    </div>
</div>
<script src="<?= e(asset('js/admin.js')) ?>" defer></script>
<?php endif; ?>
</body>
</html>
