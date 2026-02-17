<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null)) {
    $name = trim((string)($_POST['client_name'] ?? ''));
    $designation = trim((string)($_POST['designation'] ?? 'Client'));
    $content = trim((string)($_POST['content'] ?? ''));
    if ($name !== '' && $content !== '' && db()) {
        db()->prepare('INSERT INTO testimonials (client_name, designation, content, rating) VALUES (:n,:d,:c,5)')->execute([':n'=>$name,':d'=>$designation,':c'=>$content]);
    }
    header('Location: testimonials.php');exit;
}
$items = fetchAllRows('SELECT * FROM testimonials ORDER BY id DESC');
?>
<!doctype html><html><head><meta charset="UTF-8"><script src="https://cdn.tailwindcss.com"></script></head><body class="p-6 bg-slate-100"><div class="max-w-4xl mx-auto space-y-4">
<a href="dashboard.php" class="text-green-700">← Dashboard</a><h1 class="text-2xl font-bold">Testimonials</h1>
<form method="post" class="bg-white p-4 rounded shadow grid gap-2"><input name="client_name" class="border p-2" placeholder="Client Name" required><input name="designation" class="border p-2" placeholder="Designation"><textarea name="content" class="border p-2" placeholder="Review" required></textarea><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>"><button class="bg-green-600 text-white p-2 rounded">Add</button></form>
<div class="bg-white p-4 rounded shadow"><?php foreach($items as $row): ?><div class="border-b py-2"><strong><?= htmlspecialchars($row['client_name']) ?></strong>: <?= htmlspecialchars($row['content']) ?></div><?php endforeach; ?></div>
</div></body></html>
