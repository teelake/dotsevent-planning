<?php
declare(strict_types=1);
?>
<div class="app-shell">
    <div class="app-shell__main">
<section class="page-pad">
    <div class="shell error-page" data-reveal>
        <h1 class="section__title">Page not found</h1>
        <p class="section__lead" style="margin:0 auto var(--space-lg); max-width: 42ch;">The page you requested does not exist or was moved.</p>
        <div class="error-page__actions">
            <a class="btn btn--primary" href="<?= e(app_url('')) ?>">Back to home</a>
            <a class="btn btn--secondary" href="<?= e(app_url('book')) ?>">Book an event</a>
        </div>
        <nav class="error-page__nav" aria-label="Popular pages">
            <h2 class="error-page__nav-heading">You might be looking for</h2>
            <ul class="error-page__links">
                <li><a class="text-link" href="<?= e(app_url('services')) ?>">Services</a></li>
                <li><a class="text-link" href="<?= e(app_url('rentals')) ?>">Rentals</a></li>
                <li><a class="text-link" href="<?= e(app_url('portfolio')) ?>">Portfolio</a></li>
                <li><a class="text-link" href="<?= e(app_url('contact')) ?>">Contact</a></li>
            </ul>
        </nav>
    </div>
</section>
    </div>
</div>
