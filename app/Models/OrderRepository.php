<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use RuntimeException;

final class OrderRepository
{
    private ?PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * @param list<array{product_id:int, quantity:int, unit_price_cents:int}> $lines
     */
    public function createPaid(
        int $totalCents,
        string $currency,
        string $squarePaymentId,
        string $idempotencyKey,
        ?string $email,
        ?string $customerName,
        ?string $phone,
        array $lines
    ): int {
        if ($this->pdo === null) {
            throw new RuntimeException('Database not configured.');
        }
        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare(
                'INSERT INTO orders (customer_email, customer_name, phone, total_cents, currency, status, square_payment_id, idempotency_key)
                 VALUES (:email, :name, :phone, :total, :cur, :status, :sq, :idem)'
            );
            $st->execute([
                'email' => $email,
                'name' => $customerName,
                'phone' => $phone,
                'total' => $totalCents,
                'cur' => strtoupper($currency),
                'status' => 'paid',
                'sq' => $squarePaymentId,
                'idem' => $idempotencyKey,
            ]);
            $orderId = (int) $this->pdo->lastInsertId();
            $li = $this->pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, unit_price_cents) VALUES (:oid, :pid, :qty, :price)'
            );
            foreach ($lines as $line) {
                $li->execute([
                    'oid' => $orderId,
                    'pid' => $line['product_id'],
                    'qty' => $line['quantity'],
                    'price' => $line['unit_price_cents'],
                ]);
            }
            $this->pdo->commit();
            return $orderId;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new RuntimeException('Could not save order.', 0, $e);
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listAll(int $limit, int $offset): array
    {
        if ($this->pdo === null) {
            return [];
        }
        $limit = max(1, min(100, $limit));
        $offset = max(0, $offset);
        $st = $this->pdo->prepare(
            'SELECT o.id, o.customer_email, o.customer_name, o.phone, o.total_cents, o.currency, o.status,
                    o.square_payment_id, o.created_at
             FROM orders o
             ORDER BY o.id DESC
             LIMIT :lim OFFSET :off'
        );
        $st->bindValue('lim', $limit, PDO::PARAM_INT);
        $st->bindValue('off', $offset, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return is_array($rows) ? $rows : [];
    }

    public function countAll(): int
    {
        if ($this->pdo === null) {
            return 0;
        }
        $st = $this->pdo->query('SELECT COUNT(*) FROM orders');
        if ($st === false) {
            return 0;
        }
        return (int) $st->fetchColumn();
    }

    /**
     * @return array{order: array<string, mixed>, items: list<array<string, mixed>>}|null
     */
    public function findWithItems(int $id): ?array
    {
        if ($this->pdo === null) {
            return null;
        }
        $st = $this->pdo->prepare(
            'SELECT id, customer_email, customer_name, phone, total_cents, currency, status, square_payment_id, idempotency_key, created_at
             FROM orders WHERE id = :id LIMIT 1'
        );
        $st->execute(['id' => $id]);
        $order = $st->fetch(PDO::FETCH_ASSOC);
        if ($order === false) {
            return null;
        }
        $li = $this->pdo->prepare(
            'SELECT oi.id, oi.product_id, oi.quantity, oi.unit_price_cents, p.name AS product_name
             FROM order_items oi
             LEFT JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = :oid
             ORDER BY oi.id ASC'
        );
        $li->execute(['oid' => $id]);
        $items = $li->fetchAll(PDO::FETCH_ASSOC);
        return [
            'order' => $order,
            'items' => is_array($items) ? $items : [],
        ];
    }
}
