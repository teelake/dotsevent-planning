<?php
declare(strict_types=1);
/**
 * @var list<array<string, mixed>> $products
 * @var bool                       $db_ready
 * @var array<string, mixed>       $rentals_blocks
 * @var array<string, mixed>       $cms
 */
$products       = $products       ?? [];
$db_ready       = $db_ready       ?? false;
$rentals_blocks = $rentals_blocks ?? [];
$cms            = $cms            ?? [];

// page-hero partial vars are set by the controller
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<?php if (!empty($cms['has_custom_body'])): ?>
<div class="shell shell--wide page-pad prose cms-page-body page-rentals__cms-body" data-reveal>
<?= $cms['body_html'] ?>
</div>
<?php endif; ?>
<?php
include __DIR__ . '/partials/catalog-structured.php';
