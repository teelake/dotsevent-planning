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

$storedBlocks = null;
if (is_array($dataPreview) && isset($dataPreview['blocks']) && is_array($dataPreview['blocks'])) {
    $storedBlocks = $dataPreview['blocks'];
}
$mergedBlocks = null;
if ($slug === 'home') {
    $mergedBlocks = \App\Services\HomePageBlocks::merged($storedBlocks);
}
$mergedAboutBlocks = null;
if ($slug === 'about') {
    $mergedAboutBlocks = \App\Services\AboutPageBlocks::merged($storedBlocks);
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
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Hero slides: <a class="text-link" href="<?= e(app_url('admin/cms/slides')) ?>">Hero carousel</a>. Edit intro HTML, meta description, and the structured homepage sections below. Changes are saved as <code>content_json.blocks</code> together with the rich-text body.</p>
        <?php elseif ($slug === 'about'): ?>
            <p class="text-muted" style="font-size: 0.88rem; margin: 0 0 1rem;">Structured About page content is saved under <code>content_json.blocks</code>. The rich-text “Body” field remains available for legacy copy; public About uses the modular sections below.</p>
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
                <span class="text-muted" style="font-size:0.85rem;">Stored in page JSON; overrides the default for this route.</span>
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
      metrics.push({
        label: label ? label.value.trim() : '',
        display: display ? display.value.trim() : '',
        target: isNaN(tn) ? 0 : tn,
        suffix: suffix && suffix.value.trim() !== '' ? suffix.value.trim() : '+',
      });
    });

    var clusters = [];
    hbRoot.querySelectorAll('.js-hb-cluster-row').forEach(function (row) {
      var t = row.querySelector('.js-cluster-title');
      var x = row.querySelector('.js-cluster-text');
      var o = {
        title: t ? t.value.trim() : '',
        text: x ? x.value.trim() : '',
      };
      var ac = row.querySelector('.js-cluster-accent');
      var mu = row.querySelector('.js-cluster-muted');
      if (ac && ac.checked) {
        o.accent = true;
      }
      if (mu && mu.checked) {
        o.muted = true;
      }
      clusters.push(o);
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
      clusters: {
        enabled: ck('hb-cl-enabled'),
        eyebrow: v('hb-cl-eyebrow'),
        title: v('hb-cl-title'),
        link_all_label: v('hb-cl-link-label'),
        link_all_href: v('hb-cl-link-href'),
        items: clusters,
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
      var btn = ev.target.closest('.hb-row-remove');
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
    var addCluster = document.getElementById('hb-add-cluster');
    if (addCluster) {
      addCluster.addEventListener('click', function () {
        hbAppendTemplate('hb-tpl-cluster', 'hb-cluster-rows');
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
    if (Array.isArray(data.slides)) {
      payload.slides = data.slides;
    }
    document.getElementById('cms-content-json').value = JSON.stringify(payload);
  });
})();
</script>
