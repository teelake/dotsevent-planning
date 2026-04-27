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
        ]);
    }
}
