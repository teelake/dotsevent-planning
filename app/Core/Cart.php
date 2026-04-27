<?php

declare(strict_types=1);

namespace App\Core;

final class Cart
{
    private const KEY = 'shop_cart';

    /** @return array<int, int> productId => quantity */
    public static function items(): array
    {
        Session::start();
        $raw = $_SESSION[self::KEY] ?? [];
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        foreach ($raw as $id => $q) {
            $pid = (int) $id;
            $qty = max(0, (int) $q);
            if ($pid > 0 && $qty > 0) {
                $out[$pid] = $qty;
            }
        }
        return $out;
    }

    public static function count(): int
    {
        $n = 0;
        foreach (self::items() as $q) {
            $n += $q;
        }
        return $n;
    }

    public static function set(int $productId, int $quantity): void
    {
        Session::start();
        if (!isset($_SESSION[self::KEY]) || !is_array($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }
        if ($quantity < 1) {
            unset($_SESSION[self::KEY][$productId]);
            return;
        }
        $_SESSION[self::KEY][$productId] = $quantity;
    }

    public static function add(int $productId, int $addQty = 1): void
    {
        $items = self::items();
        $cur = $items[$productId] ?? 0;
        self::set($productId, $cur + max(1, $addQty));
    }

    public static function remove(int $productId): void
    {
        self::set($productId, 0);
    }

    public static function clear(): void
    {
        Session::start();
        $_SESSION[self::KEY] = [];
    }
}
