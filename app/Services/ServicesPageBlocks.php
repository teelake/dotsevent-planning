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
        $s = self::normalizeStoredLists($stored);
        $out = array_replace_recursive($defaults, $s);

        if (!empty($s['offerings']) && is_array($s['offerings'])) {
            /* array_replace_recursive keeps stale default rows when saved list is shorter or empty */
            if (array_key_exists('items', $s['offerings']) && is_array($s['offerings']['items'])) {
                $out['offerings']['items'] = array_values($s['offerings']['items']);
            }
        }
        if (!empty($s['partnership']) && is_array($s['partnership'])) {
            if (array_key_exists('metrics', $s['partnership']) && is_array($s['partnership']['metrics'])) {
                $out['partnership']['metrics'] = array_values($s['partnership']['metrics']);
            }
        }
        if (!empty($s['faq']) && is_array($s['faq'])) {
            if (array_key_exists('items', $s['faq']) && is_array($s['faq']['items'])) {
                $out['faq']['items'] = array_values($s['faq']['items']);
            }
        }

        return self::finalize($out);
    }

    /**
     * Shape used by {@see \App\Views\home\index.php} — fed from Services CMS Offerings only.
     *
     * @param array<string, mixed> $merged Result of {@see self::merged()}
     *
     * @return array{enabled: bool, eyebrow: string, title: string, link_all_label: string, link_all_href: string, items: list<array<string, mixed>>}
     */
    public static function homeTeaserFromOfferings(array $merged): array
    {
        $off = isset($merged['offerings']) && is_array($merged['offerings']) ? $merged['offerings'] : [];
        $teaserOn = (($off['home_teaser_enabled'] ?? true) !== false);

        $items = [];
        if (isset($off['items']) && is_array($off['items'])) {
            foreach ($off['items'] as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $title = trim((string) ($row['title'] ?? ''));
                if ($title === '') {
                    continue;
                }
                $html = isset($row['summary_html']) && is_string($row['summary_html']) ? $row['summary_html'] : '';
                $text = trim((string) preg_replace('/\s+/u', ' ', strip_tags($html)));

                $items[] = [
                    'title' => $title,
                    'text' => $text,
                    'accent' => !empty($row['accent']),
                    'muted' => !empty($row['muted']),
                ];
            }
        }

        $servicesUrl = app_url('services');
        $ctaL = isset($off['home_teaser_cta_label']) ? trim((string) $off['home_teaser_cta_label']) : '';
        $ctaH = isset($off['home_teaser_cta_href']) ? trim((string) $off['home_teaser_cta_href']) : '';

        return [
            'enabled' => $teaserOn && $items !== [],
            'eyebrow' => isset($off['eyebrow']) ? trim((string) $off['eyebrow']) : '',
            'title' => isset($off['section_title']) ? trim((string) $off['section_title']) : '',
            'link_all_label' => $ctaL !== '' ? $ctaL : 'Explore services',
            'link_all_href' => $ctaH !== '' ? $ctaH : $servicesUrl,
            'items' => array_values($items),
        ];
    }

    /** @param array<string, mixed> $stored */
    private static function normalizeStoredLists(array $stored): array
    {
        $s = $stored;
        for ($i = 0; $i < 10 && isset($s['blocks']) && is_array($s['blocks']); $i++) {
            $inner = $s['blocks'];
            unset($s['blocks']);
            if (!is_array($inner)) {
                break;
            }
            $s = array_replace($s, $inner);
        }

        if (isset($s['offerings']) && is_array($s['offerings'])) {
            self::liftNumericListChildren($s['offerings'], 'items');
        }
        if (isset($s['partnership']) && is_array($s['partnership'])) {
            self::liftNumericListChildren($s['partnership'], 'metrics');
        }
        if (isset($s['faq']) && is_array($s['faq'])) {
            self::liftNumericListChildren($s['faq'], 'items');
        }

        return $s;
    }

    /**
     * @see \App\Services\HomePageBlocks (same hydration quirk from cms_page_fields)
     *
     * @param array<string, mixed> $section
     */
    private static function liftNumericListChildren(array &$section, string $listKey): void
    {
        $toUnset = [];
        $lifted = [];
        foreach ($section as $k => $v) {
            if ($k === $listKey || !is_array($v)) {
                continue;
            }
            if (is_int($k)) {
                $lifted[$k] = $v;
                $toUnset[] = $k;

                continue;
            }
            if (is_string($k) && $k !== '' && ctype_digit($k)) {
                $lifted[(int) $k] = $v;
                $toUnset[] = $k;
            }
        }
        if ($lifted === []) {
            return;
        }
        ksort($lifted, SORT_NUMERIC);
        $mergedList = array_values($lifted);

        $hadListKey = array_key_exists($listKey, $section);
        $existing = ($hadListKey && is_array($section[$listKey])) ? $section[$listKey] : null;

        $useLifted =
            !$hadListKey
            || !is_array($existing)
            || $existing === [];

        if ($useLifted) {
            foreach ($toUnset as $k) {
                unset($section[$k]);
            }
            $section[$listKey] = $mergedList;
        } elseif (is_array($existing) && $existing !== []) {
            foreach ($toUnset as $k) {
                unset($section[$k]);
            }
        }
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
