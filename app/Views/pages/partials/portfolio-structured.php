<?php
declare(strict_types=1);
/** @var array<string, mixed> $portfolio_blocks */
$pb = $portfolio_blocks ?? [];

$intro = is_array($pb['intro'] ?? null) ? $pb['intro'] : [];
$controls = is_array($pb['controls'] ?? null) ? $pb['controls'] : [];
$featured = is_array($pb['featured'] ?? null) ? $pb['featured'] : [];
$gallery = is_array($pb['gallery'] ?? null) ? $pb['gallery'] : [];
$nw = is_array($pb['newsletter_cta'] ?? null) ? $pb['newsletter_cta'] : [];

$introOn = (($intro['enabled'] ?? true) !== false);
$controlsOn = (($controls['enabled'] ?? true) !== false);
$featuredOn = (($featured['enabled'] ?? true) !== false);
$galleryOn = (($gallery['enabled'] ?? true) !== false);
$nwOn = (($nw['enabled'] ?? true) !== false);

$featuredItems = is_array($featured['items'] ?? null) ? $featured['items'] : [];
$galleryItems = is_array($gallery['items'] ?? null) ? $gallery['items'] : [];
$filters = is_array($controls['filters'] ?? null) ? $controls['filters'] : [];
?>

<?php if ($introOn): ?>
<section data-reveal>
    <?php if (trim((string) ($intro['eyebrow'] ?? '')) !== ''): ?>
    <p class="eyebrow"><?= e((string) $intro['eyebrow']) ?></p>
    <?php endif; ?>
    <?php if (trim((string) ($intro['title'] ?? '')) !== ''): ?>
    <h2 class="section__title"><?= e((string) $intro['title']) ?></h2>
    <?php endif; ?>
    <?php if (!empty($intro['lead_html']) && is_string($intro['lead_html'])): ?>
    <div class="prose"><?= $intro['lead_html'] ?></div>
    <?php endif; ?>
</section>
<?php endif; ?>

<?php if ($controlsOn): ?>
<section data-reveal style="margin-top:1rem;">
    <div class="app-panel" style="display:grid; gap:0.8rem;">
        <?php if (!empty($controls['show_search'])): ?>
        <label class="visually-hidden" for="portfolio-search">Search portfolio</label>
        <input id="portfolio-search" class="input" type="search" placeholder="Search projects..." aria-label="Search projects">
        <?php endif; ?>

        <?php foreach ($filters as $f): ?>
            <?php if (!is_array($f)) { continue; } ?>
            <?php $label = trim((string) ($f['label'] ?? 'Filter')); $opts = is_array($f['options'] ?? null) ? $f['options'] : []; ?>
            <?php if ($opts === []) { continue; } ?>
            <label class="visually-hidden" for="portfolio-filter-<?= e((string) ($f['key'] ?? 'x')) ?>"><?= e($label) ?></label>
            <select id="portfolio-filter-<?= e((string) ($f['key'] ?? 'x')) ?>" class="input" style="padding:.65rem 1rem;border-radius:var(--radius-sm);" aria-label="<?= e($label) ?>">
                <?php foreach ($opts as $opt): ?>
                <option value="<?= e((string) $opt) ?>"><?= e((string) $opt) ?></option>
                <?php endforeach; ?>
            </select>
        <?php endforeach; ?>

        <?php if (!empty($controls['show_sort'])): ?>
        <label class="visually-hidden" for="portfolio-sort">Sort</label>
        <select id="portfolio-sort" class="input" style="padding:.65rem 1rem;border-radius:var(--radius-sm);" aria-label="Sort projects">
            <option value="featured"<?= (($controls['default_sort'] ?? '') === 'featured') ? ' selected' : '' ?>>Featured</option>
            <option value="newest"<?= (($controls['default_sort'] ?? '') === 'newest') ? ' selected' : '' ?>>Newest</option>
            <option value="a_z"<?= (($controls['default_sort'] ?? '') === 'a_z') ? ' selected' : '' ?>>A–Z</option>
        </select>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($featuredOn && $featuredItems !== []): ?>
<section data-reveal style="margin-top:1.5rem;">
    <?php if (trim((string) ($featured['title'] ?? '')) !== ''): ?>
    <h2 class="section__title"><?= e((string) $featured['title']) ?></h2>
    <?php endif; ?>
    <?php if (trim((string) ($featured['subtitle'] ?? '')) !== ''): ?>
    <p class="section__lead"><?= e((string) $featured['subtitle']) ?></p>
    <?php endif; ?>
    <div class="portfolio-cases">
        <?php foreach ($featuredItems as $item): ?>
        <?php if (!is_array($item)) { continue; } ?>
        <?php
            $img = trim((string) ($item['image_path'] ?? ''));
            $imgUrl = $img !== '' ? public_file_url($img) : '';
            $alt = trim((string) ($item['alt'] ?? 'Portfolio image'));
        ?>
        <article class="portfolio-case">
            <?php if ($imgUrl !== ''): ?>
            <div class="portfolio-case__media" style="background:#0f0f0f;">
                <img src="<?= e($imgUrl) ?>" alt="<?= e($alt) ?>" style="width:100%;height:100%;object-fit:cover;display:block;">
            </div>
            <?php else: ?>
            <div class="portfolio-case__media" aria-hidden="true">Project</div>
            <?php endif; ?>
            <div class="portfolio-case__body">
                <span class="portfolio-case__tag"><?= e((string) ($item['tag'] ?? 'Project')) ?></span>
                <h3 class="portfolio-case__title"><?= e((string) ($item['title'] ?? 'Untitled project')) ?></h3>
                <p class="portfolio-case__outcome"><?= e((string) ($item['summary'] ?? '')) ?></p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($galleryOn && $galleryItems !== []): ?>
<section data-reveal style="margin-top:1.5rem;">
    <?php if (trim((string) ($gallery['title'] ?? '')) !== ''): ?>
    <h2 class="section__title"><?= e((string) $gallery['title']) ?></h2>
    <?php endif; ?>
    <div class="portfolio-cases" aria-label="Portfolio gallery">
        <?php foreach ($galleryItems as $item): ?>
        <?php if (!is_array($item)) { continue; } ?>
        <?php
            $img = trim((string) ($item['image_path'] ?? ''));
            $imgUrl = $img !== '' ? public_file_url($img) : '';
            $alt = trim((string) ($item['alt'] ?? 'Portfolio image'));
        ?>
        <article class="portfolio-case">
            <?php if ($imgUrl !== ''): ?>
            <div class="portfolio-case__media" style="background:#0f0f0f;">
                <img src="<?= e($imgUrl) ?>" alt="<?= e($alt) ?>" style="width:100%;height:100%;object-fit:cover;display:block;">
            </div>
            <?php else: ?>
            <div class="portfolio-case__media" aria-hidden="true">Image</div>
            <?php endif; ?>
            <div class="portfolio-case__body">
                <span class="portfolio-case__tag"><?= e((string) ($item['tag'] ?? 'Project')) ?></span>
                <h3 class="portfolio-case__title"><?= e((string) ($item['title'] ?? 'Untitled project')) ?></h3>
                <p class="portfolio-case__outcome"><?= e((string) ($item['summary'] ?? '')) ?></p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($nwOn && trim((string) ($nw['title'] ?? '')) !== ''): ?>
<section class="app-band app-band--newsletter" aria-labelledby="portfolio-news-title" data-reveal>
    <div class="shell shell--wide newsletter-app">
        <div>
            <h2 id="portfolio-news-title" class="newsletter__title"><?= e((string) $nw['title']) ?></h2>
            <?php if (!empty($nw['text_html']) && is_string($nw['text_html'])): ?>
            <div class="newsletter__text prose"><?= $nw['text_html'] ?></div>
            <?php endif; ?>
        </div>
        <?php include dirname(__DIR__, 2) . '/partials/newsletter-band-flash.php'; ?>
        <form class="newsletter__form newsletter-app__form" method="post" action="<?= e(app_url('newsletter')) ?>" novalidate data-newsletter-form>
            <?= csrf_field() ?>
            <input type="hidden" name="_newsletter_return" value="portfolio">
            <label class="visually-hidden" for="portfolio-news-email"><?= e((string) ($nw['placeholder'] ?? 'Your email address')) ?></label>
            <input id="portfolio-news-email" class="input" type="email" name="email" placeholder="<?= e((string) ($nw['placeholder'] ?? 'Your email address')) ?>" autocomplete="email" required aria-describedby="portfolio-newsletter-error">
            <p id="portfolio-newsletter-error" class="newsletter-app__feedback newsletter-app__feedback--error" data-newsletter-error hidden role="alert"></p>
            <button class="btn btn--dark" type="submit"><?= e((string) ($nw['button_label'] ?? 'Submit')) ?></button>
        </form>
    </div>
</section>
<?php endif; ?>

