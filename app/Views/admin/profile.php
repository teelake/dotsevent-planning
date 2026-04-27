<?php
declare(strict_types=1);
/** @var array<string, mixed> $user */
$user = $user ?? [];
$email = (string) ($user['email'] ?? '');
$role = (string) ($user['role'] ?? '');
$created = (string) ($user['created_at'] ?? '');
?>
<section class="section--tight" style="padding-top: 0;">
    <div class="card card--folio" style="max-width: 36rem;">
        <h2 class="card__title" style="margin: 0 0 0.5rem;">Account</h2>
        <p class="card__text" style="margin: 0 0 1.25rem;">Update the email you use to sign in. To change your password, use <a class="text-link" href="<?= e(app_url('admin/password')) ?>">Password</a>.</p>

        <dl class="admin-meta" style="margin: 0 0 1.25rem; display: grid; gap: 0.5rem; font-size: 0.95rem;">
            <div><dt class="text-muted" style="display:inline;margin-right:0.35rem;">Role</dt><dd style="display:inline;margin:0;"><?= e($role !== '' ? $role : '—') ?></dd></div>
            <?php if ($created !== ''): ?>
            <div><dt class="text-muted" style="display:inline;margin-right:0.35rem;">Member since</dt><dd style="display:inline;margin:0;"><?= e($created) ?></dd></div>
            <?php endif; ?>
        </dl>

        <form class="admin-form" method="post" action="<?= e(app_url('admin/profile/save')) ?>">
            <?= csrf_field() ?>
            <div class="form-row">
                <label for="profile-email">Email</label>
                <input class="input" id="profile-email" name="email" type="email" required autocomplete="email" value="<?= e($email) ?>">
            </div>
            <button class="btn btn--primary" type="submit">Save profile</button>
        </form>
    </div>
</section>
