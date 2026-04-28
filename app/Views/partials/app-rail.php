<?php
declare(strict_types=1);
/** Reusable sticky rail: same link labels as primary nav (no new copy). */
$nav = $active_nav ?? $activeNav ?? '';
$bookCurrent = $nav === 'book';
$rentalsCurrent = $nav === 'rentals';
$svcCurrent = $nav === 'services';
$folioCurrent = $nav === 'portfolio';
$contactCurrent = $nav === 'contact';
?>
<aside class="app-rail" aria-label="Shortcuts">
    <a class="btn btn--primary app-rail__cta" href="<?= e(app_url('book')) ?>"<?= $bookCurrent ? ' aria-current="page"' : '' ?>>Book</a>
    <ul class="app-rail__list" role="list">
        <li><a class="app-rail__link<?= $svcCurrent ? ' is-active' : '' ?>" href="<?= e(app_url('services')) ?>"<?= $svcCurrent ? ' aria-current="page"' : '' ?>>Services</a></li>
        <li><a class="app-rail__link<?= $rentalsCurrent ? ' is-active' : '' ?>" href="<?= e(app_url('rentals')) ?>"<?= $rentalsCurrent ? ' aria-current="page"' : '' ?>>Rentals</a></li>
        <li><a class="app-rail__link<?= $folioCurrent ? ' is-active' : '' ?>" href="<?= e(app_url('portfolio')) ?>"<?= $folioCurrent ? ' aria-current="page"' : '' ?>>Portfolio</a></li>
        <li><a class="app-rail__link<?= $contactCurrent ? ' is-active' : '' ?>" href="<?= e(app_url('contact')) ?>"<?= $contactCurrent ? ' aria-current="page"' : '' ?>>Contact</a></li>
    </ul>
</aside>
