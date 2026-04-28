<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$page_title = (string) ($cms['doc_title'] ?? 'Book');
$crumb_current = $page_title;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <div class="app-shell__main">
        <?php if (!empty($cms['has_custom_body'])): ?>
        <div class="shell shell--wide page-pad prose cms-page-body" data-reveal>
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
        <div class="shell shell--wide page-pad book-page" data-reveal>
            <p class="section__lead book-page__lead">Compare packages below, then tell us about your event. <strong>Payment is not</strong> taken for event packages on this form—we follow up to confirm details and next steps.</p>
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
            <h2 class="section__title book-page__form-title">Request a booking</h2>
            <form class="contact-form contact-form--book" method="post" action="<?= e(app_url('book')) ?>">
                <?= csrf_field() ?>
                <label class="visually-hidden" for="b-name">Name</label>
                <input id="b-name" class="input" name="name" type="text" placeholder="Your name" autocomplete="name" required>
                <label class="visually-hidden" for="b-email">Email</label>
                <input id="b-email" class="input" name="email" type="email" placeholder="Email" autocomplete="email" required>
                <label class="visually-hidden" for="b-phone">Phone</label>
                <input id="b-phone" class="input" name="phone" type="tel" placeholder="Phone" autocomplete="tel">
                <label for="b-pkg" class="field-label">Package</label>
                <select id="b-pkg" class="input input--block" name="package">
                    <?php foreach ($packages as $val => $label): ?>
                    <option value="<?= e($val) ?>"<?= $val === $defaultPackage ? ' selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="b-date" class="field-label field-label--spaced">Preferred event date (estimate)</label>
                <input id="b-date" class="input input--block" name="event_date" type="date">
                <label for="b-guests" class="field-label field-label--spaced">Guest count (approx.)</label>
                <input id="b-guests" class="input input--block" name="guest_count" type="text" placeholder="e.g. 80" inputmode="numeric">
                <label for="b-venue" class="field-label field-label--spaced">City / venue</label>
                <input id="b-venue" class="input input--block" name="venue_city" type="text" placeholder="Saint John area, venue TBD">
                <label class="visually-hidden" for="b-msg">Message</label>
                <textarea id="b-msg" class="input input--textarea" name="message" placeholder="Tell us about your event, style, and timing."></textarea>
                <button class="btn btn--primary book-page__submit" type="submit">Send request</button>
            </form>
        </div>
    </div>
</div>
