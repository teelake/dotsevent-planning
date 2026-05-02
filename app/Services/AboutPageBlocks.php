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

        if (!empty($s['story']) && is_array($s['story'])) {
            if (isset($s['story']['chapters']) && is_array($s['story']['chapters']) && $s['story']['chapters'] !== []) {
                $out['story']['chapters'] = $s['story']['chapters'];
            }
            if (isset($s['story']['metrics']) && is_array($s['story']['metrics']) && $s['story']['metrics'] !== []) {
                $out['story']['metrics'] = $s['story']['metrics'];
            }
        }
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
     * @param array<string, mixed> $merged
     *
     * @return array<string, mixed>
     */
    private static function finalize(array $merged): array
    {
        if (isset($merged['story']['chapters']) && is_array($merged['story']['chapters'])) {
            foreach ($merged['story']['chapters'] as $i => $ch) {
                if (!is_array($ch)) {
                    continue;
                }
                $h = isset($ch['heading']) ? trim((string) $ch['heading']) : '';
                $merged['story']['chapters'][$i]['heading'] = $h;
                $body = isset($ch['body_html']) && is_string($ch['body_html']) ? CmsHtmlSanitizer::sanitize($ch['body_html']) : '';
                $merged['story']['chapters'][$i]['body_html'] = $body;
            }
        }

        $pq = isset($merged['story']['pull_quote']) ? trim((string) $merged['story']['pull_quote']) : '';
        $merged['story']['pull_quote'] = $pq;

        if (isset($merged['story']['metrics']) && is_array($merged['story']['metrics'])) {
            foreach ($merged['story']['metrics'] as $i => $m) {
                if (!is_array($m)) {
                    continue;
                }
                $merged['story']['metrics'][$i]['suffix'] = isset($m['suffix']) ? (string) $m['suffix'] : '+';
                $t = isset($m['target']) ? (int) $m['target'] : null;
                if ($t !== null && $t >= 0) {
                    $merged['story']['metrics'][$i]['target'] = $t;
                }
                if (!isset($m['display']) || (string) $m['display'] === '') {
                    $merged['story']['metrics'][$i]['display'] = self::guessMetricDisplay($m);
                }
            }
        }

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
                $merged['approach']['images'][$j]['src'] = $src !== '' ? self::finalizeUrl($src) : '';
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
                $merged['team']['members'][$mi]['photo'] = $ph !== '' ? self::finalizeUrl($ph) : '';
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
     * @param mixed $metric
     */
    private static function guessMetricDisplay($metric): string
    {
        if (!is_array($metric)) {
            return '—';
        }
        $t = isset($metric['target']) ? (int) $metric['target'] : 0;
        $suf = isset($metric['suffix']) ? (string) $metric['suffix'] : '+';

        return $t >= 0 ? ((string) $t . ($suf === '°' ? '°' : $suf)) : '—';
    }

    private static function finalizeUrl(string $src): string
    {
        $src = trim($src);
        if ($src === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $src)) {
            return $src;
        }

        return public_file_url(ltrim($src, '/'));
    }
}
