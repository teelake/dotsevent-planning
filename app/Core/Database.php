<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): ?PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $configPath = dirname(__DIR__, 2) . '/config/database.php';
        if (!is_file($configPath)) {
            return null;
        }

        $c = require $configPath;
        if (!is_array($c)) {
            return null;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $c['host'] ?? '127.0.0.1',
            (int) ($c['port'] ?? 3306),
            $c['database'] ?? 'dots_event',
            $c['charset'] ?? 'utf8mb4'
        );

        try {
            self::$instance = new PDO(
                $dsn,
                (string) ($c['username'] ?? 'root'),
                (string) ($c['password'] ?? ''),
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            if (($c['soft_fail'] ?? false) === true) {
                return null;
            }
            throw new RuntimeException('Database connection failed.', 0, $e);
        }

        return self::$instance;
    }
}
