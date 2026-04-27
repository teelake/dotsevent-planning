<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        $appConfig = require dirname(__DIR__, 2) . '/config/app.php';
        $data['app'] = $appConfig;

        $viewFile = dirname(__DIR__) . '/Views/' . str_replace('.', '/', $view) . '.php';
        if (!is_file($viewFile)) {
            throw new \InvalidArgumentException('View not found: ' . $view);
        }

        ob_start();
        extract($data, EXTR_SKIP);
        include $viewFile;
        $content = (string) ob_get_clean();

        $title = $data['title'] ?? ($appConfig['name'] ?? 'DOTS');
        $activeNav = $data['active_nav'] ?? '';
        $bodyClass = $data['body_class'] ?? '';
        $extraHeader = $data['extra_header'] ?? '';
        $extraFooter = $data['extra_footer'] ?? '';
        $app = $appConfig;

        $layout = $data['layout'] ?? 'layouts/main';
        $layoutFile = dirname(__DIR__) . '/Views/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            $layoutFile = dirname(__DIR__) . '/Views/layouts/main.php';
        }
        include $layoutFile;
    }

    protected function redirect(string $path): void
    {
        $url = $path;
        if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
            $url = app_url(ltrim($path, '/'));
        }
        header('Location: ' . $url, true, 302);
        exit;
    }
}
