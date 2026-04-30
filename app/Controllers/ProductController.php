<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductRepository;

final class ProductController extends Controller
{
    public function show(int $id): void
    {
        $repo    = new ProductRepository();
        $product = $repo->find($id);
        if ($product === null) {
            http_response_code(404);
            (new PageController())->notFound();
            return;
        }

        $rawDesc = trim(strip_tags((string) ($product['description'] ?? '')));
        $metaDescription = $rawDesc !== ''
            ? (function_exists('mb_substr') ? mb_substr($rawDesc, 0, 160) : substr($rawDesc, 0, 160))
            : 'Rent ' . (string) $product['name'] . ' from DOTS Event Planning — event decor and inventory in Saint John, NB with online checkout.';

        // Fetch per-product options from the product_options table
        $options = $repo->findOptions($id);

        // Related products (same category, excluding self)
        $categoryKey     = (string) ($product['category_key'] ?? '');
        $relatedProducts = $categoryKey !== ''
            ? $repo->relatedByCategory($id, $categoryKey, 4)
            : [];

        $this->render('product/show', [
            'title'            => (string) $product['name'],
            'active_nav'       => 'rentals',
            'body_class'       => 'page-product',
            'product'          => $product,
            'options'          => $options,
            'related_products' => $relatedProducts,
            'meta_description' => $metaDescription,
            'crumb_current'    => (string) $product['name'],
            'show_breadcrumbs' => true,
        ]);
    }
}
