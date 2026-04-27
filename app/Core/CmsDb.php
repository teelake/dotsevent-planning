<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Detect missing CMS tables (e.g. schema not applied on this database yet).
 */
final class CmsDb
{
    public static function isMissingTable(\Throwable $e): bool
    {
        if ($e instanceof \PDOException) {
            $info = $e->errorInfo ?? [];
            if (($info[0] ?? '') === '42S02') {
                return true;
            }
        }
        $msg = $e->getMessage();

        return str_contains($msg, 'Base table or view not found')
            || str_contains($msg, "doesn't exist");
    }
}
