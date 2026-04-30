<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Structured JSON-lines audit log (separate from PHP error_log).
 * Enable via config/app.php action_logging.enabled.
 */
final class AppActionLogger
{
    private static bool $requestBootstrapped = false;

    private static bool $shutdownRegistered = false;

    private static string $requestId = '';

    private static float $t0 = 0.0;

    private static bool $requestLineLogged = false;

    /** @param array<string, mixed> $context */
    public static function requestBegin(): void
    {
        if (!self::configured()) {
            return;
        }
        self::bootstrapRequestOnly();
        if (self::$requestLineLogged || self::$requestId === '') {
            return;
        }
        self::$requestLineLogged = true;
        self::writeLine(array_merge([
            'ts' => self::utcIso(),
            'channel' => 'backend',
            'action' => 'http.request',
            'request_id' => self::$requestId,
            'method' => strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')),
            'path' => self::normalizedPath(),
            'ip' => self::clientIp(),
            'ua' => self::truncate((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 200),
            'surface' => str_starts_with(self::normalizedPath(), 'admin/')
                ? 'admin'
                : 'public',
        ], self::actorContext()));
        self::registerShutdown();
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function event(string $channel, string $action, array $context = []): void
    {
        if (!self::configured()) {
            return;
        }
        self::bootstrapRequestOnly();
        $context['request_id'] = self::$requestId;
        self::registerShutdown();
        self::writeLine(array_merge([
            'ts' => self::utcIso(),
            'channel' => $channel,
            'action' => $action,
        ], self::sanitizeContext($context)));
    }

    private static function registerShutdown(): void
    {
        if (!self::configured()) {
            return;
        }
        if (self::$shutdownRegistered || self::$requestId === '') {
            return;
        }
        self::$shutdownRegistered = true;
        register_shutdown_function(static function (): void {
            if (!self::configured()) {
                return;
            }
            $status = http_response_code();
            if ($status === false) {
                $status = 0;
            }
            self::writeLine(array_merge([
                'ts' => self::utcIso(),
                'channel' => 'backend',
                'action' => 'http.response',
                'request_id' => self::$requestId,
                'status' => (int) $status,
                'ms' => self::$t0 > 0 ? round((microtime(true) - self::$t0) * 1000, 3) : null,
                'surface' => str_starts_with(self::normalizedPath(), 'admin/')
                    ? 'admin'
                    : 'public',
            ], self::actorContext()));
        });
    }

    private static function bootstrapRequestOnly(): void
    {
        if (self::$requestBootstrapped) {
            return;
        }
        self::$requestBootstrapped = true;
        self::$requestId = bin2hex(random_bytes(8));
        self::$t0 = microtime(true);
    }

    private static function configured(): bool
    {
        try {
            $raw = app_config()['action_logging'] ?? null;
        } catch (\Throwable) {
            return false;
        }
        if (!is_array($raw)) {
            return false;
        }
        if (($raw['enabled'] ?? false) !== true) {
            return false;
        }
        $h = (($raw['log_http'] ?? true) !== false);
        $ev = (($raw['log_browser'] ?? true) !== false || ($raw['log_events'] ?? true) !== false);
        if (! $h && ! $ev) {
            return false;
        }

        return trim((string) ($raw['file'] ?? '')) !== '';
    }

    /** @param array<string, mixed> $row */
    private static function writeLine(array $row): void
    {
        try {
            $alg = app_config()['action_logging'];
            if (!is_array($alg) || ($alg['enabled'] ?? false) !== true) {
                return;
            }
        } catch (\Throwable) {
            return;
        }

        $isHttpLifecycle = (($row['action'] ?? '') === 'http.request' || ($row['action'] ?? '') === 'http.response');
        $logHttp = (($alg['log_http'] ?? true) !== false);
        $logEvents = (($alg['log_events'] ?? true) !== false);
        $logBrowser = (($alg['log_browser'] ?? true) !== false);
        $isFrontend = (($row['channel'] ?? '') === 'frontend');
        if ($isHttpLifecycle && ! $logHttp) {
            return;
        }
        if (! $isHttpLifecycle && $isFrontend && ! $logBrowser) {
            return;
        }
        if (! $isHttpLifecycle && ! $isFrontend && ! $logEvents) {
            return;
        }

        $file = trim((string) ($alg['file'] ?? ''));
        if ($file === '') {
            return;
        }
        $encoded = json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($encoded === false) {
            error_log('AppActionLogger: json_encode failed for action ' . (($row['action'] ?? '?')));

            return;
        }

        self::touchDir(dirname($file));
        @file_put_contents($file, $encoded . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private static function touchDir(string $dir): void
    {
        if ($dir === '' || $dir === '.' || is_dir($dir)) {
            return;
        }
        @mkdir($dir, 0755, true);
    }

    /** @param array<string, mixed> $c */
    private static function sanitizeContext(array $c): array
    {
        $out = [];
        foreach ($c as $k => $v) {
            $key = (string) $k;
            if ($key === '') {
                continue;
            }
            if (stripos($key, 'csrf') !== false) {
                continue;
            }
            if (stripos($key, 'password') !== false || stripos($key, 'token') !== false) {
                $out[$key] = '[redacted]';

                continue;
            }
            if (is_scalar($v) || $v === null) {
                $out[$key] = $v;
            } elseif (is_array($v)) {
                $out[$key] = self::sanitizeContextAssoc($v, 6);
            } else {
                $out[$key] = '[non-scalar]';
            }
        }

        return $out;
    }

    /**
     * @param array<mixed> $arr
     * @return array<mixed>|string
     */
    private static function sanitizeContextAssoc(array $arr, int $depth): array|string
    {
        if ($depth <= 0) {
            return '[truncated]';
        }
        $r = [];
        foreach ($arr as $k => $v) {
            $ks = is_string($k) ? $k : (string) $k;
            if (stripos($ks, 'password') !== false || stripos($ks, 'csrf') !== false || stripos($ks, 'token') !== false) {
                $r[$ks] = '[redacted]';

                continue;
            }
            if (is_array($v)) {
                $r[$ks] = self::sanitizeContextAssoc($v, $depth - 1);

                continue;
            }
            $r[$ks] = is_scalar($v) || $v === null ? $v : '[non-scalar]';
        }

        return $r;
    }

    /** @return array<string, string> */
    private static function actorContext(): array
    {
        if (!function_exists('current_admin_user_email')) {
            return [];
        }
        $e = trim((string) current_admin_user_email());
        if ($e === '') {
            return [];
        }

        return ['actor_admin_email' => $e];
    }

    private static function utcIso(): string
    {
        try {
            $d = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

            return $d->format('Y-m-d\TH:i:s.v\Z');
        } catch (\Throwable) {
            return gmdate('Y-m-d\TH:i:s\Z');
        }
    }

    private static function clientIp(): string
    {
        $ip = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));
        if ($ip !== '') {
            return substr($ip, 0, 45);
        }
        $fwd = trim((string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''));
        if ($fwd !== '') {
            $parts = explode(',', $fwd);

            return substr(trim((string) ($parts[0] ?? '')), 0, 45);
        }

        return '';
    }

    private static function normalizedPath(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = trim((string) $path, '/');
        try {
            $cfg = app_config();
            $base = trim((string) ($cfg['base_url'] ?? ''), '/');
        } catch (\Throwable) {
            $base = '';
        }
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = trim(substr($path, strlen($base)), '/');
        }
        if ($path === 'index.php') {
            return '';
        }

        return $path;
    }

    private static function truncate(string $s, int $max): string
    {
        return strlen($s) <= $max ? $s : substr($s, 0, $max) . '…';
    }
}
