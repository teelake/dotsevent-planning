<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$services_blocks = isset($cms['services_blocks']) && is_array($cms['services_blocks'])
    ? $cms['services_blocks']
    : \App\Services\ServicesPageBlocks::merged(null);

$hero = is_array($services_blocks['hero'] ?? null) ? $services_blocks['hero'] : [];
$page_title = (string) ($cms['doc_title'] ?? 'Services');
$hEnabled = (($hero['enabled'] ?? true) !== false);
if ($hEnabled && trim((string) ($hero['title'] ?? '')) !== '') {
    $page_title = trim((string) $hero['title']);
}
$crumb_current = $page_title;
$hero_kicker = trim((string) ($hero['kicker'] ?? ''));
$show_breadcrumbs = ! isset($hero['show_breadcrumbs']) || $hero['show_breadcrumbs'] !== false;

include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <div class="app-shell__main">
        <?php if (!empty($cms['has_custom_body'])): ?>
        <div class="shell shell--wide page-pad prose cms-page-body" data-reveal>
        <?= $cms['body_html'] ?>
        </div>
        <?php endif; ?>
        <?php include __DIR__ . '/partials/services-structured.php'; ?>
    </div>
</div>
