<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Merges cms_pages.services content_json.blocks with config defaults.
 */
final class ServicesPageBlocks
{
    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        $path = dirname(__DIR__, 2) . '/config/services_blocks.php';
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

        if (!empty($s['offerings']) && is_array($s['offerings'])) {
            if (isset($s['offerings']['items']) && is_array($s['offerings']['items']) && $s['offerings']['items'] !== []) {
                $out['offerings']['items'] = $s['offerings']['items'];
            }
        }
        if (!empty($s['partnership']) && is_array($s['partnership'])) {
            if (isset($s['partnership']['metrics']) && is_array($s['partnership']['metrics']) && $s['partnership']['metrics'] !== []) {
                $out['partnership']['metrics'] = $s['partnership']['metrics'];
            }
        }
        if (!empty($s['faq']) && is_array($s['faq'])) {
            if (isset($s['faq']['items']) && is_array($s['faq']['items']) && $s['faq']['items'] !== []) {
                $out['faq']['items'] = $s['faq']['items'];
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
        $in = &$merged['intro'];
        if (isset($in['lead_html']) && is_string($in['lead_html'])) {
            $in['lead_html'] = CmsHtmlSanitizer::sanitize($in['lead_html']);
        }

        if (isset($merged['offerings']['items']) && is_array($merged['offerings']['items'])) {
            foreach ($merged['offerings']['items'] as $i => $item) {
                if (!is_array($item)) {
                    continue;
                }
                $sum = isset($item['summary_html']) && is_string($item['summary_html']) ? CmsHtmlSanitizer::sanitize($item['summary_html']) : '';
                $merged['offerings']['items'][$i]['summary_html'] = $sum;
                $merged['offerings']['items'][$i]['title'] = isset($item['title']) ? trim((string) $item['title']) : '';
                $href = trim((string) ($item['href'] ?? ''));
                $merged['offerings']['items'][$i]['href'] = $href;
            }
        }

        $p = &$merged['partnership'];
        if (isset($p['lead_html']) && is_string($p['lead_html'])) {
            $p['lead_html'] = CmsHtmlSanitizer::sanitize($p['lead_html']);
        }
        $href = isset($p['cta_href']) ? trim((string) $p['cta_href']) : '';
        if ($href === '') {
            $p['cta_href'] = app_url('about');
        } else {
            $p['cta_href'] = $href;
        }

        if (isset($p['metrics']) && is_array($p['metrics'])) {
            foreach ($p['metrics'] as $mi => $m) {
                if (!is_array($m)) {
                    continue;
                }
                $merged['partnership']['metrics'][$mi]['suffix'] = isset($m['suffix']) ? (string) $m['suffix'] : '+';
                $t = isset($m['target']) ? (int) $m['target'] : null;
                if ($t !== null && $t >= 0) {
                    $merged['partnership']['metrics'][$mi]['target'] = $t;
                }
                if (!isset($m['display']) || (string) $m['display'] === '') {
                    $merged['partnership']['metrics'][$mi]['display'] = self::guessMetricDisplay($m);
                }
            }
        }

        $f = &$merged['faq'];
        if (isset($f['lead_html']) && is_string($f['lead_html'])) {
            $f['lead_html'] = CmsHtmlSanitizer::sanitize($f['lead_html']);
        }
        if (isset($f['items']) && is_array($f['items'])) {
            foreach ($f['items'] as $fi => $it) {
                if (!is_array($it)) {
                    continue;
                }
                $merged['faq']['items'][$fi]['question'] = isset($it['question']) ? trim((string) $it['question']) : '';
                $ans = isset($it['answer_html']) && is_string($it['answer_html']) ? CmsHtmlSanitizer::sanitize($it['answer_html']) : '';
                $merged['faq']['items'][$fi]['answer_html'] = $ans;
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
}
