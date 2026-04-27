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

/**
 * @return array<string, mixed>|null
 */
function square_config(): ?array
{
    $p = dirname(__DIR__) . '/config/square.php';
    if (!is_file($p)) {
        return null;
    }
    $c = require $p;
    return is_array($c) ? $c : null;
}

function cart_count(): int
{
    return \App\Core\Cart::count();
}

/**
 * @param 'CAD'|'USD' $code
 */
function money_format_cents(int $cents, string $code = 'CAD'): string
{
    $dollars = $cents / 100;
    $label = (string) (app_config()['currency_label'] ?? '$');
    return $label . number_format($dollars, 2, '.', ',');
}

function csrf_field(): string
{
    $t = \App\Core\Csrf::token();
    return '<input type="hidden" name="_csrf" value="' . e($t) . '">';
}

/**
 * @param list<string> $safePrefixes
 */
function allowed_return(string $url, array $safePrefixes = ['/rentals', '/product/']): string
{
    $u = $url;
    if ($u === '' || str_contains($u, '://') || str_starts_with($u, '//')) {
        return '/rentals';
    }
    if (!str_starts_with($u, '/')) {
        $u = '/' . $u;
    }
    foreach ($safePrefixes as $p) {
        if (str_starts_with($u, $p)) {
            return $u;
        }
    }
    return '/rentals';
}
