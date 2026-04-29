<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$about_blocks = isset($cms['about_blocks']) && is_array($cms['about_blocks'])
    ? $cms['about_blocks']
    : \App\Services\AboutPageBlocks::merged(null);

$hero = is_array($about_blocks['hero'] ?? null) ? $about_blocks['hero'] : [];
$page_title = (string) ($cms['doc_title'] ?? 'About us');
$hEnabled = (($hero['enabled'] ?? true) !== false);
if ($hEnabled && trim((string) ($hero['title'] ?? '')) !== '') {
    $page_title = trim((string) $hero['title']);
}
$crumb_current = $page_title;
$hero_kicker = trim((string) ($hero['kicker'] ?? ''));
$show_breadcrumbs = !isset($hero['show_breadcrumbs']) || $hero['show_breadcrumbs'] !== false;

include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <div class="app-shell__main">
        <?php include __DIR__ . '/partials/about-structured.php'; ?>
    </div>
</div>
