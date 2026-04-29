<?php

declare(strict_types=1);

namespace App\Services;

final class ContactPageBlocks
{
    /**
     * @param mixed $stored
     * @return array<string, mixed>
     */
    public static function merged($stored): array
    {
        $path = dirname(__DIR__, 2) . '/config/contact_blocks.php';
        $defaults = is_file($path) ? (require $path) : [];
        if (!is_array($defaults)) {
            $defaults = [];
        }

        if (!is_array($stored) || $stored === []) {
            return self::finalize($defaults);
        }

        $out = array_replace_recursive($defaults, $stored);
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

        if (isset($merged['newsletter_cta']['description_html']) && is_string($merged['newsletter_cta']['description_html'])) {
            $merged['newsletter_cta']['description_html'] = CmsHtmlSanitizer::sanitize($merged['newsletter_cta']['description_html']);
        }
        if (isset($merged['newsletter_cta']['privacy_note_html']) && is_string($merged['newsletter_cta']['privacy_note_html'])) {
            $merged['newsletter_cta']['privacy_note_html'] = CmsHtmlSanitizer::sanitize($merged['newsletter_cta']['privacy_note_html']);
        }

        if (isset($merged['trust']['star_count'])) {
            $n = (int) $merged['trust']['star_count'];
            $merged['trust']['star_count'] = max(1, min(8, $n));
        } else {
            $merged['trust']['star_count'] = 5;
        }

        return $merged;
    }
}

