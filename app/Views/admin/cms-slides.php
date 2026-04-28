<?php
declare(strict_types=1);
/** @var list<array<string, mixed>> $slides */
/** @var string $storefront_url */
$slides = $slides ?? [];
$storefront_url = $storefront_url ?? app_url('');
$n = count($slides);
?>
<section class="section--tight hero-slides-hub" style="padding-top: 0;">
    <header class="hero-slides-hub__header">
        <div>
            <p class="hero-slides-hub__badge"><span aria-hidden="true">◇</span> Homepage experience</p>
            <h2 class="hero-slides-hub__title">Hero carousel</h2>
            <p class="hero-slides-hub__lead">Design the full-width story visitors see first. When no slides are <strong>live</strong> (or none match the schedule), the site falls back to the default hero from code / legacy home JSON.</p>
        </div>
        <div class="hero-slides-hub__actions">
            <a class="btn btn--secondary" href="<?= e($storefront_url) ?>" target="_blank" rel="noopener noreferrer">View storefront</a>
            <a class="btn btn--primary" href="<?= e(app_url('admin/cms/slide/new')) ?>">+ New slide</a>
        </div>
    </header>

    <p class="hero-slides-hub__meta text-muted"><?= (int) $n ?> slide<?= $n === 1 ? '' : 's' ?> · reorder with arrows on each card</p>

    <?php if ($n === 0): ?>
        <div class="card card--folio" style="padding: 2rem;">
            <p class="card__text" style="margin:0;">No slides yet. Add one, upload a desktop image, mark it live, and it will replace the default homepage carousel.</p>
            <p style="margin: 1rem 0 0;"><a class="btn btn--primary" href="<?= e(app_url('admin/cms/slide/new')) ?>">Create first slide</a></p>
        </div>
    <?php else: ?>
        <div class="hero-slides-hub__grid">
            <?php foreach ($slides as $i => $s): ?>
                <?php
                $sid = (int) ($s['id'] ?? 0);
                $live = !empty($s['is_live']);
                $desk = trim((string) ($s['image_desktop_path'] ?? ''));
                $thumb = $desk !== '' ? app_url(ltrim($desk, '/')) : '';
                $badge = trim((string) ($s['badge'] ?? ''));
                $headline = trim((string) ($s['headline'] ?? ''));
                $support = trim((string) ($s['supporting'] ?? ''));
                $excerpt = function_exists('mb_substr') ? mb_substr($support, 0, 120) : substr($support, 0, 120);
                if (strlen($support) > 120) {
                    $excerpt .= '…';
                }
                ?>
                <article class="slide-admin-card">
                    <div class="slide-admin-card__top">
                        <span class="slide-admin-card__index"><?= (int) ($i + 1) ?></span>
                        <?php if ($live): ?>
                            <span class="slide-admin-card__status slide-admin-card__status--live">Live</span>
                        <?php else: ?>
                            <span class="slide-admin-card__status slide-admin-card__status--draft">Draft</span>
                        <?php endif; ?>
                    </div>
                    <div class="slide-admin-card__media">
                        <?php if ($thumb !== ''): ?>
                            <img src="<?= e($thumb) ?>" alt="" width="640" height="360" loading="lazy" decoding="async">
                        <?php else: ?>
                            <div class="slide-admin-card__media-placeholder">No image</div>
                        <?php endif; ?>
                    </div>
                    <div class="slide-admin-card__body">
                        <?php if ($badge !== ''): ?>
                            <p class="slide-admin-card__eyebrow"><?= e($badge) ?></p>
                        <?php endif; ?>
                        <h3 class="slide-admin-card__headline"><?= e($headline !== '' ? $headline : 'Untitled slide') ?></h3>
                        <?php if ($excerpt !== ''): ?>
                            <p class="slide-admin-card__text"><?= e($excerpt) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="slide-admin-card__footer">
                        <a class="text-link" href="<?= e(app_url('admin/cms/slide/' . $sid . '/edit')) ?>">Edit</a>
                        <span class="slide-admin-card__sep" aria-hidden="true">·</span>
                        <form class="slide-admin-card__reorder" method="post" action="<?= e(app_url('admin/cms/slide/' . $sid . '/move-up')) ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="slide-admin-card__icon-btn" title="Move up" aria-label="Move slide up"<?= $i === 0 ? ' disabled' : '' ?>>↑</button>
                        </form>
                        <form class="slide-admin-card__reorder" method="post" action="<?= e(app_url('admin/cms/slide/' . $sid . '/move-down')) ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="slide-admin-card__icon-btn" title="Move down" aria-label="Move slide down"<?= $i >= $n - 1 ? ' disabled' : '' ?>>↓</button>
                        </form>
                        <form class="slide-admin-card__delete" method="post" action="<?= e(app_url('admin/cms/slide/' . $sid . '/delete')) ?>" onsubmit="return confirm('Delete this slide?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="text-link slide-admin-card__delete-btn">Delete</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <p class="text-muted" style="margin-top: 1.5rem; font-size: 0.88rem;"><a class="text-link" href="<?= e(app_url('admin/cms/pages')) ?>">← Pages &amp; content</a> · <a class="text-link" href="<?= e(app_url('admin/cms')) ?>">Site settings</a></p>
</section>
