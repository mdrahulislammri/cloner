<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null)) {
    $title = trim((string)($_POST['title'] ?? ''));
    $category = trim((string)($_POST['category'] ?? 'Social'));
    $image = trim((string)($_POST['image_path'] ?? 'access/img/img.jpg'));
    if ($title !== '' && db()) {
        db()->prepare('INSERT INTO portfolio_items (title, category, image_path) VALUES (:t,:c,:i)')->execute([':t'=>$title,':c'=>$category,':i'=>$image]);
    }
    header('Location: portfolio.php');exit;
}
$items = fetchAllRows('SELECT * FROM portfolio_items ORDER BY id DESC');
?>
<!doctype html><html><head><meta charset="UTF-8"><script src="https://cdn.tailwindcss.com"></script></head><body class="p-6 bg-slate-100"><div class="max-w-4xl mx-auto space-y-4">
<a href="dashboard.php" class="text-green-700">← Dashboard</a><h1 class="text-2xl font-bold">Portfolio</h1>
<form method="post" class="bg-white p-4 rounded shadow grid gap-2"><input name="title" class="border p-2" placeholder="Title" required><input name="category" class="border p-2" placeholder="Category"><input name="image_path" class="border p-2" placeholder="Image path"><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>"><button class="bg-green-600 text-white p-2 rounded">Add</button></form>
<div class="bg-white p-4 rounded shadow"><?php foreach($items as $row): ?><div class="border-b py-2"><strong><?= htmlspecialchars($row['title']) ?></strong> (<?= htmlspecialchars($row['category']) ?>)</div><?php endforeach; ?></div>
</div></body></html>
