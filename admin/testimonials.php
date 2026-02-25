<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf'] ?? null)) {
    if (isset($_POST['delete_id']) && db()) {
        db()->prepare('DELETE FROM testimonials WHERE id = :id')->execute([':id' => (int)$_POST['delete_id']]);
        header('Location: testimonials.php');
        exit;
    }

    $name = trim((string)($_POST['client_name'] ?? ''));
    $designation = trim((string)($_POST['designation'] ?? 'Client'));
    $content = trim((string)($_POST['content'] ?? ''));
    $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    if ($name !== '' && $content !== '' && db()) {
        db()->prepare('INSERT INTO testimonials (client_name, designation, content, rating) VALUES (:n,:d,:c,:r)')->execute([':n' => $name, ':d' => $designation, ':c' => $content, ':r' => $rating]);
    }
    header('Location: testimonials.php');
    exit;
}
$items = fetchAllRows('SELECT * FROM testimonials ORDER BY id DESC');

require_once __DIR__ . '/layout.php';
adminLayoutStart('Testimonials', 'testimonials');
?>
<form method="post" class="bg-white p-4 rounded-xl shadow border border-emerald-100 grid gap-2">
  <input name="client_name" class="border p-2 rounded" placeholder="Client Name" required>
  <input name="designation" class="border p-2 rounded" placeholder="Designation">
  <textarea name="content" class="border p-2 rounded" placeholder="Review" required></textarea>
  <input name="rating" type="number" min="1" max="5" class="border p-2 rounded" value="5">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>">
  <button class="bg-emerald-600 text-white p-2 rounded">Add</button>
</form>
<div class="bg-white p-4 rounded-xl shadow border border-emerald-100">
  <?php foreach($items as $row): ?>
    <div class="border-b py-2 last:border-b-0">
      <strong><?= htmlspecialchars($row['client_name']) ?></strong> (<?= (int)$row['rating'] ?>/5): <?= htmlspecialchars($row['content']) ?>
      <button type="button" class="text-red-600 text-sm ml-2" data-confirm-action data-confirm-title="Delete testimonial" data-confirm-message="এই রিভিউটি মুছে ফেলতে চান?" data-confirm-yes="Yes, Delete" data-confirm-no="No" data-confirm-form-id="delete-testimonial-<?= (int)$row['id'] ?>">Delete</button>
      <form id="delete-testimonial-<?= (int)$row['id'] ?>" method="post" class="hidden"><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken()) ?>"><input type="hidden" name="delete_id" value="<?= (int)$row['id'] ?>"></form>
    </div>
  <?php endforeach; ?>
</div>
<?php adminLayoutEnd(); ?>
