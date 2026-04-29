<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$contact_blocks = isset($cms['contact_blocks']) && is_array($cms['contact_blocks'])
    ? $cms['contact_blocks']
    : \App\Services\ContactPageBlocks::merged(null);

$hero = is_array($contact_blocks['hero'] ?? null) ? $contact_blocks['hero'] : [];
$page_title = (string) ($cms['doc_title'] ?? 'Contact us');
$crumb_current = $page_title;
$hero_kicker = trim((string) ($hero['kicker'] ?? ''));
$show_breadcrumbs = !isset($hero['show_breadcrumbs']) || $hero['show_breadcrumbs'] !== false;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <div class="app-shell__main">
        <div class="shell shell--wide page-pad" data-reveal>
            <?php include __DIR__ . '/partials/contact-structured.php'; ?>
        </div>
    </div>
</div>

