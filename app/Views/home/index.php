<?php
declare(strict_types=1);
/** @var array<int, array<string, string>> $slides */
$slides = $slides ?? [];
/** @var array<string, mixed> $home_blocks */
$home_blocks = isset($home_blocks) && is_array($home_blocks) ? $home_blocks : \App\Services\HomePageBlocks::merged(null);
$home_intro_html = trim((string) ($home_intro_html ?? ''));

$hEnabled = static function (?array $section): bool {
    if ($section === null) {
        return false;
    }

    return ($section['enabled'] ?? true) !== false;
};

$confidence = is_array($home_blocks['confidence'] ?? null) ? $home_blocks['confidence'] : [];
$partnership = is_array($home_blocks['partnership'] ?? null) ? $home_blocks['partnership'] : [];
$clusters = is_array($home_blocks['clusters'] ?? null) ? $home_blocks['clusters'] : [];
$operating = is_array($home_blocks['operating_model'] ?? null) ? $home_blocks['operating_model'] : [];
$packages = is_array($home_blocks['packages'] ?? null) ? $home_blocks['packages'] : [];
$testimonials = is_array($home_blocks['testimonials'] ?? null) ? $home_blocks['testimonials'] : [];
$newsletterBk = is_array($home_blocks['newsletter'] ?? null) ? $home_blocks['newsletter'] : [];
?>
<div class="app-shell app-shell--home">
        <section
            class="hero hero--slider hero--console"
            data-hero-slider
            aria-roledescription="carousel"
            aria-label="Featured events and services"
        >
            <p id="hero-aria-live" class="visually-hidden" aria-live="polite" aria-atomic="true"></p>
            <div class="hero__viewport" data-hero-viewport>
                <?php foreach ($slides as $i => $slide): ?>
                <article
                    class="hero__slide<?= $i === 0 ? ' is-active' : '' ?><?= ($i % 2 === 0) ? ' hero__slide--align-left' : ' hero__slide--align-right' ?>"
                    data-hero-slide
                    data-hero-label="<?= e($slide['title']) ?>"
                    role="group"
                    aria-roledescription="slide"
                    aria-label="<?= (int) ($i + 1) ?> of <?= count($slides) ?>"
                    <?= $i === 0 ? '' : 'aria-hidden="true"' ?>
                >
                    <div class="hero__media">
                        <?php
                        $mobImg = trim((string) ($slide['image_mobile'] ?? ''));
                        ?>
                        <?php if ($mobImg !== ''): ?>
                        <picture>
                            <source media="(max-width: 767px)" srcset="<?= e($mobImg) ?>">
                            <img
                                class="hero__img"
                                src="<?= e($slide['image']) ?>"
                                alt="<?= e($slide['alt']) ?>"
                                width="1920"
                                height="1080"
                                loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
                                decoding="async"
                            >
                        </picture>
                        <?php else: ?>
                        <img
                            class="hero__img"
                            src="<?= e($slide['image']) ?>"
                            alt="<?= e($slide['alt']) ?>"
                            width="1920"
                            height="1080"
                            loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
                            decoding="async"
                        >
                        <?php endif; ?>
                        <div class="hero__scrim" aria-hidden="true"></div>
                    </div>
                    <div class="shell shell--wide hero__content">
                        <?php if (trim((string) ($slide['eyebrow'] ?? '')) !== ''): ?>
                        <p class="hero__eyebrow"><?= e($slide['eyebrow']) ?></p>
                        <?php endif; ?>
                        <h1 class="hero__title"><?= e($slide['title']) ?></h1>
                        <?php if (trim((string) ($slide['subtitle'] ?? '')) !== ''): ?>
                        <p class="hero__subtitle"><?= e($slide['subtitle']) ?></p>
                        <?php endif; ?>
                        <?php
                        $ctaLabel = trim((string) ($slide['cta_label'] ?? ''));
                        $ctaHref = trim((string) ($slide['cta_href'] ?? ''));
                        $secLabel = trim((string) ($slide['secondary_label'] ?? ''));
                        $secHref = trim((string) ($slide['secondary_href'] ?? ''));
                        ?>
                        <?php if ($ctaLabel !== '' && $ctaHref !== '' || $secLabel !== '' && $secHref !== ''): ?>
                        <div class="hero__actions">
                            <?php if ($ctaLabel !== '' && $ctaHref !== ''): ?>
                            <a class="btn btn--primary" href="<?= e($ctaHref) ?>"><?= e($ctaLabel) ?></a>
                            <?php endif; ?>
                            <?php if ($secLabel !== '' && $secHref !== ''): ?>
                            <a class="btn btn--ghost" href="<?= e($secHref) ?>"><?= e($secLabel) ?></a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <div class="hero__controls shell" aria-hidden="false">
                <button class="hero__arrow hero__arrow--prev" type="button" data-hero-prev aria-label="Previous slide">
                    <span aria-hidden="true">‹</span>
                </button>
                <button class="hero__arrow hero__arrow--next" type="button" data-hero-next aria-label="Next slide">
                    <span aria-hidden="true">›</span>
                </button>
            </div>

            <div class="hero__dots shell" role="tablist" aria-label="Slide selection" data-hero-dots>
                <?php foreach ($slides as $i => $_): ?>
                <button
                    class="hero__dot<?= $i === 0 ? ' is-active' : '' ?>"
                    type="button"
                    role="tab"
                    data-hero-dot="<?= (int) $i ?>"
                    aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                    aria-label="Go to slide <?= (int) ($i + 1) ?>"
                ></button>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="app-shell__below">
            <div class="app-shell__main">
        <?php if ($home_intro_html !== ''): ?>
        <section class="app-band app-band--surface section--tight" data-reveal>
            <div class="shell shell--wide prose cms-home-intro">
                <?= $home_intro_html ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($hEnabled($confidence)): ?>
        <?php
        $__cm = isset($confidence['metrics']) && is_array($confidence['metrics']) ? $confidence['metrics'] : [];
        $confEyebrow = trim((string) ($confidence['eyebrow'] ?? ''));
        $confTitle = trim((string) ($confidence['title'] ?? ''));
        $confLead = trim((string) ($confidence['lead'] ?? ''));
        $confCta = trim((string) ($confidence['cta_label'] ?? ''));
        $confHref = trim((string) ($confidence['cta_href'] ?? ''));
        ?>
        <section id="confidence" class="app-band app-band--surface section--tight home-blocks-confidence" aria-labelledby="home-confidence-heading" data-reveal>
            <div class="shell shell--wide">
                <div class="home-intro-grid home-confidence-panel">
                    <div class="home-intro-grid__copy">
                        <?php if ($confEyebrow !== ''): ?>
                        <p class="eyebrow"><?= e($confEyebrow) ?></p>
                        <?php endif; ?>
                        <?php if ($confTitle !== ''): ?>
                        <h2 id="home-confidence-heading" class="section__title"><?= e($confTitle) ?></h2>
                        <?php endif; ?>
                        <?php if ($confLead !== ''): ?>
                        <p class="section__lead"><?= e($confLead) ?></p>
                        <?php endif; ?>
                        <?php if ($confCta !== '' && $confHref !== ''): ?>
                        <a class="text-link" href="<?= e($confHref) ?>"><?= e($confCta) ?></a>
                        <?php endif; ?>
                    </div>
                    <?php if ($__cm !== []): ?>
                    <div class="tile-metric-strip home-blocks-confidence__strip" data-metric-strip role="list">
                        <?php foreach ($__cm as $m): ?>
                        <?php
                        if (!is_array($m)) {
                            continue;
                        }
                        $label = trim((string) ($m['label'] ?? ''));
                        $display = trim((string) ($m['display'] ?? ''));
                        $target = isset($m['target']) ? (int) $m['target'] : 0;
                        $suffix = (string) ($m['suffix'] ?? '+');
                        if ($label === '' && $display === '' && $target <= 0) {
                            continue;
                        }
                        $animate = $target > 0;
                        ?>
                        <div class="tile-metric" role="listitem">
                            <span class="tile-metric__value">
                                <?php if ($animate): ?>
                                <span class="tile-metric__num" data-metric-count data-target="<?= (int) $target ?>" data-suffix="<?= e($suffix) ?>"><?= e($display) ?></span>
                                <?php else: ?>
                                <span class="tile-metric__num"><?= e($display !== '' ? $display : '—') ?></span>
                                <?php endif; ?>
                            </span>
                            <?php if ($label !== ''): ?>
                            <span class="tile-metric__label"><?= e($label) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($hEnabled($partnership)): ?>
        <?php
        $pq = trim((string) ($partnership['pull_quote'] ?? ''));
        $pkt = trim((string) ($partnership['kicker'] ?? ''));
        $pt = trim((string) ($partnership['title'] ?? ''));
        $pl = trim((string) ($partnership['lead'] ?? ''));
        $pCta = trim((string) ($partnership['cta_label'] ?? ''));
        $pHref = trim((string) ($partnership['cta_href'] ?? ''));
        ?>
        <section class="app-band section home-blocks-partnership" aria-labelledby="home-partnership-heading" data-reveal>
            <div class="shell shell--wide home-blocks-partnership__layout">
                <div class="home-blocks-partnership__main">
                    <?php if ($pkt !== ''): ?>
                    <p class="eyebrow"><?= e($pkt) ?></p>
                    <?php endif; ?>
                    <?php if ($pt !== ''): ?>
                    <h2 id="home-partnership-heading" class="section__title"><?= e($pt) ?></h2>
                    <?php endif; ?>
                    <?php if ($pl !== ''): ?>
                    <p class="section__lead"><?= e($pl) ?></p>
                    <?php endif; ?>
                    <?php if ($pCta !== '' && $pHref !== ''): ?>
                    <p class="section__cta-row" style="margin-top: 1.25rem;">
                        <a class="btn btn--secondary" href="<?= e($pHref) ?>"><?= e($pCta) ?></a>
                    </p>
                    <?php endif; ?>
                </div>
                <?php if ($pq !== ''): ?>
                <blockquote class="home-blocks-partnership__quote">
                    <p><?= e($pq) ?></p>
                </blockquote>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($hEnabled($clusters)): ?>
        <?php
        $cEyebrow = trim((string) ($clusters['eyebrow'] ?? ''));
        $cTitle = trim((string) ($clusters['title'] ?? ''));
        $cLink = trim((string) ($clusters['link_all_label'] ?? ''));
        $cHref = trim((string) ($clusters['link_all_href'] ?? ''));
        $cItems = isset($clusters['items']) && is_array($clusters['items']) ? $clusters['items'] : [];
        ?>
        <section class="app-band app-band--surface section home-blocks-clusters" aria-labelledby="home-clusters-heading" data-reveal>
            <div class="shell shell--wide">
                <div class="section__head">
                    <?php if ($cEyebrow !== ''): ?>
                    <p class="eyebrow"><?= e($cEyebrow) ?></p>
                    <?php endif; ?>
                    <?php if ($cTitle !== ''): ?>
                    <h2 id="home-clusters-heading" class="section__title"><?= e($cTitle) ?></h2>
                    <?php endif; ?>
                </div>
                <?php if ($cItems !== []): ?>
                <div class="home-cluster-bento reveal-stagger">
                    <?php foreach ($cItems as $ci): ?>
                    <?php
                    if (!is_array($ci)) {
                        continue;
                    }
                    $ct = trim((string) ($ci['title'] ?? ''));
                    $ctx = trim((string) ($ci['text'] ?? ''));
                    $mods = ['home-cluster-card'];
                    if (!empty($ci['accent'])) {
                        $mods[] = 'home-cluster-card--accent';
                    }
                    if (!empty($ci['muted'])) {
                        $mods[] = 'home-cluster-card--muted';
                    }
                    ?>
                    <article class="<?= e(implode(' ', $mods)) ?>">
                        <?php if ($ct !== ''): ?>
                        <h3 class="home-cluster-card__title"><?= e($ct) ?></h3>
                        <?php endif; ?>
                        <?php if ($ctx !== ''): ?>
                        <p class="home-cluster-card__text"><?= e($ctx) ?></p>
                        <?php endif; ?>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php if ($cLink !== '' && $cHref !== ''): ?>
                <p class="section__cta-row">
                    <a class="btn btn--secondary" href="<?= e($cHref) ?>"><?= e($cLink) ?></a>
                </p>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($hEnabled($operating)): ?>
        <?php
        $omTitle = trim((string) ($operating['title'] ?? ''));
        $omSub = trim((string) ($operating['subtitle'] ?? ''));
        $steps = isset($operating['steps']) && is_array($operating['steps']) ? $operating['steps'] : [];
        $hl = is_array($operating['highlight'] ?? null) ? $operating['highlight'] : [];
        $hlTitle = trim((string) ($hl['title'] ?? ''));
        $hlBody = trim((string) ($hl['body'] ?? ''));
        ?>
        <section class="app-band section home-blocks-ops" aria-labelledby="home-ops-heading" data-reveal>
            <div class="shell shell--wide">
                <div class="section__head">
                    <?php if ($omTitle !== ''): ?>
                    <h2 id="home-ops-heading" class="section__title"><?= e($omTitle) ?></h2>
                    <?php endif; ?>
                    <?php if ($omSub !== ''): ?>
                    <p class="section__lead" style="margin-top: 0.5rem;"><?= e($omSub) ?></p>
                    <?php endif; ?>
                </div>
                <div class="home-blocks-ops__grid">
                    <?php if ($steps !== []): ?>
                    <ol class="home-blocks-ops__steps">
                        <?php foreach ($steps as $si => $st): ?>
                        <?php
                        if (!is_array($st)) {
                            continue;
                        }
                        $stitle = trim((string) ($st['title'] ?? ''));
                        $stext = trim((string) ($st['text'] ?? ''));
                        ?>
                        <li class="home-blocks-ops__step">
                            <span class="home-blocks-ops__step-index" aria-hidden="true"><?= str_pad((string) ((int) $si + 1), 2, '0', STR_PAD_LEFT) ?></span>
                            <div>
                                <?php if ($stitle !== ''): ?>
                                <h3 class="home-blocks-ops__step-title"><?= e($stitle) ?></h3>
                                <?php endif; ?>
                                <?php if ($stext !== ''): ?>
                                <p class="home-blocks-ops__step-text"><?= e($stext) ?></p>
                                <?php endif; ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                    <?php endif; ?>
                    <?php if ($hlTitle !== '' || $hlBody !== ''): ?>
                    <aside class="home-blocks-ops__highlight">
                        <?php if ($hlTitle !== ''): ?>
                        <h3 class="home-blocks-ops__highlight-title"><?= e($hlTitle) ?></h3>
                        <?php endif; ?>
                        <?php if ($hlBody !== ''): ?>
                        <p class="home-blocks-ops__highlight-body"><?= e($hlBody) ?></p>
                        <?php endif; ?>
                    </aside>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($hEnabled($packages)): ?>
        <?php
        $pkgEyebrow = trim((string) ($packages['eyebrow'] ?? ''));
        $pkgTitle = trim((string) ($packages['title'] ?? ''));
        $pkgSub = trim((string) ($packages['subtitle'] ?? ''));
        $pkgItems = isset($packages['items']) && is_array($packages['items']) ? $packages['items'] : [];
        ?>
        <section id="investment" class="app-band app-band--surface section home-blocks-packages" aria-labelledby="home-packages-heading" data-reveal>
            <div class="shell shell--wide">
                <div class="section__head">
                    <?php if ($pkgEyebrow !== ''): ?>
                    <p class="eyebrow"><?= e($pkgEyebrow) ?></p>
                    <?php endif; ?>
                    <?php if ($pkgTitle !== ''): ?>
                    <h2 id="home-packages-heading" class="section__title"><?= e($pkgTitle) ?></h2>
                    <?php endif; ?>
                    <?php if ($pkgSub !== ''): ?>
                    <p class="section__lead" style="margin-top: 0.5rem; max-width: 56ch;"><?= e($pkgSub) ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($pkgItems !== []): ?>
                <div class="home-packages-grid reveal-stagger">
                    <?php foreach ($pkgItems as $pkg): ?>
                    <?php
                    if (!is_array($pkg)) {
                        continue;
                    }
                    $pname = trim((string) ($pkg['name'] ?? ''));
                    $pprice = trim((string) ($pkg['price_display'] ?? ''));
                    $pfeat = isset($pkg['features']) && is_array($pkg['features']) ? $pkg['features'] : [];
                    $pCta = trim((string) ($pkg['cta_label'] ?? ''));
                    $pHref = trim((string) ($pkg['cta_href'] ?? ''));
                    $featured = !empty($pkg['featured']);
                    ?>
                    <article class="home-package-card<?= $featured ? ' home-package-card--featured' : '' ?>">
                        <?php if ($featured): ?>
                        <p class="home-package-card__ribbon">Featured</p>
                        <?php endif; ?>
                        <?php if ($pname !== ''): ?>
                        <h3 class="home-package-card__name"><?= e($pname) ?></h3>
                        <?php endif; ?>
                        <?php if ($pprice !== ''): ?>
                        <p class="home-package-card__price"><?= e($pprice) ?></p>
                        <?php endif; ?>
                        <?php if ($pfeat !== []): ?>
                        <ul class="home-package-card__features">
                            <?php foreach ($pfeat as $line): ?>
                            <?php if (is_string($line) && trim($line) !== ''): ?>
                            <li><?= e(trim($line)) ?></li>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <?php if ($pCta !== '' && $pHref !== ''): ?>
                        <div class="home-package-card__foot">
                            <a class="<?= $featured ? 'btn btn--primary' : 'btn btn--secondary' ?>" href="<?= e($pHref) ?>"><?= e($pCta) ?></a>
                        </div>
                        <?php endif; ?>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($hEnabled($testimonials)): ?>
        <?php
        $tstTitle = trim((string) ($testimonials['title'] ?? ''));
        $tstSub = trim((string) ($testimonials['subtitle'] ?? ''));
        $quotes = isset($testimonials['quotes']) && is_array($testimonials['quotes']) ? $testimonials['quotes'] : [];
        ?>
        <section class="app-band section home-blocks-testimonials" aria-labelledby="home-testimonials-heading" data-reveal>
            <div class="shell shell--wide">
                <div class="section__head">
                    <?php if ($tstTitle !== ''): ?>
                    <h2 id="home-testimonials-heading" class="section__title"><?= e($tstTitle) ?></h2>
                    <?php endif; ?>
                    <?php if ($tstSub !== ''): ?>
                    <p class="section__lead" style="margin-top: 0.5rem;"><?= e($tstSub) ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($quotes !== []): ?>
                <div class="home-testimonials-grid reveal-stagger">
                    <?php foreach ($quotes as $q): ?>
                    <?php
                    if (!is_array($q)) {
                        continue;
                    }
                    $qq = trim((string) ($q['quote'] ?? ''));
                    $qn = trim((string) ($q['name'] ?? ''));
                    $qr = trim((string) ($q['role'] ?? ''));
                    ?>
                    <figure class="home-testimonial-card">
                        <?php if ($qq !== ''): ?>
                        <blockquote class="home-testimonial-card__quote">
                            <p><?= e($qq) ?></p>
                        </blockquote>
                        <?php endif; ?>
                        <figcaption class="home-testimonial-card__cite">
                            <?php if ($qn !== ''): ?>
                            <cite class="home-testimonial-card__name"><?= e($qn) ?></cite>
                            <?php endif; ?>
                            <?php if ($qr !== ''): ?>
                            <span class="home-testimonial-card__role"><?= e($qr) ?></span>
                            <?php endif; ?>
                        </figcaption>
                    </figure>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($hEnabled($newsletterBk)): ?>
        <?php
        $nwTitle = trim((string) ($newsletterBk['title'] ?? ''));
        $nwText = trim((string) ($newsletterBk['text'] ?? ''));
        $nwBtn = trim((string) ($newsletterBk['button_label'] ?? ''));
        $nwPh = trim((string) ($newsletterBk['placeholder'] ?? ''));
        if ($nwBtn === '') {
            $nwBtn = 'Subscribe';
        }
        if ($nwPh === '') {
            $nwPh = 'Your email';
        }
        ?>
        <section class="app-band app-band--newsletter home-blocks-newsletter" aria-labelledby="home-newsletter-heading" data-reveal>
            <div class="shell shell--wide newsletter-app">
                <div>
                    <?php if ($nwTitle !== ''): ?>
                    <h2 id="home-newsletter-heading" class="newsletter__title"><?= e($nwTitle) ?></h2>
                    <?php endif; ?>
                    <?php if ($nwText !== ''): ?>
                    <p class="newsletter__text"><?= e($nwText) ?></p>
                    <?php endif; ?>
                </div>
                <form class="newsletter__form newsletter-app__form" method="post" action="<?= e(app_url('newsletter')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <label class="visually-hidden" for="newsletter-email-home"><?= e($nwPh) ?></label>
                    <input id="newsletter-email-home" class="input" type="email" name="email" placeholder="<?= e($nwPh) ?>" autocomplete="email" required>
                    <button class="btn btn--dark" type="submit"><?= e($nwBtn) ?></button>
                </form>
            </div>
        </section>
        <?php endif; ?>
            </div>
        </div>
</div>
