<?php
declare(strict_types=1);
/** @var array<string, mixed> $services_blocks */
$svc = isset($services_blocks) && is_array($services_blocks) ? $services_blocks : [];

$on = static function (?array $sec): bool {
    if ($sec === null) {
        return false;
    }

    return ($sec['enabled'] ?? true) !== false;
};

$intro = is_array($svc['intro'] ?? null) ? $svc['intro'] : [];
$off = is_array($svc['offerings'] ?? null) ? $svc['offerings'] : [];
$partner = is_array($svc['partnership'] ?? null) ? $svc['partnership'] : [];
$faq = is_array($svc['faq'] ?? null) ? $svc['faq'] : [];
$nwa = is_array($svc['newsletter_cta'] ?? null) ? $svc['newsletter_cta'] : [];
?>

<div class="services-modern">
<?php if ($on($intro)): ?>
<section class="app-band app-band--surface section services-modern__intro" aria-labelledby="services-intro-heading" data-reveal>
    <div class="shell shell--wide">
        <?php $ie = trim((string) ($intro['eyebrow'] ?? '')); ?>
        <?php if ($ie !== ''): ?>
        <p class="eyebrow"><?= e($ie) ?></p>
        <?php endif; ?>
        <?php $it = trim((string) ($intro['title'] ?? '')); ?>
        <?php if ($it !== ''): ?>
        <h2 id="services-intro-heading" class="section__title"><?= e($it) ?></h2>
        <?php endif; ?>
        <?php $lld = isset($intro['lead_html']) && is_string($intro['lead_html']) ? $intro['lead_html'] : ''; ?>
        <?php if ($lld !== '') { ?>
        <div class="services-modern__lead prose services-modern-prose"><?= $lld ?></div>
        <?php } ?>
    </div>
</section>
<?php endif; ?>

<?php if ($on($off)): ?>
<section class="app-band section services-modern__offerings" aria-labelledby="services-catalog-heading" data-reveal>
    <div class="shell shell--wide">
        <?php $oe = trim((string) ($off['eyebrow'] ?? '')); ?>
        <?php if ($oe !== ''): ?>
        <p class="eyebrow"><?= e($oe) ?></p>
        <?php endif; ?>
        <?php $ost = trim((string) ($off['section_title'] ?? '')); ?>
        <?php if ($ost !== ''): ?>
        <h2 id="services-catalog-heading" class="section__title"><?= e($ost) ?></h2>
        <?php endif; ?>

        <?php $items = isset($off['items']) && is_array($off['items']) ? $off['items'] : []; ?>
        <?php if ($items !== []): ?>
        <div class="services-dossiers reveal-stagger">
            <?php foreach ($items as $ix => $it): ?>
            <?php if (!is_array($it)) {
                continue;
            } ?>
            <?php
                $title = trim((string) ($it['title'] ?? ''));
                $sum = isset($it['summary_html']) && is_string($it['summary_html']) ? $it['summary_html'] : '';
                $mods = ['services-dossier'];
                if (! empty($it['accent'])) {
                    $mods[] = 'services-dossier--accent';
                }
                if (! empty($it['muted'])) {
                    $mods[] = 'services-dossier--muted';
                }
                $idxPad = str_pad((string) ((int) $ix + 1), 2, '0', STR_PAD_LEFT);
                ?>
            <article class="<?= e(implode(' ', $mods)) ?>">
                <span class="services-dossier__index" aria-hidden="true"><?= e($idxPad) ?></span>
                <?php if ($title !== ''): ?>
                <h3 class="services-dossier__title"><?= e($title) ?></h3>
                <?php endif; ?>
                <?php if ($sum !== '') { ?>
                <div class="services-dossier__body prose"><?= $sum ?></div>
                <?php } ?>
                <?php
                $lnk = trim((string) ($it['href'] ?? ''));
                    if ($lnk !== '') {
                        ?>
                <p class="services-dossier__link"><a class="text-link" href="<?= e($lnk) ?>">Learn more</a></p>
                    <?php
                    }
                ?>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($on($partner)): ?>
<section class="app-band app-band--surface section services-modern__partner" aria-labelledby="services-partner-heading" data-reveal>
    <div class="shell shell--wide">
        <div class="services-partner-grid">
            <div class="services-partner-grid__story">
                <?php $pt = trim((string) ($partner['title'] ?? '')); ?>
                <?php if ($pt !== ''): ?>
                <h2 id="services-partner-heading" class="section__title"><?= e($pt) ?></h2>
                <?php endif; ?>
                <?php $pl = isset($partner['lead_html']) && is_string($partner['lead_html']) ? $partner['lead_html'] : ''; ?>
                <?php if ($pl !== '') { ?>
                <div class="services-modern-prose prose"><?= $pl ?></div>
                <?php } ?>
                <?php
                $pcta = trim((string) ($partner['cta_label'] ?? ''));
                $phrf = trim((string) ($partner['cta_href'] ?? ''));
                ?>
                <?php if ($pcta !== '' && $phrf !== ''): ?>
                <p style="margin-top: 1rem;">
                    <a class="btn btn--secondary" href="<?= e($phrf) ?>"><?= e($pcta) ?></a>
                </p>
                <?php endif; ?>
            </div>
            <?php $met = isset($partner['metrics']) && is_array($partner['metrics']) ? $partner['metrics'] : []; ?>
            <?php if ($met !== []): ?>
            <aside class="services-partner-grid__figures" aria-label="Signals">
                <div class="tile-metric-strip services-partner__strip" data-metric-strip role="list">
                    <?php foreach ($met as $m): ?>
                    <?php if (!is_array($m)) {
                        continue;
                    } ?>
                    <?php
                        $ml = trim((string) ($m['label'] ?? ''));
                        $md = trim((string) ($m['display'] ?? ''));
                        $mt = isset($m['target']) ? (int) $m['target'] : 0;
                        $mf = (string) ($m['suffix'] ?? '+');
                        $doAnim = $mt > 0;
                        ?>
                    <div class="tile-metric tile-metric--services" role="listitem">
                        <span class="tile-metric__value">
                            <?php if ($doAnim): ?>
                            <span class="tile-metric__num" data-metric-count data-target="<?= (int) $mt ?>" data-suffix="<?= e($mf) ?>"><?= e($md !== '' ? $md : (string) $mt . $mf) ?></span>
                            <?php else: ?>
                            <span class="tile-metric__num"><?= e($md !== '' ? $md : '—') ?></span>
                            <?php endif; ?>
                        </span>
                        <?php if ($ml !== ''): ?>
                        <span class="tile-metric__label"><?= e($ml) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </aside>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($on($faq)): ?>
<section class="app-band section services-modern__faq" aria-labelledby="services-faq-heading" data-reveal>
    <div class="shell shell--wide">
        <?php $fe = trim((string) ($faq['eyebrow'] ?? '')); ?>
        <?php if ($fe !== ''): ?>
        <p class="eyebrow"><?= e($fe) ?></p>
        <?php endif; ?>
        <?php $ft = trim((string) ($faq['title'] ?? '')); ?>
        <?php if ($ft !== ''): ?>
        <h2 id="services-faq-heading" class="section__title"><?= e($ft) ?></h2>
        <?php endif; ?>
        <?php $fl = isset($faq['lead_html']) && is_string($faq['lead_html']) ? $faq['lead_html'] : ''; ?>
        <?php if ($fl !== '') { ?>
        <div class="services-faq-intro prose"><?= $fl ?></div>
        <?php } ?>

        <?php $fitems = isset($faq['items']) && is_array($faq['items']) ? $faq['items'] : []; ?>
        <?php $openFirst = ! empty($faq['open_first']); ?>
        <?php if ($fitems !== []): ?>
        <div class="services-faq-list">
            <?php foreach ($fitems as $fi => $q): ?>
            <?php if (!is_array($q)) {
                continue;
            } ?>
            <?php
                $fq = trim((string) ($q['question'] ?? ''));
                $ans = isset($q['answer_html']) && is_string($q['answer_html']) ? $q['answer_html'] : '';
                ?>
            <?php if ($fq !== '' || $ans !== ''): ?>
            <details class="services-faq-item"<?= $openFirst && $fi === 0 ? ' open' : '' ?>>
                <summary class="services-faq-item__summary"><?= e($fq !== '' ? $fq : 'Question') ?></summary>
                <div class="services-faq-item__answer prose"><?= $ans ?></div>
            </details>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($on($nwa)): ?>
<?php
    $ntl = trim((string) ($nwa['title'] ?? ''));
    $btn = trim((string) ($nwa['button_label'] ?? 'Subscribe'));
    $ph = trim((string) ($nwa['placeholder'] ?? 'Your email'));
$nth = isset($nwa['text_html']) && is_string($nwa['text_html']) ? $nwa['text_html'] : '';
?>
<section class="app-band app-band--newsletter services-modern__newsletter" aria-labelledby="services-nw-heading" data-reveal>
    <div class="shell shell--wide newsletter-app">
        <div>
            <?php if ($ntl !== ''): ?>
            <h2 id="services-nw-heading" class="newsletter__title"><?= e($ntl) ?></h2>
            <?php endif; ?>
            <?php if ($nth !== '') { ?>
            <div class="newsletter__text prose"><?= $nth ?></div>
            <?php } ?>
        </div>
        <form class="newsletter__form newsletter-app__form" method="post" action="<?= e(app_url('newsletter')) ?>" novalidate>
            <?= csrf_field() ?>
            <label class="visually-hidden" for="services-news-email"><?= e($ph) ?></label>
            <input id="services-news-email" class="input" type="email" name="email" placeholder="<?= e($ph) ?>" autocomplete="email" required>
            <button class="btn btn--dark" type="submit"><?= e($btn) ?></button>
        </form>
    </div>
</section>
<?php endif; ?>
</div>
