<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if (isset($_GET['delete']) && db()) {
    db()->prepare('DELETE FROM services WHERE id = :id')->execute([':id' => (int)$_GET['delete']]);
    header('Location: services.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null)) {
    $title = trim((string)($_POST['title'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $icon = trim((string)($_POST['icon'] ?? '🟢'));
    if ($title !== '' && $description !== '' && db()) {
        db()->prepare('INSERT INTO services (title, description, icon) VALUES (:t,:d,:i)')->execute([':t' => $title, ':d' => $description, ':i' => $icon]);
    }
    header('Location: services.php');
    exit;
}
$services = fetchAllRows('SELECT * FROM services ORDER BY id DESC');
?>
<!doctype html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdn.tailwindcss.com"></script></head><body class="p-6 bg-slate-100"><div class="max-w-4xl mx-auto space-y-4">
<a href="dashboard.php" class="text-green-700">← Dashboard</a><h1 class="text-2xl font-bold">Services</h1>
<form method="post" class="bg-white p-4 rounded shadow grid gap-2"><input name="title" class="border p-2" placeholder="Title" required><textarea name="description" class="border p-2" placeholder="Description" required></textarea><input name="icon" class="border p-2" placeholder="Icon (emoji)"><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>"><button class="bg-green-600 text-white p-2 rounded">Add</button></form>
<div class="bg-white p-4 rounded shadow"><?php foreach($services as $row): ?><div class="border-b py-2"><?= htmlspecialchars($row['icon']) ?> <strong><?= htmlspecialchars($row['title']) ?></strong> - <?= htmlspecialchars($row['description']) ?> <a class="text-red-600 text-sm" href="services.php?delete=<?= (int)$row['id'] ?>" data-confirm-action data-confirm-title="Delete service" data-confirm-message="এই সার্ভিসটি মুছে ফেলতে চান?" data-confirm-yes="Yes, Delete" data-confirm-no="No">Delete</a></div><?php endforeach; ?></div>
</div><script src="../access/javascript/admin-confirm.js"></script></body></html>
