<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$portfolio_blocks = isset($cms['portfolio_blocks']) && is_array($cms['portfolio_blocks'])
    ? $cms['portfolio_blocks']
    : \App\Services\PortfolioPageBlocks::merged(null);

$hero = is_array($portfolio_blocks['hero'] ?? null) ? $portfolio_blocks['hero'] : [];
$page_title = (string) ($cms['doc_title'] ?? 'Portfolio');
$crumb_current = $page_title;
$hero_kicker = trim((string) ($hero['kicker'] ?? ''));
$show_breadcrumbs = !isset($hero['show_breadcrumbs']) || $hero['show_breadcrumbs'] !== false;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <div class="app-shell__main">
        <div class="shell shell--wide page-pad" data-reveal>
            <?php include __DIR__ . '/partials/portfolio-structured.php'; ?>
        </div>
    </div>
</div>

