<?php
declare(strict_types=1);
/**
 * @var list<array<string, mixed>> $products
 * @var bool                       $db_ready
 * @var array<string, mixed>       $rentals_blocks
 */
$products       = $products       ?? [];
$db_ready       = $db_ready       ?? false;
$rentals_blocks = $rentals_blocks ?? [];

// page-hero partial vars are set by the controller
include dirname(__DIR__) . '/partials/page-hero.php';
include __DIR__ . '/partials/catalog-structured.php';
