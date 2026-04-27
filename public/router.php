<?php

declare(strict_types=1);

/**
 * Dev server router (PHP built-in server):
 *   cd public && php -S localhost:8080 router.php
 * Serves static files; everything else goes to index.php.
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');
if ($uri !== '/' && is_file(__DIR__ . $uri)) {
    return false;
}

require __DIR__ . '/index.php';
