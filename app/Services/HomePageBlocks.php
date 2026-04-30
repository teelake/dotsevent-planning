<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Merges cms_pages.home content_json.blocks with config defaults and resolves URLs.
 *
 * @phpstan-type Metric array{display: string, label: string, target?: int, suffix?: string}
 */
final class HomePageBlocks
{
    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        $path = dirname(__DIR__, 2) . '/config/home_blocks.php';
        if (!is_file($path)) {
            return [];
        }

        /** @var array<string, mixed> $d */
        $d = require $path;

        return is_array($d) ? $d : [];
    }

    /**
     * @param mixed $stored Blocks object from CMS (may be null)
     * @return array<string, mixed>
     */
    public static function merged($stored): array
    {
        $defaults = self::defaults();
        if ($stored === null || $stored === [] || !is_array($stored)) {
            return self::finalize($defaults);
        }

        /** @var array<string, mixed> $s */
        $s = self::normalizeStoredHomeBlocks($stored);
        $out = array_replace_recursive($defaults, $s);
        unset($out['clusters']);

        /** Full array replace where partial lists hurt UX (use array_key_exists so [] is intentional) */
        if (!empty($s['confidence']) && is_array($s['confidence']) && array_key_exists('metrics', $s['confidence']) && is_array($s['confidence']['metrics'])) {
            $out['confidence']['metrics'] = $s['confidence']['metrics'];
        }
        if (!empty($s['packages']) && is_array($s['packages']) && array_key_exists('items', $s['packages']) && is_array($s['packages']['items'])) {
            $out['packages']['items'] = $s['packages']['items'];
        }
        if (!empty($s['operating_model']) && is_array($s['operating_model']) && array_key_exists('steps', $s['operating_model']) && is_array($s['operating_model']['steps'])) {
            $out['operating_model']['steps'] = $s['operating_model']['steps'];
        }
        if (!empty($s['testimonials']) && is_array($s['testimonials']) && array_key_exists('quotes', $s['testimonials']) && is_array($s['testimonials']['quotes'])) {
            $out['testimonials']['quotes'] = $s['testimonials']['quotes'];
        }

        return self::finalize($out);
    }

    /**
     * Fix accidentally nested payloads (blocks.blocks...) and coerce list shapes after cms_page_fields round-trips.
     *
     * @param array<string, mixed> $stored
     * @return array<string, mixed>
     */
    private static function normalizeStoredHomeBlocks(array $stored): array
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

        unset($s['clusters']);

        foreach (['confidence', 'partnership', 'operating_model', 'packages', 'testimonials', 'newsletter'] as $root) {
            if (!array_key_exists($root, $s)) {
                continue;
            }
            if ($s[$root] === null || !is_array($s[$root])) {
                unset($s[$root]);
            }
        }

        if (isset($s['confidence']) && is_array($s['confidence'])) {
            self::liftNumericListChildren($s['confidence'], 'metrics');
        }
        if (isset($s['packages']) && is_array($s['packages'])) {
            self::liftNumericListChildren($s['packages'], 'items');
        }
        if (isset($s['operating_model']) && is_array($s['operating_model'])) {
            self::liftNumericListChildren($s['operating_model'], 'steps');
        }
        if (isset($s['testimonials']) && is_array($s['testimonials'])) {
            self::liftNumericListChildren($s['testimonials'], 'quotes');
        }

        if (isset($s['confidence']['metrics']) && is_array($s['confidence']['metrics'])) {
            $s['confidence']['metrics'] = array_values($s['confidence']['metrics']);
        }
        if (isset($s['packages']['items']) && is_array($s['packages']['items'])) {
            $s['packages']['items'] = array_values($s['packages']['items']);
        }
        if (isset($s['operating_model']['steps']) && is_array($s['operating_model']['steps'])) {
            $s['operating_model']['steps'] = array_values($s['operating_model']['steps']);
        }
        if (isset($s['testimonials']['quotes']) && is_array($s['testimonials']['quotes'])) {
            $s['testimonials']['quotes'] = array_values($s['testimonials']['quotes']);
        }

        return $s;
    }

    /**
     * Relational hydrate may put list rows under numeric keys beside the canonical list key.
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
     * Resolve empty href placeholders and coerce lists.
     *
     * @param array<string, mixed> $merged
     * @return array<string, mixed>
     */
    private static function finalize(array $merged): array
    {
        $confidence = &$merged['confidence'];
        if (isset($confidence['cta_href']) && trim((string) $confidence['cta_href']) === '') {
            $confidence['cta_href'] = app_url('about');
        }
        if (isset($confidence['metrics']) && is_array($confidence['metrics'])) {
            foreach ($confidence['metrics'] as $i => $m) {
                if (!is_array($m)) {
                    continue;
                }
                if (! isset($m['display']) || (string) $m['display'] === '') {
                    $merged['confidence']['metrics'][$i]['display'] = self::guessDisplayFromTarget($m);
                }
                $suffix = isset($m['suffix']) ? (string) $m['suffix'] : '+';
                $merged['confidence']['metrics'][$i]['suffix'] = $suffix;
                $tgt = isset($m['target']) ? (int) $m['target'] : null;
                if ($tgt !== null && $tgt > 0) {
                    $merged['confidence']['metrics'][$i]['target'] = $tgt;
                }
            }
        }

        $p = &$merged['partnership'];
        if (isset($p['cta_href']) && trim((string) $p['cta_href']) === '') {
            $p['cta_href'] = app_url('about');
        }

        if (isset($merged['packages']['items']) && is_array($merged['packages']['items'])) {
            foreach ($merged['packages']['items'] as $i => $pkg) {
                if (!is_array($pkg)) {
                    continue;
                }
                $href = trim((string) ($pkg['cta_href'] ?? ''));
                if ($href === '') {
                    $merged['packages']['items'][$i]['cta_href'] = app_url('book');
                }
            }
        }

        return $merged;
    }

    /**
     * @param mixed $metric
     */
    private static function guessDisplayFromTarget($metric): string
    {
        if (!is_array($metric)) {
            return '0';
        }
        $t = isset($metric['target']) ? (int) $metric['target'] : 0;
        $suf = isset($metric['suffix']) ? (string) $metric['suffix'] : '+';

        return $t > 0 ? ((string) $t . ($suf === '°' ? '°' : $suf)) : '—';
    }
}
