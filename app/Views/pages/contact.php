<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$cfg = app_config();
$contactEmail = trim(site_setting('email', (string) ($cfg['email'] ?? 'info@dotseventplanning.com')));
$phoneDisplay = trim(site_setting('phone_display', (string) ($cfg['phone_display'] ?? '')));
$phoneTel = trim(site_setting('phone_tel', (string) ($cfg['phone_tel'] ?? '')));
$addr1 = trim(site_setting('address_line1', (string) ($cfg['address_line1'] ?? '')));
$addr2 = trim(site_setting('address_line2', (string) ($cfg['address_line2'] ?? '')));
$mapUrl = site_map_embed_url();
$page_title = (string) ($cms['doc_title'] ?? 'Contact us');
$crumb_current = $page_title;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <?php include dirname(__DIR__) . '/partials/app-rail.php'; ?>
    <div class="app-shell__main">
        <?php if (!empty($cms['has_custom_body'])): ?>
        <div class="shell shell--wide page-pad prose cms-page-body" data-reveal>
        <?= $cms['body_html'] ?>
        </div>
        <?php endif; ?>
        <div class="shell shell--wide page-pad" data-reveal>
            <div class="section__split contact-page__split">
                <div class="contact-page__form-wrap">
                    <h2 class="section__title contact-form__heading">We will reach out to you</h2>
                    <form class="contact-form" method="post" action="<?= e(app_url('contact')) ?>">
                        <?= csrf_field() ?>
                        <label class="visually-hidden" for="c-name">Name</label>
                        <input id="c-name" class="input" type="text" name="name" placeholder="Name" autocomplete="name">
                        <label class="visually-hidden" for="c-email">Email</label>
                        <input id="c-email" class="input" type="email" name="email" placeholder="Email" autocomplete="email" required>
                        <label class="visually-hidden" for="c-subject">Subject</label>
                        <input id="c-subject" class="input" type="text" name="subject" placeholder="Subject">
                        <label class="visually-hidden" for="c-phone">Phone</label>
                        <input id="c-phone" class="input" type="tel" name="phone" placeholder="Phone" autocomplete="tel">
                        <label class="visually-hidden" for="c-msg">Message</label>
                        <textarea id="c-msg" class="input input--textarea" name="message" placeholder="Message" required></textarea>
                        <button class="btn btn--primary contact-form__submit" type="submit">Send</button>
                    </form>
                </div>
                <aside class="contact-page__aside app-panel app-panel--rail">
                    <h2 class="contact-page__aside-title">Visit or call</h2>
                    <?php if ($addr1 !== ''): ?>
                    <p class="contact-page__address"><?= e($addr1) ?></p>
                    <?php endif; ?>
                    <?php if ($addr2 !== ''): ?>
                    <p class="contact-page__address"><?= e($addr2) ?></p>
                    <?php endif; ?>
                    <p class="contact-page__email"><a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a></p>
                    <?php if ($phoneTel !== '' && $phoneDisplay !== ''): ?>
                    <p class="contact-page__phone"><a href="tel:<?= e(preg_replace('/\s+/', '', $phoneTel)) ?>"><?= e($phoneDisplay) ?></a></p>
                    <?php endif; ?>
                    <div class="map-embed map-embed--contact contact-page__map">
                        <iframe class="map-embed__frame" title="Map: our location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= e($mapUrl) ?>"></iframe>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
