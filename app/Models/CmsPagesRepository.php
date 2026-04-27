<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class CmsPagesRepository
{
    use CmsRepositorySafety;

    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * @return array{slug: string, title: string, content_json: string}|null
     */
    public function findBySlug(string $slug): ?array
    {
        if ($this->pdo === null) {
            return null;
        }

        return $this->runCmsOrMissingTable(function () use ($slug): ?array {
            $st = $this->pdo->prepare('SELECT slug, title, content_json FROM cms_pages WHERE slug = :s LIMIT 1');
            $st->execute(['s' => $slug]);
            $r = $st->fetch(PDO::FETCH_ASSOC);
            if ($r === false) {
                return null;
            }

            return [
                'slug' => (string) ($r['slug'] ?? ''),
                'title' => (string) ($r['title'] ?? ''),
                'content_json' => (string) ($r['content_json'] ?? ''),
            ];
        }, null);
    }

    public function upsert(string $slug, string $title, string $contentJson): bool
    {
        if ($this->pdo === null) {
            return false;
        }

        return $this->runCmsOrMissingTable(function () use ($slug, $title, $contentJson): bool {
            $st = $this->pdo->prepare(
                'INSERT INTO cms_pages (slug, title, content_json) VALUES (:s, :t, :c)
                 ON DUPLICATE KEY UPDATE title = VALUES(title), content_json = VALUES(content_json)'
            );

            return $st->execute([
                's' => $slug,
                't' => $title,
                'c' => $contentJson,
            ]);
        }, false);
    }
}

