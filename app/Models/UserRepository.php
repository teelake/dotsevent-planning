<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class UserRepository
{
    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /** @return array<string, mixed>|null */
    public function findById(int $id): ?array
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare('SELECT id, email, role, created_at FROM users WHERE id = :id LIMIT 1');
        $st->execute(['id' => $id]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r !== false ? $r : null;
    }

    /** @return array<string, mixed>|null */
    public function findByEmail(string $email): ?array
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare('SELECT id, email, password_hash, role, created_at FROM users WHERE email = :e LIMIT 1');
        $st->execute(['e' => $email]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r !== false ? $r : null;
    }
}
