<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class LeadRepository
{
    private ?PDO $pdo;
    private static ?bool $extendedCols = null;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

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
                   AND TABLE_NAME = 'leads'
                   AND COLUMN_NAME = 'subject'"
            );
            self::$extendedCols = (int) $st->fetchColumn() > 0;
        } catch (\Throwable) {
            self::$extendedCols = false;
        }
        return self::$extendedCols;
    }

    public function create(
        string $type,
        string $email,
        ?string $name,
        ?string $phone,
        ?string $message,
        ?string $subject = null,
        ?string $packageKey = null,
        ?string $eventDate = null,
        ?string $guestCount = null,
        ?string $venueCity = null
    ): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        if (! $this->hasExtendedCols()) {
            $legacyExtra = null;
            $legacyParts = [];
            if ($subject !== null && $subject !== '') {
                $legacyParts[] = 'Subject: ' . $subject;
            }
            if ($packageKey !== null && $packageKey !== '') {
                $legacyParts[] = 'Package: ' . $packageKey;
            }
            if ($eventDate !== null && $eventDate !== '') {
                $legacyParts[] = 'Event date: ' . $eventDate;
            }
            if ($guestCount !== null && $guestCount !== '') {
                $legacyParts[] = 'Guests: ' . $guestCount;
            }
            if ($venueCity !== null && $venueCity !== '') {
                $legacyParts[] = 'Venue/city: ' . $venueCity;
            }
            if ($legacyParts !== []) {
                $legacyExtra = implode("\n", $legacyParts);
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
                'extra' => $legacyExtra,
            ]);
        }
        $st = $this->pdo->prepare(
            'INSERT INTO leads (type, email, name, phone, message, subject, package_key, event_date, guest_count, venue_city)
             VALUES (:type, :email, :name, :phone, :message, :subject, :package_key, :event_date, :guest_count, :venue_city)'
        );
        return $st->execute([
            'type' => $type,
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
            'message' => $message,
            'subject' => $subject,
            'package_key' => $packageKey,
            'event_date' => $eventDate,
            'guest_count' => $guestCount,
            'venue_city' => $venueCity,
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
        $cols = $this->hasExtendedCols()
            ? 'id, type, email, name, phone, message, subject, package_key, event_date, guest_count, venue_city, created_at'
            : 'id, type, email, name, phone, message, extra, created_at';
        if ($type === null || $type === '') {
            $st = $this->pdo->prepare(
                'SELECT ' . $cols . '
                 FROM leads ORDER BY id DESC LIMIT :lim OFFSET :off'
            );
            $st->bindValue('lim', $limit, PDO::PARAM_INT);
            $st->bindValue('off', $offset, PDO::PARAM_INT);
            $st->execute();
        } else {
            $st = $this->pdo->prepare(
                'SELECT ' . $cols . '
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
