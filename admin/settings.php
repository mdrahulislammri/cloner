<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/content.php';
requireAdmin();

$uploadError = '';


function uploadErrorMessage(int $errorCode): string
{
    return match ($errorCode) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Uploaded file is too large.',
        UPLOAD_ERR_PARTIAL => 'File upload was interrupted. Please retry.',
        UPLOAD_ERR_NO_FILE => 'No file selected for upload.',
        UPLOAD_ERR_NO_TMP_DIR => 'Temporary upload directory is missing on server.',
        UPLOAD_ERR_CANT_WRITE => 'Server could not write uploaded file.',
        UPLOAD_ERR_EXTENSION => 'File upload blocked by a server extension.',
        default => 'Unknown file upload error.',
    };
}

function detectUploadedFileExtension(string $tmpPath, string $originalName, array $allowedMime): ?string
{
    $mime = '';
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $detected = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);
            if (is_string($detected)) {
                $mime = trim($detected);
            }
        }
    }

    if ($mime === '' && function_exists('mime_content_type')) {
        $detected = mime_content_type($tmpPath);
        if (is_string($detected)) {
            $mime = trim($detected);
        }
    }

    if ($mime !== '' && isset($allowedMime[$mime])) {
        return $allowedMime[$mime];
    }

    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = array_values(array_unique($allowedMime));
    if ($ext !== '' && in_array($ext, $allowedExtensions, true)) {
        return $ext;
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null) && db()) {
    $allowed = [
        'site_title','hero_title','hero_subtitle','hero_badge','hero_cta_primary','hero_cta_secondary',
        'about_title','about_description','portfolio_title','portfolio_subtitle','reviews_title',
        'contact_title','contact_subtitle','phone','email','address',
        'whatsapp_number','whatsapp_notice_title','whatsapp_notice_text',
        'chat_widget_title','chat_widget_subtitle','messenger_url','telegram_url','call_number',
        'toast_position','toast_duration_ms','footer_text','admin_ip_whitelist','nav_logo','favicon','trade_license_number','trade_license_qr'
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

    $allowedMime = [
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png' => 'png',
        'image/x-png' => 'png',
        'image/webp' => 'webp',
        'image/x-icon' => 'ico',
        'image/vnd.microsoft.icon' => 'ico',
    ];
    $targetDir = __DIR__ . '/../access/img/uploads';
    if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
        $uploadError = 'Upload folder could not be created.';
    }

    if ($uploadError === '' && !is_writable($targetDir)) {
        $uploadError = 'Upload folder is not writable.';
    }

    $uploadMap = [
        'hero_image' => ['setting' => 'hero_image', 'prefix' => 'hero'],
        'nav_logo_file' => ['setting' => 'nav_logo', 'prefix' => 'logo'],
        'favicon_file' => ['setting' => 'favicon', 'prefix' => 'favicon'],
        'trade_license_qr_file' => ['setting' => 'trade_license_qr', 'prefix' => 'trade-license-qr'],
    ];

    if ($uploadError === '') {
        foreach ($uploadMap as $inputName => $meta) {
            if (!isset($_FILES[$inputName]) || (int)($_FILES[$inputName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $fileError = (int)($_FILES[$inputName]['error'] ?? UPLOAD_ERR_OK);
            if ($fileError !== UPLOAD_ERR_OK) {
                $uploadError = uploadErrorMessage($fileError);
                break;
            }

            $tmpPath = (string)($_FILES[$inputName]['tmp_name'] ?? '');
            $originalName = (string)($_FILES[$inputName]['name'] ?? '');
            if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
                $uploadError = 'Invalid upload payload for ' . $meta['setting'] . '.';
                break;
            }

            $ext = detectUploadedFileExtension($tmpPath, $originalName, $allowedMime);
            if ($ext === null) {
                $uploadError = 'Invalid file format for ' . $meta['setting'] . '. Allowed: jpg, png, webp, ico.';
                break;
            }

            $fileName = $meta['prefix'] . '-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $ext;
            $targetPath = $targetDir . '/' . $fileName;

            if (!move_uploaded_file($tmpPath, $targetPath)) {
                $uploadError = 'Failed to upload ' . $meta['setting'] . '. Check folder permission.';
                break;
            }

            $webPath = 'access/img/uploads/' . $fileName;
            $stmt->execute([':k' => $meta['setting'], ':v' => $webPath]);
            $stmt->execute([':k' => $meta['setting'] . '_updated_at', ':v' => date('c')]);

            try {
                $mediaStmt = db()->prepare('INSERT INTO media_uploads (setting_key, file_path, file_ext) VALUES (:k, :p, :e)');
                $mediaStmt->execute([':k' => $meta['setting'], ':p' => $webPath, ':e' => $ext]);
            } catch (Throwable $exception) {
                // Keep settings save successful even if media log table is unavailable.
            }
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
      'nav_logo' => 'Navbar Logo Path (optional)',
      'favicon' => 'Favicon Path (optional)',
      'trade_license_number' => 'Trade License Number',
      'trade_license_qr' => 'Trade License QR Image Path (optional)',
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

    <label class="text-sm">Navbar Logo Upload
      <input type="file" name="nav_logo_file" accept="image/*" class="w-full border p-2 rounded bg-white">
    </label>

    <label class="text-sm">Favicon Upload
      <input type="file" name="favicon_file" accept="image/*,.ico" class="w-full border p-2 rounded bg-white">
    </label>

    <label class="text-sm">Trade License QR Upload
      <input type="file" name="trade_license_qr_file" accept="image/*" class="w-full border p-2 rounded bg-white">
    </label>

    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
    <div class="md:col-span-2"><button class="bg-green-600 text-white p-2 rounded">Save All</button></div>
  </form>
</div>
</body></html>
