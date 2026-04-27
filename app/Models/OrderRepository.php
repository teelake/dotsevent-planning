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
}
