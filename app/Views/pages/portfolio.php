<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$page_title = (string) ($cms['doc_title'] ?? 'Portfolio');
$crumb_current = $page_title;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<div class="app-shell">
    <div class="app-shell__main">
        <?php if (!empty($cms['has_custom_body'])): ?>
        <div class="shell shell--wide page-pad prose cms-page-body" data-reveal>
        <?= $cms['body_html'] ?>
        </div>
        <?php else: ?>
        <div class="shell shell--wide page-pad" data-reveal>
            <p class="eyebrow">Our work</p>
            <h2 class="section__title">Moments that felt like you</h2>
            <p class="section__lead portfolio-page__lead">A few recent highlights. Photos and full stories can grow into a richer gallery when you are ready; for launch, this page focuses on <strong>outcome and style</strong> so couples and clients know what to expect.</p>
            <div class="portfolio-stack" role="list" aria-label="Case highlights">
                <article class="portfolio-record">
                    <div class="portfolio-record__media" aria-hidden="true">Photo</div>
                    <div class="portfolio-record__body">
                        <span class="portfolio-record__tag">Wedding</span>
                        <h3 class="portfolio-record__title">Waterfront ceremony → tented evening</h3>
                        <p class="portfolio-record__outcome">Rain plan executed in under twenty minutes, zero gap in the timeline. Gold-and-ivory palette with live band handoff and a sparkler exit that actually started on time.</p>
                    </div>
                </article>
                <article class="portfolio-record">
                    <div class="portfolio-record__media" aria-hidden="true">Photo</div>
                    <div class="portfolio-record__body">
                        <span class="portfolio-record__tag">Corporate</span>
                        <h3 class="portfolio-record__title">Awards night for 180</h3>
                        <p class="portfolio-record__outcome">Stage, AV, and catering in lockstep; branded wayfinding and a tight awards script so execs could stay present instead of herding mics.</p>
                    </div>
                </article>
                <article class="portfolio-record">
                    <div class="portfolio-record__media" aria-hidden="true">Photo</div>
                    <div class="portfolio-record__body">
                        <span class="portfolio-record__tag">Kids &amp; family</span>
                        <h3 class="portfolio-record__title">Milestone party with “wow” on a budget</h3>
                        <p class="portfolio-record__outcome">High-impact entry moment and a game rotation that kept ages 4–9 engaged—parents could talk, kids stayed off the ceiling, teardown was done before the last car left.</p>
                    </div>
                </article>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
