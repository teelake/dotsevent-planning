<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Merges cms_pages.about content_json.blocks with config defaults.
 */
final class AboutPageBlocks
{
    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        $path = dirname(__DIR__, 2) . '/config/about_blocks.php';
        if (!is_file($path)) {
            return [];
        }

        /** @var array<string, mixed> $d */
        $d = require $path;

        return is_array($d) ? $d : [];
    }

    /**
     * @param mixed $stored Blocks object from CMS (may be null)
     *
     * @return array<string, mixed>
     */
    public static function merged($stored): array
    {
        $defaults = self::defaults();
        if ($stored === null || $stored === [] || !is_array($stored)) {
            return self::finalize($defaults);
        }

        /** @var array<string, mixed> $s */
        $s = $stored;
        $out = array_replace_recursive($defaults, $s);

        unset($out['story']);

        if (!empty($s['approach']) && is_array($s['approach'])) {
            if (isset($s['approach']['images']) && is_array($s['approach']['images'])) {
                $out['approach']['images'] = $s['approach']['images'];
            }
        }
        if (!empty($s['values']) && is_array($s['values'])) {
            if (isset($s['values']['items']) && is_array($s['values']['items']) && $s['values']['items'] !== []) {
                $out['values']['items'] = $s['values']['items'];
            }
        }
        if (!empty($s['team']) && is_array($s['team'])) {
            if (isset($s['team']['members']) && is_array($s['team']['members']) && $s['team']['members'] !== []) {
                $out['team']['members'] = $s['team']['members'];
            }
        }

        return self::finalize($out);
    }

    /**
     * Collapse duplicated subdirectory prefixes and full URLs into stable paths for JSON storage.
     *
     * @param array<string, mixed> $blocks
     *
     * @return array<string, mixed>
     */
    public static function normalizeIncomingPathsForStorage(array $blocks): array
    {
        $b = $blocks;
        if (isset($b['approach']['images']) && is_array($b['approach']['images'])) {
            foreach ($b['approach']['images'] as $i => $img) {
                if (! is_array($img)) {
                    continue;
                }
                $src = isset($img['src']) ? (string) $img['src'] : '';
                $b['approach']['images'][$i]['src'] = canonical_upload_reference_for_storage($src);
            }
        }
        if (isset($b['team']['members']) && is_array($b['team']['members'])) {
            foreach ($b['team']['members'] as $i => $mem) {
                if (! is_array($mem)) {
                    continue;
                }
                $ph = isset($mem['photo']) ? (string) $mem['photo'] : '';
                $b['team']['members'][$i]['photo'] = canonical_upload_reference_for_storage($ph);
            }
        }

        return $b;
    }

    /**
     * @param array<string, mixed> $merged
     *
     * @return array<string, mixed>
     */
    private static function finalize(array $merged): array
    {
        unset($merged['story']);

        $ap = &$merged['approach'];
        if (isset($ap['lead_html']) && is_string($ap['lead_html'])) {
            $ap['lead_html'] = CmsHtmlSanitizer::sanitize($ap['lead_html']);
        }
        if (isset($ap['images']) && is_array($ap['images'])) {
            foreach ($ap['images'] as $j => $img) {
                if (!is_array($img)) {
                    continue;
                }
                $src = trim((string) ($img['src'] ?? ''));
                $merged['approach']['images'][$j]['src'] = $src !== '' ? canonical_upload_reference_for_storage($src) : '';
                $merged['approach']['images'][$j]['alt'] = isset($img['alt']) ? trim((string) $img['alt']) : '';
            }
        }

        $vals = &$merged['values'];
        if (isset($vals['items']) && is_array($vals['items'])) {
            foreach ($vals['items'] as $k => $vi) {
                if (!is_array($vi)) {
                    continue;
                }
                $sum = isset($vi['summary_html']) && is_string($vi['summary_html']) ? CmsHtmlSanitizer::sanitize($vi['summary_html']) : '';
                $merged['values']['items'][$k]['summary_html'] = $sum;
                $merged['values']['items'][$k]['title'] = isset($vi['title']) ? trim((string) $vi['title']) : '';
                $merged['values']['items'][$k]['subtitle'] = isset($vi['subtitle']) ? trim((string) $vi['subtitle']) : '';
                $merged['values']['items'][$k]['banner_tone'] = self::normalizeValueBannerTone($vi['banner_tone'] ?? '');
            }
        }

        $tm = &$merged['team'];
        if (isset($tm['intro_html']) && is_string($tm['intro_html'])) {
            $tm['intro_html'] = CmsHtmlSanitizer::sanitize($tm['intro_html']);
        }
        if (isset($tm['members']) && is_array($tm['members'])) {
            foreach ($tm['members'] as $mi => $mem) {
                if (!is_array($mem)) {
                    continue;
                }
                $ph = trim((string) ($mem['photo'] ?? ''));
                $merged['team']['members'][$mi]['photo'] = $ph !== '' ? canonical_upload_reference_for_storage($ph) : '';
                $merged['team']['members'][$mi]['name'] = isset($mem['name']) ? trim((string) $mem['name']) : '';
                $merged['team']['members'][$mi]['role'] = isset($mem['role']) ? trim((string) $mem['role']) : '';
                $bio = isset($mem['bio_html']) && is_string($mem['bio_html']) ? CmsHtmlSanitizer::sanitize($mem['bio_html']) : '';
                $merged['team']['members'][$mi]['bio_html'] = $bio;
            }
        }

        $nw = &$merged['newsletter_cta'];
        if (isset($nw['text_html']) && is_string($nw['text_html'])) {
            $nw['text_html'] = CmsHtmlSanitizer::sanitize($nw['text_html']);
        }

        return $merged;
    }

    /**
     * Preset banner backgrounds for core values: '' = auto-rotate, 1–4 = fixed preset.
     */
    private static function normalizeValueBannerTone(mixed $raw): string
    {
        $s = strtolower(trim((string) $raw));
        if ($s === '' || $s === 'auto') {
            return '';
        }
        if ($s === '1' || $s === '2' || $s === '3' || $s === '4') {
            return $s;
        }

        return '';
    }
}
