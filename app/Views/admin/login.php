<?php
declare(strict_types=1);
?>
<form class="admin-login__form" method="post" action="<?= e(app_url('admin/login')) ?>" autocomplete="on">
    <?= csrf_field() ?>
    <div class="admin-login__field">
        <label class="admin-login__label" for="adm-email">Work email</label>
        <input class="admin-login__input" id="adm-email" name="email" type="email" inputmode="email" required autocomplete="username" placeholder="you@company.com">
    </div>
    <div class="admin-login__field">
        <label class="admin-login__label" for="adm-pass">Password</label>
        <input class="admin-login__input" id="adm-pass" name="password" type="password" required autocomplete="current-password" placeholder="••••••••">
    </div>
    <button class="admin-login__submit" type="submit">Continue</button>
</form>
