<?php
declare(strict_types=1);
/** @var array<string, mixed> $cms */
$cms = $cms ?? [];
$page_title = (string) ($cms['doc_title'] ?? 'About us');
$crumb_current = $page_title;
include dirname(__DIR__) . '/partials/page-hero.php';
?>
<?php if (!empty($cms['has_custom_body'])): ?>
<div class="shell page-pad prose cms-page-body" data-reveal>
<?= $cms['body_html'] ?>
</div>
<?php else: ?>
<div class="shell page-pad" data-reveal>
    <div class="section__split">
        <div class="prose">
            <p class="eyebrow">Since 2017</p>
            <h2 class="section__title">A team obsessed with the details</h2>
            <p>DOTS Event Planning started with a simple idea: your event should feel effortless on the day, even if months of work went into it. We plan weddings, corporate gatherings, and celebrations across New Brunswick with one north star—<strong>clear communication</strong> and <strong>flawless execution</strong> so you can actually enjoy the room.</p>
            <p>Our planners blend logistics with design—timelines, vendor handoffs, and floor plans sit alongside color, texture, and lighting. Whether you need a nudge in the right direction or someone to run the show from first sketch to last dance, we show up on site and in the group chat so nothing falls through the cracks.</p>
            <h3 class="section__title" style="font-size: 1.2rem; margin-top: 1.75rem;">What we value</h3>
            <ul class="prose__list" style="margin: 0.5rem 0 0; padding-left: 1.2rem; line-height: 1.65; color: var(--color-ink-muted);">
                <li><strong>Honesty</strong>—realistic budgets and timelines, early.</li>
                <li><strong>Respect</strong>—for your guests, your vendors, and your story.</li>
                <li><strong>Calm</strong>—problems get solved; you get solutions, not drama.</li>
            </ul>
        </div>
        <div>
            <div class="stat" style="margin-bottom: 1.5rem;">
                <span class="stat__value">300+</span>
                <span class="stat__label">Events supported</span>
            </div>
            <div style="border-radius: var(--radius-md); border: 1px solid var(--color-line); padding: 1.25rem 1.35rem; background: var(--color-surface, #fffcf7); max-width: 32ch;">
                <p style="margin: 0; font-size: 1.05rem; line-height: 1.6; font-style: italic; color: var(--color-ink-muted);">“The best compliment we get is when guests assume the family did it all—then we know the plan disappeared into the experience.”</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
