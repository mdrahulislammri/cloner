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
<?php
require_once __DIR__ . '/layout.php';
adminLayoutStart('Dashboard', 'dashboard');
?>
<div class="grid sm:grid-cols-3 gap-4">
  <div class="bg-white p-4 rounded-xl shadow border border-emerald-100"><p class="text-xs text-slate-500">Services</p><p class="text-2xl font-bold text-emerald-700"><?= (int)$serviceCount ?></p></div>
  <div class="bg-white p-4 rounded-xl shadow border border-emerald-100"><p class="text-xs text-slate-500">Portfolio</p><p class="text-2xl font-bold text-emerald-700"><?= (int)$portfolioCount ?></p></div>
  <div class="bg-white p-4 rounded-xl shadow border border-emerald-100"><p class="text-xs text-slate-500">Messages</p><p class="text-2xl font-bold text-emerald-700"><?= (int)$messageCount ?></p></div>
</div>

<div class="bg-white p-4 rounded-xl shadow border border-emerald-100">
  <p class="text-sm text-slate-500">Current Admin IP: <strong><?= htmlspecialchars($currentIp) ?></strong></p>
</div>

<div class="bg-white p-4 rounded-xl shadow border border-emerald-100">
  <div class="flex items-center justify-between gap-3 mb-3">
    <h3 class="text-lg font-bold text-slate-800">Monitoring Chat</h3>
    <a href="monitoring.php" class="text-sm font-semibold text-emerald-700">Open Full Monitoring →</a>
  </div>

  <div class="grid sm:grid-cols-3 gap-3 mb-4">
    <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><p class="text-xs text-slate-500">Today Visits</p><p class="text-xl font-bold text-emerald-700"><?= (int)($monitoring['today_visits'] ?? 0) ?></p></div>
    <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><p class="text-xs text-slate-500">Mobile</p><p class="text-xl font-bold text-emerald-700"><?= (int)($monitoring['device_counts']['Mobile'] ?? 0) ?></p></div>
    <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><p class="text-xs text-slate-500">Desktop</p><p class="text-xl font-bold text-emerald-700"><?= (int)($monitoring['device_counts']['Desktop'] ?? 0) ?></p></div>
  </div>

  <div class="space-y-3 max-h-[380px] overflow-auto pr-1">
    <?php if ($chatItems === []): ?>
      <div class="rounded-lg border border-dashed border-slate-300 p-4 text-sm text-slate-500">No recent visitor activity yet.</div>
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
<?php adminLayoutEnd(); ?>
