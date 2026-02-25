<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null)) {
    if (isset($_POST['delete_id']) && db()) {
        db()->prepare('DELETE FROM services WHERE id = :id')->execute([':id' => (int)$_POST['delete_id']]);
        header('Location: services.php');
        exit;
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $icon = trim((string)($_POST['icon'] ?? 'fa-solid fa-bolt'));
    if ($title !== '' && $description !== '' && db()) {
        db()->prepare('INSERT INTO services (title, description, icon) VALUES (:t,:d,:i)')->execute([':t' => $title, ':d' => $description, ':i' => $icon]);
    }
    header('Location: services.php');
    exit;
}
$services = fetchAllRows('SELECT * FROM services ORDER BY id DESC');

require_once __DIR__ . '/layout.php';
adminLayoutStart('Services', 'services');
?>
<form method="post" class="bg-white p-4 rounded-xl shadow border border-emerald-100 grid gap-2">
  <input name="title" class="border p-2 rounded" placeholder="Title" required>
  <textarea name="description" class="border p-2 rounded" placeholder="Description" required></textarea>
  <input name="icon" class="border p-2 rounded" placeholder="Icon class (e.g. fa-solid fa-bolt)">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
  <button class="bg-emerald-600 text-white p-2 rounded">Add</button>
</form>
<div class="bg-white p-4 rounded-xl shadow border border-emerald-100 space-y-2">
  <?php foreach($services as $row): ?>
    <div class="border-b py-2 last:border-b-0">
      <i class="<?= htmlspecialchars((string)$row['icon']) ?>" aria-hidden="true"></i> <strong><?= htmlspecialchars($row['title']) ?></strong> - <?= htmlspecialchars($row['description']) ?>
      <button type="button" class="text-red-600 text-sm ml-2" data-confirm-action data-confirm-title="Delete service" data-confirm-message="এই সার্ভিসটি মুছে ফেলতে চান?" data-confirm-yes="Yes, Delete" data-confirm-no="No" data-confirm-form-id="delete-service-<?= (int)$row['id'] ?>">Delete</button>
      <form id="delete-service-<?= (int)$row['id'] ?>" method="post" class="hidden"><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>"><input type="hidden" name="delete_id" value="<?= (int)$row['id'] ?>"></form>
    </div>
  <?php endforeach; ?>
</div>
<?php adminLayoutEnd(); ?>
