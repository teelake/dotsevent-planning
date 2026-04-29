<?php
declare(strict_types=1);
/**
 * Rentals catalog — structured CMS-driven sections.
 *
 * @var list<array<string, mixed>> $products
 * @var bool                       $db_ready
 * @var array<string, mixed>       $rentals_blocks
 */

$rb          = $rentals_blocks ?? [];
$hero        = $rb['hero']          ?? [];
$categories  = $rb['categories']    ?? [];
$controls    = $rb['controls']      ?? [];
$howItWorks  = $rb['how_it_works']  ?? [];
$logistics   = $rb['logistics']     ?? [];
$newsletter  = $rb['newsletter_cta'] ?? [];

$catItems    = is_array($categories['items'] ?? null) ? $categories['items'] : [];
$hiSteps     = is_array($howItWorks['steps'] ?? null) ? $howItWorks['steps'] : [];
$logItems    = is_array($logistics['items']  ?? null) ? $logistics['items']  : [];
$sortOptions = is_array($controls['sort_options'] ?? null) ? $controls['sort_options'] : [];

/* Build category label map for eyebrow display on cards */
$catLabelMap = [];
foreach ($catItems as $c) {
    $k = (string) ($c['key'] ?? '');
    if ($k !== '') {
        $catLabelMap[$k] = (string) ($c['label'] ?? ucfirst($k));
    }
}

/* Logistics icon SVGs (inline, keyed by identifier) */
$logIcons = [
    'truck'   => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
    'sparkle' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 7.2H22l-6.2 4.5 2.4 7.2L12 17l-6.2 3.9 2.4-7.2L2 9.2h7.6z"/></svg>',
    'shield'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'lock'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
];
?>

<?php /* ── CATEGORY FILTER STRIP ──────────────────────────────────── */ ?>
<?php if (($categories['enabled'] ?? false) && $catItems !== []): ?>
<div class="rental-cat-strip" role="navigation" aria-label="Filter by category">
    <div class="shell shell--wide">
        <div class="rental-cat-strip__inner">
            <button class="rental-cat-pill rental-cat-pill--active" data-filter="all" type="button">
                <?= e((string) ($categories['all_label'] ?? 'All items')) ?>
            </button>
            <?php foreach ($catItems as $cat): ?>
                <button
                    class="rental-cat-pill"
                    data-filter="<?= e((string) ($cat['key'] ?? '')) ?>"
                    type="button"
                    aria-label="Show <?= e((string) ($cat['label'] ?? '')) ?>"
                >
                    <?php if (!empty($cat['icon'])): ?>
                        <span class="rental-cat-pill__icon" aria-hidden="true"><?= $cat['icon'] /* trusted — set from PHP config */ ?></span>
                    <?php endif; ?>
                    <span><?= e((string) ($cat['label'] ?? '')) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php /* ── CONTROLS BAR ─────────────────────────────────────────────── */ ?>
<div class="app-shell" id="rentals-catalog">
    <div class="app-shell__main">
        <div class="shell shell--wide page-pad" data-reveal>

<?php if (!$db_ready): ?>
    <p class="banner banner--warn" role="status">Database is not connected. Add <code>config/database.php</code> and import <code>database/schema.sql</code> to show products.</p>
<?php elseif ($products === []): ?>
    <p class="section__lead">No rental products yet. Add rows in the admin or import <code>database/seed.sql</code>.</p>
<?php else: ?>

            <?php if ($controls['enabled'] ?? true): ?>
            <div class="rental-controls-bar">
                <?php if ($controls['show_search'] ?? true): ?>
                <div class="rental-controls-bar__search">
                    <label for="rental-search" class="visually-hidden"><?= e((string) ($controls['search_placeholder'] ?? 'Search rentals…')) ?></label>
                    <input
                        id="rental-search"
                        class="input rental-controls-bar__input"
                        type="search"
                        placeholder="<?= e((string) ($controls['search_placeholder'] ?? 'Search rentals…')) ?>"
                        aria-controls="rental-grid"
                    >
                </div>
                <?php endif; ?>
                <p class="rental-controls-bar__count" id="rental-result-count" aria-live="polite">
                    <span id="rental-count-num"><?= count($products) ?></span>
                    <?= e((string) ($controls['result_label_plural'] ?? 'rentals')) ?>
                </p>
                <?php if ($sortOptions !== []): ?>
                <div class="rental-controls-bar__sort">
                    <label for="rental-sort" class="visually-hidden">Sort</label>
                    <select id="rental-sort" class="input rental-controls-bar__select" aria-controls="rental-grid">
                        <?php foreach ($sortOptions as $opt): ?>
                            <option value="<?= e((string) ($opt['value'] ?? '')) ?>">
                                <?= e((string) ($opt['label'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php /* ── PRODUCT GRID ── */ ?>
            <ul class="product-grid reveal-stagger" id="rental-grid" data-reveal>
                <?php foreach ($products as $p):
                    $id        = (int)    $p['id'];
                    $name      = (string) $p['name'];
                    $cents     = (int)    $p['price_cents'];
                    $maxCents  = isset($p['price_max_cents']) && $p['price_max_cents'] !== null ? (int) $p['price_max_cents'] : null;
                    $cur       = (string) ($p['currency'] ?? 'CAD');
                    $img       = !empty($p['image_url']) ? (string) $p['image_url'] : null;
                    $catKey    = (string) ($p['category_key'] ?? '');
                    $badge     = (string) ($p['badge_label']  ?? '');
                    $eyebrow   = $catKey !== '' ? ($catLabelMap[$catKey] ?? ucfirst($catKey)) : 'Rental';
                    $priceLabel = $maxCents !== null
                        ? e(money_format_cents($cents, $cur)) . ' – ' . e(money_format_cents($maxCents, $cur))
                        : e(money_format_cents($cents, $cur));
                ?>
                <li
                    class="product-card"
                    data-category="<?= e($catKey) ?>"
                    data-name="<?= e(strtolower($name)) ?>"
                    data-price="<?= $cents ?>"
                >
                    <a class="product-card__media" href="<?= e(app_url('product/' . $id)) ?>" tabindex="-1" aria-hidden="true">
                        <?php if ($img): ?>
                            <img src="<?= e($img) ?>" alt="" loading="lazy" width="400" height="400">
                        <?php else: ?>
                            <div class="product-card__ph" role="img" aria-label="<?= e($name) ?>"></div>
                        <?php endif; ?>
                        <?php if ($badge !== ''): ?>
                            <span class="product-card__badge"><?= e($badge) ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="product-card__body">
                        <span class="product-card__eyebrow"><?= e($eyebrow) ?></span>
                        <h2 class="product-card__title">
                            <a href="<?= e(app_url('product/' . $id)) ?>"><?= e($name) ?></a>
                        </h2>
                        <p class="product-card__price"><?= $priceLabel ?></p>
                        <a class="btn btn--secondary product-card__cta" href="<?= e(app_url('product/' . $id)) ?>">View details</a>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>

            <p class="rental-no-results" id="rental-no-results" hidden aria-live="polite">
                No rentals match your search. <button class="text-link" id="rental-clear-search" type="button">Clear filters</button>
            </p>

<?php endif; ?>
        </div>
    </div>
</div>

<?php /* ── HOW IT WORKS ─────────────────────────────────────────────── */ ?>
<?php if (($howItWorks['enabled'] ?? false) && $hiSteps !== []): ?>
<section class="app-band rental-how-it-works" data-reveal>
    <div class="shell shell--wide">
        <?php if (!empty($howItWorks['title'])): ?>
            <h2 class="section__title rental-how-it-works__title"><?= e((string) $howItWorks['title']) ?></h2>
        <?php endif; ?>
        <ol class="rental-hiw-steps">
            <?php foreach ($hiSteps as $step): ?>
            <li class="rental-hiw-step">
                <span class="rental-hiw-step__num" aria-hidden="true"><?= e((string) ($step['number'] ?? '')) ?></span>
                <div class="rental-hiw-step__body">
                    <h3 class="rental-hiw-step__title"><?= e((string) ($step['title'] ?? '')) ?></h3>
                    <p class="rental-hiw-step__desc"><?= e((string) ($step['description'] ?? '')) ?></p>
                </div>
            </li>
            <?php endforeach; ?>
        </ol>
        <?php if (!empty($howItWorks['cta_label']) && !empty($howItWorks['cta_href'])): ?>
        <div class="rental-how-it-works__foot">
            <a class="btn btn--primary" href="<?= e((string) $howItWorks['cta_href']) ?>"><?= e((string) $howItWorks['cta_label']) ?></a>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php /* ── LOGISTICS TRUST BAR ──────────────────────────────────────── */ ?>
<?php if (($logistics['enabled'] ?? false) && $logItems !== []): ?>
<div class="rental-logistics-bar" data-reveal>
    <div class="shell shell--wide">
        <ul class="rental-logistics-bar__list">
            <?php foreach ($logItems as $item):
                $iconKey = (string) ($item['icon'] ?? '');
                $iconSvg = $logIcons[$iconKey] ?? '';
            ?>
            <li class="rental-logistics-bar__item">
                <?php if ($iconSvg !== ''): ?>
                    <span class="rental-logistics-bar__icon" aria-hidden="true"><?= $iconSvg ?></span>
                <?php endif; ?>
                <span><?= e((string) ($item['label'] ?? '')) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>

<?php /* ── NEWSLETTER CTA ───────────────────────────────────────────── */ ?>
<?php if ($newsletter['enabled'] ?? true): ?>
<section class="app-band app-band--newsletter" data-reveal>
    <div class="shell shell--wide newsletter">
        <div class="newsletter__text">
            <?php if (!empty($newsletter['title'])): ?>
                <h2 class="newsletter__title"><?= e((string) $newsletter['title']) ?></h2>
            <?php endif; ?>
            <?php if (!empty($newsletter['text_html'])): ?>
                <div class="prose"><?= $newsletter['text_html'] /* sanitized in RentalsPageBlocks */ ?></div>
            <?php endif; ?>
        </div>
        <form class="newsletter__form" method="post" action="<?= e(app_url('form/newsletter')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="source" value="rentals-cta">
            <label for="nw-email-rentals" class="visually-hidden">Email address</label>
            <input
                id="nw-email-rentals"
                class="input newsletter__input"
                type="email"
                name="email"
                required
                placeholder="<?= e((string) ($newsletter['placeholder'] ?? 'Your email address')) ?>"
                autocomplete="email"
            >
            <button class="btn btn--primary newsletter__btn" type="submit">
                <?= e((string) ($newsletter['button_label'] ?? 'Submit')) ?>
            </button>
        </form>
    </div>
</section>
<?php endif; ?>

<script>
(function () {
    'use strict';
    var grid      = document.getElementById('rental-grid');
    var searchEl  = document.getElementById('rental-search');
    var sortEl    = document.getElementById('rental-sort');
    var countEl   = document.getElementById('rental-count-num');
    var noResults = document.getElementById('rental-no-results');
    var clearBtn  = document.getElementById('rental-clear-search');
    var catPills  = document.querySelectorAll('.rental-cat-pill');

    if (!grid) return;

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.product-card'));
    var activeFilter = 'all';
    var searchQuery  = '';

    function applyFilters() {
        var q   = searchQuery.toLowerCase().trim();
        var vis = 0;

        // Collect current sort
        var sortVal = sortEl ? sortEl.value : 'default';

        var sorted = cards.slice();
        if (sortVal === 'price_asc') {
            sorted.sort(function (a, b) { return parseInt(a.dataset.price, 10) - parseInt(b.dataset.price, 10); });
        } else if (sortVal === 'price_desc') {
            sorted.sort(function (a, b) { return parseInt(b.dataset.price, 10) - parseInt(a.dataset.price, 10); });
        } else if (sortVal === 'name_asc') {
            sorted.sort(function (a, b) { return (a.dataset.name || '').localeCompare(b.dataset.name || ''); });
        }

        // Re-append in sorted order
        sorted.forEach(function (card) { grid.appendChild(card); });

        // Show/hide
        sorted.forEach(function (card) {
            var catMatch  = activeFilter === 'all' || card.dataset.category === activeFilter;
            var nameMatch = q === '' || (card.dataset.name || '').indexOf(q) !== -1;
            var visible   = catMatch && nameMatch;
            card.style.display = visible ? '' : 'none';
            if (visible) vis++;
        });

        if (countEl) countEl.textContent = vis;
        if (noResults) noResults.hidden = vis > 0;
    }

    catPills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            activeFilter = pill.dataset.filter || 'all';
            catPills.forEach(function (p) { p.classList.remove('rental-cat-pill--active'); });
            pill.classList.add('rental-cat-pill--active');
            applyFilters();
        });
    });

    if (searchEl) {
        searchEl.addEventListener('input', function () {
            searchQuery = searchEl.value;
            applyFilters();
        });
    }
    if (sortEl) {
        sortEl.addEventListener('change', applyFilters);
    }
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            searchQuery  = '';
            activeFilter = 'all';
            if (searchEl) searchEl.value = '';
            catPills.forEach(function (p) { p.classList.remove('rental-cat-pill--active'); });
            var allPill = document.querySelector('.rental-cat-pill[data-filter="all"]');
            if (allPill) allPill.classList.add('rental-cat-pill--active');
            applyFilters();
        });
    }
})();
</script>
