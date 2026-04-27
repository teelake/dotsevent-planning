<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\ProductRepository;

final class RentalController extends Controller
{
    public function index(): void
    {
        $repo = new ProductRepository();
        $products = $repo->allActive();
        $this->render('rentals/index', [
            'title' => 'Rentals',
            'active_nav' => 'rentals',
            'body_class' => 'page-rentals',
            'products' => $products,
            'db_ready' => Database::getInstance() !== null,
            'meta_description' => 'Browse decor and event rentals from DOTS in Saint John—chairs, backdrops, and finishing pieces. Real inventory, add to cart, and check out online.',
        ]);
    }
}
