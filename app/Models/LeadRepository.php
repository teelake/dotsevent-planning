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

    public function countAll(?string $type = null): int
    {
        if ($this->pdo === null) {
            return 0;
        }
        if ($type === null || $type === '') {
            $st = $this->pdo->query('SELECT COUNT(*) FROM leads');
        } else {
            $st = $this->pdo->prepare('SELECT COUNT(*) FROM leads WHERE type = :t');
            $st->execute(['t' => $type]);
        }
        if ($st === false) {
            return 0;
        }
        return (int) $st->fetchColumn();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listAll(int $limit, int $offset, ?string $type = null): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $limit = max(1, min(100, $limit));
        $offset = max(0, $offset);
        if ($type === null || $type === '') {
            $st = $this->pdo->prepare(
                'SELECT id, type, email, name, phone, message, extra, created_at
                 FROM leads ORDER BY id DESC LIMIT :lim OFFSET :off'
            );
            $st->bindValue('lim', $limit, PDO::PARAM_INT);
            $st->bindValue('off', $offset, PDO::PARAM_INT);
            $st->execute();
        } else {
            $st = $this->pdo->prepare(
                'SELECT id, type, email, name, phone, message, extra, created_at
                 FROM leads WHERE type = :t ORDER BY id DESC LIMIT :lim OFFSET :off'
            );
            $st->bindValue('t', $type, PDO::PARAM_STR);
            $st->bindValue('lim', $limit, PDO::PARAM_INT);
            $st->bindValue('off', $offset, PDO::PARAM_INT);
            $st->execute();
        }
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return is_array($rows) ? $rows : [];
    }

    public function countSince(\DateTimeInterface $since): int
    {
        if ($this->pdo === null) {
            return 0;
        }
        $st = $this->pdo->prepare('SELECT COUNT(*) FROM leads WHERE created_at >= :t');
        $st->execute(['t' => $since->format('Y-m-d H:i:s')]);
        return (int) $st->fetchColumn();
    }

    /**
     * @return list<array{date: string, count: int}>
     */
    public function leadsPerDay(int $days): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $days = max(1, min(90, $days));
        $start = (new \DateTimeImmutable('today'))->modify('-' . ($days - 1) . ' days');
        $st = $this->pdo->prepare(
            'SELECT DATE(created_at) AS d, COUNT(*) AS c FROM leads
             WHERE created_at >= :t
             GROUP BY DATE(created_at) ORDER BY d ASC'
        );
        $st->execute(['t' => $start->format('Y-m-d 00:00:00')]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        $byDay = [];
        if (is_array($rows)) {
            foreach ($rows as $row) {
                $d = (string) ($row['d'] ?? '');
                if ($d !== '') {
                    $byDay[$d] = (int) ($row['c'] ?? 0);
                }
            }
        }
        $out = [];
        for ($i = 0; $i < $days; $i++) {
            $d = $start->modify('+' . $i . ' days')->format('Y-m-d');
            $out[] = ['date' => $d, 'count' => $byDay[$d] ?? 0];
        }
        return $out;
    }
}
