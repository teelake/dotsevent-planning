<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\CmsDb;
use PDOException;

/**
 * Catch "table does not exist" so the site stays usable until migrations are run.
 */
trait CmsRepositorySafety
{
    /**
     * @template T
     * @param callable(): T $fn
     * @param T $onMissingTable
     * @return T
     */
    protected function runCmsOrMissingTable(callable $fn, mixed $onMissingTable): mixed
    {
        try {
            return $fn();
        } catch (PDOException $e) {
            if (CmsDb::isMissingTable($e)) {
                return $onMissingTable;
            }
            throw $e;
        }
    }
}
