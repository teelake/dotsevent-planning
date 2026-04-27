<?php

declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Restrict CMS HTML to a safe subset suitable for Quill output.
 */
final class CmsHtmlSanitizer
{
    private const MAX_INPUT_BYTES = 500_000;

    /** @var array<string, list<string>> */
    private const TAG_POLICY = [
        'p' => ['class'],
        'br' => [],
        'strong' => ['class'],
        'b' => ['class'],
        'em' => ['class'],
        'i' => ['class'],
        'u' => ['class'],
        's' => ['class'],
        'strike' => ['class'],
        'h1' => ['class'],
        'h2' => ['class'],
        'h3' => ['class'],
        'h4' => ['class'],
        'h5' => ['class'],
        'h6' => ['class'],
        'ul' => ['class'],
        'ol' => ['class'],
        'li' => ['class'],
        'blockquote' => ['class'],
        'pre' => ['class'],
        'code' => ['class'],
        'a' => ['href', 'title', 'target', 'rel', 'class'],
        'img' => ['src', 'alt', 'width', 'height', 'class', 'loading'],
        'span' => ['class'],
        'div' => ['class'],
        'sup' => ['class'],
        'sub' => ['class'],
        'hr' => ['class'],
    ];

    public static function sanitize(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }
        if (strlen($html) > self::MAX_INPUT_BYTES) {
            return '';
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $wrapped = '<?xml encoding="UTF-8"><div id="__cms_root">' . $html . '</div>';
        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $dom->getElementById('__cms_root');
        if ($root === null) {
            return self::fallbackStrip($html);
        }

        self::cleanChildren($root);
        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $dom->saveHTML($child);
        }
        return trim($out);
    }

    private static function fallbackStrip(string $html): string
    {
        $allowed = '<' . implode('><', array_keys(self::TAG_POLICY)) . '><iframe>';
        return trim(strip_tags($html, $allowed));
    }

    private static function cleanChildren(DOMElement $el): void
    {
        $nodes = [];
        foreach ($el->childNodes as $c) {
            $nodes[] = $c;
        }
        foreach ($nodes as $c) {
            self::cleanNode($c);
        }
    }

    private static function cleanNode(DOMNode $node): void
    {
        if ($node->nodeType === XML_TEXT_NODE || $node->nodeType === XML_CDATA_SECTION_NODE) {
            return;
        }
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            $node->parentNode?->removeChild($node);

            return;
        }
        /** @var DOMElement $el */
        $el = $node;
        $tag = strtolower($el->tagName);

        if ($tag === 'script' || $tag === 'style') {
            $el->parentNode?->removeChild($el);

            return;
        }

        if ($tag === 'iframe') {
            if (!self::iframeSrcAllowed($el->getAttribute('src'))) {
                $el->parentNode?->removeChild($el);

                return;
            }
            self::retainAttrs($el, ['src', 'title', 'width', 'height', 'allow', 'allowfullscreen', 'loading', 'referrerpolicy']);
            self::cleanChildren($el);

            return;
        }

        if (!isset(self::TAG_POLICY[$tag])) {
            self::cleanChildren($el);
            self::unwrap($el);

            return;
        }

        self::applyTagPolicy($el, $tag);
        self::cleanChildren($el);
    }

    private static function unwrap(DOMElement $el): void
    {
        $parent = $el->parentNode;
        if ($parent === null) {
            return;
        }
        while ($el->firstChild !== null) {
            $parent->insertBefore($el->firstChild, $el);
        }
        $parent->removeChild($el);
    }

    /**
     * @param list<string> $names
     */
    private static function retainAttrs(DOMElement $el, array $names): void
    {
        $keep = array_fill_keys($names, true);
        if ($el->hasAttributes()) {
            $toRemove = [];
            foreach ($el->attributes as $attr) {
                $n = strtolower($attr->name);
                if (!isset($keep[$n])) {
                    $toRemove[] = $attr->name;
                }
            }
            foreach ($toRemove as $n) {
                $el->removeAttribute($n);
            }
        }
    }

    private static function applyTagPolicy(DOMElement $el, string $tag): void
    {
        $allowed = self::TAG_POLICY[$tag];
        self::retainAttrs($el, $allowed);

        if ($tag === 'a') {
            $href = $el->getAttribute('href');
            if (!self::safeHref($href)) {
                $el->removeAttribute('href');
            }
            $t = strtolower($el->getAttribute('target'));
            if ($t === '_blank') {
                $el->setAttribute('rel', 'noopener noreferrer');
            } elseif ($el->hasAttribute('target')) {
                $el->removeAttribute('target');
            }
        }

        if ($tag === 'img') {
            $src = $el->getAttribute('src');
            if (!self::safeImgSrc($src)) {
                $el->setAttribute('src', '');
                $el->setAttribute('alt', '');
            }
        }
    }

    private static function safeHref(string $href): bool
    {
        $href = trim($href);
        if ($href === '' || stripos($href, 'javascript:') === 0 || stripos($href, 'data:') === 0) {
            return false;
        }
        if (str_starts_with($href, '#')) {
            return strlen($href) < 200;
        }
        if (str_starts_with($href, '/') && !str_starts_with($href, '//')) {
            return !str_contains($href, '..');
        }
        if (preg_match('#^https?://#i', $href) === 1) {
            return true;
        }
        if (str_starts_with($href, 'mailto:') && strlen($href) < 500) {
            return stripos($href, '@') !== false;
        }
        if (str_starts_with($href, 'tel:') && strlen($href) < 80) {
            return true;
        }

        return false;
    }

    private static function safeImgSrc(string $src): bool
    {
        $src = trim($src);
        if ($src === '') {
            return false;
        }
        if (stripos($src, 'javascript:') === 0 || stripos($src, 'data:') === 0) {
            return false;
        }
        if (preg_match('#^https?://#i', $src) === 1) {
            return true;
        }
        if (str_starts_with($src, '/') && !str_starts_with($src, '//')) {
            return !str_contains($src, '..');
        }

        return false;
    }

    private static function iframeSrcAllowed(string $src): bool
    {
        $src = trim($src);
        if ($src === '' || !preg_match('#^https://#i', $src)) {
            return false;
        }
        $p = parse_url($src);
        if (!is_array($p) || empty($p['host'])) {
            return false;
        }
        $host = strtolower((string) $p['host']);

        return str_ends_with($host, 'youtube.com')
            || str_ends_with($host, 'youtube-nocookie.com')
            || str_ends_with($host, 'youtu.be')
            || str_ends_with($host, 'vimeo.com');
    }
}
