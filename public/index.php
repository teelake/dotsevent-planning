<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/bootstrap.php';

$router = new App\Core\Router();
$router->dispatch();
