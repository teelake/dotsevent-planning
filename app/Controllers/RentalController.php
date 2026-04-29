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
        $hero           = $rentals_blocks['hero'] ?? [];

        $this->render('rentals/index', [
            'title'           => $cms['doc_title']       !== '' ? $cms['doc_title']       : 'Rentals',
            'active_nav'      => 'rentals',
            'body_class'      => 'page-rentals',
            'products'        => $products,
            'db_ready'        => Database::getInstance() !== null,
            'rentals_blocks'  => $rentals_blocks,
            'hero_kicker'     => (string) ($hero['kicker']  ?? 'Event-ready inventory'),
            'page_title'      => (string) ($hero['title']   ?? 'Rentals'),
            'crumb_current'   => 'Rentals',
            'show_breadcrumbs'=> (bool)   ($hero['show_breadcrumbs'] ?? true),
            'meta_description'=> $cms['meta_description'],
        ]);
    }
}
