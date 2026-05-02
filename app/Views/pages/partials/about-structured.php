<?php
declare(strict_types=1);
/** @var array<string, mixed> $about_blocks */
$ab = isset($about_blocks) && is_array($about_blocks) ? $about_blocks : [];

$h = static function (?array $sec): bool {
    if ($sec === null) {
        return false;
    }

    return ($sec['enabled'] ?? true) !== false;
};

$hero = is_array($ab['hero'] ?? null) ? $ab['hero'] : [];
$story = is_array($ab['story'] ?? null) ? $ab['story'] : [];
$approach = is_array($ab['approach'] ?? null) ? $ab['approach'] : [];
$values = is_array($ab['values'] ?? null) ? $ab['values'] : [];
$team = is_array($ab['team'] ?? null) ? $ab['team'] : [];
$nw = is_array($ab['newsletter_cta'] ?? null) ? $ab['newsletter_cta'] : [];
?>

<div class="about-modern">
<?php if ($h($story)): ?>
<section class="app-band app-band--surface section about-modern__story" aria-labelledby="about-story-heading" data-reveal>
    <div class="shell shell--wide about-modern-story">
        <?php $se = trim((string) ($story['eyebrow'] ?? '')); ?>
        <?php if ($se !== ''): ?>
        <p class="about-modern-story__eyebrow eyebrow"><?= e($se) ?></p>
        <?php endif; ?>

        <div class="about-modern-story__grid">
            <div class="about-modern-story__chapters prose about-modern-prose">
                <?php $chapters = isset($story['chapters']) && is_array($story['chapters']) ? $story['chapters'] : []; ?>
                <?php foreach ($chapters as $i => $ch): ?>
                    <?php if (!is_array($ch)) {
                        continue;
                    } ?>
                <?php $head = trim((string) ($ch['heading'] ?? '')); ?>
                <?php $bod = isset($ch['body_html']) && is_string($ch['body_html']) ? $ch['body_html'] : ''; ?>
                <?php if ($head !== '' || $bod !== ''): ?>
                <article class="about-modern-chapter<?= $i > 0 ? ' about-modern-chapter--spaced' : '' ?>">
                    <?php if ($head !== ''): ?>
                    <h2 class="section__title"<?= $i === 0 ? ' id="about-story-heading"' : '' ?>><?= e($head) ?></h2>
                    <?php endif; ?>
                    <?php if ($bod !== '') { ?>
                    <div class="about-modern-chapter__body"><?= $bod ?></div>
                    <?php } ?>
                </article>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <aside class="about-modern-story__aside">
                <?php $pq = trim((string) ($story['pull_quote'] ?? '')); ?>
                <?php if ($pq !== ''): ?>
                <blockquote class="about-modern-quote">
                    <p><?= e($pq) ?></p>
                </blockquote>
                <?php endif; ?>

                <?php $metrics = isset($story['metrics']) && is_array($story['metrics']) ? $story['metrics'] : []; ?>
                <?php if ($metrics !== []): ?>
                <div class="about-modern-metrics tile-metric-strip" data-metric-strip role="list" aria-label="Key figures">
                    <?php foreach ($metrics as $m): ?>
                    <?php if (!is_array($m)) {
                        continue;
                    } ?>
                    <?php
                        $lab = trim((string) ($m['label'] ?? ''));
                        $disp = trim((string) ($m['display'] ?? ''));
                        $tgt = isset($m['target']) ? (int) $m['target'] : 0;
                        $suf = (string) ($m['suffix'] ?? '+');
                        $anim = $tgt > 0;
                    ?>
                    <div class="tile-metric tile-metric--about" role="listitem">
                        <span class="tile-metric__value">
                            <?php if ($anim): ?>
                            <span class="tile-metric__num" data-metric-count data-target="<?= (int) $tgt ?>" data-suffix="<?= e($suf) ?>"><?= e($disp !== '' ? $disp : (string) $tgt . $suf) ?></span>
                            <?php else: ?>
                            <span class="tile-metric__num"><?= e($disp !== '' ? $disp : '—') ?></span>
                            <?php endif; ?>
                        </span>
                        <?php if ($lab !== ''): ?>
                        <span class="tile-metric__label"><?= e($lab) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($h($approach)): ?>
<section class="app-band section about-modern__approach" aria-labelledby="about-approach-heading" data-reveal>
    <div class="shell shell--wide">
        <?php $ae = trim((string) ($approach['eyebrow'] ?? '')); ?>
        <?php if ($ae !== ''): ?>
        <p class="eyebrow"><?= e($ae) ?></p>
        <?php endif; ?>
        <?php $at = trim((string) ($approach['title'] ?? '')); ?>
        <?php if ($at !== ''): ?>
        <h2 id="about-approach-heading" class="section__title"><?= e($at) ?></h2>
        <?php endif; ?>
        <div class="about-modern-approach">
            <div class="about-modern-approach__copy prose about-modern-prose">
                <?php $lead = isset($approach['lead_html']) && is_string($approach['lead_html']) ? $approach['lead_html'] : ''; ?>
                <?php if ($lead !== ''): ?>
                <?= $lead ?>
                <?php endif; ?>
            </div>
            <?php $imgs = isset($approach['images']) && is_array($approach['images']) ? $approach['images'] : []; ?>
            <?php
            $imgsVis = [];
            foreach ($imgs as $im) {
                if (!is_array($im)) {
                    continue;
                }
                $su = trim((string) ($im['src'] ?? ''));
                if ($su !== '') {
                    $imgsVis[] = $im;
                }
            }
            ?>
            <?php if ($imgsVis !== []): ?>
            <div class="about-modern-stack" aria-hidden="false">
                <?php foreach ($imgsVis as $ii => $im): ?>
                    <?php if (!is_array($im)) {
                        continue;
                    } ?>
                <?php
                    $src = trim((string) ($im['src'] ?? ''));
                    $alt = trim((string) ($im['alt'] ?? ''));
                    $modClass = ['about-modern-stack__frame'];
                    $modClass[] = ($ii % 2 === 0) ? 'about-modern-stack__frame--rise' : 'about-modern-stack__frame--quiet';
                    ?>
                <figure class="<?= e(implode(' ', $modClass)) ?>">
                    <img class="about-modern-stack__img" src="<?= e($src) ?>" alt="<?= e($alt) ?>" loading="lazy" decoding="async" width="800" height="520">
                </figure>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($h($values)): ?>
<section class="app-band app-band--surface section about-modern__values" aria-labelledby="about-values-heading" data-reveal>
    <div class="shell shell--wide">
        <?php $ve = trim((string) ($values['eyebrow'] ?? '')); ?>
        <?php if ($ve !== ''): ?>
        <p class="eyebrow"><?= e($ve) ?></p>
        <?php endif; ?>
        <?php $vt = trim((string) ($values['title'] ?? '')); ?>
        <?php if ($vt !== ''): ?>
        <h2 id="about-values-heading" class="section__title"><?= e($vt) ?></h2>
        <?php endif; ?>

        <?php $items = isset($values['items']) && is_array($values['items']) ? $values['items'] : []; ?>
        <?php if ($items !== []): ?>
        <div class="about-values-mosaic reveal-stagger">
            <?php foreach ($items as $vi => $it): ?>
            <?php if (!is_array($it)) {
                continue;
            } ?>
            <?php
                $tit = trim((string) ($it['title'] ?? ''));
                $sub = trim((string) ($it['subtitle'] ?? ''));
                $sum = isset($it['summary_html']) && is_string($it['summary_html']) ? $it['summary_html'] : '';
                $tone = ((int) $vi % 3) + 1;
                ?>
            <article class="about-value-cap about-value-cap--split about-value-cap--tone-<?= (int) $tone ?>">
                <?php if ($tit !== '' || $sub !== ''): ?>
                <header class="about-value-cap__banner">
                    <?php if ($tit !== ''): ?>
                    <h3 class="about-value-cap__title"><?= e($tit) ?></h3>
                    <?php endif; ?>
                    <?php if ($sub !== ''): ?>
                    <p class="about-value-cap__subtitle"><?= e($sub) ?></p>
                    <?php endif; ?>
                </header>
                <?php endif; ?>
                <?php if ($sum !== '') { ?>
                <div class="about-value-cap__body prose"><?= $sum ?></div>
                <?php } ?>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($h($team)): ?>
<section class="app-band section about-modern__team" aria-labelledby="about-team-heading" data-reveal>
    <div class="shell shell--wide">
        <?php $te = trim((string) ($team['eyebrow'] ?? '')); ?>
        <?php if ($te !== ''): ?>
        <p class="eyebrow"><?= e($te) ?></p>
        <?php endif; ?>
        <?php $tt = trim((string) ($team['title'] ?? '')); ?>
        <?php if ($tt !== ''): ?>
        <h2 id="about-team-heading" class="section__title"><?= e($tt) ?></h2>
        <?php endif; ?>
        <?php $tintro = isset($team['intro_html']) && is_string($team['intro_html']) ? $team['intro_html'] : ''; ?>
        <?php if ($tintro !== '') { ?>
        <div class="about-modern-team-intro prose"><?= $tintro ?></div>
        <?php } ?>

        <?php $members = isset($team['members']) && is_array($team['members']) ? $team['members'] : []; ?>
        <?php if ($members !== []): ?>
        <div class="about-team-cards reveal-stagger">
            <?php foreach ($members as $mem): ?>
            <?php if (!is_array($mem)) {
                continue;
            } ?>
            <?php
                $photo = trim((string) ($mem['photo'] ?? ''));
                $nm = trim((string) ($mem['name'] ?? ''));
                $role = trim((string) ($mem['role'] ?? ''));
                $bio = isset($mem['bio_html']) && is_string($mem['bio_html']) ? trim($mem['bio_html']) : '';
                ?>
            <article class="about-team-card">
                <div class="about-team-card__photo"<?= $photo === '' ? ' data-placeholder' : '' ?>>
                    <?php if ($photo !== ''): ?>
                    <img src="<?= e($photo) ?>" alt="<?= e($nm !== '' ? $nm : 'Team member') ?>" width="320" height="400" loading="lazy" decoding="async">
                    <?php endif; ?>
                </div>
                <div class="about-team-card__body">
                    <?php if ($nm !== ''): ?>
                    <h3 class="about-team-card__name"><?= e($nm) ?></h3>
                    <?php endif; ?>
                    <?php if ($role !== ''): ?>
                    <p class="about-team-card__role"><?= e($role) ?></p>
                    <?php endif; ?>
                    <?php if ($bio !== '') { ?>
                    <div class="about-team-card__bio prose"><?= $bio ?></div>
                    <?php } ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

</div>

<?php if ($h($nw)): ?>
<?php
    $ntl = trim((string) ($nw['title'] ?? ''));
    $btn = trim((string) ($nw['button_label'] ?? 'Subscribe'));
    $ph = trim((string) ($nw['placeholder'] ?? 'Your email'));
$nhtml = isset($nw['text_html']) && is_string($nw['text_html']) ? $nw['text_html'] : '';
?>
<section class="app-band app-band--newsletter about-modern__newsletter services-modern__band--fluid" aria-labelledby="about-nw-heading" data-reveal>
    <div class="shell shell--fluid newsletter-app">
        <div>
            <?php if ($ntl !== ''): ?>
            <h2 id="about-nw-heading" class="newsletter__title"><?= e($ntl) ?></h2>
            <?php endif; ?>
            <?php if ($nhtml !== '') { ?>
            <div class="newsletter__text prose"><?= $nhtml ?></div>
            <?php } ?>
        </div>
        <?php include dirname(__DIR__, 2) . '/partials/newsletter-band-flash.php'; ?>
        <form class="newsletter__form newsletter-app__form" method="post" action="<?= e(app_url('newsletter')) ?>" novalidate data-newsletter-form>
            <?= csrf_field() ?>
            <input type="hidden" name="_newsletter_return" value="about">
            <label class="visually-hidden" for="about-newsletter-email"><?= e($ph) ?></label>
            <input id="about-newsletter-email" class="input" type="email" name="email" placeholder="<?= e($ph) ?>" autocomplete="email" required aria-describedby="about-newsletter-error">
            <p id="about-newsletter-error" class="newsletter-app__feedback newsletter-app__feedback--error" data-newsletter-error hidden role="alert"></p>
            <button class="btn btn--dark" type="submit"><?= e($btn) ?></button>
        </form>
    </div>
</section>
<?php endif; ?>
