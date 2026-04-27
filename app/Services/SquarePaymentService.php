<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * Square Payments API (REST) — no Composer SDK required.
 * @see https://developer.squareup.com/reference/square/payments-api/create-payment
 */
final class SquarePaymentService
{
    private string $baseUrl;
    private string $accessToken;
    private string $locationId;
    private string $apiVersion;

    public function __construct(?array $config = null)
    {
        $c = $config ?? $this->loadConfig();
        if ($c === null) {
            throw new RuntimeException('Square is not configured. Copy config/square.php.example to config/square.php.');
        }
        $env = (string) ($c['environment'] ?? 'sandbox');
        $this->baseUrl = $env === 'production'
            ? 'https://connect.squareup.com'
            : 'https://connect.squareupsandbox.com';
        $this->accessToken = (string) ($c['access_token'] ?? '');
        $this->locationId = (string) ($c['location_id'] ?? '');
        $this->apiVersion = (string) ($c['api_version'] ?? '2025-01-23');
        if ($this->accessToken === '' || $this->locationId === '') {
            throw new RuntimeException('Square access_token and location_id are required.');
        }
    }

    private function loadConfig(): ?array
    {
        $path = dirname(__DIR__, 2) . '/config/square.php';
        if (!is_file($path)) {
            return null;
        }
        $c = require $path;
        return is_array($c) ? $c : null;
    }

    /**
     * @return array{payment: array<string, mixed>, raw: array<string, mixed>}
     */
    public function createPayment(
        string $sourceId,
        int $amountCents,
        string $currency,
        string $idempotencyKey,
        ?string $customerEmail = null,
        ?string $note = null
    ): array {
        if ($amountCents < 1) {
            throw new RuntimeException('Invalid amount.');
        }
        $currency = strtoupper($currency);
        $body = [
            'idempotency_key' => $idempotencyKey,
            'source_id' => $sourceId,
            'location_id' => $this->locationId,
            'amount_money' => [
                'amount' => $amountCents,
                'currency' => $currency,
            ],
        ];
        if ($note !== null && $note !== '') {
            $body['note'] = mb_substr($note, 0, 500);
        }
        if ($customerEmail !== null && filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $body['buyer_email_address'] = $customerEmail;
        }

        $raw = $this->postJson('/v2/payments', $body);
        if (!empty($raw['errors'])) {
            $msg = $this->formatErrors($raw['errors']);
            throw new RuntimeException($msg);
        }
        $payment = $raw['payment'] ?? null;
        if (!is_array($payment)) {
            throw new RuntimeException('Unexpected Square response.');
        }
        return ['payment' => $payment, 'raw' => $raw];
    }

    /**
     * @param array<string, mixed> $body
     * @return array<string, mixed>
     */
    private function postJson(string $path, array $body): array
    {
        $url = $this->baseUrl . $path;
        $payload = json_encode($body, JSON_THROW_ON_ERROR);

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->accessToken,
                    'Content-Type: application/json',
                    'Square-Version: ' . $this->apiVersion,
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 60,
            ]);
            $resp = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($resp === false) {
                throw new RuntimeException('Square request failed.');
            }
        } else {
            $ctx = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => implode("\r\n", [
                        'Authorization: Bearer ' . $this->accessToken,
                        'Content-Type: application/json',
                        'Square-Version: ' . $this->apiVersion,
                    ]),
                    'content' => $payload,
                    'timeout' => 60,
                ],
            ]);
            $resp = @file_get_contents($url, false, $ctx);
            $code = 0;
            if ($resp === false) {
                throw new RuntimeException('Square request failed.');
            }
        }

        $data = json_decode((string) $resp, true);
        if (!is_array($data)) {
            throw new RuntimeException('Invalid JSON from Square (HTTP ' . $code . ').');
        }
        return $data;
    }

    /**
     * @param list<array<string, mixed>> $errors
     */
    private function formatErrors(array $errors): string
    {
        $parts = [];
        foreach ($errors as $e) {
            if (!is_array($e)) {
                continue;
            }
            $cat = (string) ($e['category'] ?? '');
            $code = (string) ($e['code'] ?? '');
            $detail = (string) ($e['detail'] ?? '');
            $parts[] = trim($cat . ' ' . $code . ': ' . $detail);
        }
        return $parts !== [] ? implode(' ', $parts) : 'Payment declined.';
    }
}
