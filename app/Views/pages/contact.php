<?php
declare(strict_types=1);
$page_title = 'Contact us';
$crumb_current = 'Contact';
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="shell page-pad" data-reveal>
    <div class="section__split contact-page__split">
        <div>
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
        <div>
            <p>181 McNamara Drive, Saint John, NB E2J 3L2</p>
            <p><a href="mailto:info@dotseventplanning.com">info@dotseventplanning.com</a></p>
            <p class="map-placeholder" role="img" aria-label="Map area">Map embed</p>
        </div>
    </div>
</div>
