<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class CmsMediaRepository
{
    use CmsRepositorySafety;

    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function create(string $filePath, string $mime, int $sizeBytes, ?string $originalName): int
    {
        if ($this->pdo === null) {
            return 0;
        }

        return (int) $this->runCmsOrMissingTable(function () use ($filePath, $mime, $sizeBytes, $originalName): int {
            $st = $this->pdo->prepare(
                'INSERT INTO cms_media (file_path, mime, size_bytes, original_name) VALUES (:p, :m, :s, :o)'
            );
            $ok = $st->execute([
                'p' => $filePath,
                'm' => $mime,
                's' => max(0, $sizeBytes),
                'o' => $originalName,
            ]);
            if (!$ok) {
                return 0;
            }

            return (int) $this->pdo->lastInsertId();
        }, 0);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listRecent(int $limit = 40): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $limit = max(1, min(200, $limit));

        return $this->runCmsOrMissingTable(function () use ($limit): array {
            $st = $this->pdo->prepare('SELECT id, file_path, mime, size_bytes, original_name, created_at FROM cms_media ORDER BY id DESC LIMIT :lim');
            $st->bindValue('lim', $limit, PDO::PARAM_INT);
            $st->execute();
            $rows = $st->fetchAll(PDO::FETCH_ASSOC);

            return is_array($rows) ? $rows : [];
        }, []);
    }
}

