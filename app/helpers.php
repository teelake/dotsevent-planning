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
 * CMS setting, falling back to config/app.php. Returns string (possibly empty).
 * Requires DB configured; otherwise always falls back.
 */
function site_setting(string $key, string $fallback = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            $repo = new \App\Models\CmsSettingsRepository();
            $cache = $repo->all();
        } catch (\Throwable) {
            $cache = [];
        }
    }
    if (array_key_exists($key, $cache)) {
        return (string) ($cache[$key] ?? '');
    }
    $cfg = app_config();
    if (array_key_exists($key, $cfg)) {
        return (string) ($cfg[$key] ?? '');
    }
    return $fallback;
}

/** Public site map embed URL (Google maps output=embed). */
function site_map_embed_url(): string
{
    $u = trim(site_setting('map_embed_url', ''));
    return $u !== '' ? $u : 'https://maps.google.com/maps?q=Saint+John%2C+New+Brunswick%2C+Canada&z=12&output=embed';
}

/**
 * @return array{facebook: string, instagram: string, youtube: string}
 */
function site_social_urls(): array
{
    $c = app_config();
    return [
        'facebook' => trim(site_setting('social_facebook', (string) ($c['social_facebook'] ?? ''))),
        'instagram' => trim(site_setting('social_instagram', (string) ($c['social_instagram'] ?? ''))),
        'youtube' => trim(site_setting('social_youtube', (string) ($c['social_youtube'] ?? ''))),
    ];
}

/** Scheme + host, no trailing slash, for sharing meta tags. */
function site_public_origin(): string
{
    return rtrim((string) (app_config()['public_origin'] ?? ''), "/ \t");
}

/**
 * Absolute URL to a file under /public/ (e.g. assets/og.jpg). Returns '' if public_origin is not set.
 */
function absolute_public_url(string $pathUnderPublic): string
{
    $origin = site_public_origin();
    if ($origin === '') {
        return '';
    }
    $p = app_url(ltrim($pathUnderPublic, '/'));
    return $origin . $p;
}

/**
 * Current request as absolute URL (requires public_origin). Query string included if present.
 * Uses the same path the browser requested (including base_url prefix, e.g. /new/…).
 */
function current_canonical_url(): string
{
    $origin = site_public_origin();
    if ($origin === '') {
        return '';
    }
    $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
    $path = (string) (parse_url($uri, PHP_URL_PATH) ?? '/');
    $query = parse_url($uri, PHP_URL_QUERY);
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . ltrim($path, '/');
    }
    $out = $origin . $path;
    if (is_string($query) && $query !== '') {
        $out .= '?' . $query;
    }
    return $out;
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

/**
 * Email of the currently signed-in admin, or empty string.
 */
function current_admin_user_email(): string
{
    $id = \App\Core\AdminAuth::id();
    if ($id === null) {
        return '';
    }
    $u = (new \App\Models\UserRepository())->findById($id);
    return $u !== null ? trim((string) ($u['email'] ?? '')) : '';
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

/**
 * CMS page content for public routes (sanitized HTML + optional title/meta).
 *
 * @return array{
 *   has_custom_body: bool,
 *   body_html: string,
 *   doc_title: string,
 *   meta_description: string
 * }
 */
function cms_public_page(string $slug, string $defaultTitle, string $defaultMeta): array
{
    return \App\Services\CmsPublicPage::page($slug, $defaultTitle, $defaultMeta);
}

/**
 * Home hero + intro from CMS (optional slides JSON + HTML intro).
 *
 * @param list<array<string, string>> $defaultSlides
 * @return array{
 *   slides: list<array<string, string>>,
 *   intro_html: string,
 *   meta_description: string
 * }
 */
function cms_public_home(array $defaultSlides, string $defaultMeta): array
{
    return \App\Services\CmsPublicPage::home($defaultSlides, $defaultMeta);
}
