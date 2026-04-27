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

    /** @return list<array<string, mixed>> */
    public function allActive(): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $st = $this->pdo->query(
            'SELECT id, slug, name, description, price_cents, currency, image_url, stock, has_options
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
            'SELECT id, slug, name, description, price_cents, currency, image_url, stock, has_options
             FROM products WHERE id = :id AND is_active = 1 LIMIT 1'
        );
        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }
}
