<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if (isset($_GET['delete']) && db()) {
    db()->prepare('DELETE FROM portfolio_items WHERE id = :id')->execute([':id' => (int)$_GET['delete']]);
    header('Location: portfolio.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null)) {
    $title = trim((string)($_POST['title'] ?? ''));
    $category = trim((string)($_POST['category'] ?? 'Social'));
    $image = trim((string)($_POST['image_path'] ?? ''));

    if (!empty($_FILES['image_file']['name']) && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
        $mime = mime_content_type($_FILES['image_file']['tmp_name']) ?: '';
        $allowedMime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/svg+xml' => 'svg'];
        if (isset($allowedMime[$mime])) {
            $ext = $allowedMime[$mime];
            $targetDir = __DIR__ . '/../access/img/uploads';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0775, true);
            }
            $fileName = 'portfolio-' . time() . '-' . random_int(100, 999) . '.' . $ext;
            $targetPath = $targetDir . '/' . $fileName;
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                $image = 'access/img/uploads/' . $fileName;
            }
        }
    }

    if ($image === '') {
        $image = 'access/img/portfolio-placeholder.svg';
    }

    if ($title !== '' && db()) {
        db()->prepare('INSERT INTO portfolio_items (title, category, image_path) VALUES (:t,:c,:i)')->execute([':t' => $title, ':c' => $category, ':i' => $image]);
    }
    header('Location: portfolio.php');
    exit;
}

$items = fetchAllRows('SELECT * FROM portfolio_items ORDER BY id DESC');
?>
<!doctype html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdn.tailwindcss.com"></script></head><body class="p-6 bg-slate-100"><div class="max-w-5xl mx-auto space-y-4">
<a href="dashboard.php" class="text-green-700">← Dashboard</a><h1 class="text-2xl font-bold">Portfolio</h1>
<form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow grid gap-2 md:grid-cols-2">
  <input name="title" class="border p-2" placeholder="Title" required>
  <input name="category" class="border p-2" placeholder="Category">
  <input name="image_path" class="border p-2 md:col-span-2" placeholder="Image path (optional)">
  <input type="file" name="image_file" accept="image/*" class="border p-2 md:col-span-2 bg-white">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
  <button class="bg-green-600 text-white p-2 rounded md:col-span-2">Add Portfolio</button>
</form>
<div class="bg-white p-4 rounded shadow space-y-2">
  <?php foreach($items as $row): ?>
    <div class="border-b pb-2">
      <strong><?= htmlspecialchars($row['title']) ?></strong> (<?= htmlspecialchars($row['category']) ?>)
      <div class="text-xs text-gray-500 break-all"><?= htmlspecialchars($row['image_path']) ?></div>
      <a class="text-red-600 text-sm" href="portfolio.php?delete=<?= (int)$row['id'] ?>" onclick="return confirm('Delete item?')">Delete</a>
    </div>
  <?php endforeach; ?>
</div>
</div></body></html>
