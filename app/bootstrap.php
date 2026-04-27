<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$logDir = $projectRoot . '/logs';
$defaultLog = $logDir . '/php-error.log';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
if (is_dir($logDir) && (is_writable($logDir) || (file_exists($defaultLog) && is_writable($defaultLog)) || @touch($defaultLog))) {
    ini_set('log_errors', '1');
    ini_set('error_log', $defaultLog);
} elseif (is_writable($projectRoot) && @touch($projectRoot . '/php-error.log')) {
    ini_set('log_errors', '1');
    ini_set('error_log', $projectRoot . '/php-error.log');
} else {
    ini_set('log_errors', '1');
}
error_reporting(E_ALL);

require_once __DIR__ . '/helpers.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = __DIR__ . '/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$appCfg = app_config();
if (!empty($appCfg['error_log']) && is_string($appCfg['error_log'])) {
    $p = $appCfg['error_log'];
    $d = dirname($p);
    if (!is_dir($d)) {
        @mkdir($d, 0755, true);
    }
    if ((is_dir($d) && is_writable($d)) || (file_exists($p) && is_writable($p))) {
        ini_set('error_log', $p);
    }
}
$debug = (bool) ($appCfg['debug'] ?? false);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('display_startup_errors', $debug ? '1' : '0');

\App\Core\ErrorHandler::register();
