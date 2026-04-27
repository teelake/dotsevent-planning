<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class LeadRepository
{
    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function create(string $type, string $email, ?string $name, ?string $phone, ?string $message, ?string $extraJson): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        $st = $this->pdo->prepare(
            'INSERT INTO leads (type, email, name, phone, message, extra) VALUES (:type, :email, :name, :phone, :message, :extra)'
        );
        return $st->execute([
            'type' => $type,
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
            'message' => $message,
            'extra' => $extraJson,
        ]);
    }
}
