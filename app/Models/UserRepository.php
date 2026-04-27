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

    public function findPasswordHashById(int $id): ?string
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare('SELECT password_hash FROM users WHERE id = :id LIMIT 1');
        $st->execute(['id' => $id]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if ($r === false) {
            return null;
        }
        $h = (string) ($r['password_hash'] ?? '');
        return $h !== '' ? $h : null;
    }

    public function emailTakenByOtherUser(string $email, int $exceptId): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare('SELECT id FROM users WHERE email = :e AND id != :id LIMIT 1');
        $st->execute(['e' => $email, 'id' => $exceptId]);
        return $st->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function updateEmail(int $id, string $email): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare('UPDATE users SET email = :e WHERE id = :id LIMIT 1');
        return $st->execute(['e' => $email, 'id' => $id]);
    }

    public function updatePasswordHash(int $id, string $passwordHash): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare('UPDATE users SET password_hash = :h WHERE id = :id LIMIT 1');
        return $st->execute(['h' => $passwordHash, 'id' => $id]);
    }
}
