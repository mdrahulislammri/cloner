<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/content.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null) && db()) {
    $allowed = ['site_title','hero_title','hero_subtitle','phone','email','address'];
    $stmt = db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:k,:v) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    foreach ($allowed as $key) {
        $value = trim((string)($_POST[$key] ?? ''));
        $stmt->execute([':k'=>$key,':v'=>$value]);
    }
    header('Location: settings.php');exit;
}
$settings = getSiteSettings();
?>
<!doctype html><html><head><meta charset="UTF-8"><script src="https://cdn.tailwindcss.com"></script></head><body class="p-6 bg-slate-100"><div class="max-w-4xl mx-auto space-y-4">
<a href="dashboard.php" class="text-green-700">← Dashboard</a><h1 class="text-2xl font-bold">Settings</h1>
<form method="post" class="bg-white p-4 rounded shadow grid gap-2">
<?php foreach(['site_title','hero_title','hero_subtitle','phone','email','address'] as $key): ?>
<input name="<?= $key ?>" value="<?= htmlspecialchars($settings[$key] ?? '') ?>" class="border p-2" placeholder="<?= $key ?>">
<?php endforeach; ?>
<input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>"><button class="bg-green-600 text-white p-2 rounded">Save</button></form>
</div></body></html>
