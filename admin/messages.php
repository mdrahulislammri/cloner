<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
redirectToInstallerIfNeeded();

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();
$messages = fetchAllRows('SELECT * FROM contact_messages ORDER BY id DESC');

require_once __DIR__ . '/layout.php';
adminLayoutStart('Messages', 'messages');
?>
<div class="bg-white rounded-xl shadow border border-emerald-100 p-4 space-y-3">
  <?php if ($messages === []): ?>
    <p class="text-slate-500 text-sm">No messages yet.</p>
  <?php else: ?>
    <?php foreach($messages as $msg): ?>
      <article class="border-b pb-2 last:border-b-0">
        <h3 class="font-semibold"><?= htmlspecialchars($msg['name']) ?> <span class="text-xs text-gray-500">(<?= htmlspecialchars($msg['email']) ?>)</span></h3>
        <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
      </article>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php adminLayoutEnd(); ?>
