<?php
declare(strict_types=1);
/**
 * @var int $revenue_cents_30d
 * @var int $revenue_cents_all
 * @var int $orders_paid_30d
 * @var int $orders_paid_7d
 * @var int $orders_paid_all
 * @var int $leads_30d
 * @var int $leads_7d
 * @var int $leads_all
 * @var list<array{date: string, count: int}> $orders_by_day
 * @var list<array{date: string, count: int}> $leads_by_day
 */
$maxOrd = 1;
foreach ($orders_by_day as $b) {
    $maxOrd = max($maxOrd, (int) $b['count']);
}
$maxLead = 1;
foreach ($leads_by_day as $b) {
    $maxLead = max($maxLead, (int) $b['count']);
}
?>
<div class="admin-analytics__grid">
    <div class="admin-stat">
        <div class="admin-stat__l">Revenue (30 days) · paid orders</div>
        <div class="admin-stat__v"><?= e(money_format_cents($revenue_cents_30d)) ?></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__l">Revenue (all time)</div>
        <div class="admin-stat__v"><?= e(money_format_cents($revenue_cents_all)) ?></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__l">Paid orders (30d / 7d / all)</div>
        <div class="admin-stat__v"><?= (int) $orders_paid_30d ?> / <?= (int) $orders_paid_7d ?> / <?= (int) $orders_paid_all ?></div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__l">Leads (30d / 7d / all)</div>
        <div class="admin-stat__v"><?= (int) $leads_30d ?> / <?= (int) $leads_7d ?> / <?= (int) $leads_all ?></div>
    </div>
</div>
<p class="section__lead" style="max-width: 50ch; margin-bottom: 1.25rem;">Revenue and order trends use rows with status <code>paid</code>. For mixed currencies, totals assume a single display currency (see your Square setup).</p>

<div class="admin-chart">
    <h2 class="admin-chart__title">Paid orders · last 7 days</h2>
    <div class="admin-chart__row" role="img" aria-label="Bar chart of paid orders by day, last 7 days">
        <?php foreach ($orders_by_day as $b): ?>
            <?php
            $c = (int) $b['count'];
            $pct = (int) round(100 * $c / $maxOrd);
            $d = (string) $b['date'];
            $dayShort = (new DateTimeImmutable($d))->format('D j');
            ?>
        <div class="admin-chart__col">
            <div class="admin-chart__bar-track">
                <div class="admin-chart__bar" style="<?= $c > 0 ? 'height: ' . (string) (int) max(3, $pct) . '%;min-height:2px' : 'height:0;min-height:0' ?>;"></div>
            </div>
            <span class="admin-chart__day" title="<?= e($d) ?>"><?= e($dayShort) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="admin-chart">
    <h2 class="admin-chart__title">Leads · last 7 days</h2>
    <div class="admin-chart__row" role="img" aria-label="Bar chart of new leads by day, last 7 days">
        <?php foreach ($leads_by_day as $b): ?>
            <?php
            $c = (int) $b['count'];
            $pct = (int) round(100 * $c / $maxLead);
            $d = (string) $b['date'];
            $dayShort = (new DateTimeImmutable($d))->format('D j');
            ?>
        <div class="admin-chart__col">
            <div class="admin-chart__bar-track">
                <div class="admin-chart__bar" style="<?= $c > 0 ? 'height: ' . (string) (int) max(3, $pct) . '%;min-height:2px' : 'height:0;min-height:0' ?>; background: linear-gradient(180deg, #c4b8a8, #8a7d6f 55%, #5c5348);"></div>
            </div>
            <span class="admin-chart__day" title="<?= e($d) ?>"><?= e($dayShort) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<p class="text-muted" style="font-size: 0.88rem; margin: 0;">Data from your local database. For deeper commerce analytics, use Square or export.</p>
