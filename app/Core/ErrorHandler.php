<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

/**
 * Captures what PHP’s default file logging can miss: uncaught exceptions and some fatals
 * (still uses the same file as ini "error_log", typically logs/php-error.log).
 */
final class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler(self::class . '::handleException');
        register_shutdown_function(self::class . '::handleShutdown');
    }

    public static function handleException(Throwable $e): void
    {
        error_log(self::formatThrowable('Uncaught ' . $e::class, $e));
        self::respondAndExit();
    }

    public static function handleShutdown(): void
    {
        $e = error_get_last();
        if ($e === null) {
            return;
        }
        if (!in_array(
            (int) $e['type'],
            [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR],
            true
        )) {
            return;
        }
        $msg = sprintf(
            '[%s] Shutdown fatal: [%s] %s in %s:%d' . "\n",
            date('Y-m-d H:i:s T'),
            self::errorTypeName((int) $e['type']),
            $e['message'],
            $e['file'],
            (int) $e['line']
        );
        error_log($msg);
    }

    public static function logFilePath(): string
    {
        if (is_callable('app_config')) {
            $c = app_config();
            if (is_array($c) && !empty($c['error_log']) && is_string($c['error_log'])) {
                return $c['error_log'];
            }
        }
        return dirname(__DIR__, 2) . '/logs/php-error.log';
    }

    private static function formatThrowable(string $label, Throwable $e): string
    {
        return sprintf(
            "[%s] %s: %s in %s:%d\nStack trace:\n%s\n",
            date('Y-m-d H:i:s T'),
            $label,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }

    private static function errorTypeName(int $type): string
    {
        $map = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_USER_ERROR => 'E_USER_ERROR',
        ];
        return $map[$type] ?? (string) $type;
    }

    private static function respondAndExit(): void
    {
        $debug = (bool) (is_callable('app_config') ? (app_config()['debug'] ?? false) : false);
        if (!headers_sent()) {
            http_response_code(500);
        }
        if (PHP_SAPI === 'cli') {
            echo $debug ? "Error (see " . self::logFilePath() . ")\n" : "Error\n";
        } else {
            header('Content-Type: text/html; charset=UTF-8');
            if ($debug) {
                echo '<!DOCTYPE html><html><head><title>Error</title></head><body><h1>500</h1><p>Exception logged to php-error.log</p></body></html>';
            } else {
                echo '<!DOCTYPE html><html><head><title>Error</title></head><body><h1>Something went wrong</h1></body></html>';
            }
        }
        exit(1);
    }
}
