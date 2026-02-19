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
?>
<!doctype html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-slate-100 p-6"><div class="max-w-5xl mx-auto">
<div class="flex justify-between items-center mb-6"><h1 class="text-3xl font-bold text-green-700">Admin Dashboard</h1><a href="logout.php" class="text-red-600">Logout</a></div>
<div class="grid sm:grid-cols-3 gap-4 mb-6">
  <div class="bg-white p-4 rounded shadow">Services: <strong><?= (int)$serviceCount ?></strong></div>
  <div class="bg-white p-4 rounded shadow">Portfolio: <strong><?= (int)$portfolioCount ?></strong></div>
  <div class="bg-white p-4 rounded shadow">Messages: <strong><?= (int)$messageCount ?></strong></div>
</div>
<div class="bg-white p-4 rounded shadow space-y-2">
  <p class="text-xs text-slate-500">Current Admin IP: <strong><?= htmlspecialchars($currentIp) ?></strong></p>
  <a class="block text-green-700" href="services.php">Manage Services</a>
  <a class="block text-green-700" href="portfolio.php">Manage Portfolio</a>
  <a class="block text-green-700" href="testimonials.php">Manage Testimonials</a>
  <a class="block text-green-700" href="settings.php">Manage Settings</a>
  <a class="block text-green-700" href="messages.php">View Messages</a>
  <a class="block text-green-700" href="monitoring.php">Visitor Monitoring</a>
</div>
</div></body></html>
