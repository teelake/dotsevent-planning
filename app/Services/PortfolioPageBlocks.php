<?php

declare(strict_types=1);

namespace App\Services;

final class PortfolioPageBlocks
{
    /**
     * @param mixed $stored
     * @return array<string, mixed>
     */
    public static function merged($stored): array
    {
        $path = dirname(__DIR__, 2) . '/config/portfolio_blocks.php';
        $defaults = is_file($path) ? (require $path) : [];
        if (!is_array($defaults)) {
            $defaults = [];
        }

        if (!is_array($stored) || $stored === []) {
            return self::finalize($defaults);
        }

        $out = array_replace_recursive($defaults, $stored);

        // Full list replace for repeaters
        if (!empty($stored['featured']) && is_array($stored['featured']) && isset($stored['featured']['items']) && is_array($stored['featured']['items'])) {
            $out['featured']['items'] = $stored['featured']['items'];
        }
        if (!empty($stored['gallery']) && is_array($stored['gallery']) && isset($stored['gallery']['items']) && is_array($stored['gallery']['items'])) {
            $out['gallery']['items'] = $stored['gallery']['items'];
        }
        if (!empty($stored['controls']) && is_array($stored['controls']) && isset($stored['controls']['filters']) && is_array($stored['controls']['filters'])) {
            $out['controls']['filters'] = $stored['controls']['filters'];
        }

        return self::finalize($out);
    }

    /**
     * @param array<string, mixed> $merged
     * @return array<string, mixed>
     */
    private static function finalize(array $merged): array
    {
        if (isset($merged['intro']['lead_html']) && is_string($merged['intro']['lead_html'])) {
            $merged['intro']['lead_html'] = CmsHtmlSanitizer::sanitize($merged['intro']['lead_html']);
        }
        if (isset($merged['newsletter_cta']['text_html']) && is_string($merged['newsletter_cta']['text_html'])) {
            $merged['newsletter_cta']['text_html'] = CmsHtmlSanitizer::sanitize($merged['newsletter_cta']['text_html']);
        }

        foreach (['featured', 'gallery'] as $sectionKey) {
            if (!isset($merged[$sectionKey]['items']) || !is_array($merged[$sectionKey]['items'])) {
                $merged[$sectionKey]['items'] = [];
                continue;
            }
            foreach ($merged[$sectionKey]['items'] as $i => $item) {
                if (!is_array($item)) {
                    $merged[$sectionKey]['items'][$i] = [];
                    continue;
                }
                $img = trim((string) ($item['image_path'] ?? ''));
                if ($img !== '' && !str_starts_with($img, 'http://') && !str_starts_with($img, 'https://') && !str_starts_with($img, '/')) {
                    $merged[$sectionKey]['items'][$i]['image_path'] = '/' . ltrim($img, '/');
                }
            }
        }

        return $merged;
    }
}

