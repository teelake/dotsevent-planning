<?php
declare(strict_types=1);
/** @var array<string, mixed> $contact_blocks */
$b = $contact_blocks ?? [];
$cfg = app_config();
$contactEmail = trim(site_setting('email', (string) ($cfg['email'] ?? 'info@dotseventplanning.com')));
$phoneDisplay = trim(site_setting('phone_display', (string) ($cfg['phone_display'] ?? '')));
$phoneTel = trim(site_setting('phone_tel', (string) ($cfg['phone_tel'] ?? '')));
$addr1 = trim(site_setting('address_line1', (string) ($cfg['address_line1'] ?? '')));
$addr2 = trim(site_setting('address_line2', (string) ($cfg['address_line2'] ?? '')));
$mapUrl = site_map_embed_url();
$mapOpen = trim(site_setting('map_open_url', ''));

$intro = is_array($b['intro'] ?? null) ? $b['intro'] : [];
$channels = is_array($b['channels'] ?? null) ? $b['channels'] : [];
$form = is_array($b['contact_form'] ?? null) ? $b['contact_form'] : [];
$loc = is_array($b['location'] ?? null) ? $b['location'] : [];
$nw = is_array($b['newsletter_cta'] ?? null) ? $b['newsletter_cta'] : [];
$trust = is_array($b['trust'] ?? null) ? $b['trust'] : [];
$float = is_array($b['floating_widget'] ?? null) ? $b['floating_widget'] : [];
?>

<?php if (($intro['enabled'] ?? true) !== false && trim((string) ($intro['title'] ?? '')) !== ''): ?>
<section data-reveal>
    <h2 class="section__title"><?= e((string) $intro['title']) ?></h2>
    <?php if (!empty($intro['lead_html'])): ?><div class="prose"><?= (string) $intro['lead_html'] ?></div><?php endif; ?>
</section>
<?php endif; ?>

<?php if (($channels['enabled'] ?? true) !== false): ?>
<?php $items = is_array($channels['items'] ?? null) ? $channels['items'] : []; ?>
<section data-reveal style="margin-top:1rem;">
    <div class="contact-channels-grid" style="display:grid;gap:1rem;grid-template-columns:1fr;">
        <?php foreach ($items as $it): if (!is_array($it)) continue; $type = (string) ($it['type'] ?? ''); ?>
        <article class="app-panel">
            <h3 style="margin:0 0 .4rem;"><?= e((string) ($it['label'] ?? '')) ?></h3>
            <?php if ($type === 'email' && $contactEmail !== ''): ?>
                <p style="margin:.2rem 0;"><a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a></p>
            <?php elseif ($type === 'phone' && $phoneDisplay !== ''): ?>
                <p style="margin:.2rem 0;"><a href="tel:<?= e(preg_replace('/\s+/', '', $phoneTel)) ?>"><?= e($phoneDisplay) ?></a></p>
            <?php elseif ($type === 'office'): ?>
                <p style="margin:.2rem 0;"><?= e(trim($addr1 . ($addr2 !== '' ? ', ' . $addr2 : ''))) ?></p>
            <?php endif; ?>
            <?php if (trim((string) ($it['availability_line'] ?? '')) !== ''): ?>
                <p style="margin:.2rem 0;color:var(--color-ink-muted);"><?= e((string) $it['availability_line']) ?></p>
            <?php endif; ?>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<div class="section__split contact-page__split" style="margin-top:1.25rem;" data-reveal>
    <div class="contact-page__form-wrap">
        <?php if (($form['enabled'] ?? true) !== false): ?>
        <h2 class="section__title contact-form__heading"><?= e((string) ($form['heading'] ?? 'We will reach out to you')) ?></h2>
        <?php
            $fields = is_array($form['fields'] ?? null) ? $form['fields'] : [];
            $nameF = is_array($fields['name'] ?? null) ? $fields['name'] : [];
            $emailF = is_array($fields['email'] ?? null) ? $fields['email'] : [];
            $phoneF = is_array($fields['phone'] ?? null) ? $fields['phone'] : [];
            $subjectF = is_array($fields['subject'] ?? null) ? $fields['subject'] : [];
            $messageF = is_array($fields['message'] ?? null) ? $fields['message'] : [];
            $intents = is_array($form['intent_subjects'] ?? null) ? $form['intent_subjects'] : [];
        ?>
        <form class="contact-form" method="post" action="<?= e(app_url('contact')) ?>">
            <?= csrf_field() ?>
            <label class="visually-hidden" for="c-name"><?= e((string) ($nameF['label'] ?? 'Name')) ?></label>
            <input id="c-name" class="input" type="text" name="name" placeholder="<?= e((string) ($nameF['placeholder'] ?? 'Name')) ?>" autocomplete="name" <?= !empty($nameF['required']) ? 'required' : '' ?>>

            <label class="visually-hidden" for="c-email"><?= e((string) ($emailF['label'] ?? 'Email')) ?></label>
            <input id="c-email" class="input" type="email" name="email" placeholder="<?= e((string) ($emailF['placeholder'] ?? 'Email')) ?>" autocomplete="email" <?= !isset($emailF['required']) || !empty($emailF['required']) ? 'required' : '' ?>>

            <label class="visually-hidden" for="c-phone"><?= e((string) ($phoneF['label'] ?? 'Phone')) ?></label>
            <input id="c-phone" class="input" type="tel" name="phone" placeholder="<?= e((string) ($phoneF['placeholder'] ?? 'Phone')) ?>" autocomplete="tel" <?= !empty($phoneF['required']) ? 'required' : '' ?>>

            <?php if ($intents !== []): ?>
            <label class="visually-hidden" for="c-intent">Intent</label>
            <select id="c-intent" class="input" style="padding:.65rem 1rem;border-radius:var(--radius-sm);">
                <?php foreach ($intents as $row): if (!is_array($row)) continue; $sub = trim((string) ($row['subject'] ?? '')); ?>
                <option value="<?= e($sub) ?>"><?= e((string) ($row['label'] ?? $sub)) ?></option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>

            <label class="visually-hidden" for="c-subject"><?= e((string) ($subjectF['label'] ?? 'Subject')) ?></label>
            <input id="c-subject" class="input" type="text" name="subject" placeholder="<?= e((string) ($subjectF['placeholder'] ?? 'Subject')) ?>" <?= !empty($subjectF['required']) ? 'required' : '' ?>>

            <label class="visually-hidden" for="c-msg"><?= e((string) ($messageF['label'] ?? 'Message')) ?></label>
            <textarea id="c-msg" class="input input--textarea" name="message" placeholder="<?= e((string) ($messageF['placeholder'] ?? 'Message')) ?>" <?= !isset($messageF['required']) || !empty($messageF['required']) ? 'required' : '' ?>></textarea>

            <button class="btn btn--primary contact-form__submit" type="submit"><?= e((string) ($form['submit_label'] ?? 'Send')) ?></button>
        </form>
        <?php if ($intents !== []): ?>
        <script>
        (function () {
          var sel = document.getElementById('c-intent');
          var sub = document.getElementById('c-subject');
          if (!sel || !sub) return;
          function sync() { if ((sub.value || '').trim() === '') sub.value = sel.value || ''; }
          sel.addEventListener('change', function () { sub.value = sel.value || ''; });
          sync();
        })();
        </script>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <aside class="contact-page__aside app-panel app-panel--rail">
        <?php if (($loc['enabled'] ?? true) !== false): ?>
        <h2 class="contact-page__aside-title"><?= e((string) ($loc['aside_title'] ?? 'Visit or call')) ?></h2>
        <?php if ($addr1 !== ''): ?><p class="contact-page__address"><?= e($addr1) ?></p><?php endif; ?>
        <?php if ($addr2 !== ''): ?><p class="contact-page__address"><?= e($addr2) ?></p><?php endif; ?>
        <?php if ($contactEmail !== ''): ?><p class="contact-page__email"><a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a></p><?php endif; ?>
        <?php if ($phoneDisplay !== ''): ?><p class="contact-page__phone"><a href="tel:<?= e(preg_replace('/\s+/', '', $phoneTel)) ?>"><?= e($phoneDisplay) ?></a></p><?php endif; ?>
        <div class="map-embed map-embed--contact contact-page__map">
            <iframe class="map-embed__frame" title="<?= e((string) ($loc['map_title'] ?? 'Our location')) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= e($mapUrl) ?>"></iframe>
        </div>
        <?php if ($mapOpen !== ''): ?><p style="margin-top:.7rem;"><a class="text-link" href="<?= e($mapOpen) ?>" target="_blank" rel="noopener noreferrer"><?= e((string) ($loc['open_in_maps_label'] ?? 'Open in Maps')) ?></a></p><?php endif; ?>
        <?php endif; ?>
    </aside>
</div>

<?php if (($nw['enabled'] ?? true) !== false && trim((string) ($nw['title'] ?? '')) !== ''): ?>
<section class="app-band app-band--newsletter" aria-labelledby="contact-news-title" data-reveal>
  <div class="shell shell--wide newsletter-app">
    <div>
      <h2 id="contact-news-title" class="newsletter__title"><?= e((string) $nw['title']) ?></h2>
      <?php if (!empty($nw['description_html'])): ?><div class="newsletter__text prose"><?= (string) $nw['description_html'] ?></div><?php endif; ?>
    </div>
    <?php include dirname(__DIR__, 2) . '/partials/newsletter-band-flash.php'; ?>
    <form class="newsletter__form newsletter-app__form" method="post" action="<?= e(app_url('newsletter')) ?>" novalidate data-newsletter-form>
      <?= csrf_field() ?>
      <input type="hidden" name="_newsletter_return" value="contact">
      <label class="visually-hidden" for="contact-news-email"><?= e((string) ($nw['email_placeholder'] ?? 'Your email')) ?></label>
      <input id="contact-news-email" class="input" type="email" name="email" placeholder="<?= e((string) ($nw['email_placeholder'] ?? 'Your email')) ?>" autocomplete="email" required aria-describedby="contact-newsletter-error">
      <p id="contact-newsletter-error" class="newsletter-app__feedback newsletter-app__feedback--error" data-newsletter-error hidden role="alert"></p>
      <button class="btn btn--dark" type="submit"><?= e((string) ($nw['button_label'] ?? 'Submit')) ?></button>
    </form>
  </div>
</section>
<?php endif; ?>

<?php if (($trust['enabled'] ?? true) !== false): ?>
<section class="shell shell--wide" data-reveal style="padding:1rem 0 0;">
  <?php if (trim((string) ($trust['microcopy'] ?? '')) !== ''): ?><p style="margin:0 0 .6rem;color:var(--color-ink-muted);"><?= e((string) $trust['microcopy']) ?></p><?php endif; ?>
  <div style="display:flex;gap:.4rem;flex-wrap:wrap;" aria-hidden="true">
    <?php for ($i = 0; $i < (int) ($trust['star_count'] ?? 5); $i++): ?><span style="width:12px;height:12px;background:#f5c013;border-radius:50%;display:inline-block;"></span><?php endfor; ?>
  </div>
</section>
<?php endif; ?>


