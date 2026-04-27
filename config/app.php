<?php

declare(strict_types=1);

return [
    'name' => 'DOTS Event Planning',
    // Staging path on live host (review vs current site). Use '' for local dev at site root.
    'base_url' => '/new',
    'debug' => true,
    // Canada: Square location + payments should use CAD.
    'currency' => 'CAD',
    'currency_label' => 'CA$',
    // Absolute path (written by app/bootstrap). Server must allow writing to logs/.
    'error_log' => dirname(__DIR__) . '/logs/php-error.log',
];
