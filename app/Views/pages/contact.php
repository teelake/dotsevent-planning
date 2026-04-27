<?php
declare(strict_types=1);
$page_title = 'Contact us';
$crumb_current = 'Contact';
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="shell page-pad">
    <div class="section__split" style="align-items: start;">
        <div>
            <h2 class="section__title" style="font-size: 1.4rem; margin-bottom: 1rem;">We will reach out to you</h2>
            <form class="contact-form" method="post" action="<?= e(app_url('contact')) ?>">
                <?= csrf_field() ?>
                <label class="visually-hidden" for="c-name">Name</label>
                <input id="c-name" class="input" style="max-width: 100%;" type="text" name="name" placeholder="Name" autocomplete="name">
                <label class="visually-hidden" for="c-email">Email</label>
                <input id="c-email" class="input" style="max-width: 100%;" type="email" name="email" placeholder="Email" autocomplete="email" required>
                <label class="visually-hidden" for="c-subject">Subject</label>
                <input id="c-subject" class="input" style="max-width: 100%;" type="text" name="subject" placeholder="Subject">
                <label class="visually-hidden" for="c-phone">Phone</label>
                <input id="c-phone" class="input" style="max-width: 100%;" type="tel" name="phone" placeholder="Phone" autocomplete="tel">
                <label class="visually-hidden" for="c-msg">Message</label>
                <textarea id="c-msg" class="input" style="max-width: 100%; min-height: 120px; border-radius: 12px;" name="message" placeholder="Message" required></textarea>
                <button class="btn btn--primary" type="submit" style="align-self: flex-start;">Send</button>
            </form>
        </div>
        <div>
            <p>181 McNamara Drive, Saint John, NB E2J 3L2</p>
            <p><a href="mailto:info@dotseventplanning.com">info@dotseventplanning.com</a></p>
            <p class="section__map-placeholder" style="margin-top: 1rem; min-height: 200px; background: #eee; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #888;">Map embed</p>
        </div>
    </div>
</div>
