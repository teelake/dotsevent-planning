<?php
declare(strict_types=1);
?>
<h1 class="section__title" style="margin-bottom: 0.5rem;">Sign in</h1>
<p class="section__lead" style="margin-bottom: 1.5rem;">Administrator access for products, leads, and orders.</p>
<form class="admin-form" method="post" action="<?= e(app_url('admin/login')) ?>" style="max-width: 22rem;">
    <?= csrf_field() ?>
    <div class="form-row">
        <label for="adm-email">Email</label>
        <input class="input" id="adm-email" name="email" type="email" required autocomplete="username">
    </div>
    <div class="form-row">
        <label for="adm-pass">Password</label>
        <input class="input" id="adm-pass" name="password" type="password" required autocomplete="current-password">
    </div>
    <button class="btn btn--primary" type="submit">Sign in</button>
</form>
