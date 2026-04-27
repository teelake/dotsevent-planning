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
 * Safe redirect targets after add-to-cart (must stay under base_url, e.g. /new).
 */
/**
 * URL-safe slug from a title (a-z0-9 hyphens).
 */
function slugify(string $s): string
{
    $s = trim($s);
    $s = function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s);
    $s = preg_replace('~[^\pL\pN]+~u', '-', $s) ?? '';
    $s = trim((string) $s, '-');
    if ($s === '') {
        $s = 'item';
    }
    if (strlen($s) > 150) {
        $s = substr($s, 0, 150);
    }
    return $s;
}

function allowed_return(string $url): string
{
    if ($url === '' || str_contains($url, '://') || str_starts_with($url, '//')) {
        return app_url('rentals');
    }
    if (!str_starts_with($url, '/')) {
        $url = '/' . $url;
    }
    $rentals = app_url('rentals');
    if ($url === $rentals || str_starts_with($url, $rentals . '/')) {
        return $url;
    }
    $productPrefix = app_url('product/');
    if (str_starts_with($url, $productPrefix)) {
        return $url;
    }
    return app_url('rentals');
}
