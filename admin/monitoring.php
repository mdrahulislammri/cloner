<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$summary = getVisitMonitoringSummary();
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100 p-4 md:p-6">
  <div class="max-w-6xl mx-auto space-y-5">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
      <div>
        <a href="dashboard.php" class="text-emerald-700 text-sm">← Back to Dashboard</a>
        <h1 class="text-2xl md:text-3xl font-extrabold text-emerald-800">Visitor Monitoring</h1>
      </div>
      <a href="logout.php" class="text-red-600 text-sm font-semibold">Logout</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
      <div class="bg-white rounded-xl p-4 shadow border border-emerald-100"><p class="text-xs text-slate-500">Total Visits (30d)</p><p class="text-2xl font-bold text-emerald-700"><?= (int)$summary['total_visits'] ?></p></div>
      <div class="bg-white rounded-xl p-4 shadow border border-emerald-100"><p class="text-xs text-slate-500">Today</p><p class="text-2xl font-bold text-emerald-700"><?= (int)$summary['today_visits'] ?></p></div>
      <div class="bg-white rounded-xl p-4 shadow border border-emerald-100"><p class="text-xs text-slate-500">Mobile</p><p class="text-2xl font-bold text-emerald-700"><?= (int)($summary['device_counts']['Mobile'] ?? 0) ?></p></div>
      <div class="bg-white rounded-xl p-4 shadow border border-emerald-100"><p class="text-xs text-slate-500">Desktop</p><p class="text-2xl font-bold text-emerald-700"><?= (int)($summary['device_counts']['Desktop'] ?? 0) ?></p></div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4">
      <div class="bg-white rounded-xl p-4 shadow border border-emerald-100 lg:col-span-2">
        <h2 class="font-bold text-slate-800 mb-3">Last 7 Days Visit Trend</h2>
        <canvas id="visitChart" height="120"></canvas>
      </div>
      <div class="bg-white rounded-xl p-4 shadow border border-emerald-100">
        <h2 class="font-bold text-slate-800 mb-3">Device Split</h2>
        <canvas id="deviceChart" height="180"></canvas>
      </div>
    </div>

    <div class="bg-white rounded-xl p-4 shadow border border-emerald-100">
      <h2 class="font-bold text-slate-800 mb-3">Recent Visitors</h2>
      <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
          <thead>
            <tr class="text-left text-slate-500 border-b">
              <th class="py-2 pr-3">Time</th>
              <th class="py-2 pr-3">Device</th>
              <th class="py-2 pr-3">Referrer</th>
              <th class="py-2">Page</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($summary['recent'] === []): ?>
              <tr><td colspan="4" class="py-6 text-center text-slate-500">No visit data yet. Open homepage to generate analytics.</td></tr>
            <?php else: ?>
              <?php foreach ($summary['recent'] as $row): ?>
                <tr class="border-b last:border-b-0">
                  <td class="py-2 pr-3"><?= htmlspecialchars($row['time']) ?></td>
                  <td class="py-2 pr-3"><?= htmlspecialchars($row['device']) ?></td>
                  <td class="py-2 pr-3 max-w-[260px] truncate" title="<?= htmlspecialchars($row['referrer']) ?>"><?= htmlspecialchars($row['referrer']) ?></td>
                  <td class="py-2 text-emerald-700 font-semibold"><?= htmlspecialchars($row['path']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const visitLabels = <?= json_encode($summary['daily_labels']) ?>;
    const visitValues = <?= json_encode($summary['daily_values']) ?>;
    const deviceValues = [
      <?= (int)($summary['device_counts']['Desktop'] ?? 0) ?>,
      <?= (int)($summary['device_counts']['Mobile'] ?? 0) ?>,
      <?= (int)($summary['device_counts']['Tablet'] ?? 0) ?>
    ];

    new Chart(document.getElementById('visitChart'), {
      type: 'line',
      data: {
        labels: visitLabels,
        datasets: [{
          label: 'Visits',
          data: visitValues,
          borderColor: '#059669',
          backgroundColor: 'rgba(16,185,129,.12)',
          fill: true,
          tension: 0.35,
          pointRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {legend: {display: false}}
      }
    });

    new Chart(document.getElementById('deviceChart'), {
      type: 'doughnut',
      data: {
        labels: ['Desktop', 'Mobile', 'Tablet'],
        datasets: [{
          data: deviceValues,
          backgroundColor: ['#065f46', '#10b981', '#6ee7b7']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {legend: {position: 'bottom'}}
      }
    });
  </script>
</body>
</html>
