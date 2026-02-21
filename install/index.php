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
    } elseif (!isStrongAdminPassword($form['admin_password'])) {
        $error = 'Admin password must be at least 8 characters and include uppercase, lowercase, and number.';
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

                writeConfigPhp($dbConfig, $form['base_url'] === '' ? '/' : $form['base_url']);

                if (!upsertSettingWithConnection($connection, 'app_installed', '1')) {
                    throw new RuntimeException('Failed to finalize installation state.');
                }

                try {
                    removeInstallerEntryPoint();
                } catch (Throwable $exception) {
                    upsertSettingWithConnection($connection, 'app_installed', '0');
                    throw $exception;
                }

                $success = 'Installation completed! Redirecting to login...';
                header('Refresh: 3; url=../admin/login.php?installed=1');
            } catch (Throwable $exception) {
                $error = 'Install failed. Check DB credentials and folder permissions.';
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
  <title>GreenTech | Advanced Installer</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    body {
        background-color: #f8fafc;
        background-image: radial-gradient(#22c55e 0.5px, transparent 0.5px);
        background-size: 24px 24px;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    .step-number {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #22c55e;
        color: white;
        border-radius: 50%;
        font-weight: bold;
        font-size: 14px;
    }
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
  </style>
</head>
<body class="min-h-screen py-12 px-4">

  <div id="toast-container"></div>

  <div class="max-w-6xl mx-auto grid lg:grid-cols-12 gap-8">
    
    <div class="lg:col-span-4 space-y-6 animate__animated animate__fadeInLeft">
      <div class="glass-card rounded-3xl shadow-xl p-8 h-fit">
        <div class="mb-6">
            <h2 class="font-black text-2xl text-green-700 tracking-tight">Requirement</h2>
            <p class="text-xs text-slate-500 uppercase font-bold tracking-widest">Server Compatibility</p>
        </div>
        
        <div class="space-y-3">
          <?php foreach ($requirements as $item): ?>
            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-green-200 transition-colors">
              <span class="text-sm font-medium text-slate-700"><?= htmlspecialchars((string)$item['label']) ?></span>
              <?php if(!empty($item['ok'])): ?>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">OK</span>
              <?php else: ?>
                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">FAIL</span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
        
        <div class="mt-8 p-4 bg-green-50 rounded-2xl border border-green-100">
            <p class="text-[11px] text-green-800 leading-relaxed font-medium">
               ⚠️ Install সম্পূর্ণ না হওয়া পর্যন্ত আপনার ওয়েবসাইটটি এই ইনস্টলেশন পেজেই রিডাইরেক্ট হবে।
            </p>
        </div>
      </div>
    </div>

    <div class="lg:col-span-8 animate__animated animate__fadeInUp">
      <form method="post" class="glass-card rounded-3xl shadow-2xl p-8 md:p-12 space-y-10">
        
        <header>
            <h1 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight">Script <span class="text-green-600">Installation</span></h1>
            <p class="text-slate-500 mt-2">ব্যাস কয়েকটা ধাপ আর আপনার প্লাটফর্ম প্রস্তুত।</p>
        </header>

        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <span class="step-number">1</span>
                <h3 class="font-bold text-slate-800 uppercase tracking-wide text-sm">Database Configuration</h3>
            </div>
            <div class="grid md:grid-cols-2 gap-5 pl-10">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Host</label>
                    <input name="db_host" value="<?= htmlspecialchars($form['db_host']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" required>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Port</label>
                    <input name="db_port" value="<?= htmlspecialchars($form['db_port']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" required>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Database Name</label>
                    <input name="db_name" value="<?= htmlspecialchars($form['db_name']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" required>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Username</label>
                    <input name="db_user" value="<?= htmlspecialchars($form['db_user']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" required>
                </div>
                <div class="md:col-span-2 space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Password</label>
                    <input type="password" name="db_pass" value="<?= htmlspecialchars($form['db_pass']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all">
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <span class="step-number">2</span>
                <h3 class="font-bold text-slate-800 uppercase tracking-wide text-sm">Super Admin Setup</h3>
            </div>
            <div class="grid md:grid-cols-2 gap-5 pl-10">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Admin Name</label>
                    <input name="admin_name" value="<?= htmlspecialchars($form['admin_name']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" required>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Email Address</label>
                    <input type="email" name="admin_email" value="<?= htmlspecialchars($form['admin_email']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" required>
                </div>
                <div class="md:col-span-2 space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Admin Password</label>
                    <input type="password" name="admin_password" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" placeholder="At least 8 chars..." required>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <span class="step-number">3</span>
                <h3 class="font-bold text-slate-800 uppercase tracking-wide text-sm">Site Preferences</h3>
            </div>
            <div class="space-y-4 pl-10">
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase">Site Title</label>
                        <input name="site_title" value="<?= htmlspecialchars($form['site_title']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase">Base URL</label>
                        <input name="base_url" value="<?= htmlspecialchars($form['base_url']) ?>" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all" placeholder="/ or /subfolder" required>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500">DETECTED IP: <span class="text-green-600 ml-1"><?= htmlspecialchars($currentIp) ?></span></span>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Admin IP Whitelist</label>
                    <textarea name="admin_ip_whitelist" rows="3" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-green-500 rounded-xl p-3 text-sm transition-all shadow-inner" required><?= htmlspecialchars($form['admin_ip_whitelist']) ?></textarea>
                </div>
            </div>
        </div>

        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
        
        <div class="pt-6">
            <button class="w-full bg-green-600 hover:bg-green-700 hover:shadow-lg hover:shadow-green-200 text-white font-black py-4 rounded-2xl transition-all flex items-center justify-center gap-2 group">
                🚀 INSTALL NOW
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </div>
      </form>
    </div>
  </div>

  <footer class="mt-12 text-center text-slate-400 text-xs font-medium">
      &copy; 2026 GreenTech Boost System. All rights reserved.
  </footer>

  <script>
    function showToast(message, type = 'error') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
        
        toast.className = `animate__animated animate__slideInRight ${bgColor} text-white px-6 py-4 rounded-2xl shadow-2xl mb-4 flex items-center gap-3 min-w-[300px]`;
        toast.innerHTML = `
            <div class="bg-white/20 rounded-full p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="font-bold text-sm text-white">${message}</span>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.replace('animate__slideInRight', 'animate__fadeOutRight');
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }

    // PHP থেকে এরর বা সাকসেস মেসেজ টোস্টে পুশ করা
    <?php if ($error !== ''): ?>
        showToast("<?= addslashes(htmlspecialchars($error)) ?>", 'error');
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        showToast("<?= addslashes(htmlspecialchars($success)) ?>", 'success');
    <?php endif; ?>
  </script>
</body>
</html>
