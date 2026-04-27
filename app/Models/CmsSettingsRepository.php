<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class CmsSettingsRepository
{
    use CmsRepositorySafety;

    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        if ($this->pdo === null) {
            return [];
        }

        return $this->runCmsOrMissingTable(function (): array {
            $st = $this->pdo->query('SELECT `key`, `value` FROM cms_settings');
            if ($st === false) {
                return [];
            }
            $rows = $st->fetchAll(PDO::FETCH_ASSOC);
            $out = [];
            if (is_array($rows)) {
                foreach ($rows as $r) {
                    $k = (string) ($r['key'] ?? '');
                    if ($k === '') {
                        continue;
                    }
                    $out[$k] = (string) ($r['value'] ?? '');
                }
            }

            return $out;
        }, []);
    }

    public function get(string $key): ?string
    {
        if ($this->pdo === null) {
            return null;
        }

        return $this->runCmsOrMissingTable(function () use ($key): ?string {
            $st = $this->pdo->prepare('SELECT `value` FROM cms_settings WHERE `key` = :k LIMIT 1');
            $st->execute(['k' => $key]);
            $v = $st->fetchColumn();
            if ($v === false) {
                return null;
            }

            return (string) $v;
        }, null);
    }

    public function set(string $key, ?string $value): bool
    {
        if ($this->pdo === null) {
            return false;
        }

        return $this->runCmsOrMissingTable(function () use ($key, $value): bool {
            $st = $this->pdo->prepare(
                'INSERT INTO cms_settings (`key`, `value`) VALUES (:k, :v)
                 ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
            );

            return $st->execute(['k' => $key, 'v' => $value]);
        }, false);
    }

    /**
     * @param array<string, string|null> $pairs
     */
    public function setMany(array $pairs): bool
    {
        if ($this->pdo === null) {
            return false;
        }

        return $this->runCmsOrMissingTable(function () use ($pairs): bool {
            $this->pdo->beginTransaction();
            try {
                $st = $this->pdo->prepare(
                    'INSERT INTO cms_settings (`key`, `value`) VALUES (:k, :v)
                     ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
                );
                foreach ($pairs as $k => $v) {
                    $st->execute(['k' => (string) $k, 'v' => $v]);
                }
                $this->pdo->commit();

                return true;
            } catch (\Throwable) {
                $this->pdo->rollBack();

                return false;
            }
        }, false);
    }
}

