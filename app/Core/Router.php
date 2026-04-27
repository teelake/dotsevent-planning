<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\AdminController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\FormController;
use App\Controllers\HomeController;
use App\Controllers\PageController;
use App\Controllers\ProductController;
use App\Controllers\RentalController;

final class Router
{
    public function dispatch(): void
    {
        $path = $this->pathString();
        $segs = $path === '' ? [] : explode('/', $path);
        $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $first = $segs[0] ?? '';

        if ($method === 'POST') {
            if ($first === 'contact' && count($segs) === 1) {
                (new FormController())->contactSubmit();
                return;
            }
            if ($first === 'newsletter' && count($segs) === 1) {
                (new FormController())->newsletterSubmit();
                return;
            }
            if ($first === 'cart' && ($segs[1] ?? '') === 'add' && count($segs) === 2) {
                (new CartController())->add();
                return;
            }
            if ($first === 'cart' && ($segs[1] ?? '') === 'update' && count($segs) === 2) {
                (new CartController())->update();
                return;
            }
            if ($first === 'cart' && ($segs[1] ?? '') === 'remove' && count($segs) === 2) {
                (new CartController())->remove();
                return;
            }
            if ($first === 'checkout' && ($segs[1] ?? '') === 'pay' && count($segs) === 2) {
                (new CheckoutController())->pay();
                return;
            }
            if (($first === 'book' || $first === 'book-your-event') && count($segs) === 1) {
                (new FormController())->bookYourEventSubmit();
                return;
            }
        }

        if ($first === 'product' && isset($segs[1]) && ctype_digit((string) $segs[1]) && count($segs) === 2) {
            (new ProductController())->show((int) $segs[1]);
            return;
        }
        if ($first === 'rentals' && count($segs) === 1) {
            (new RentalController())->index();
            return;
        }
        if ($first === 'cart' && count($segs) === 1) {
            (new CartController())->index();
            return;
        }
        if ($first === 'checkout' && count($segs) === 1) {
            (new CheckoutController())->index();
            return;
        }
        if ($first === 'order' && ($segs[1] ?? '') === 'success' && count($segs) === 2) {
            (new CheckoutController())->success();
            return;
        }
        if ($first === 'admin') {
            (new AdminController())->route($method, $segs);
            return;
        }

        $simple = $this->simpleMap();
        if (isset($simple[$path])) {
            $route = $simple[$path];
            $c = new $route[0]();
            $c->{$route[1]}();
            return;
        }

        http_response_code(404);
        (new PageController())->notFound();
    }

    /**
     * @return array<string, array{0: class-string, 1: string}>
     */
    private function simpleMap(): array
    {
        return [
            '' => [HomeController::class, 'index'],
            'home' => [HomeController::class, 'index'],
            'about' => [PageController::class, 'about'],
            'services' => [PageController::class, 'services'],
            'kids' => [PageController::class, 'kidsParty'],
            'kids-party' => [PageController::class, 'kidsParty'],
            'portfolio' => [PageController::class, 'portfolio'],
            'book' => [PageController::class, 'bookYourEvent'],
            'book-your-event' => [PageController::class, 'bookYourEvent'],
            'contact' => [PageController::class, 'contact'],
        ];
    }

    private function pathString(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = trim((string) $path, '/');
        $app = require dirname(__DIR__, 2) . '/config/app.php';
        $base = trim((string) (is_array($app) ? ($app['base_url'] ?? '') : ''), '/');
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = trim(substr($path, strlen($base)), '/');
        }
        if ($path === 'index.php') {
            return '';
        }
        return $path;
    }
}
