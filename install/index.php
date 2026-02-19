<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/install.php';

startSecureSession();

if (isInstalled()) {
    header('Location: ../admin/login.php');
    exit;
}

$currentIp = getClientIpAddress();
$requirements = installRequirements();
$error = '';
$success = '';

$form = [
    'db_host' => (string)appEnv('DB_HOST', DB_HOST),
    'db_port' => (string)appEnv('DB_PORT', DB_PORT),
    'db_name' => (string)appEnv('DB_NAME', DB_NAME),
    'db_user' => (string)appEnv('DB_USER', DB_USER),
    'db_pass' => (string)appEnv('DB_PASS', DB_PASS),
    'base_url' => (string)appEnv('BASE_URL', BASE_URL),
    'admin_name' => 'Super Admin',
    'admin_email' => 'admin@example.com',
    'admin_password' => '',
    'site_title' => APP_NAME,
    'admin_ip_whitelist' => $currentIp,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($form) as $key) {
        $form[$key] = trim((string)($_POST[$key] ?? $form[$key]));
    }

    if (!verifyCsrfToken($_POST['csrf'] ?? null)) {
        $error = 'Invalid security token.';
    } elseif (!installRequirementsPassed()) {
        $error = 'Server requirement check failed. Please fix red items and retry.';
    } elseif ($form['admin_password'] === '' || strlen($form['admin_password']) < 8) {
        $error = 'Admin password must be at least 8 characters.';
    } elseif (!filter_var($form['admin_email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please provide a valid admin email.';
    } else {
        $ips = normalizeIpList($form['admin_ip_whitelist']);
        if ($ips === []) {
            $error = 'Please provide at least one valid IP or CIDR.';
        } elseif (!in_array($currentIp, $ips, true)) {
            $ips[] = $currentIp;
        }

        if ($error === '') {
            $dbConfig = [
                'host' => $form['db_host'],
                'port' => $form['db_port'],
                'name' => $form['db_name'],
                'user' => $form['db_user'],
                'pass' => $form['db_pass'],
            ];

            try {
                $connection = installerConnect($dbConfig);
                importSchema($connection);
                upsertAdmin($connection, $form['admin_name'], $form['admin_email'], $form['admin_password']);

                $whitelist = implode(',', array_values(array_unique($ips)));
                upsertSettingWithConnection($connection, 'site_title', $form['site_title']);
                upsertSettingWithConnection($connection, 'admin_ip_whitelist', $whitelist);
                upsertSettingWithConnection($connection, 'app_installed', '1');

                writeConfigPhp($dbConfig, $form['base_url'] === '' ? '/' : $form['base_url']);

                $success = 'Installation completed successfully. Redirecting to admin login...';
                header('Refresh: 2; url=../admin/login.php?installed=1');
            } catch (Throwable $exception) {
                $error = 'Install failed. Please check DB credentials, config.php file permissions, and schema compatibility.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Installer | GreenTech Boost</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 py-8 px-4">
  <div class="max-w-5xl mx-auto grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 bg-white rounded-2xl shadow p-5 space-y-3 h-fit">
      <h2 class="font-bold text-lg text-green-700">Server Requirement Check</h2>
      <?php foreach ($requirements as $item): ?>
        <div class="flex items-center justify-between text-sm border rounded p-2">
          <span><?= htmlspecialchars((string)$item['label']) ?></span>
          <span class="font-semibold <?= !empty($item['ok']) ? 'text-green-700' : 'text-red-700' ?>"><?= !empty($item['ok']) ? 'PASS' : 'FAIL' ?></span>
        </div>
      <?php endforeach; ?>
      <p class="text-xs text-slate-500">Install complete না হওয়া পর্যন্ত পুরো website installer page-এ redirect হবে।</p>
    </div>

    <form method="post" class="lg:col-span-2 bg-white rounded-2xl shadow p-6 space-y-4">
      <h1 class="text-2xl font-bold text-green-700">Script Installation Wizard</h1>
      <p class="text-sm text-slate-600">এই installer সাধারণ premium script installer-এর মতো database setup, admin setup, IP whitelist setup একসাথে complete করবে।</p>

      <?php if ($error !== ''): ?>
        <p class="text-sm bg-red-50 text-red-700 p-3 rounded"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
      <?php if ($success !== ''): ?>
        <p class="text-sm bg-green-50 text-green-700 p-3 rounded"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <div class="grid md:grid-cols-2 gap-3">
        <label class="text-sm">DB Host
          <input name="db_host" value="<?= htmlspecialchars($form['db_host']) ?>" class="w-full border rounded p-2" required>
        </label>
        <label class="text-sm">DB Port
          <input name="db_port" value="<?= htmlspecialchars($form['db_port']) ?>" class="w-full border rounded p-2" required>
        </label>
        <label class="text-sm">DB Name
          <input name="db_name" value="<?= htmlspecialchars($form['db_name']) ?>" class="w-full border rounded p-2" required>
        </label>
        <label class="text-sm">DB User
          <input name="db_user" value="<?= htmlspecialchars($form['db_user']) ?>" class="w-full border rounded p-2" required>
        </label>
        <label class="text-sm md:col-span-2">DB Password
          <input type="password" name="db_pass" value="<?= htmlspecialchars($form['db_pass']) ?>" class="w-full border rounded p-2">
        </label>
      </div>

      <div class="grid md:grid-cols-2 gap-3">
        <label class="text-sm">Admin Name
          <input name="admin_name" value="<?= htmlspecialchars($form['admin_name']) ?>" class="w-full border rounded p-2" required>
        </label>
        <label class="text-sm">Admin Email
          <input type="email" name="admin_email" value="<?= htmlspecialchars($form['admin_email']) ?>" class="w-full border rounded p-2" required>
        </label>
        <label class="text-sm md:col-span-2">Admin Password (min 8)
          <input type="password" name="admin_password" class="w-full border rounded p-2" required>
        </label>
      </div>

      <label class="text-sm block">Site Title
        <input name="site_title" value="<?= htmlspecialchars($form['site_title']) ?>" class="w-full border rounded p-2" required>
      </label>

      <label class="text-sm block">Base URL (example: / অথবা /subfolder)
        <input name="base_url" value="<?= htmlspecialchars($form['base_url']) ?>" class="w-full border rounded p-2" required>
      </label>

      <div class="bg-green-50 rounded p-3 text-sm text-green-800">
        Detected IP: <strong><?= htmlspecialchars($currentIp) ?></strong>
      </div>

      <label class="text-sm block">Admin IP Whitelist (comma/newline separated)
        <textarea name="admin_ip_whitelist" rows="4" class="w-full border rounded p-2" required><?= htmlspecialchars($form['admin_ip_whitelist']) ?></textarea>
      </label>

      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
      <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold rounded p-3">Install Now</button>
    </form>
  </div>
</body>
</html>
