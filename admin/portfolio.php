<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

function portfolioUploadErrorMessage(int $errorCode): string
{
    return match ($errorCode) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Uploaded image is too large.',
        UPLOAD_ERR_PARTIAL => 'Image upload was interrupted. Please retry.',
        UPLOAD_ERR_NO_TMP_DIR => 'Server temporary upload directory is missing.',
        UPLOAD_ERR_CANT_WRITE => 'Server could not write uploaded image.',
        UPLOAD_ERR_EXTENSION => 'Image upload blocked by a server extension.',
        default => 'Unknown upload error.',
    };
}

function detectPortfolioImageExtension(string $tmpPath, string $originalName, array $allowedMime): ?string
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

$error = '';
$old = [
    'title' => '',
    'category' => 'Social',
    'image_path' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null)) {
    if (isset($_POST['delete_id']) && db()) {
        db()->prepare('DELETE FROM portfolio_items WHERE id = :id')->execute([':id' => (int)$_POST['delete_id']]);
        header('Location: portfolio.php');
        exit;
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $category = trim((string)($_POST['category'] ?? 'Social'));
    $image = trim((string)($_POST['image_path'] ?? ''));

    $old['title'] = $title;
    $old['category'] = $category;
    $old['image_path'] = $image;

    $allowedMime = [
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png' => 'png',
        'image/x-png' => 'png',
        'image/webp' => 'webp',
    ];

    if (isset($_FILES['image_file']) && (int)($_FILES['image_file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $fileError = (int)($_FILES['image_file']['error'] ?? UPLOAD_ERR_OK);

        if ($fileError !== UPLOAD_ERR_OK) {
            $error = portfolioUploadErrorMessage($fileError);
        } else {
            $tmpPath = (string)($_FILES['image_file']['tmp_name'] ?? '');
            $originalName = (string)($_FILES['image_file']['name'] ?? '');

            if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
                $error = 'Invalid uploaded image payload.';
            } else {
                $ext = detectPortfolioImageExtension($tmpPath, $originalName, $allowedMime);
                if ($ext === null) {
                    $error = 'Invalid image format. Allowed: jpg, png, webp.';
                } else {
                    $targetDir = __DIR__ . '/../access/img/uploads';
                    if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                        $error = 'Portfolio upload folder could not be created.';
                    } elseif (!is_writable($targetDir)) {
                        $error = 'Portfolio upload folder is not writable.';
                    } else {
                        $fileName = 'portfolio-' . time() . '-' . random_int(100, 999) . '.' . $ext;
                        $targetPath = $targetDir . '/' . $fileName;

                        if (!move_uploaded_file($tmpPath, $targetPath)) {
                            $error = 'Failed to move uploaded image. Check folder permission.';
                        } else {
                            $image = 'access/img/uploads/' . $fileName;
                        }
                    }
                }
            }
        }
    }

    if ($image === '') {
        $image = 'access/img/portfolio-placeholder.svg';
    }

    if ($title === '') {
        $error = 'Title is required.';
    }

    if ($error === '' && db()) {
        db()->prepare('INSERT INTO portfolio_items (title, category, image_path) VALUES (:t,:c,:i)')->execute([
            ':t' => $title,
            ':c' => $category,
            ':i' => $image,
        ]);

        header('Location: portfolio.php?saved=1');
        exit;
    }
}

$items = fetchAllRows('SELECT * FROM portfolio_items ORDER BY id DESC');
?>
<!doctype html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdn.tailwindcss.com"></script></head><body class="p-6 bg-slate-100"><div class="max-w-5xl mx-auto space-y-4">
<a href="dashboard.php" class="text-green-700">← Dashboard</a><h1 class="text-2xl font-bold">Portfolio</h1>
<?php if (isset($_GET['saved'])): ?><p class="text-green-700 bg-green-50 p-2 rounded">Portfolio item saved successfully.</p><?php endif; ?>
<?php if ($error !== ''): ?><p class="text-red-700 bg-red-50 p-2 rounded"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow grid gap-2 md:grid-cols-2">
  <input name="title" class="border p-2" placeholder="Title" required value="<?= htmlspecialchars($old['title']) ?>">
  <input name="category" class="border p-2" placeholder="Category" value="<?= htmlspecialchars($old['category']) ?>">
  <input name="image_path" class="border p-2 md:col-span-2" placeholder="Image path (optional)" value="<?= htmlspecialchars($old['image_path']) ?>">
  <input type="file" name="image_file" accept="image/*" class="border p-2 md:col-span-2 bg-white">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
  <button class="bg-green-600 text-white p-2 rounded md:col-span-2">Add Portfolio</button>
</form>
<div class="bg-white p-4 rounded shadow space-y-2">
  <?php foreach($items as $row): ?>
    <div class="border-b pb-2">
      <strong><?= htmlspecialchars($row['title']) ?></strong> (<?= htmlspecialchars($row['category']) ?>)
      <div class="text-xs text-gray-500 break-all"><?= htmlspecialchars($row['image_path']) ?></div>
      <button type="button" class="text-red-600 text-sm" data-confirm-action data-confirm-title="Delete portfolio item" data-confirm-message="এই পোর্টফোলিও আইটেমটি মুছে ফেলতে চান?" data-confirm-yes="Yes, Delete" data-confirm-no="No" data-confirm-form-id="delete-portfolio-<?= (int)$row['id'] ?>">Delete</button><form id="delete-portfolio-<?= (int)$row['id'] ?>" method="post" class="hidden"><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>"><input type="hidden" name="delete_id" value="<?= (int)$row['id'] ?>"></form>
    </div>
  <?php endforeach; ?>
</div>
</div><script src="../access/javascript/admin-confirm.js"></script></body></html>
