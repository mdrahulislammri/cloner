<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireAdmin();

$serviceCount = fetchOneRow('SELECT COUNT(*) as count FROM services')['count'] ?? 0;
$portfolioCount = fetchOneRow('SELECT COUNT(*) as count FROM portfolio_items')['count'] ?? 0;
$messageCount = fetchOneRow('SELECT COUNT(*) as count FROM contact_messages')['count'] ?? 0;
$currentIp = getClientIpAddress();
$monitoring = getVisitMonitoringSummary();
$chatItems = array_slice((array)($monitoring['recent'] ?? []), 0, 6);
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-4 md:p-6">
  <div class="max-w-6xl mx-auto space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <h1 class="text-2xl md:text-3xl font-extrabold text-emerald-800">Admin Dashboard</h1>
      <a href="logout.php" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-white text-sm font-semibold hover:bg-red-700">Logout</a>
    </div>

    <div class="grid sm:grid-cols-3 gap-4">
      <div class="bg-white p-4 rounded-xl shadow border border-emerald-100"><p class="text-xs text-slate-500">Services</p><p class="text-2xl font-bold text-emerald-700"><?= (int)$serviceCount ?></p></div>
      <div class="bg-white p-4 rounded-xl shadow border border-emerald-100"><p class="text-xs text-slate-500">Portfolio</p><p class="text-2xl font-bold text-emerald-700"><?= (int)$portfolioCount ?></p></div>
      <div class="bg-white p-4 rounded-xl shadow border border-emerald-100"><p class="text-xs text-slate-500">Messages</p><p class="text-2xl font-bold text-emerald-700"><?= (int)$messageCount ?></p></div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4">
      <div class="bg-white p-4 rounded-xl shadow border border-emerald-100 space-y-2 lg:col-span-1">
        <p class="text-xs text-slate-500">Current Admin IP: <strong><?= htmlspecialchars($currentIp) ?></strong></p>
        <a class="block text-emerald-700 font-medium" href="services.php">Manage Services</a>
        <a class="block text-emerald-700 font-medium" href="portfolio.php">Manage Portfolio</a>
        <a class="block text-emerald-700 font-medium" href="testimonials.php">Manage Testimonials</a>
        <a class="block text-emerald-700 font-medium" href="settings.php">Manage Settings</a>
        <a class="block text-emerald-700 font-medium" href="messages.php">View Messages</a>
        <a class="block text-emerald-700 font-medium" href="monitoring.php">Visitor Monitoring</a>
      </div>

      <div class="bg-white p-4 rounded-xl shadow border border-emerald-100 lg:col-span-2">
        <div class="flex items-center justify-between gap-3 mb-3">
          <h2 class="text-lg font-bold text-slate-800">Monitoring Chat</h2>
          <a href="monitoring.php" class="text-sm font-semibold text-emerald-700">Open Full Monitoring →</a>
        </div>

        <div class="grid sm:grid-cols-3 gap-3 mb-4">
          <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><p class="text-xs text-slate-500">Today Visits</p><p class="text-xl font-bold text-emerald-700"><?= (int)($monitoring['today_visits'] ?? 0) ?></p></div>
          <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><p class="text-xs text-slate-500">Mobile</p><p class="text-xl font-bold text-emerald-700"><?= (int)(($monitoring['device_counts']['Mobile'] ?? 0)) ?></p></div>
          <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><p class="text-xs text-slate-500">Desktop</p><p class="text-xl font-bold text-emerald-700"><?= (int)(($monitoring['device_counts']['Desktop'] ?? 0)) ?></p></div>
        </div>

        <div class="space-y-3 max-h-[380px] overflow-auto pr-1">
          <?php if ($chatItems === []): ?>
            <div class="rounded-lg border border-dashed border-slate-300 p-4 text-sm text-slate-500">
              No recent visitor activity yet. Visit homepage to generate monitoring chat events.
            </div>
          <?php else: ?>
            <?php foreach ($chatItems as $item): ?>
              <div class="rounded-xl border border-slate-200 p-3 bg-slate-50">
                <div class="flex items-center justify-between gap-2">
                  <p class="text-sm font-semibold text-slate-800">👤 <?= htmlspecialchars((string)$item['device']) ?> visitor</p>
                  <p class="text-xs text-slate-500"><?= htmlspecialchars((string)$item['time']) ?></p>
                </div>
                <p class="text-sm text-slate-700 mt-1">Visited <span class="font-semibold text-emerald-700"><?= htmlspecialchars((string)$item['path']) ?></span></p>
                <p class="text-xs text-slate-500 mt-1 truncate" title="<?= htmlspecialchars((string)$item['referrer']) ?>">Referrer: <?= htmlspecialchars((string)$item['referrer']) ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
