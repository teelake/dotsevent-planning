<?php

declare(strict_types=1);

namespace App\Core;

final class Flash
{
    public const SUCCESS = 'success';
    public const ERROR = 'error';
    public const NOTICE = 'notice';

    public static function set(string $key, string $message): void
    {
        Session::start();
        $_SESSION['_flash'][$key] = $message;
    }

    public static function get(string $key): ?string
    {
        Session::start();
        if (!isset($_SESSION['_flash'][$key])) {
            return null;
        }
        $m = (string) $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $m;
    }
}
