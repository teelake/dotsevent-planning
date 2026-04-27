<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string
    {
        Session::start();
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function validate(?string $token): bool
    {
        Session::start();
        $expected = $_SESSION['_csrf_token'] ?? '';
        if ($expected === '' || $token === null) {
            return false;
        }
        return hash_equals($expected, $token);
    }
}
