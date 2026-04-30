<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CmsPagesRepository;
use App\Models\CmsSlidesRepository;

final class CmsPublicPage
{
    /**
     * CMS page content for public routes (sanitized HTML + optional title/meta).
     *
     * @return array{
     *   has_custom_body: bool,
     *   body_html: string,
     *   doc_title: string,
     *   meta_description: string,
     *   about_blocks?: array<string, mixed>,
     *   services_blocks?: array<string, mixed>,
     *   contact_blocks?: array<string, mixed>,
     *   portfolio_blocks?: array<string, mixed>,
     *   rentals_blocks?: array<string, mixed>
     * }
     */
    public static function page(string $slug, string $defaultTitle, string $defaultMeta): array
    {
        $data = null;
        $row = self::findRow($slug);

        $out = [
            'has_custom_body' => false,
            'body_html' => '',
            'doc_title' => $defaultTitle,
            'meta_description' => $defaultMeta,
        ];

        if ($row !== null) {
            $t = trim((string) ($row['title'] ?? ''));
            if ($t !== '') {
                $out['doc_title'] = $t;
            }

            $data = self::decodeJson((string) ($row['content_json'] ?? ''));
            if ($data !== null) {
                $meta = $data['meta_description'] ?? null;
                if (is_string($meta)) {
                    $m = trim($meta);
                    if ($m !== '') {
                        $out['meta_description'] = $m;
                    }
                }

                $html = isset($data['html']) && is_string($data['html']) ? $data['html'] : '';
                $san = CmsHtmlSanitizer::sanitize($html);
                if ($san !== '') {
                    $out['has_custom_body'] = true;
                    $out['body_html'] = $san;
                }
            }
        }

        if ($slug === 'about') {
            $storedAbout = $data !== null ? self::blocksFromContentData($data) : null;
            $out['about_blocks'] = AboutPageBlocks::merged($storedAbout);
        }

        if ($slug === 'services') {
            $storedServices = $data !== null ? self::blocksFromContentData($data) : null;
            $out['services_blocks'] = ServicesPageBlocks::merged($storedServices);
        }

        if ($slug === 'contact') {
            $storedContact = $data !== null ? self::blocksFromContentData($data) : null;
            $out['contact_blocks'] = ContactPageBlocks::merged($storedContact);
        }

        if ($slug === 'portfolio') {
            $storedPortfolio = $data !== null ? self::blocksFromContentData($data) : null;
            $out['portfolio_blocks'] = PortfolioPageBlocks::merged($storedPortfolio);
        }

        if ($slug === 'rentals') {
            $storedRentals = $data !== null ? self::blocksFromContentData($data) : null;
            $out['rentals_blocks'] = RentalsPageBlocks::merged($storedRentals);
        }

        return $out;
    }

    /**
     * @param list<array<string, string>> $defaultSlides
     * @return array{
     *   slides: list<array<string, string>>,
     *   intro_html: string,
     *   meta_description: string,
     *   home_blocks: array<string, mixed>,
     *   home_services_teaser: array{enabled: bool, eyebrow: string, title: string, link_all_label: string, link_all_href: string, items: list<array<string, mixed>>}
     * }
     */
    public static function home(array $defaultSlides, string $defaultMeta): array
    {
        $servicesMergedCatalog = self::mergedServicesBlocks();
        $out = [
            'slides' => $defaultSlides,
            'intro_html' => '',
            'meta_description' => $defaultMeta,
            'home_blocks' => HomePageBlocks::merged(null),
            'home_services_teaser' => ServicesPageBlocks::homeTeaserFromOfferings($servicesMergedCatalog),
        ];

        $dbSlides = self::slidesFromDatabase();
        if ($dbSlides !== null && $dbSlides !== []) {
            $out['slides'] = $dbSlides;
        }

        $row = self::findRow('home');
        if ($row === null) {
            return $out;
        }

        $data = self::decodeJson((string) ($row['content_json'] ?? ''));
        if ($data === null) {
            return $out;
        }

        $meta = $data['meta_description'] ?? null;
        if (is_string($meta)) {
            $m = trim($meta);
            if ($m !== '') {
                $out['meta_description'] = $m;
            }
        }

        if ($dbSlides === null || $dbSlides === []) {
            $slidesRaw = $data['slides'] ?? null;
            if (is_array($slidesRaw) && $slidesRaw !== []) {
                $parsed = self::normalizeSlides($slidesRaw);
                if ($parsed !== null) {
                    $out['slides'] = $parsed;
                }
            }
        }

        $html = isset($data['html']) && is_string($data['html']) ? $data['html'] : '';
        $san = CmsHtmlSanitizer::sanitize($html);
        if ($san !== '') {
            $out['intro_html'] = $san;
        }

        $blocksRaw = self::blocksFromContentData($data);
        $out['home_blocks'] = HomePageBlocks::merged($blocksRaw);

        return $out;
    }

    /** @return array<string, mixed> */
    private static function mergedServicesBlocks(): array
    {
        $row = self::findRow('services');
        if ($row === null) {
            return ServicesPageBlocks::merged(null);
        }
        $data = self::decodeJson((string) ($row['content_json'] ?? ''));
        if ($data === null) {
            return ServicesPageBlocks::merged(null);
        }

        return ServicesPageBlocks::merged(self::blocksFromContentData($data));
    }

    /**
     * @return list<array<string, string>>|null Null if DB unavailable or no table
     */
    private static function slidesFromDatabase(): ?array
    {
        try {
            $rows = (new CmsSlidesRepository())->listLiveForPublic();
        } catch (\Throwable) {
            return null;
        }
        if ($rows === []) {
            return null;
        }

        $out = [];
        foreach ($rows as $r) {
            if (!is_array($r)) {
                continue;
            }
            $slide = self::cmsSlideRowToCarousel($r);
            if ($slide['image'] !== '' && $slide['title'] !== '') {
                $out[] = $slide;
            }
        }

        return $out === [] ? null : $out;
    }

    /**
     * @param array<string, mixed> $r
     * @return array<string, string>
     */
    private static function cmsSlideRowToCarousel(array $r): array
    {
        $desk = trim((string) ($r['image_desktop_path'] ?? ''));
        $mob = trim((string) ($r['image_mobile_path'] ?? ''));
        $headline = trim((string) ($r['headline'] ?? ''));
        $alt = trim((string) ($r['image_alt'] ?? ''));
        if ($alt === '') {
            $alt = $headline;
        }
        $image = $desk !== '' ? app_url(ltrim($desk, '/')) : '';
        $imageMobile = $mob !== '' ? app_url(ltrim($mob, '/')) : '';

        return [
            'image' => $image,
            'image_mobile' => $imageMobile,
            'alt' => $alt,
            'eyebrow' => trim((string) ($r['badge'] ?? '')),
            'title' => $headline,
            'subtitle' => trim((string) ($r['supporting'] ?? '')),
            'cta_label' => trim((string) ($r['btn_primary_label'] ?? '')),
            'cta_href' => trim((string) ($r['btn_primary_href'] ?? '')),
            'secondary_label' => trim((string) ($r['btn_secondary_label'] ?? '')),
            'secondary_href' => trim((string) ($r['btn_secondary_href'] ?? '')),
        ];
    }

    /** @return array<string, mixed>|null */
    private static function findRow(string $slug): ?array
    {
        try {
            return (new CmsPagesRepository())->findBySlug($slug);
        } catch (\Throwable) {
            return null;
        }
    }

    /** @return array<string, mixed>|null */
    private static function decodeJson(string $raw): ?array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return null;
        }

        return $data;
    }

    /**
     * Homepage (and block editors) historically read `content_json.blocks`; relational storage
     * hydrates `blocks.*` keys. Support extra shapes: nested `blocks.blocks`, empty `blocks{}`
     * with section keys at root, and mis-saved root-level `confidence`/… .
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    public static function blocksFromContentData(array $data): ?array
    {
        $b = $data['blocks'] ?? null;
        while (is_array($b) && isset($b['blocks']) && is_array($b['blocks'])) {
            $b = $b['blocks'];
        }
        if (is_array($b) && $b !== []) {
            return $b;
        }

        $markers = [
            'version',
            'confidence',
            'partnership',
            'operating_model',
            'packages',
            'testimonials',
            'newsletter',
        ];
        $fromRoot = [];
        foreach ($markers as $k) {
            if (array_key_exists($k, $data)) {
                $fromRoot[$k] = $data[$k];
            }
        }

        return $fromRoot === [] ? null : $fromRoot;
    }

    /**
     * @param list<mixed> $slides
     * @return list<array<string, string>>|null
     */
    private static function normalizeSlides(array $slides): ?array
    {
        $keys = [
            'image',
            'alt',
            'eyebrow',
            'title',
            'subtitle',
            'cta_label',
            'cta_href',
            'secondary_label',
            'secondary_href',
        ];
        $out = [];
        foreach ($slides as $item) {
            if (!is_array($item)) {
                return null;
            }
            $row = [];
            foreach ($keys as $k) {
                if (!isset($item[$k]) || !is_string($item[$k])) {
                    return null;
                }
                $row[$k] = trim($item[$k]);
            }
            if ($row['image'] === '' || $row['title'] === '') {
                return null;
            }
            if (!self::safeHttpUrl($row['image'])) {
                return null;
            }
            if (!self::safeHref($row['cta_href']) || !self::safeHref($row['secondary_href'])) {
                return null;
            }
            $out[] = $row;
        }

        return $out === [] ? null : $out;
    }

    private static function safeHttpUrl(string $u): bool
    {
        return self::safeHref($u) && preg_match('#^https?://#i', trim($u)) === 1;
    }

    private static function safeHref(string $href): bool
    {
        $href = trim($href);
        if ($href === '') {
            return false;
        }
        if (stripos($href, 'javascript:') === 0 || stripos($href, 'data:') === 0) {
            return false;
        }
        if (str_starts_with($href, '/') && !str_starts_with($href, '//')) {
            return !str_contains($href, '..');
        }

        return preg_match('#^https?://#i', $href) === 1;
    }
}

