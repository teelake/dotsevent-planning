<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$page_title = (string) ($cms['doc_title'] ?? 'Services');
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
        <?php else: ?>
        <div class="shell shell--wide page-pad" data-reveal>
            <p class="section__lead services__lead">Weddings, corporate, social events, kids parties, 360° photo booth, and expert planning—delivered with a single, calm process.</p>
            <div class="spec-grid spec-grid--services">
                <article class="spec-tile">
                    <span class="spec-tile__index" aria-hidden="true">01</span>
                    <h2 class="spec-tile__title">Weddings</h2>
                    <p class="spec-tile__text">Full-service coordination and design—flow, family dynamics, and a day that still feels like yours.</p>
                </article>
                <article class="spec-tile spec-tile--dark">
                    <span class="spec-tile__glyph" aria-hidden="true">◆</span>
                    <span class="spec-tile__index" aria-hidden="true">02</span>
                    <h2 class="spec-tile__title">Corporate</h2>
                    <p class="spec-tile__text">Launches, galas, and brand experiences with run-of-show and signage that match the real brief.</p>
                </article>
                <article class="spec-tile">
                    <span class="spec-tile__index" aria-hidden="true">03</span>
                    <h2 class="spec-tile__title">Parties</h2>
                    <p class="spec-tile__text">Private celebrations with polished production—so you’re in the room, not the wiring closet.</p>
                </article>
                <article class="spec-tile spec-tile--accent">
                    <span class="spec-tile__index" aria-hidden="true">04</span>
                    <h2 class="spec-tile__title">360° photo booth</h2>
                    <p class="spec-tile__text">Shareable, high-energy moments for guests—planned so lines and lighting actually work.</p>
                </article>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
