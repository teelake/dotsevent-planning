<?php

declare(strict_types=1);

function app_config(): array
{
    static $config;
    if ($config === null) {
        $config = require dirname(__DIR__) . '/config/app.php';
    }
    return is_array($config) ? $config : [];
}

function app_url(string $path = ''): string
{
    $base = rtrim((string) (app_config()['base_url'] ?? ''), '/');
    $p = ltrim($path, '/');
    if ($base === '') {
        return $p === '' ? '/' : '/' . $p;
    }
    return $p === '' ? $base . '/' : $base . '/' . $p;
}

function e(?string $s): string
{
    return $s === null ? '' : htmlspecialchars($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function asset(string $path): string
{
    return app_url('assets/' . ltrim($path, '/'));
}
