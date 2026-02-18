<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function isInstalled(): bool
{
    $connection = db();
    if (!$connection) {
        return false;
    }

    try {
        $stmt = $connection->prepare("SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1");
        $stmt->execute([':key' => 'app_installed']);
        $value = $stmt->fetchColumn();

        return $value === '1';
    } catch (Throwable $exception) {
        return false;
    }
}

function upsertSetting(string $key, string $value): bool
{
    $connection = db();
    if (!$connection) {
        return false;
    }

    try {
        $stmt = $connection->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        return $stmt->execute([':k' => $key, ':v' => $value]);
    } catch (Throwable $exception) {
        return false;
    }
}

function normalizeIpList(string $rawList): array
{
    $items = preg_split('/[\r\n,]+/', $rawList) ?: [];
    $normalized = [];

    foreach ($items as $item) {
        $candidate = trim($item);
        if ($candidate === '') {
            continue;
        }

        if (strpos($candidate, '/') !== false) {
            [$ip, $mask] = array_pad(explode('/', $candidate, 2), 2, '');
            if (filter_var($ip, FILTER_VALIDATE_IP) && ctype_digit($mask)) {
                $normalized[] = $ip . '/' . $mask;
            }
            continue;
        }

        if (filter_var($candidate, FILTER_VALIDATE_IP)) {
            $normalized[] = $candidate;
        }
    }

    return array_values(array_unique($normalized));
}
