<?php
declare(strict_types=1);
?>
<section class="section--tight" style="padding-top: 0;">
    <div class="card card--folio" style="max-width: 36rem;">
        <h2 class="card__title" style="margin: 0 0 0.5rem;">Change password</h2>
        <p class="card__text" style="margin: 0 0 1.25rem;">Use a strong password you don’t reuse elsewhere. Minimum 10 characters. <a class="text-link" href="<?= e(app_url('admin/profile')) ?>">Back to profile</a>.</p>

        <form class="admin-form" method="post" action="<?= e(app_url('admin/password/save')) ?>">
            <?= csrf_field() ?>
            <div class="form-row">
                <label for="pwd-current">Current password</label>
                <input class="input" id="pwd-current" name="current_password" type="password" required autocomplete="current-password">
            </div>
            <div class="form-row">
                <label for="pwd-new">New password</label>
                <input class="input" id="pwd-new" name="new_password" type="password" required autocomplete="new-password" minlength="10">
            </div>
            <div class="form-row">
                <label for="pwd-confirm">Confirm new password</label>
                <input class="input" id="pwd-confirm" name="confirm_password" type="password" required autocomplete="new-password" minlength="10">
            </div>
            <button class="btn btn--primary" type="submit">Update password</button>
        </form>
    </div>
</section>
