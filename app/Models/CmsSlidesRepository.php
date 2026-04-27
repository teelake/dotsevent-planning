<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class CmsSlidesRepository
{
    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /** @return list<array<string, mixed>> */
    public function listAllForAdmin(): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $st = $this->pdo->query(
            'SELECT * FROM cms_slides ORDER BY sort_order ASC, id ASC'
        );
        if ($st === false) {
            return [];
        }
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        return is_array($rows) ? $rows : [];
    }

    /**
     * Slides visible on the storefront: live flag + optional schedule window.
     *
     * @return list<array<string, mixed>>
     */
    public function listLiveForPublic(): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $sql = 'SELECT * FROM cms_slides
            WHERE is_live = 1
            AND (starts_at IS NULL OR starts_at <= NOW())
            AND (ends_at IS NULL OR ends_at >= NOW())
            ORDER BY sort_order ASC, id ASC';
        $st = $this->pdo->query($sql);
        if ($st === false) {
            return [];
        }
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        return is_array($rows) ? $rows : [];
    }

    /** @return array<string, mixed>|null */
    public function findById(int $id): ?array
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare('SELECT * FROM cms_slides WHERE id = :id LIMIT 1');
        $st->execute(['id' => $id]);
        $r = $st->fetch(PDO::FETCH_ASSOC);

        return $r !== false ? $r : null;
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        if ($this->pdo === null) {
            return 0;
        }
        $max = (int) $this->pdo->query('SELECT COALESCE(MAX(sort_order), -1) + 1 AS n FROM cms_slides')->fetchColumn();
        $st = $this->pdo->prepare(
            'INSERT INTO cms_slides (
                sort_order, is_live, badge, headline, supporting,
                btn_primary_label, btn_primary_href, btn_secondary_label, btn_secondary_href,
                image_desktop_path, image_mobile_path, image_alt, starts_at, ends_at
            ) VALUES (
                :sort_order, :is_live, :badge, :headline, :supporting,
                :btn_primary_label, :btn_primary_href, :btn_secondary_label, :btn_secondary_href,
                :image_desktop_path, :image_mobile_path, :image_alt, :starts_at, :ends_at
            )'
        );
        $st->execute([
            'sort_order' => $max,
            'is_live' => (int) ($data['is_live'] ?? 1),
            'badge' => (string) ($data['badge'] ?? ''),
            'headline' => (string) ($data['headline'] ?? ''),
            'supporting' => (string) ($data['supporting'] ?? ''),
            'btn_primary_label' => (string) ($data['btn_primary_label'] ?? ''),
            'btn_primary_href' => (string) ($data['btn_primary_href'] ?? ''),
            'btn_secondary_label' => (string) ($data['btn_secondary_label'] ?? ''),
            'btn_secondary_href' => (string) ($data['btn_secondary_href'] ?? ''),
            'image_desktop_path' => (string) ($data['image_desktop_path'] ?? ''),
            'image_mobile_path' => $data['image_mobile_path'] !== null && $data['image_mobile_path'] !== ''
                ? (string) $data['image_mobile_path'] : null,
            'image_alt' => (string) ($data['image_alt'] ?? ''),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare(
            'UPDATE cms_slides SET
                is_live = :is_live,
                badge = :badge,
                headline = :headline,
                supporting = :supporting,
                btn_primary_label = :btn_primary_label,
                btn_primary_href = :btn_primary_href,
                btn_secondary_label = :btn_secondary_label,
                btn_secondary_href = :btn_secondary_href,
                image_desktop_path = :image_desktop_path,
                image_mobile_path = :image_mobile_path,
                image_alt = :image_alt,
                starts_at = :starts_at,
                ends_at = :ends_at
            WHERE id = :id LIMIT 1'
        );

        return $st->execute([
            'id' => $id,
            'is_live' => (int) ($data['is_live'] ?? 1),
            'badge' => (string) ($data['badge'] ?? ''),
            'headline' => (string) ($data['headline'] ?? ''),
            'supporting' => (string) ($data['supporting'] ?? ''),
            'btn_primary_label' => (string) ($data['btn_primary_label'] ?? ''),
            'btn_primary_href' => (string) ($data['btn_primary_href'] ?? ''),
            'btn_secondary_label' => (string) ($data['btn_secondary_label'] ?? ''),
            'btn_secondary_href' => (string) ($data['btn_secondary_href'] ?? ''),
            'image_desktop_path' => (string) ($data['image_desktop_path'] ?? ''),
            'image_mobile_path' => $data['image_mobile_path'] !== null && $data['image_mobile_path'] !== ''
                ? (string) $data['image_mobile_path'] : null,
            'image_alt' => (string) ($data['image_alt'] ?? ''),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare('DELETE FROM cms_slides WHERE id = :id LIMIT 1');

        return $st->execute(['id' => $id]);
    }

    public function updateSortOrder(int $id, int $sortOrder): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare('UPDATE cms_slides SET sort_order = :s WHERE id = :id LIMIT 1');

        return $st->execute(['s' => $sortOrder, 'id' => $id]);
    }

    /** @param list<int> $orderedIds */
    public function applyOrder(array $orderedIds): void
    {
        if ($this->pdo === null || $orderedIds === []) {
            return;
        }
        foreach ($orderedIds as $i => $slideId) {
            $this->updateSortOrder((int) $slideId, $i);
        }
    }
}
