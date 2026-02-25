<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function detectDeviceType(string $userAgent): string
{
    $ua = strtolower($userAgent);
    if (preg_match('/mobile|android|iphone|ipod|windows phone/', $ua)) {
        return 'Mobile';
    }

    if (preg_match('/ipad|tablet/', $ua)) {
        return 'Tablet';
    }

    return 'Desktop';
}

function analyticsClientKey(): string
{
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    $ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
    return hash('sha256', $ip . '|' . $ua);
}

function hasVisitAnalyticsTable(): bool
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $connection = db();
    if (!$connection) {
        $cache = false;
        return false;
    }

    try {
        $stmt = $connection->query("SHOW TABLES LIKE 'visitor_analytics'");
        $cache = $stmt !== false && (bool)$stmt->fetchColumn();
    } catch (Throwable $exception) {
        $cache = false;
    }

    return $cache;
}

function trackWebsiteVisit(): void
{
    if (PHP_SAPI === 'cli') {
        return;
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
        return;
    }

    $path = (string)parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    if ($path !== '/' && $path !== '/index.php') {
        return;
    }

    $connection = db();
    if (!$connection) {
        return;
    }

    if (!hasVisitAnalyticsTable()) {
        return;
    }

    $clientKey = analyticsClientKey();
    $userAgent = substr((string)($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'), 0, 255);
    $device = detectDeviceType($userAgent);
    $referrer = substr((string)($_SERVER['HTTP_REFERER'] ?? 'Direct'), 0, 255);

    $dedupeCheck = $connection->prepare(
        'SELECT id FROM visitor_analytics
         WHERE client_key = :client_key
           AND visited_at >= (NOW() - INTERVAL 15 MINUTE)
         ORDER BY id DESC
         LIMIT 1'
    );
    $dedupeCheck->execute([':client_key' => $clientKey]);
    if ($dedupeCheck->fetch()) {
        return;
    }

    $insert = $connection->prepare(
        'INSERT INTO visitor_analytics (client_key, path, referrer, device, browser)
         VALUES (:client_key, :path, :referrer, :device, :browser)'
    );
    $insert->execute([
        ':client_key' => $clientKey,
        ':path' => substr($path, 0, 255),
        ':referrer' => $referrer,
        ':device' => $device,
        ':browser' => $userAgent,
    ]);

    $connection->exec('DELETE FROM visitor_analytics WHERE visited_at < (NOW() - INTERVAL 30 DAY)');
}

function getVisitMonitoringSummary(): array
{
    $empty = [
        'total_visits' => 0,
        'today_visits' => 0,
        'device_counts' => ['Desktop' => 0, 'Mobile' => 0, 'Tablet' => 0],
        'daily_labels' => [],
        'daily_values' => [],
        'recent' => [],
    ];

    $connection = db();
    if (!$connection) {
        return $empty;
    }

    if (!hasVisitAnalyticsTable()) {
        return $empty;
    }

    $totalRow = fetchOneRow('SELECT COUNT(*) AS c FROM visitor_analytics');
    $todayRow = fetchOneRow('SELECT COUNT(*) AS c FROM visitor_analytics WHERE DATE(visited_at) = CURRENT_DATE()');

    $deviceRows = fetchAllRows('SELECT device, COUNT(*) AS c FROM visitor_analytics GROUP BY device');
    $deviceCounts = ['Desktop' => 0, 'Mobile' => 0, 'Tablet' => 0];
    foreach ($deviceRows as $row) {
        $device = (string)($row['device'] ?? 'Desktop');
        $deviceCounts[$device] = (int)($row['c'] ?? 0);
    }

    $dailyRows = fetchAllRows(
        'SELECT DATE(visited_at) AS d, COUNT(*) AS c
         FROM visitor_analytics
         WHERE visited_at >= (CURRENT_DATE() - INTERVAL 6 DAY)
         GROUP BY DATE(visited_at)
         ORDER BY d ASC'
    );

    $dailyMap = [];
    foreach ($dailyRows as $row) {
        $dailyMap[(string)$row['d']] = (int)$row['c'];
    }

    $dailyLabels = [];
    $dailyValues = [];
    for ($i = 6; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime("-$i days"));
        $dailyLabels[] = date('d M', strtotime($day));
        $dailyValues[] = $dailyMap[$day] ?? 0;
    }

    $recentRows = fetchAllRows(
        'SELECT visited_at, device, referrer, path
         FROM visitor_analytics
         ORDER BY id DESC
         LIMIT 10'
    );

    $recent = [];
    foreach ($recentRows as $row) {
        $recent[] = [
            'time' => date('d M Y, h:i A', strtotime((string)$row['visited_at'])),
            'device' => (string)($row['device'] ?? 'Unknown'),
            'referrer' => (string)($row['referrer'] ?? 'Direct'),
            'path' => (string)($row['path'] ?? '/'),
        ];
    }

    return [
        'total_visits' => (int)($totalRow['c'] ?? 0),
        'today_visits' => (int)($todayRow['c'] ?? 0),
        'device_counts' => $deviceCounts,
        'daily_labels' => $dailyLabels,
        'daily_values' => $dailyValues,
        'recent' => $recent,
    ];
}
