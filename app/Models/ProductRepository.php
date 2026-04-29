<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class ProductRepository
{
    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    private const CATALOG_COLS = 'id, slug, name, description, price_cents, price_max_cents, currency, image_url, stock, has_options, category_key, badge_label, meta_json';

    /** @return list<array<string, mixed>> */
    public function allActive(): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $st = $this->pdo->query(
            'SELECT ' . self::CATALOG_COLS . '
             FROM products WHERE is_active = 1 ORDER BY sort_order ASC, name ASC'
        );
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return is_array($rows) ? $rows : [];
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare(
            'SELECT ' . self::CATALOG_COLS . '
             FROM products WHERE id = :id AND is_active = 1 LIMIT 1'
        );
        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    /**
     * Returns up to $limit active products in the same category, excluding the current product.
     *
     * @return list<array<string, mixed>>
     */
    public function relatedByCategory(int $currentId, string $categoryKey, int $limit = 4): array
    {
        if ($this->pdo === null || $categoryKey === '') {
            return [];
        }
        $st = $this->pdo->prepare(
            'SELECT ' . self::CATALOG_COLS . '
             FROM products
             WHERE is_active = 1 AND category_key = :cat AND id != :id
             ORDER BY sort_order ASC, name ASC
             LIMIT :lim'
        );
        $st->bindValue('cat', $categoryKey);
        $st->bindValue('id', $currentId, PDO::PARAM_INT);
        $st->bindValue('lim', $limit, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return is_array($rows) ? $rows : [];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForAdmin(): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $st = $this->pdo->query(
            'SELECT id, slug, name, description, price_cents, price_max_cents, currency, image_url, stock, has_options, category_key, badge_label, meta_json, is_active, sort_order, created_at
             FROM products ORDER BY sort_order ASC, id ASC'
        );
        if ($st === false) {
            return [];
        }
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return is_array($rows) ? $rows : [];
    }

    /** @return array<string, mixed>|null */
    public function findAny(int $id): ?array
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare(
            'SELECT id, slug, name, description, price_cents, price_max_cents, currency, image_url, stock, has_options, category_key, badge_label, meta_json, is_active, sort_order, created_at
             FROM products WHERE id = :id LIMIT 1'
        );
        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        if ($exceptId === null) {
            $st = $this->pdo->prepare('SELECT 1 FROM products WHERE slug = :s LIMIT 1');
            $st->execute(['s' => $slug]);
        } else {
            $st = $this->pdo->prepare('SELECT 1 FROM products WHERE slug = :s AND id != :id LIMIT 1');
            $st->execute(['s' => $slug, 'id' => $exceptId]);
        }
        return (bool) $st->fetchColumn();
    }

    /**
     * @param array{
     *   slug: string, name: string, description: ?string, price_cents: int, currency: string,
     *   image_url: ?string, stock: ?int, has_options: int, is_active: int, sort_order: int
     * } $d
     */
    public function create(array $d): int
    {
        if ($this->pdo === null) {
            return 0;
        }
        $st = $this->pdo->prepare(
            'INSERT INTO products (slug, name, description, price_cents, currency, image_url, stock, has_options, is_active, sort_order)
             VALUES (:slug, :name, :desc, :price, :cur, :img, :stock, :opts, :active, :sort)'
        );
        $st->execute([
            'slug' => $d['slug'],
            'name' => $d['name'],
            'desc' => $d['description'],
            'price' => $d['price_cents'],
            'cur' => strtoupper($d['currency']),
            'img' => $d['image_url'],
            'stock' => $d['stock'],
            'opts' => $d['has_options'],
            'active' => $d['is_active'],
            'sort' => $d['sort_order'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array{
     *   slug: string, name: string, description: ?string, price_cents: int, currency: string,
     *   image_url: ?string, stock: ?int, has_options: int, is_active: int, sort_order: int
     * } $d
     */
    public function update(int $id, array $d): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare(
            'UPDATE products SET slug = :slug, name = :name, description = :desc, price_cents = :price,
                currency = :cur, image_url = :img, stock = :stock, has_options = :opts, is_active = :active, sort_order = :sort
             WHERE id = :id'
        );
        return $st->execute([
            'id' => $id,
            'slug' => $d['slug'],
            'name' => $d['name'],
            'desc' => $d['description'],
            'price' => $d['price_cents'],
            'cur' => strtoupper($d['currency']),
            'img' => $d['image_url'],
            'stock' => $d['stock'],
            'opts' => $d['has_options'],
            'active' => $d['is_active'],
            'sort' => $d['sort_order'],
        ]);
    }
}
