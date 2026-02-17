<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();
$messages = fetchAllRows('SELECT * FROM contact_messages ORDER BY id DESC');
?>
<!doctype html><html><head><meta charset="UTF-8"><script src="https://cdn.tailwindcss.com"></script></head><body class="p-6 bg-slate-100"><div class="max-w-4xl mx-auto">
<a href="dashboard.php" class="text-green-700">← Dashboard</a><h1 class="text-2xl font-bold mb-3">Messages</h1>
<div class="bg-white rounded shadow p-4 space-y-3"><?php foreach($messages as $msg): ?><article class="border-b pb-2"><h3 class="font-semibold"><?= htmlspecialchars($msg['name']) ?> <span class="text-xs text-gray-500">(<?= htmlspecialchars($msg['email']) ?>)</span></h3><p><?= nl2br(htmlspecialchars($msg['message'])) ?></p></article><?php endforeach; ?></div>
</div></body></html>
