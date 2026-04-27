<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$page_title = (string) ($cms['doc_title'] ?? 'Book');
$crumb_current = $page_title;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<?php if (!empty($cms['has_custom_body'])): ?>
<div class="shell page-pad prose cms-page-body" data-reveal>
<?= $cms['body_html'] ?>
</div>
<?php endif; ?>
<?php
$packages = [
    'basic' => 'Basic',
    'premium' => 'Premium',
    'vip' => 'VIP',
    'not_sure' => 'Not sure yet',
];
$preselect_package = $preselect_package ?? null;
$defaultPackage = is_string($preselect_package) && $preselect_package !== '' && isset($packages[$preselect_package])
    ? $preselect_package
    : 'not_sure';
?>
<div class="shell page-pad" data-reveal>
    <p class="section__lead" style="max-width: 56ch; margin-bottom: 2rem;">Compare packages below, then tell us about your event. <strong>Payment is not</strong> taken for event packages on this form—we follow up to confirm details and next steps.</p>
    <ul class="package-compare" role="list" aria-label="Event packages">
        <li class="package-compare__item">
            <h3 class="package-compare__name">Basic</h3>
            <p class="package-compare__blurb">Essential coordination, timeline, and day-of support for smaller gatherings.</p>
        </li>
        <li class="package-compare__item">
            <h3 class="package-compare__name">Premium</h3>
            <p class="package-compare__blurb">Full planning, vendor alignment, and stress-free execution for milestone events.</p>
        </li>
        <li class="package-compare__item">
            <h3 class="package-compare__name">VIP</h3>
            <p class="package-compare__blurb">White-glove end-to-end—creative direction, premium touches, and dedicated leadership.</p>
        </li>
    </ul>
    <h2 class="section__title" style="margin-top: 0; margin-bottom: 1rem; font-size: 1.45rem;">Request a booking</h2>
    <form class="contact-form contact-form--book" method="post" action="<?= e(app_url('book')) ?>">
        <?= csrf_field() ?>
        <label class="visually-hidden" for="b-name">Name</label>
        <input id="b-name" class="input" name="name" type="text" placeholder="Your name" autocomplete="name" required>
        <label class="visually-hidden" for="b-email">Email</label>
        <input id="b-email" class="input" name="email" type="email" placeholder="Email" autocomplete="email" required>
        <label class="visually-hidden" for="b-phone">Phone</label>
        <input id="b-phone" class="input" name="phone" type="tel" placeholder="Phone" autocomplete="tel">
        <label for="b-pkg" class="text-muted" style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.25rem;">Package</label>
        <select id="b-pkg" class="input" name="package" style="max-width: 100%; width: 100%; border-radius: var(--radius-cta); padding: 0.65rem 1rem; border: 1px solid var(--color-line-strong); background: var(--color-surface); font: inherit;">
            <?php foreach ($packages as $val => $label): ?>
            <option value="<?= e($val) ?>"<?= $val === $defaultPackage ? ' selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <label for="b-date" class="text-muted" style="font-size:0.85rem; font-weight:600; display:block; margin: 0.75rem 0 0.25rem;">Preferred event date (estimate)</label>
        <input id="b-date" class="input" name="event_date" type="date" style="max-width: 100%;">
        <label for="b-guests" class="text-muted" style="font-size:0.85rem; font-weight:600; display:block; margin: 0.75rem 0 0.25rem;">Guest count (approx.)</label>
        <input id="b-guests" class="input" name="guest_count" type="text" placeholder="e.g. 80" inputmode="numeric" style="max-width: 100%;">
        <label for="b-venue" class="text-muted" style="font-size:0.85rem; font-weight:600; display:block; margin: 0.75rem 0 0.25rem;">City / venue</label>
        <input id="b-venue" class="input" name="venue_city" type="text" placeholder="Saint John area, venue TBD" style="max-width: 100%;">
        <label class="visually-hidden" for="b-msg">Message</label>
        <textarea id="b-msg" class="input input--textarea" name="message" placeholder="Tell us about your event, style, and timing."></textarea>
        <button class="btn btn--primary" type="submit" style="align-self: flex-start;">Send request</button>
    </form>
</div>
