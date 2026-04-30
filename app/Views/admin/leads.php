<?php
declare(strict_types=1);
/** @var list<array<string, mixed>> $leads */
/** @var int $page */
/** @var int $pages */
/** @var int $total */
/** @var int $per_page */
?>
<p class="section__lead" style="margin-bottom: 1rem;">Form submissions and newsletter signups from the site.</p>
<p class="text-muted" style="margin-bottom:1rem;">Total: <?= (int) $total ?> · Page <?= (int) $page ?> of <?= (int) $pages ?></p>
<div style="overflow-x:auto;">
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Email</th>
            <th>Name</th>
            <th>Message / notes</th>
            <th>When</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($leads as $row): ?>
        <tr>
            <td><?= (int) $row['id'] ?></td>
            <td><code><?= e((string) $row['type']) ?></code></td>
            <td><?= e((string) $row['email']) ?></td>
            <td><?= e((string) ($row['name'] ?? '')) ?></td>
            <td>
                <?php
                $m = (string) ($row['message'] ?? '');
                $details = [];
                foreach ([
                    'subject' => 'Subject',
                    'package_key' => 'Package',
                    'event_date' => 'Event date',
                    'guest_count' => 'Guests',
                    'venue_city' => 'Venue/city',
                    'extra' => 'Legacy details',
                ] as $key => $label) {
                    $value = trim((string) ($row[$key] ?? ''));
                    if ($value !== '') {
                        $details[] = $label . ': ' . $value;
                    }
                }
                if ($details !== []) {
                    echo '<details><summary>Details</summary><pre style="white-space:pre-wrap;font-size:0.8rem;margin:0.5rem 0 0;">' . e(implode("\n", $details)) . '</pre></details>';
                }
                if ($m !== '') {
                    $short = (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($m) > 200)
                        ? mb_substr($m, 0, 200) . '…'
                        : (strlen($m) > 200 ? substr($m, 0, 200) . '…' : $m);
                    echo '<p style="margin:0.25rem 0 0;font-size:0.9rem;">' . e($short) . '</p>';
                }
                ?>
            </td>
            <td style="white-space:nowrap;"><?= e((string) ($row['created_at'] ?? '')) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php if ($leads === []): ?>
<p class="section__lead">No leads yet.</p>
<?php endif; ?>
<nav class="text-muted" style="margin-top:1rem; display:flex; gap:0.75rem; flex-wrap:wrap;">
    <?php if ($page > 1): ?><a class="text-link" href="?page=<?= (int) $page - 1 ?>">← Previous</a><?php endif; ?>
    <?php if ($page < $pages): ?><a class="text-link" href="?page=<?= (int) $page + 1 ?>">Next →</a><?php endif; ?>
</nav>
