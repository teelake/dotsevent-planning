<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$bookKids = e(app_url('book')) . '?package=premium';
$page_title = (string) ($cms['doc_title'] ?? 'Kids party packages');
$crumb_current = $page_title;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<?php if (!empty($cms['has_custom_body'])): ?>
<div class="shell page-pad prose cms-page-body" data-reveal>
<?= $cms['body_html'] ?>
</div>
<?php else: ?>
<div class="shell page-pad" data-reveal>
    <p class="section__lead" style="max-width: 52ch; margin-bottom: 2rem;">Themed kids’ parties with setup, activities, and teardown handled for you. Packages are a starting point—we tailor everything to age, venue, and your sanity level. <strong>No online payment</strong> here: we confirm details and pricing after you reach out.</p>
    <ul class="kids-offers" role="list" aria-label="Kids party package tiers">
        <li class="kids-offer">
            <h2 class="kids-offer__name">Mini</h2>
            <p class="text-muted" style="margin: 0; font-size: 0.9rem;">2–3 hours · smaller guest lists</p>
            <ul class="kids-offer__list">
                <li>Theme consult + simple decor plan</li>
                <li>On-site lead for games & flow</li>
                <li>Basic clean-up and pack-out</li>
            </ul>
        </li>
        <li class="kids-offer">
            <h2 class="kids-offer__name">Standard</h2>
            <p class="text-muted" style="margin: 0; font-size: 0.9rem;">Half-day · most birthdays</p>
            <ul class="kids-offer__list">
                <li>Styling, balloon or backdrop focal</li>
                <li>Activity run-of-show and vendor touchpoints</li>
                <li>Coordination with your cake & entertainment</li>
            </ul>
        </li>
        <li class="kids-offer">
            <h2 class="kids-offer__name">Classic</h2>
            <p class="text-muted" style="margin: 0; font-size: 0.9rem;">Full venue takeover · showpiece parties</p>
            <ul class="kids-offer__list">
                <li>Full creative direction and floor plan</li>
                <li>Photo moments and premium finishing</li>
                <li>End-to-end run of day through load-out</li>
            </ul>
        </li>
    </ul>
    <div style="text-align: center; padding: 1.5rem 0 0;">
        <a class="btn btn--primary" href="<?= $bookKids ?>">Book your event</a>
        <p class="text-muted" style="margin: 0.75rem 0 0; font-size: 0.9rem;">We’ll pre-select <strong>Premium</strong> on the booking form; change it there if you prefer another package.</p>
    </div>
</div>
<?php endif; ?>
