<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\ProductRepository;
use App\Services\CmsPublicPage;

final class RentalController extends Controller
{
    public function index(): void
    {
        $repo     = new ProductRepository();
        $products = $repo->allActive();

        $cms = CmsPublicPage::page(
            'rentals',
            'Rentals',
            'Browse decor and event rentals from DOTS in Saint John — chairs, backdrops, linens and finishing pieces. Add to cart and check out online.'
        );

        $rentals_blocks = $cms['rentals_blocks'] ?? [];
        $hero           = is_array($rentals_blocks['hero'] ?? null) ? $rentals_blocks['hero'] : [];
        $heroEnabled    = (($hero['enabled'] ?? true) !== false);

        $fallbackTitle = trim((string) ($cms['doc_title'] ?? '')) !== '' ? trim((string) $cms['doc_title']) : 'Rentals';
        $heroTitleRaw  = trim((string) ($hero['title'] ?? ''));
        $page_title    = $heroEnabled ? ($heroTitleRaw !== '' ? $heroTitleRaw : $fallbackTitle) : $fallbackTitle;
        $hero_kicker   = $heroEnabled ? trim((string) ($hero['kicker'] ?? '')) : '';

        $bgRaw = trim((string) ($hero['bg_image_path'] ?? ''));
        $hero_bg_url = '';
        if ($heroEnabled && $bgRaw !== '') {
            $hero_bg_url = preg_match('#^https?://#i', $bgRaw) === 1
                ? $bgRaw
                : app_url(ltrim(str_replace('\\', '/', $bgRaw), '/'));
        }

        $this->render('rentals/index', [
            'title'                    => trim((string) ($cms['doc_title'] ?? '')) !== '' ? (string) $cms['doc_title'] : 'Rentals',
            'active_nav'               => 'rentals',
            'body_class'               => 'page-rentals',
            'products'                 => $products,
            'db_ready'                 => Database::getInstance() !== null,
            'cms'                      => $cms,
            'rentals_blocks'           => $rentals_blocks,
            'hero_kicker'              => $hero_kicker,
            'page_title'               => $page_title,
            'crumb_current'            => $page_title,
            'show_breadcrumbs'         => !$heroEnabled
                ? true
                : (!isset($hero['show_breadcrumbs']) || ($hero['show_breadcrumbs'] !== false)),
            'meta_description'         => $cms['meta_description'],
            'hero_subtitle'           => $heroEnabled ? trim((string) ($hero['subtitle'] ?? '')) : '',
            'hero_primary_cta_label'  => $heroEnabled ? trim((string) ($hero['cta_primary_label'] ?? '')) : '',
            'hero_primary_cta_href'   => $heroEnabled ? trim((string) ($hero['cta_primary_href'] ?? '')) : '',
            'hero_secondary_cta_label'=> $heroEnabled ? trim((string) ($hero['cta_secondary_label'] ?? '')) : '',
            'hero_secondary_cta_href' => $heroEnabled ? trim((string) ($hero['cta_secondary_href'] ?? '')) : '',
            'hero_bg_url'             => $hero_bg_url,
        ]);
    }
}
