<?php

declare(strict_types=1);

function analyticsFilePath(): string
{
    return __DIR__ . '/../storage/visit-analytics.json';
}

function ensureAnalyticsStorage(): void
{
    $dir = dirname(analyticsFilePath());
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}

function readVisitAnalytics(): array
{
    $file = analyticsFilePath();
    if (!is_file($file)) {
        return ['events' => []];
    }

    $raw = @file_get_contents($file);
    if (!is_string($raw) || $raw === '') {
        return ['events' => []];
    }

    $data = json_decode($raw, true);
    if (!is_array($data) || !isset($data['events']) || !is_array($data['events'])) {
        return ['events' => []];
    }

    return $data;
}

function writeVisitAnalytics(array $data): bool
{
    ensureAnalyticsStorage();

    $file = analyticsFilePath();
    $tmp = $file . '.tmp';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if (!is_string($json)) {
        return false;
    }

    if (@file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }

    return @rename($tmp, $file);
}

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

function trackWebsiteVisit(): void
{
    if (PHP_SAPI === 'cli') {
        return;
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
        return;
    }

    $now = time();
    $path = (string)parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);

    if ($path !== '/' && $path !== '/index.php') {
        return;
    }

    $data = readVisitAnalytics();
    $events = $data['events'] ?? [];

    $clientKey = analyticsClientKey();
    $lastSeenTs = 0;
    foreach (array_reverse($events) as $event) {
        if (($event['client_key'] ?? '') === $clientKey) {
            $lastSeenTs = (int)($event['timestamp'] ?? 0);
            break;
        }
    }

    if ($lastSeenTs > 0 && ($now - $lastSeenTs) < 900) {
        return;
    }

    $userAgent = (string)($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');
    $events[] = [
        'timestamp' => $now,
        'path' => $path,
        'referrer' => (string)($_SERVER['HTTP_REFERER'] ?? 'Direct'),
        'device' => detectDeviceType($userAgent),
        'browser' => substr($userAgent, 0, 160),
        'client_key' => $clientKey,
    ];

    $cutoff = $now - (60 * 60 * 24 * 30);
    $events = array_values(array_filter($events, static function (array $event) use ($cutoff): bool {
        return (int)($event['timestamp'] ?? 0) >= $cutoff;
    }));

    writeVisitAnalytics(['events' => $events]);
}

function getVisitMonitoringSummary(): array
{
    $data = readVisitAnalytics();
    $events = $data['events'] ?? [];

    $totalVisits = count($events);
    $todayStart = strtotime('today');
    $todayVisits = 0;

    $deviceCounts = ['Desktop' => 0, 'Mobile' => 0, 'Tablet' => 0];
    $dailyVisits = [];
    $recent = [];

    for ($i = 6; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime("-$i days"));
        $dailyVisits[$day] = 0;
    }

    foreach ($events as $event) {
        $ts = (int)($event['timestamp'] ?? 0);
        if ($ts >= $todayStart) {
            $todayVisits++;
        }

        $device = (string)($event['device'] ?? 'Desktop');
        if (!isset($deviceCounts[$device])) {
            $deviceCounts[$device] = 0;
        }
        $deviceCounts[$device]++;

        $dayKey = date('Y-m-d', $ts);
        if (isset($dailyVisits[$dayKey])) {
            $dailyVisits[$dayKey]++;
        }
    }

    $recentEvents = array_slice(array_reverse($events), 0, 10);
    foreach ($recentEvents as $event) {
        $recent[] = [
            'time' => date('d M Y, h:i A', (int)$event['timestamp']),
            'device' => (string)($event['device'] ?? 'Unknown'),
            'referrer' => (string)($event['referrer'] ?? 'Direct'),
            'path' => (string)($event['path'] ?? '/'),
        ];
    }

    return [
        'total_visits' => $totalVisits,
        'today_visits' => $todayVisits,
        'device_counts' => $deviceCounts,
        'daily_labels' => array_map(static fn(string $day): string => date('d M', strtotime($day)), array_keys($dailyVisits)),
        'daily_values' => array_values($dailyVisits),
        'recent' => $recent,
    ];
}
