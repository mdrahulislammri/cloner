<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/install.php';

startSecureSession();

if (isInstalled()) {
    header('Location: admin/login.php');
    exit;
}

$currentIp = getClientIpAddress();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid security token.';
    } elseif (!db()) {
        $error = 'Database connection failed. Please check DB config first.';
    } else {
        $ipListInput = trim((string)($_POST['admin_ip_whitelist'] ?? ''));
        $ipListInput = $ipListInput === '' ? $currentIp : $ipListInput;

        $ips = normalizeIpList($ipListInput);
        if ($ips === []) {
            $error = 'Please provide at least one valid IP or CIDR.';
        } elseif (!in_array($currentIp, $ips, true)) {
            $ips[] = $currentIp;
        }

        if ($error === '') {
            $saveOk = upsertSetting('admin_ip_whitelist', implode(',', array_values(array_unique($ips))))
                && upsertSetting('app_installed', '1');

            if ($saveOk) {
                header('Location: admin/login.php?installed=1');
                exit;
            }

            $error = 'Installer could not save settings. Please retry.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Install Script | GreenTech Boost</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 grid place-items-center p-4">
  <form method="post" class="w-full max-w-xl bg-white shadow rounded-2xl p-6 space-y-4">
    <h1 class="text-2xl font-bold text-green-700">Install Script</h1>
    <p class="text-sm text-slate-600">cPanel upload করার পরে একবার installer run করুন। আপনার current IP auto detect করা হয়েছে এবং whitelist এ add হবে।</p>

    <?php if ($error !== ''): ?>
      <p class="text-sm bg-red-50 text-red-700 p-2 rounded"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="bg-green-50 rounded p-3 text-sm text-green-800">
      Detected IP: <strong><?= htmlspecialchars($currentIp) ?></strong>
    </div>

    <label class="block text-sm font-medium text-slate-700">
      Admin IP Whitelist (comma/newline separated)
      <textarea name="admin_ip_whitelist" rows="4" class="mt-1 w-full border rounded p-2" placeholder="<?= htmlspecialchars($currentIp) ?>"><?= htmlspecialchars((string)($_POST['admin_ip_whitelist'] ?? $currentIp)) ?></textarea>
    </label>
    <p class="text-xs text-slate-500">Example: 103.25.44.10, 103.25.44.0/24, 2405:201:....</p>

    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
    <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold rounded p-2">Complete Installation</button>
  </form>
</body>
</html>
