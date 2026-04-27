<?php
declare(strict_types=1);
/** @var array<int, array<string, string>> $slides */
$slides = $slides ?? [];
?>
<section
    class="hero hero--slider"
    data-hero-slider
    aria-roledescription="carousel"
    aria-label="Featured events and services"
>
    <div class="hero__viewport" data-hero-viewport>
        <?php foreach ($slides as $i => $slide): ?>
        <article
            class="hero__slide<?= $i === 0 ? ' is-active' : '' ?>"
            data-hero-slide
            role="group"
            aria-roledescription="slide"
            aria-label="<?= (int) ($i + 1) ?> of <?= count($slides) ?>"
            <?= $i === 0 ? '' : 'aria-hidden="true"' ?>
        >
            <div class="hero__media">
                <img
                    class="hero__img"
                    src="<?= e($slide['image']) ?>"
                    alt="<?= e($slide['alt']) ?>"
                    width="1920"
                    height="1080"
                    loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
                    decoding="async"
                >
                <div class="hero__scrim" aria-hidden="true"></div>
            </div>
            <div class="shell shell--wide hero__content">
                <p class="hero__eyebrow"><?= e($slide['eyebrow']) ?></p>
                <h1 class="hero__title"><?= e($slide['title']) ?></h1>
                <p class="hero__subtitle"><?= e($slide['subtitle']) ?></p>
                <div class="hero__actions">
                    <a class="btn btn--primary" href="<?= e($slide['cta_href']) ?>"><?= e($slide['cta_label']) ?></a>
                    <a class="btn btn--ghost" href="<?= e($slide['secondary_href']) ?>"><?= e($slide['secondary_label']) ?></a>
                </div>
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

<section class="section section--tight section--surface" aria-labelledby="home-intro-heading">
    <div class="shell section__split">
        <div>
            <p class="eyebrow">Saint John &amp; region</p>
            <h2 id="home-intro-heading" class="section__title">Events are loud—planning shouldn’t be</h2>
            <p class="section__lead">We sweat the brief, the budget, and the backup plan so you’re not doing it the night before. Honest timelines, clear costs, and a crew that shows up like they mean it.</p>
            <a class="text-link" href="<?= e(app_url('about')) ?>">How we work</a>
        </div>
        <div class="stat-row" role="list">
            <div class="stat" role="listitem">
                <span class="stat__value">300+</span>
                <span class="stat__label">Happy clients</span>
            </div>
            <div class="stat" role="listitem">
                <span class="stat__value">150+</span>
                <span class="stat__label">Events delivered</span>
            </div>
            <div class="stat" role="listitem">
                <span class="stat__value">360°</span>
                <span class="stat__label">Photo booth &amp; more</span>
            </div>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="home-services-heading">
    <div class="shell">
        <div class="section__head">
            <p class="eyebrow">Where we help</p>
            <h2 id="home-services-heading" class="section__title">A few things we get asked for a lot</h2>
        </div>
        <div class="card-grid card-grid--home">
            <article class="card">
                <h3 class="card__title">Corporate &amp; brand</h3>
                <p class="card__text">Launches, staff nights, and client events—tight run-of-show, AV that works, and signage that actually matches the deck.</p>
            </article>
            <article class="card">
                <h3 class="card__title">Weddings &amp; social</h3>
                <p class="card__text">Mood, flow, and those small touches guests remember. We coordinate vendors so you’re not passing notes on the dance floor.</p>
            </article>
            <article class="card">
                <h3 class="card__title">Kids parties</h3>
                <p class="card__text">Packages, play, and add-ons for real families (not just Pinterest). Less chaos, more “they actually enjoyed it.”</p>
            </article>
            <article class="card">
                <h3 class="card__title">Rentals</h3>
                <p class="card__text">Chairs, backdrops, and finishing pieces—see what’s in stock and check out when you’re ready.</p>
            </article>
        </div>
        <p class="section__cta-row">
            <a class="btn btn--secondary" href="<?= e(app_url('services')) ?>">View all services</a>
        </p>
    </div>
</section>

<section class="section section--newsletter" aria-labelledby="newsletter-heading">
    <div class="shell newsletter">
        <div>
            <h2 id="newsletter-heading" class="newsletter__title">Short notes, zero fluff</h2>
            <p class="newsletter__text">A few times a year: one useful idea, one photo worth stealing, and where we’re booking next. Unsubscribe any time.</p>
        </div>
        <form class="newsletter__form" method="post" action="<?= e(app_url('newsletter')) ?>" novalidate>
            <?= csrf_field() ?>
            <label class="visually-hidden" for="newsletter-email">Email</label>
            <input id="newsletter-email" class="input" type="email" name="email" placeholder="Your email" autocomplete="email" required>
            <button class="btn btn--dark" type="submit">Subscribe</button>
        </form>
    </div>
</section>
