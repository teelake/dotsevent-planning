<?php

declare(strict_types=1);

namespace App\Services;

final class RentalsPageBlocks
{
    /**
     * @param mixed $stored  Raw blocks array from cms_pages.content_json.blocks (or null)
     * @return array<string, mixed>
     */
    public static function merged($stored): array
    {
        $path = dirname(__DIR__, 2) . '/config/rentals_blocks.php';
        $defaults = is_file($path) ? (require $path) : [];
        if (!is_array($defaults)) {
            $defaults = [];
        }

        if (!is_array($stored) || $stored === []) {
            return self::finalize($defaults);
        }

        $out = array_replace_recursive($defaults, $stored);

        // Full list replacement for repeaters so stored arrays don't partially merge with defaults
        foreach (['categories.items', 'how_it_works.steps', 'logistics.items', 'controls.sort_options'] as $dotPath) {
            [$section, $key] = explode('.', $dotPath);
            if (
                isset($stored[$section][$key]) &&
                is_array($stored[$section][$key])
            ) {
                $out[$section][$key] = $stored[$section][$key];
            }
        }

        return self::finalize($out);
    }

    /**
     * @param array<string, mixed> $merged
     * @return array<string, mixed>
     */
    private static function finalize(array $merged): array
    {
        if (isset($merged['newsletter_cta']['text_html']) && is_string($merged['newsletter_cta']['text_html'])) {
            $merged['newsletter_cta']['text_html'] = CmsHtmlSanitizer::sanitize($merged['newsletter_cta']['text_html']);
        }

        if (isset($merged['hero']['bg_image_path'])) {
            $img = trim((string) $merged['hero']['bg_image_path']);
            if ($img !== '' && !str_starts_with($img, 'http://') && !str_starts_with($img, 'https://') && !str_starts_with($img, '/')) {
                $merged['hero']['bg_image_path'] = '/' . ltrim($img, '/');
            }
        }

        foreach (['cta_primary_href', 'cta_secondary_href'] as $hrefKey) {
            if (!isset($merged['hero'][$hrefKey]) || !is_string($merged['hero'][$hrefKey])) {
                continue;
            }
            $h = trim((string) $merged['hero'][$hrefKey]);
            if ($h !== '' && !self::safeRentalHeroHref($h)) {
                $merged['hero'][$hrefKey] = '';
            }
        }

        return $merged;
    }

    private static function safeRentalHeroHref(string $href): bool
    {
        if (stripos($href, 'javascript:') === 0 || stripos($href, 'data:') === 0) {
            return false;
        }
        if (str_starts_with($href, '#')) {
            return strlen($href) <= 200;
        }
        if (str_starts_with($href, '/') && !str_starts_with($href, '//')) {
            return !str_contains($href, '..');
        }

        return preg_match('#^https?://#i', $href) === 1;
    }
}
