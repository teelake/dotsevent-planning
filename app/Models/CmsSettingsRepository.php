<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class CmsSettingsRepository
{
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
    }

    public function get(string $key): ?string
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare('SELECT `value` FROM cms_settings WHERE `key` = :k LIMIT 1');
        $st->execute(['k' => $key]);
        $v = $st->fetchColumn();
        if ($v === false) {
            return null;
        }
        return (string) $v;
    }

    public function set(string $key, ?string $value): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare(
            'INSERT INTO cms_settings (`key`, `value`) VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
        );
        return $st->execute(['k' => $key, 'v' => $value]);
    }

    /**
     * @param array<string, string|null> $pairs
     */
    public function setMany(array $pairs): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $this->pdo->beginTransaction();
        try {
            foreach ($pairs as $k => $v) {
                $this->set((string) $k, $v);
            }
            $this->pdo->commit();
            return true;
        } catch (\Throwable) {
            $this->pdo->rollBack();
            return false;
        }
    }
}

