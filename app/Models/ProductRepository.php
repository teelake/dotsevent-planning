<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class ProductRepository
{
    private ?PDO $pdo;

    /** Cached per-process: true once migration-003 columns are present */
    private static ?bool $extendedCols = null;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Checks whether the migration-003 columns exist (once per PHP process).
     */
    private function hasExtendedCols(): bool
    {
        if (self::$extendedCols !== null) {
            return self::$extendedCols;
        }
        if ($this->pdo === null) {
            return self::$extendedCols = false;
        }
        try {
            $st = $this->pdo->query(
                "SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME   = 'products'
                   AND COLUMN_NAME  = 'price_max_cents'"
            );
            self::$extendedCols = (int) $st->fetchColumn() > 0;
        } catch (\Throwable) {
            self::$extendedCols = false;
        }
        return self::$extendedCols;
    }

    /** Core columns present in the original schema */
    private const COLS_LEGACY = 'id, slug, name, description, price_cents, currency, image_url, stock, has_options';

    /** Extended columns added by migration-003 */
    private const COLS_EXTRA = ', price_max_cents, category_key, badge_label, details, ideal_for, policy_note';

    private function catalogCols(): string
    {
        return $this->hasExtendedCols()
            ? self::COLS_LEGACY . self::COLS_EXTRA
            : self::COLS_LEGACY;
    }

    /**
     * Ensures every row has the extended keys (null when migration not yet run).
     *
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private static function normalise(array $row): array
    {
        $row['price_max_cents'] ??= null;
        $row['category_key']    ??= null;
        $row['badge_label']     ??= null;
        $row['details']         ??= null;
        $row['ideal_for']       ??= null;
        $row['policy_note']     ??= null;
        return $row;
    }

    /** @return list<array<string, mixed>> */
    public function allActive(): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $st = $this->pdo->query(
            'SELECT ' . $this->catalogCols() . '
             FROM products WHERE is_active = 1 ORDER BY sort_order ASC, name ASC'
        );
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        if (!is_array($rows)) {
            return [];
        }
        return array_map([self::class, 'normalise'], $rows);
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare(
            'SELECT ' . $this->catalogCols() . '
             FROM products WHERE id = :id AND is_active = 1 LIMIT 1'
        );
        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? self::normalise($row) : null;
    }

    /**
     * Fetches all options for a product, ordered by sort_order.
     * Returns an empty array when the product_options table doesn't exist yet.
     *
     * @return list<array{id:int, label:string, price_cents:int, sort_order:int}>
     */
    public function findOptions(int $productId): array
    {
        if ($this->pdo === null) {
            return [];
        }
        try {
            $st = $this->pdo->prepare(
                'SELECT id, label, price_cents, sort_order
                 FROM product_options
                 WHERE product_id = :pid
                 ORDER BY sort_order ASC, id ASC'
            );
            $st->execute(['pid' => $productId]);
            $rows = $st->fetchAll(PDO::FETCH_ASSOC);
            return is_array($rows) ? $rows : [];
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Returns up to $limit active products in the same category, excluding the current product.
     *
     * @return list<array<string, mixed>>
     */
    public function relatedByCategory(int $currentId, string $categoryKey, int $limit = 4): array
    {
        if ($this->pdo === null || $categoryKey === '' || !$this->hasExtendedCols()) {
            return [];
        }
        $st = $this->pdo->prepare(
            'SELECT ' . self::COLS_LEGACY . self::COLS_EXTRA . '
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
        if (!is_array($rows)) {
            return [];
        }
        return array_map([self::class, 'normalise'], $rows);
    }

    /** @return list<array<string, mixed>> */
    public function allForAdmin(): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $extra = $this->hasExtendedCols() ? self::COLS_EXTRA : '';
        $st = $this->pdo->query(
            'SELECT id, slug, name, description, price_cents' . $extra . ',
                    currency, image_url, stock, has_options, is_active, sort_order, created_at
             FROM products ORDER BY sort_order ASC, id ASC'
        );
        if ($st === false) {
            return [];
        }
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        if (!is_array($rows)) {
            return [];
        }
        return array_map([self::class, 'normalise'], $rows);
    }

    /** @return array<string, mixed>|null */
    public function findAny(int $id): ?array
    {
        if ($this->pdo === null) {
            return null;
        }
        $extra = $this->hasExtendedCols() ? self::COLS_EXTRA : '';
        $st = $this->pdo->prepare(
            'SELECT id, slug, name, description, price_cents' . $extra . ',
                    currency, image_url, stock, has_options, is_active, sort_order, created_at
             FROM products WHERE id = :id LIMIT 1'
        );
        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? self::normalise($row) : null;
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
            'slug'   => $d['slug'],
            'name'   => $d['name'],
            'desc'   => $d['description'],
            'price'  => $d['price_cents'],
            'cur'    => strtoupper($d['currency']),
            'img'    => $d['image_url'],
            'stock'  => $d['stock'],
            'opts'   => $d['has_options'],
            'active' => $d['is_active'],
            'sort'   => $d['sort_order'],
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
            'id'     => $id,
            'slug'   => $d['slug'],
            'name'   => $d['name'],
            'desc'   => $d['description'],
            'price'  => $d['price_cents'],
            'cur'    => strtoupper($d['currency']),
            'img'    => $d['image_url'],
            'stock'  => $d['stock'],
            'opts'   => $d['has_options'],
            'active' => $d['is_active'],
            'sort'   => $d['sort_order'],
        ]);
    }
}
