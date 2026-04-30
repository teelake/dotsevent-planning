<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class CmsPagesRepository
{
    use CmsRepositorySafety;

    private ?PDO $pdo;
    private static ?bool $fieldStorage = null;
    private static ?bool $legacyJsonColumn = null;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /** @return array{slug: string, title: string, content_json: string}|null */
    public function findBySlug(string $slug): ?array
    {
        if ($this->pdo === null) {
            return null;
        }

        return $this->runCmsOrMissingTable(function () use ($slug): ?array {
            $st = $this->pdo->prepare('SELECT id, slug, title FROM cms_pages WHERE slug = :s LIMIT 1');
            $st->execute(['s' => $slug]);
            $r = $st->fetch(PDO::FETCH_ASSOC);
            if ($r === false) {
                return null;
            }
            $data = $this->hasFieldStorage()
                ? $this->loadPageFields((int) ($r['id'] ?? 0))
                : $this->loadLegacyContentJson($slug);
            $encoded = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            return [
                'slug' => (string) ($r['slug'] ?? ''),
                'title' => (string) ($r['title'] ?? ''),
                'content_json' => is_string($encoded) ? $encoded : '{}',
            ];
        }, null);
    }

    public function upsert(string $slug, string $title, string $contentJson): bool
    {
        if ($this->pdo === null) {
            return false;
        }

        return $this->runCmsOrMissingTable(function () use ($slug, $title, $contentJson): bool {
            if (! $this->hasFieldStorage()) {
                return $this->upsertLegacy($slug, $title, $contentJson);
            }
            $data = json_decode($contentJson, true);
            if (!is_array($data)) {
                $data = [];
            }
            $st = $this->pdo->prepare(
                'INSERT INTO cms_pages (slug, title) VALUES (:s, :t)
                 ON DUPLICATE KEY UPDATE title = VALUES(title)'
            );
            if (! $st->execute(['s' => $slug, 't' => $title])) {
                return false;
            }
            $pageId = $this->pageId($slug);
            if ($pageId <= 0) {
                return false;
            }
            $del = $this->pdo->prepare('DELETE FROM cms_page_fields WHERE page_id = :id');
            $del->execute(['id' => $pageId]);
            $rows = [];
            self::flattenFields($data, '', $rows);
            if ($rows === []) {
                return true;
            }
            $ins = $this->pdo->prepare(
                'INSERT INTO cms_page_fields (page_id, field_key, field_type, field_value)
                 VALUES (:page_id, :field_key, :field_type, :field_value)'
            );
            foreach ($rows as $row) {
                if (! $ins->execute([
                    'page_id' => $pageId,
                    'field_key' => $row['key'],
                    'field_type' => $row['type'],
                    'field_value' => $row['value'],
                ])) {
                    return false;
                }
            }

            return true;
        }, false);
    }

    private function hasFieldStorage(): bool
    {
        if (self::$fieldStorage !== null) {
            return self::$fieldStorage;
        }
        if ($this->pdo === null) {
            return self::$fieldStorage = false;
        }
        try {
            $st = $this->pdo->query(
                "SELECT COUNT(*) FROM information_schema.TABLES
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'cms_page_fields'"
            );
            self::$fieldStorage = (int) $st->fetchColumn() > 0;
        } catch (\Throwable) {
            self::$fieldStorage = false;
        }
        return self::$fieldStorage;
    }

    private function hasLegacyJsonColumn(): bool
    {
        if (self::$legacyJsonColumn !== null) {
            return self::$legacyJsonColumn;
        }
        if ($this->pdo === null) {
            return self::$legacyJsonColumn = false;
        }
        try {
            $st = $this->pdo->query(
                "SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'cms_pages'
                   AND COLUMN_NAME = 'content_json'"
            );
            self::$legacyJsonColumn = (int) $st->fetchColumn() > 0;
        } catch (\Throwable) {
            self::$legacyJsonColumn = false;
        }
        return self::$legacyJsonColumn;
    }

    /** @return array<string, mixed> */
    private function loadLegacyContentJson(string $slug): array
    {
        if (! $this->hasLegacyJsonColumn()) {
            return [];
        }
        $st = $this->pdo->prepare('SELECT content_json FROM cms_pages WHERE slug = :s LIMIT 1');
        $st->execute(['s' => $slug]);
        $raw = (string) ($st->fetchColumn() ?: '');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function upsertLegacy(string $slug, string $title, string $contentJson): bool
    {
        if (! $this->hasLegacyJsonColumn()) {
            $st = $this->pdo->prepare(
                'INSERT INTO cms_pages (slug, title) VALUES (:s, :t)
                 ON DUPLICATE KEY UPDATE title = VALUES(title)'
            );
            return $st->execute(['s' => $slug, 't' => $title]);
        }
        $st = $this->pdo->prepare(
            'INSERT INTO cms_pages (slug, title, content_json) VALUES (:s, :t, :c)
             ON DUPLICATE KEY UPDATE title = VALUES(title), content_json = VALUES(content_json)'
        );
        return $st->execute(['s' => $slug, 't' => $title, 'c' => $contentJson]);
    }

    private function pageId(string $slug): int
    {
        $st = $this->pdo->prepare('SELECT id FROM cms_pages WHERE slug = :s LIMIT 1');
        $st->execute(['s' => $slug]);
        return (int) ($st->fetchColumn() ?: 0);
    }

    /** @return array<string, mixed> */
    private function loadPageFields(int $pageId): array
    {
        if ($pageId <= 0) {
            return [];
        }
        $st = $this->pdo->prepare(
            'SELECT field_key, field_type, field_value
             FROM cms_page_fields
             WHERE page_id = :id
             ORDER BY field_key ASC'
        );
        $st->execute(['id' => $pageId]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        if (!is_array($rows)) {
            return [];
        }
        $out = [];
        foreach ($rows as $row) {
            self::setNestedValue(
                $out,
                explode('.', (string) ($row['field_key'] ?? '')),
                self::castFieldValue((string) ($row['field_type'] ?? 'string'), $row['field_value'] ?? null)
            );
        }
        return $out;
    }

    /**
     * @param array<string, mixed>|list<mixed> $data
     * @param list<array{key:string,type:string,value:?string}> $rows
     */
    private static function flattenFields(array $data, string $prefix, array &$rows): void
    {
        foreach ($data as $key => $value) {
            $path = $prefix === '' ? (string) $key : $prefix . '.' . (string) $key;
            if (is_array($value)) {
                self::flattenFields($value, $path, $rows);
                continue;
            }
            [$type, $stored] = self::serialiseScalar($value);
            $rows[] = ['key' => $path, 'type' => $type, 'value' => $stored];
        }
    }

    /** @return array{0:string,1:?string} */
    private static function serialiseScalar(mixed $value): array
    {
        if (is_bool($value)) {
            return ['bool', $value ? '1' : '0'];
        }
        if (is_int($value)) {
            return ['int', (string) $value];
        }
        if (is_float($value)) {
            return ['float', (string) $value];
        }
        if ($value === null) {
            return ['null', null];
        }
        return ['string', (string) $value];
    }

    private static function castFieldValue(string $type, mixed $value): mixed
    {
        $raw = $value === null ? null : (string) $value;
        return match ($type) {
            'bool' => $raw === '1',
            'int' => (int) $raw,
            'float' => (float) $raw,
            'null' => null,
            default => (string) ($raw ?? ''),
        };
    }

    /**
     * @param array<string, mixed> $target
     * @param list<string> $parts
     */
    private static function setNestedValue(array &$target, array $parts, mixed $value): void
    {
        if ($parts === []) {
            return;
        }
        $cursor =& $target;
        foreach ($parts as $i => $part) {
            if ($part === '') {
                return;
            }
            $key = ctype_digit($part) ? (int) $part : $part;
            if ($i === count($parts) - 1) {
                $cursor[$key] = $value;
                return;
            }
            if (!isset($cursor[$key]) || !is_array($cursor[$key])) {
                $cursor[$key] = [];
            }
            $cursor =& $cursor[$key];
        }
    }
}

