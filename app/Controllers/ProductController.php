<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductRepository;

final class ProductController extends Controller
{
    public function show(int $id): void
    {
        $repo = new ProductRepository();
        $product = $repo->find($id);
        if ($product === null) {
            http_response_code(404);
            (new PageController())->notFound();
            return;
        }
        $this->render('product/show', [
            'title' => (string) $product['name'],
            'active_nav' => 'rentals',
            'body_class' => 'page-product',
            'product' => $product,
        ]);
    }
}
