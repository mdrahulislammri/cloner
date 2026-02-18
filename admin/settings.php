<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/content.php';
requireAdmin();

$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null) && db()) {
    $allowed = [
        'site_title','hero_title','hero_subtitle','hero_badge','hero_cta_primary','hero_cta_secondary',
        'about_title','about_description','portfolio_title','portfolio_subtitle','reviews_title',
        'contact_title','contact_subtitle','phone','email','address',
        'whatsapp_number','whatsapp_notice_title','whatsapp_notice_text',
        'chat_widget_title','chat_widget_subtitle','messenger_url','telegram_url','call_number',
        'toast_position','toast_duration_ms','footer_text','admin_ip_whitelist'
    ];

    $toggleFields = [
        'chat_toggle_enabled',
        'chat_messenger_enabled',
        'chat_telegram_enabled',
        'chat_whatsapp_enabled',
        'chat_call_enabled',
        'toast_enabled',
    ];

    $stmt = db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:k,:v) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    foreach ($allowed as $key) {
        $value = trim((string)($_POST[$key] ?? ''));
        $stmt->execute([':k' => $key, ':v' => $value]);
    }

    foreach ($toggleFields as $key) {
        $value = isset($_POST[$key]) ? '1' : '0';
        $stmt->execute([':k' => $key, ':v' => $value]);
    }

    $stmt->execute([':k' => 'app_installed', ':v' => '1']);

    if (!empty($_FILES['hero_image']['name']) && is_uploaded_file($_FILES['hero_image']['tmp_name'])) {
        $mime = mime_content_type($_FILES['hero_image']['tmp_name']) ?: '';
        $allowedMime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/svg+xml' => 'svg'];

        if (isset($allowedMime[$mime])) {
            $ext = $allowedMime[$mime];
            $targetDir = __DIR__ . '/../access/img/uploads';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0775, true);
            }
            $fileName = 'hero-' . time() . '.' . $ext;
            $targetPath = $targetDir . '/' . $fileName;
            if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $targetPath)) {
                $webPath = 'access/img/uploads/' . $fileName;
                $stmt->execute([':k' => 'hero_image', ':v' => $webPath]);
            }
        } else {
            $uploadError = 'Invalid hero image format.';
        }
    }

    if ($uploadError === '') {
        header('Location: settings.php?saved=1');
        exit;
    }
}

$settings = getSiteSettings();
?>
<!doctype html>
<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdn.tailwindcss.com"></script></head>
<body class="p-4 md:p-6 bg-slate-100">
<div class="max-w-5xl mx-auto space-y-4">
  <a href="dashboard.php" class="text-green-700">← Dashboard</a>
  <h1 class="text-2xl font-bold">Manage Homepage Settings</h1>
  <?php if (isset($_GET['saved'])): ?><p class="text-green-700 bg-green-50 p-2 rounded">Saved successfully.</p><?php endif; ?>
  <?php if ($uploadError !== ''): ?><p class="text-red-700 bg-red-50 p-2 rounded"><?= htmlspecialchars($uploadError) ?></p><?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow grid md:grid-cols-2 gap-3">
    <?php
    $fields = [
      'site_title' => 'Site Title',
      'hero_badge' => 'Hero Badge',
      'hero_title' => 'Hero Title',
      'hero_subtitle' => 'Hero Subtitle',
      'hero_cta_primary' => 'Hero Primary Button',
      'hero_cta_secondary' => 'Hero Secondary Button',
      'about_title' => 'About Title',
      'portfolio_title' => 'Portfolio Title',
      'portfolio_subtitle' => 'Portfolio Subtitle',
      'reviews_title' => 'Review Title',
      'contact_title' => 'Contact Title',
      'contact_subtitle' => 'Contact Subtitle',
      'phone' => 'Phone',
      'email' => 'Email',
      'address' => 'Address',
      'whatsapp_number' => 'WhatsApp Number (with country code)',
      'whatsapp_notice_title' => 'WhatsApp Notice Title',
      'whatsapp_notice_text' => 'WhatsApp Notice Text',
      'chat_widget_title' => 'Chat Widget Title',
      'chat_widget_subtitle' => 'Chat Widget Subtitle',
      'messenger_url' => 'Messenger URL',
      'telegram_url' => 'Telegram URL',
      'call_number' => 'Call Number',
      'toast_duration_ms' => 'Toast Auto Close (ms)',
      'footer_text' => 'Footer Text',
      'admin_ip_whitelist' => 'Admin IP Whitelist (comma/newline separated)',
    ];
    foreach ($fields as $key => $label):
    ?>
      <label class="text-sm"><?= htmlspecialchars($label) ?>
        <?php if (in_array($key, ['hero_subtitle','about_description','whatsapp_notice_text','footer_text','admin_ip_whitelist'], true)): ?>
          <textarea name="<?= $key ?>" class="w-full border p-2 rounded"><?= htmlspecialchars($settings[$key] ?? '') ?></textarea>
        <?php else: ?>
          <input name="<?= $key ?>" value="<?= htmlspecialchars($settings[$key] ?? '') ?>" class="w-full border p-2 rounded">
        <?php endif; ?>
      </label>
    <?php endforeach; ?>

    <div class="md:col-span-2 border rounded p-3 bg-slate-50">
      <p class="font-semibold mb-2">Chat Channels On/Off</p>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
        <?php
        $toggles = [
          'chat_toggle_enabled' => 'Floating Chat Widget',
          'chat_messenger_enabled' => 'Messenger',
          'chat_telegram_enabled' => 'Telegram',
          'chat_whatsapp_enabled' => 'WhatsApp',
          'chat_call_enabled' => 'Call',
          'toast_enabled' => 'Popup Toast Notification',
        ];
        foreach ($toggles as $key => $label):
        ?>
          <label class="flex items-center gap-2 bg-white border rounded p-2">
            <input type="checkbox" name="<?= htmlspecialchars($key) ?>" value="1" <?= (($settings[$key] ?? '0') === '1') ? 'checked' : '' ?>>
            <span><?= htmlspecialchars($label) ?></span>
          </label>
        <?php endforeach; ?>
      </div>
    </div>


    <div class="md:col-span-2 border rounded p-3 bg-slate-50">
      <p class="font-semibold mb-2">Toast Settings</p>
      <div class="grid sm:grid-cols-2 gap-3 text-sm">
        <label class="text-sm">Toast Position
          <select name="toast_position" class="w-full border p-2 rounded bg-white">
            <?php $toastPosition = (string)($settings['toast_position'] ?? 'top-right'); ?>
            <?php $toastOptions = ['top-right','top-left','bottom-right','bottom-left']; ?>
            <?php foreach ($toastOptions as $opt): ?>
              <option value="<?= htmlspecialchars($opt) ?>" <?= $toastPosition === $opt ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>
    </div>

    <label class="text-sm">Hero Image Upload
      <input type="file" name="hero_image" accept="image/*" class="w-full border p-2 rounded bg-white">
    </label>

    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
    <div class="md:col-span-2"><button class="bg-green-600 text-white p-2 rounded">Save All</button></div>
  </form>
</div>
</body></html>
