<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array{0: class-string, 1: string}> */
    private array $routes = [];

    public function __construct()
    {
        $this->routes = [
            '' => [\App\Controllers\HomeController::class, 'index'],
            'home' => [\App\Controllers\HomeController::class, 'index'],
            'about' => [\App\Controllers\PageController::class, 'about'],
            'services' => [\App\Controllers\PageController::class, 'services'],
            'kids-party' => [\App\Controllers\PageController::class, 'kidsParty'],
            'rentals' => [\App\Controllers\PageController::class, 'rentals'],
            'portfolio' => [\App\Controllers\PageController::class, 'portfolio'],
            'book-your-event' => [\App\Controllers\PageController::class, 'bookYourEvent'],
            'contact' => [\App\Controllers\PageController::class, 'contact'],
        ];
    }

    public function dispatch(): void
    {
        $path = $this->pathSegment();
        if (!isset($this->routes[$path])) {
            http_response_code(404);
            $c = new \App\Controllers\PageController();
            $c->notFound();
            return;
        }
        $route = $this->routes[$path];
        $class = $route[0];
        $method = $route[1];
        $controller = new $class();
        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo 'Server error';
            return;
        }
        $controller->{$method}();
    }

    private function pathSegment(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = trim((string) $path, '/');
        $app = require dirname(__DIR__, 2) . '/config/app.php';
        $base = trim((string) (is_array($app) ? ($app['base_url'] ?? '') : ''), '/');
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = trim(substr($path, strlen($base)), '/');
        }
        // index.php
        if ($path === 'index.php' || $path === '') {
            return '';
        }
        return $path;
    }
}
