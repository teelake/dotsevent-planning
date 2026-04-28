<?php
declare(strict_types=1);
/** @var array<int, array<string, string>> $slides */
$slides = $slides ?? [];
$home_intro_html = trim((string) ($home_intro_html ?? ''));
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
        <?php else: ?>
        <section class="app-band app-band--surface section--tight" aria-labelledby="home-intro-heading" data-reveal>
            <div class="shell shell--wide">
                <div class="home-intro-grid">
                    <div class="home-intro-grid__copy">
                        <p class="eyebrow">Saint John &amp; region</p>
                        <h2 id="home-intro-heading" class="section__title">Events are loud—planning shouldn’t be</h2>
                        <p class="section__lead">We sweat the brief, the budget, and the backup plan so you’re not doing it the night before. Honest timelines, clear costs, and a crew that shows up like they mean it.</p>
                        <a class="text-link" href="<?= e(app_url('about')) ?>">How we work</a>
                    </div>
                    <div class="tile-metric-strip" role="list">
                        <div class="tile-metric" role="listitem">
                            <span class="tile-metric__value">300+</span>
                            <span class="tile-metric__label">Happy clients</span>
                        </div>
                        <div class="tile-metric" role="listitem">
                            <span class="tile-metric__value">150+</span>
                            <span class="tile-metric__label">Events delivered</span>
                        </div>
                        <div class="tile-metric" role="listitem">
                            <span class="tile-metric__value">360°</span>
                            <span class="tile-metric__label">Photo booth &amp; more</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section class="app-band section" aria-labelledby="home-services-heading">
            <div class="shell shell--wide">
                <div class="section__head" data-reveal>
                    <p class="eyebrow">Where we help</p>
                    <h2 id="home-services-heading" class="section__title">A few things we get asked for a lot</h2>
                </div>
                <div class="spec-grid reveal-stagger" data-reveal>
                    <article class="spec-tile spec-tile--dark">
                        <span class="spec-tile__glyph" aria-hidden="true">◆</span>
                        <span class="spec-tile__index" aria-hidden="true">01</span>
                        <h3 class="spec-tile__title">Corporate &amp; brand</h3>
                        <p class="spec-tile__text">Launches, staff nights, and client events—tight run-of-show, AV that works, and signage that actually matches the deck.</p>
                    </article>
                    <article class="spec-tile">
                        <span class="spec-tile__index" aria-hidden="true">02</span>
                        <h3 class="spec-tile__title">Weddings &amp; social</h3>
                        <p class="spec-tile__text">Mood, flow, and those small touches guests remember. We coordinate vendors so you’re not passing notes on the dance floor.</p>
                    </article>
                    <article class="spec-tile">
                        <span class="spec-tile__index" aria-hidden="true">03</span>
                        <h3 class="spec-tile__title">Kids parties</h3>
                        <p class="spec-tile__text">Packages, play, and add-ons for real families (not just Pinterest). Less chaos, more “they actually enjoyed it.”</p>
                    </article>
                    <article class="spec-tile spec-tile--accent">
                        <span class="spec-tile__index" aria-hidden="true">04</span>
                        <h3 class="spec-tile__title">Rentals</h3>
                        <p class="spec-tile__text">Chairs, backdrops, and finishing pieces—see what’s in stock and check out when you’re ready.</p>
                    </article>
                </div>
                <p class="section__cta-row">
                    <a class="btn btn--secondary" href="<?= e(app_url('services')) ?>">View all services</a>
                </p>
            </div>
        </section>

        <section class="app-band app-band--newsletter" aria-labelledby="newsletter-heading" data-reveal>
            <div class="shell shell--wide newsletter-app">
                <div>
                    <h2 id="newsletter-heading" class="newsletter__title">Short notes, zero fluff</h2>
                    <p class="newsletter__text">A few times a year: one useful idea, one photo worth stealing, and where we’re booking next. Unsubscribe any time.</p>
                </div>
                <form class="newsletter__form newsletter-app__form" method="post" action="<?= e(app_url('newsletter')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <label class="visually-hidden" for="newsletter-email">Email</label>
                    <input id="newsletter-email" class="input" type="email" name="email" placeholder="Your email" autocomplete="email" required>
                    <button class="btn btn--dark" type="submit">Subscribe</button>
                </form>
            </div>
        </section>
            </div>
        </div>
</div>
