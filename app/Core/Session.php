<?php

declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = \app_config();
            $base = rtrim((string) ($config['base_url'] ?? ''), '/');
            $cookiePath = $base === '' ? '/' : $base;
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => $cookiePath,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }
}