<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Simple session-based admin auth (see users table).
 */
final class AdminAuth
{
    private const KEY = 'admin_user_id';

    public static function id(): ?int
    {
        $v = $_SESSION[self::KEY] ?? null;
        if ($v === null || $v === '') {
            return null;
        }
        $id = (int) $v;
        return $id > 0 ? $id : null;
    }

    public static function login(int $userId): void
    {
        $_SESSION[self::KEY] = $userId;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::KEY]);
    }
}
