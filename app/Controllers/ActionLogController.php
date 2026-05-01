<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AppActionLogger;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;

/**
 * Accepts anonymous browser telemetry (clicks/submits/pageview). CSRF-required.
 */
final class ActionLogController extends Controller
{
    public function ingest(): void
    {
        Session::start();

        $raw = file_get_contents('php://input') ?: '';
        if (strlen($raw) > 65536) {
            $this->jsonResponse(413, ['ok' => false, 'error' => 'payload']);
        }
        /** @var mixed $decoded */
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            $this->jsonResponse(400, ['ok' => false, 'error' => 'json']);
        }

        /** @var array<string, mixed> $data */
        $data = $decoded;
        $token = isset($data['_csrf']) && is_string($data['_csrf'])
            ? $data['_csrf']
            : ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        if (!Csrf::validate(is_string($token) ? $token : null)) {
            $this->jsonResponse(403, ['ok' => false, 'error' => 'csrf']);
        }

        $_SESSION['_action_browser_last_ts'] = (int) round(microtime(true) * 1000);

        /** @var list<mixed> $eventsRaw */
        $eventsRaw = $data['events'] ?? [];
        if (!is_array($eventsRaw)) {
            $this->jsonResponse(400, ['ok' => false, 'error' => 'events']);
        }

        $events = [];
        foreach ($eventsRaw as $ev) {
            if (count($events) >= 80) {
                break;
            }
            if (!is_array($ev)) {
                continue;
            }
            $type = isset($ev['type']) ? trim((string) $ev['type']) : '';
            if ($type === '') {
                continue;
            }
            $entry = ['type' => substr($type, 0, 40)];
            if (isset($ev['surface']) && is_string($ev['surface'])) {
                $entry['surface'] = substr(trim($ev['surface']), 0, 24);
            }
            if (isset($ev['detail']) && is_string($ev['detail'])) {
                $entry['detail'] = substr($ev['detail'], 0, 400);
            }
            if (isset($ev['path']) && is_string($ev['path'])) {
                $entry['path'] = substr(trim($ev['path']), 0, 260);
            }
            if (isset($ev['t']) && (is_float($ev['t']) || is_int($ev['t']))) {
                $entry['t_ms'] = (float) $ev['t'];
            }
            $events[] = $entry;
        }

        foreach ($events as $entry) {
            AppActionLogger::event(
                'frontend',
                'browser.' . (string) $entry['type'],
                ['browser' => $entry]
            );
            $evType = (string) ($entry['type'] ?? '');
            if ($evType !== '' && str_starts_with($evType, 'js.')) {
                error_log(sprintf(
                    "[%s] [frontend-js] type=%s surface=%s path=%s detail=%s\n",
                    date('Y-m-d H:i:s T'),
                    $evType,
                    substr((string) ($entry['surface'] ?? ''), 0, 24),
                    substr((string) ($entry['path'] ?? ''), 0, 260),
                    substr((string) ($entry['detail'] ?? ''), 0, 500)
                ));
            }
        }

        http_response_code(204);
        exit;
    }

    /** @param array<string, mixed> $body */
    private function jsonResponse(int $code, array $body): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: no-store');
        $enc = json_encode($body);
        echo is_string($enc) ? $enc : '{"ok":false}';
        exit;
    }
}
