<?php
declare(strict_types=1);
/** @var string $slug */
/** @var string $page_title */
/** @var string $content_json */
$slug = $slug ?? '';
$page_title = $page_title ?? '';
$content_json = trim((string) ($content_json ?? ''));
if ($content_json === '') {
    $content_json = '{}';
}
$dataPreview = json_decode($content_json, true);
$meta_description_field = '';
if (is_array($dataPreview) && isset($dataPreview['meta_description']) && is_string($dataPreview['meta_description'])) {
    $meta_description_field = $dataPreview['meta_description'];
}

$storedBlocks = is_array($dataPreview) ? \App\Services\CmsPublicPage::blocksFromContentData($dataPreview) : null;
$mergedBlocks = null;
if ($slug === 'home') {
    $mergedBlocks = \App\Services\HomePageBlocks::merged($storedBlocks);
}
$mergedAboutBlocks = null;
if ($slug === 'about') {
    $mergedAboutBlocks = \App\Services\AboutPageBlocks::merged($storedBlocks);
}
$mergedServicesBlocks = null;
if ($slug === 'services') {
    $mergedServicesBlocks = \App\Services\ServicesPageBlocks::merged($storedBlocks);
}
$mergedContactBlocks = null;
if ($slug === 'contact') {
    $mergedContactBlocks = \App\Services\ContactPageBlocks::merged($storedBlocks);
}
$mergedPortfolioBlocks = null;
if ($slug === 'portfolio') {
    $mergedPortfolioBlocks = \App\Services\PortfolioPageBlocks::merged($storedBlocks);
}
$mergedRentalsBlocks = null;
if ($slug === 'rentals') {
    $mergedRentalsBlocks = \App\Services\RentalsPageBlocks::merged($storedBlocks);
}
?>
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<section class="section--tight" style="padding-top: 0;">
    <p class="text-muted" style="margin: 0 0 1rem; font-size: 0.9rem;">
        <a class="text-link" href="<?= e(app_url('admin/cms/pages')) ?>">← Pages &amp; content</a>
        <span aria-hidden="true"> · </span>
        <span>Slug: <code><?= e($slug) ?></code></span>
    </p>

    <div class="card card--folio" style="max-width: none;">
        <h2 class="card__title" style="margin: 0 0 1rem;">Edit page</h2>
        <?php if ($slug === 'home'): ?>
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Hero slides: <a class="text-link" href="<?= e(app_url('admin/cms/slides')) ?>">Hero carousel</a>. Edit intro HTML, meta description, and the structured homepage sections below. The <strong>services grid on the homepage</strong> is controlled from <a class="text-link" href="<?= e(app_url('admin/cms/page/services')) ?>">CMS → Pages → Services</a> (Offerings). Changes are saved as relational CMS fields.</p>
        <?php elseif ($slug === 'about'): ?>
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Structured About page content is saved as relational CMS fields. The rich-text “Body” field remains available for legacy copy; public About uses the modular sections below.</p>
        <?php elseif ($slug === 'services'): ?>
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Structured Services content is saved as relational CMS fields. The Body field can hold legacy markup; the live Services page uses the sections below.</p>
        <?php elseif ($slug === 'contact'): ?>
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Structured Contact content is saved as relational CMS fields. The live Contact page reads modular sections and dynamic values from CMS settings.</p>
        <?php elseif ($slug === 'portfolio'): ?>
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Structured Portfolio content is saved as relational CMS fields. Use items for featured/gallery and keep media paths dynamic via CMS media uploads.</p>
        <?php elseif ($slug === 'rentals'): ?>
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Rentals page layout blocks are saved as relational CMS fields. Individual products are managed via <a class="text-link" href="<?= e(app_url('admin/products')) ?>">Products</a>.</p>
        <?php endif; ?>

        <form class="admin-form" method="post" action="<?= e(app_url('admin/cms/page/' . $slug . '/save')) ?>" id="cms-page-form">
            <?= csrf_field() ?>
            <div class="form-row">
                <label for="cms-page-title">Page title</label>
                <input class="input" id="cms-page-title" name="title" type="text" value="<?= e($page_title) ?>" placeholder="Optional display title">
            </div>
            <div class="form-row">
                <label for="cms-meta-description">Meta description (SEO)</label>
                <textarea class="input input--textarea" id="cms-meta-description" rows="2" placeholder="Shown in search results when set"><?= e($meta_description_field) ?></textarea>
                <span class="text-muted" style="font-size:0.85rem;">Stored as page metadata; overrides the default for this route.</span>
            </div>
            <?php if ($slug === 'home' && $mergedBlocks !== null): ?>
            <div class="form-row">
                <span class="home-blocks-editor__label">Structured homepage</span>
                <?php include __DIR__ . '/partials/home-blocks-editor.php'; ?>
            </div>
        <?php elseif ($slug === 'about' && $mergedAboutBlocks !== null): ?>
            <div class="form-row">
                <span class="home-blocks-editor__label">About page sections</span>
                <?php include __DIR__ . '/partials/about-blocks-editor.php'; ?>
            </div>
            <?php elseif ($slug === 'services' && $mergedServicesBlocks !== null): ?>
            <div class="form-row">
                <span class="home-blocks-editor__label">Services page sections</span>
                <?php include __DIR__ . '/partials/services-blocks-editor.php'; ?>
            </div>
            <?php elseif ($slug === 'contact' && $mergedContactBlocks !== null): ?>
            <div class="form-row">
                <span class="home-blocks-editor__label">Contact page sections</span>
                <?php include __DIR__ . '/partials/contact-blocks-editor.php'; ?>
            </div>
            <?php elseif ($slug === 'portfolio' && $mergedPortfolioBlocks !== null): ?>
            <div class="form-row">
                <span class="home-blocks-editor__label">Portfolio page sections</span>
                <?php include __DIR__ . '/partials/portfolio-blocks-editor.php'; ?>
            </div>
            <?php elseif ($slug === 'rentals' && $mergedRentalsBlocks !== null): ?>
            <div class="form-row">
                <span class="home-blocks-editor__label">Rentals page sections</span>
                <?php include __DIR__ . '/partials/rentals-blocks-editor.php'; ?>
            </div>
            <?php endif; ?>
            <div class="form-row">
                <label for="editor">Body</label>
                <div id="editor" style="min-height: 280px; background: #fff; border: 1px solid var(--color-line); border-radius: 12px;"></div>
                <input type="hidden" name="content_json" id="cms-content-json" value="">
            </div>
            <button class="btn btn--primary" type="submit">Save page</button>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<?php if ($slug === 'about'): ?>
<script src="<?= e(asset('js/admin-about-blocks.js')) ?>"></script>
<?php elseif ($slug === 'services'): ?>
<script src="<?= e(asset('js/admin-services-blocks.js')) ?>"></script>
<?php elseif ($slug === 'contact'): ?>
<script src="<?= e(asset('js/admin-contact-blocks.js')) ?>"></script>
<?php elseif ($slug === 'portfolio'): ?>
<script src="<?= e(asset('js/admin-portfolio-blocks.js')) ?>"></script>
<?php elseif ($slug === 'rentals'): ?>
<script src="<?= e(asset('js/admin-rentals-blocks.js')) ?>"></script>
<?php endif; ?>
<script>
(function () {
  const pageSlug = <?= json_encode($slug, JSON_THROW_ON_ERROR) ?>;
  const initial = <?= json_encode($content_json, JSON_THROW_ON_ERROR) ?>;
  let data = {};
  try {
    data = JSON.parse(initial || '{}');
  } catch (e) {
    data = {};
  }
  const quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
      toolbar: {
        container: [
          [{ header: [1, 2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['blockquote', 'link', 'image', 'video'],
          ['clean'],
        ],
        handlers: {
          image: function () {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            input.onchange = async function () {
              const file = input.files && input.files[0];
              if (!file) return;
              const fd = new FormData();
              fd.append('file', file);
              fd.append('_csrf', <?= json_encode(\App\Core\Csrf::token(), JSON_THROW_ON_ERROR) ?>);
              const res = await fetch(<?= json_encode(app_url('admin/media/upload'), JSON_THROW_ON_ERROR) ?>, {
                method: 'POST',
                body: fd,
                credentials: 'same-origin',
              });
              const out = await res.json().catch(function () { return null; });
              if (!out || !out.ok || !out.url) {
                alert((out && out.error) ? out.error : 'Upload failed');
                return;
              }
              const range = quill.getSelection(true);
              quill.insertEmbed(range.index, 'image', out.url);
            };
          },
        },
      },
    },
  });
  const html = typeof data.html === 'string' ? data.html : '';
  if (html) {
    quill.root.innerHTML = html;
  }

  if (pageSlug === 'about' && typeof window.dotseAboutBlocksBind === 'function') {
    window.dotseAboutBlocksBind();
  }
  if (pageSlug === 'services' && typeof window.dotseServicesBlocksBind === 'function') {
    window.dotseServicesBlocksBind();
  }
  if (pageSlug === 'contact' && typeof window.dotseContactBlocksBind === 'function') {
    window.dotseContactBlocksBind();
  }
  if (pageSlug === 'portfolio' && typeof window.dotsePortfolioBlocksBind === 'function') {
    window.dotsePortfolioBlocksBind();
  }
  if (pageSlug === 'rentals' && typeof window.dotseRentalsBlocksBind === 'function') {
    window.dotseRentalsBlocksBind();
  }

  const metaEl = document.getElementById('cms-meta-description');
  const hbRoot = document.getElementById('home-blocks-editor');

  function hbAppendTemplate(tplId, containerId) {
    const tpl = document.getElementById(tplId);
    const c = document.getElementById(containerId);
    if (!tpl || !c || !tpl.content) {
      return;
    }
    c.appendChild(document.importNode(tpl.content, true));
  }

  function collectHomeBlocks() {
    if (!hbRoot) {
      return {};
    }
    var verEl = document.getElementById('hb-version');
    var version = verEl && verEl.value.trim() !== '' ? parseInt(verEl.value, 10) : 1;
    if (isNaN(version)) {
      version = 1;
    }

    var metrics = [];
    hbRoot.querySelectorAll('.js-hb-metric-row').forEach(function (row) {
      var label = row.querySelector('.js-metric-label');
      var display = row.querySelector('.js-metric-display');
      var targetEl = row.querySelector('.js-metric-target');
      var suffix = row.querySelector('.js-metric-suffix');
      var tv = targetEl && targetEl.value.trim();
      var tn = tv === '' ? 0 : parseInt(tv, 10);
      var labelVal = label ? label.value.trim() : '';
      var displayVal = display ? display.value.trim() : '';
      var suffixVal = suffix && suffix.value.trim() !== '' ? suffix.value.trim() : '+';
      if (labelVal === '' && displayVal === '' && (isNaN(tn) || tn === 0)) {
        return;
      }
      metrics.push({
        label: labelVal,
        display: displayVal,
        target: isNaN(tn) ? 0 : tn,
        suffix: suffixVal,
      });
    });

    var steps = [];
    hbRoot.querySelectorAll('.js-hb-step-row').forEach(function (row) {
      var st = row.querySelector('.js-step-title');
      var sx = row.querySelector('.js-step-text');
      steps.push({
        title: st ? st.value.trim() : '',
        text: sx ? sx.value.trim() : '',
      });
    });

    var pkgs = [];
    hbRoot.querySelectorAll('.js-hb-pkg-row').forEach(function (row) {
      var featsEl = row.querySelector('.js-pkg-feats');
      var feats = [];
      if (featsEl && featsEl.value) {
        featsEl.value.split(/\r?\n/).forEach(function (line) {
          var t = line.trim();
          if (t !== '') {
            feats.push(t);
          }
        });
      }
      var fp = row.querySelector('.js-pkg-featured');
      var pkg = {
        name: row.querySelector('.js-pkg-name') ? row.querySelector('.js-pkg-name').value.trim() : '',
        price_display: row.querySelector('.js-pkg-price') ? row.querySelector('.js-pkg-price').value.trim() : '',
        featured: fp ? fp.checked : false,
        cta_label: row.querySelector('.js-pkg-cta-l') ? row.querySelector('.js-pkg-cta-l').value.trim() : '',
        cta_href: row.querySelector('.js-pkg-cta-h') ? row.querySelector('.js-pkg-cta-h').value.trim() : '',
        features: feats,
      };
      pkgs.push(pkg);
    });

    var quoteRows = [];
    hbRoot.querySelectorAll('.js-hb-quote-row').forEach(function (row) {
      quoteRows.push({
        quote: row.querySelector('.js-quote-text') ? row.querySelector('.js-quote-text').value.trim() : '',
        name: row.querySelector('.js-quote-name') ? row.querySelector('.js-quote-name').value.trim() : '',
        role: row.querySelector('.js-quote-role') ? row.querySelector('.js-quote-role').value.trim() : '',
      });
    });

    function v(id) {
      var el = document.getElementById(id);
      return el ? String(el.value || '').trim() : '';
    }
    function ck(id) {
      var el = document.getElementById(id);
      return el ? el.checked : false;
    }

    return {
      version: version,
      confidence: {
        enabled: ck('hb-cf-enabled'),
        eyebrow: v('hb-cf-eyebrow'),
        title: v('hb-cf-title'),
        lead: v('hb-cf-lead'),
        cta_label: v('hb-cf-cta-label'),
        cta_href: v('hb-cf-cta-href'),
        metrics: metrics,
      },
      partnership: {
        enabled: ck('hb-pa-enabled'),
        kicker: v('hb-pa-kicker'),
        title: v('hb-pa-title'),
        lead: v('hb-pa-lead'),
        pull_quote: v('hb-pa-pull'),
        cta_label: v('hb-pa-cta-label'),
        cta_href: v('hb-pa-cta-href'),
      },
      operating_model: {
        enabled: ck('hb-om-enabled'),
        title: v('hb-om-title'),
        subtitle: v('hb-om-subtitle'),
        steps: steps,
        highlight: {
          title: v('hb-om-hl-title'),
          body: v('hb-om-hl-body'),
        },
      },
      packages: {
        enabled: ck('hb-pk-enabled'),
        eyebrow: v('hb-pk-eyebrow'),
        title: v('hb-pk-title'),
        subtitle: v('hb-pk-subtitle'),
        items: pkgs,
      },
      testimonials: {
        enabled: ck('hb-ts-enabled'),
        title: v('hb-ts-title'),
        subtitle: v('hb-ts-subtitle'),
        quotes: quoteRows,
      },
      newsletter: {
        enabled: ck('hb-nw-enabled'),
        title: v('hb-nw-title'),
        text: v('hb-nw-text'),
        button_label: v('hb-nw-btn'),
        placeholder: v('hb-nw-ph'),
      },
    };
  }

  if (hbRoot) {
    hbRoot.addEventListener('click', function (ev) {
      var target = ev.target;
      if (!target || typeof target.closest !== 'function') {
        return;
      }
      var btn = target.closest('.hb-row-remove');
      if (!btn || !hbRoot.contains(btn)) {
        return;
      }
      ev.preventDefault();
      var row = btn.closest('.hb-repeat-row');
      if (row && row.parentElement) {
        row.parentElement.removeChild(row);
      }
    });

    var addMetric = document.getElementById('hb-add-metric');
    if (addMetric) {
      addMetric.addEventListener('click', function () {
        hbAppendTemplate('hb-tpl-metric', 'hb-metrics');
      });
    }
    var addStep = document.getElementById('hb-add-step');
    if (addStep) {
      addStep.addEventListener('click', function () {
        hbAppendTemplate('hb-tpl-step', 'hb-om-steps');
      });
    }
    var addPkg = document.getElementById('hb-add-pkg');
    if (addPkg) {
      addPkg.addEventListener('click', function () {
        hbAppendTemplate('hb-tpl-pkg', 'hb-pk-items');
      });
    }
    var addQuote = document.getElementById('hb-add-quote');
    if (addQuote) {
      addQuote.addEventListener('click', function () {
        hbAppendTemplate('hb-tpl-quote', 'hb-quotes');
      });
    }
  }

  document.getElementById('cms-page-form').addEventListener('submit', function () {
    var payload = { html: quill.root.innerHTML };
    if (metaEl) {
      payload.meta_description = metaEl.value.trim();
    }
    if (pageSlug === 'home' && hbRoot) {
      payload.blocks = collectHomeBlocks();
    }
    if (pageSlug === 'about' && typeof window.dotseAboutBlocksCollect === 'function') {
      payload.blocks = window.dotseAboutBlocksCollect();
    }
    if (pageSlug === 'services' && typeof window.dotseServicesBlocksCollect === 'function') {
      payload.blocks = window.dotseServicesBlocksCollect();
    }
    if (pageSlug === 'contact' && typeof window.dotseContactBlocksCollect === 'function') {
      payload.blocks = window.dotseContactBlocksCollect();
    }
    if (pageSlug === 'portfolio' && typeof window.dotsePortfolioBlocksCollect === 'function') {
      payload.blocks = window.dotsePortfolioBlocksCollect();
    }
    if (pageSlug === 'rentals' && typeof window.dotseRentalsBlocksCollect === 'function') {
      payload.blocks = window.dotseRentalsBlocksCollect();
    }
    if (Array.isArray(data.slides)) {
      payload.slides = data.slides;
    }
    document.getElementById('cms-content-json').value = JSON.stringify(payload);
  });
})();
</script>
