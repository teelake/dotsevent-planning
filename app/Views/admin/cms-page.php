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

$cmsHasStructuredBlocks = ($slug === 'home' && $mergedBlocks !== null)
    || ($slug === 'about' && $mergedAboutBlocks !== null)
    || ($slug === 'services' && $mergedServicesBlocks !== null)
    || ($slug === 'contact' && $mergedContactBlocks !== null)
    || ($slug === 'portfolio' && $mergedPortfolioBlocks !== null)
    || ($slug === 'rentals' && $mergedRentalsBlocks !== null);

$cmsHasRichTextBody = $slug !== 'about';

$cmsPageSlugLabel = $slug !== '' ? $slug : 'page';
$cmsViewSiteUrl = $slug === 'home' ? app_url('') : app_url($slug);
?>
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">

<div class="cms-edit">
    <header class="cms-edit__masthead">
        <nav class="cms-edit__breadcrumb" aria-label="Breadcrumb">
            <a class="cms-edit__breadcrumb-link" href="<?= e(app_url('admin/cms/pages')) ?>">Pages &amp; content</a>
            <span class="cms-edit__breadcrumbsep" aria-hidden="true">/</span>
            <span class="cms-edit__breadcrumb-current"><?= e($cmsPageSlugLabel) ?></span>
        </nav>
        <div class="cms-edit__masthead-row">
            <div class="cms-edit__masthead-text">
                <h2 class="cms-edit__title">Edit <?= e(ucfirst(str_replace('-', ' ', $cmsPageSlugLabel))) ?></h2>
                <p class="cms-edit__slugline">
                    <span class="cms-edit__chip" title="CMS slug"><?= e($cmsPageSlugLabel) ?></span>
                    <?php if ($page_title !== '') : ?>
                        <span class="cms-edit__subtitle"><?= e($page_title) ?></span>
                    <?php endif; ?>
                </p>
            </div>
            <a class="btn btn--secondary cms-edit__site-link" href="<?= e($cmsViewSiteUrl) ?>" target="_blank" rel="noopener noreferrer">Preview live URL</a>
        </div>

        <?php if ($slug === 'home') : ?>
            <div class="cms-edit__hint">Slides: <a class="cms-edit__hint-a" href="<?= e(app_url('admin/cms/slides')) ?>">Hero carousel</a>.
                Homepage services grid uses <a class="cms-edit__hint-a" href="<?= e(app_url('admin/cms/page/services')) ?>">Services → Services catalogue</a>.</div>
        <?php elseif ($slug === 'about') : ?>
            <div class="cms-edit__hint">Use structured sections only — hero and modular bands drive the live About page.</div>
        <?php elseif ($slug === 'services') : ?>
            <div class="cms-edit__hint">Structured sections control the Services page. Under <strong>Services catalogue</strong> you can add or remove service cards (same list powers the Home teaser when enabled).</div>
        <?php elseif ($slug === 'contact') : ?>
            <div class="cms-edit__hint">Contact layout + CMS settings feed the live page.</div>
        <?php elseif ($slug === 'portfolio') : ?>
            <div class="cms-edit__hint">Featured &amp; gallery use paths under <code>/public</code>.</div>
        <?php elseif ($slug === 'rentals') : ?>
            <div class="cms-edit__hint">Inventory lives in <a class="cms-edit__hint-a" href="<?= e(app_url('admin/products')) ?>">Products</a>.</div>
        <?php endif; ?>
    </header>

    <form class="cms-edit__form admin-form" method="post" action="<?= e(app_url('admin/cms/page/' . $slug . '/save')) ?>" id="cms-page-form">
        <?= csrf_field() ?>
        <input type="hidden" name="content_json" id="cms-content-json" value="">

        <div class="cms-edit__layout">
            <div class="cms-edit__main">
                <div class="cms-edit-tabs" id="cms-edit-tabs-root">
                    <div class="cms-edit-tabs__bar">
                        <div role="tablist" class="cms-edit-tabs__list" aria-label="Page editor tabs">
                            <button type="button" role="tab" class="cms-edit-tabs__tab" id="cms-tab-essentials"
                                aria-selected="true" aria-controls="cms-panel-essentials" tabindex="0">Publishing &amp; SEO</button>
                            <?php if ($cmsHasStructuredBlocks) : ?>
                                <button type="button" role="tab" class="cms-edit-tabs__tab" id="cms-tab-blocks"
                                    aria-selected="false" aria-controls="cms-panel-blocks" tabindex="-1">Structured sections</button>
                            <?php endif; ?>
                            <?php if ($cmsHasRichTextBody) : ?>
                            <button type="button" role="tab" class="cms-edit-tabs__tab" id="cms-tab-body"
                                aria-selected="false" aria-controls="cms-panel-body" tabindex="-1">Rich text body</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="cms-panel-essentials" role="tabpanel" class="cms-edit-tabs__panel" aria-labelledby="cms-tab-essentials">
                        <section id="cms-section-essentials" class="cms-edit-panel">
                            <div class="cms-edit-panel__head">
                                <h3 class="cms-edit-panel__title">Publishing &amp; SEO</h3>
                                <p class="cms-edit-panel__desc">How this route appears to visitors and search.</p>
                            </div>
                            <div class="cms-edit-panel__body">
                                <div class="form-row">
                                    <label for="cms-page-title">Page title</label>
                                    <input class="input cms-edit-input" id="cms-page-title" name="title" type="text" value="<?= e($page_title) ?>" placeholder="Optional document title">
                                </div>
                                <div class="form-row">
                                    <label for="cms-meta-description">Meta description</label>
                                    <textarea class="input input--textarea cms-edit-input" id="cms-meta-description" rows="2" placeholder="Search snippet (~160 chars)"><?= e($meta_description_field) ?></textarea>
                                    <span class="cms-edit-field-help">Shown in Google when set; keep it persuasive and factual.</span>
                                </div>
                            </div>
                        </section>
                    </div>

                    <?php if ($cmsHasStructuredBlocks) : ?>
                        <div id="cms-panel-blocks" role="tabpanel" class="cms-edit-tabs__panel" aria-labelledby="cms-tab-blocks" hidden>
                            <div id="cms-edit-blocks-toc-wrap" class="cms-edit-blocks-toc-wrap" hidden>
                                <p class="cms-edit-blocks-toc-wrap__label">Within structured sections</p>
                                <ul class="cms-edit-blocks-toc" id="cms-edit-blocks-toc-list"></ul>
                            </div>
                            <section id="cms-section-blocks" class="cms-edit-panel cms-edit-panel--stretch">
                                <div class="cms-edit-panel__head">
                                    <h3 class="cms-edit-panel__title">Structured sections</h3>
                                    <p class="cms-edit-panel__desc">Composable blocks merged with sane defaults.</p>
                                </div>
                                <div class="cms-edit-panel__body cms-edit-panel__body--flush">
                                    <div id="cms-blocks-stage" class="cms-blocks-stage">
                                        <?php if ($slug === 'home' && $mergedBlocks !== null) : ?>
                                            <?php include __DIR__ . '/partials/home-blocks-editor.php'; ?>
                                        <?php elseif ($slug === 'about' && $mergedAboutBlocks !== null) : ?>
                                            <?php include __DIR__ . '/partials/about-blocks-editor.php'; ?>
                                        <?php elseif ($slug === 'services' && $mergedServicesBlocks !== null) : ?>
                                            <?php include __DIR__ . '/partials/services-blocks-editor.php'; ?>
                                        <?php elseif ($slug === 'contact' && $mergedContactBlocks !== null) : ?>
                                            <?php include __DIR__ . '/partials/contact-blocks-editor.php'; ?>
                                        <?php elseif ($slug === 'portfolio' && $mergedPortfolioBlocks !== null) : ?>
                                            <?php include __DIR__ . '/partials/portfolio-blocks-editor.php'; ?>
                                        <?php elseif ($slug === 'rentals' && $mergedRentalsBlocks !== null) : ?>
                                            <?php include __DIR__ . '/partials/rentals-blocks-editor.php'; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php endif; ?>

                    <?php if ($cmsHasRichTextBody) : ?>
                    <div id="cms-panel-body" role="tabpanel" class="cms-edit-tabs__panel" aria-labelledby="cms-tab-body" hidden>
                        <section id="cms-section-body" class="cms-edit-panel cms-edit-panel--stretch">
                            <div class="cms-edit-panel__head">
                                <h3 class="cms-edit-panel__title" id="cms-section-body-label">Rich text body</h3>
                                <p class="cms-edit-panel__desc">Optional narrative HTML (intro, legal, or legacy copy).</p>
                            </div>
                            <div class="cms-edit-panel__body">
                                <div class="form-row form-row--flush">
                                    <div id="editor" class="cms-edit-quill" aria-labelledby="cms-section-body-label"></div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <?php endif; ?>
                </div>

                <footer id="cms-section-save" class="cms-edit-savebar">
                    <div class="cms-edit-savebar__inner">
                        <button class="btn btn--primary cms-edit-savebar__submit" type="submit">Save all changes</button>
                        <p class="cms-edit-savebar__note">Relational fields and body save together for this slug.</p>
                    </div>
                </footer>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<?php if ($slug === 'about') : ?>
<script src="<?= e(asset('js/admin-about-blocks.js')) ?>"></script>
<?php elseif ($slug === 'services') : ?>
<script src="<?= e(asset('js/admin-services-blocks.js')) ?>"></script>
<?php elseif ($slug === 'contact') : ?>
<script src="<?= e(asset('js/admin-contact-blocks.js')) ?>"></script>
<?php elseif ($slug === 'portfolio') : ?>
<script src="<?= e(asset('js/admin-portfolio-blocks.js')) ?>"></script>
<?php elseif ($slug === 'rentals') : ?>
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
  const cmsMediaUploadUrl = <?= json_encode(app_url('admin/media/upload'), JSON_THROW_ON_ERROR) ?>;
  const cmsMediaCsrf = <?= json_encode(\App\Core\Csrf::token(), JSON_THROW_ON_ERROR) ?>;

  function cmsQuillImageHandler(quillGetter) {
    return function () {
      const input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      input.click();
      input.onchange = async function () {
        const q = quillGetter();
        if (!q) return;
        const file = input.files && input.files[0];
        if (!file) return;
        const fd = new FormData();
        fd.append('file', file);
        fd.append('_csrf', cmsMediaCsrf);
        const res = await fetch(cmsMediaUploadUrl, {
          method: 'POST',
          body: fd,
          credentials: 'same-origin',
        });
        const out = await res.json().catch(function () { return null; });
        if (!out || !out.ok || !out.url) {
          alert((out && out.error) ? out.error : 'Upload failed');
          return;
        }
        const range = q.getSelection(true);
        q.insertEmbed(range.index, 'image', out.url);
      };
    };
  }

  let quill = null;
  let quillAboutLead = null;
  function initQuill() {
    if (quill !== null) {
      return quill;
    }
    quill = new Quill('#editor', {
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
            image: cmsQuillImageHandler(function () { return initQuill(); }),
          },
        },
      },
    });
    const htmlSeed = typeof data.html === 'string' ? data.html : '';
    if (htmlSeed) {
      quill.root.innerHTML = htmlSeed;
    }
    return quill;
  }

  function syncAboutApproachLeadQuill() {
    if (quillAboutLead === null) return;
    var ta = document.getElementById('ab-ap-lead');
    if (ta) ta.value = quillAboutLead.root.innerHTML;
  }

  function initAboutApproachLeadQuill() {
    if (pageSlug !== 'about' || quillAboutLead !== null) {
      return quillAboutLead;
    }
    var panel = document.getElementById('cms-panel-blocks');
    if (panel && panel.hasAttribute('hidden')) {
      return null;
    }
    var apDetails = document.getElementById('cms-sec-ab-approach');
    if (!apDetails || !apDetails.open) {
      return null;
    }
    var el = document.getElementById('ab-ap-lead-editor');
    if (!el || typeof Quill === 'undefined') {
      return null;
    }
    quillAboutLead = new Quill('#ab-ap-lead-editor', {
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
            image: cmsQuillImageHandler(function () { return quillAboutLead; }),
          },
        },
      },
    });
    var ta = document.getElementById('ab-ap-lead');
    if (ta && ta.value) {
      quillAboutLead.root.innerHTML = ta.value;
    }
    quillAboutLead.on('text-change', function () {
      if (ta) ta.value = quillAboutLead.root.innerHTML;
    });
    if (ta) ta.value = quillAboutLead.root.innerHTML;
    return quillAboutLead;
  }

  if (pageSlug === 'about' && typeof window.dotseAboutBlocksBind === 'function') {
    window.dotseAboutBlocksBind({
      uploadUrl: <?= json_encode(app_url('admin/media/upload'), JSON_THROW_ON_ERROR) ?>,
      csrf: <?= json_encode(\App\Core\Csrf::token(), JSON_THROW_ON_ERROR) ?>,
      publicBase: <?= json_encode(rtrim(app_url(''), '/'), JSON_THROW_ON_ERROR) ?>,
    });
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

  function populateBlocksAnchors() {
    var list = document.getElementById('cms-edit-blocks-toc-list');
    var wrap = document.getElementById('cms-edit-blocks-toc-wrap');
    var stage = document.getElementById('cms-blocks-stage');
    if (!list || !stage) {
      return;
    }

    /** @type {{ href: string, label: string }[]} */
    var links = [];
    stage.querySelectorAll('details.home-blocks-editor__details[id], details.hb-section[id]').forEach(function (d) {
      var idAttr = d.getAttribute('id');
      var summary = d.querySelector('summary');
      var lbl = summary && summary.textContent ? summary.textContent.trim() : '';
      if (!idAttr || lbl === '') return;
      links.push({ href: '#' + idAttr, label: lbl });
    });

    list.innerHTML = '';
    links.forEach(function (l) {
      var li = document.createElement('li');
      var a = document.createElement('a');
      a.className = 'cms-edit-blocks-toc__link';
      a.href = l.href;
      a.textContent = l.label;
      a.addEventListener('click', function (e) {
        e.preventDefault();
        var el = document.getElementById(l.href.slice(1));
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        try {
          history.replaceState(null, '', l.href);
        } catch (_) {}
      });
      li.appendChild(a);
      list.appendChild(li);
    });

    if (wrap) wrap.hidden = links.length === 0;
  }

  function initCmsTabs(onBodyShown) {
    var root = document.getElementById('cms-edit-tabs-root');
    if (!root) return;
    var list = root.querySelector('[role=\"tablist\"]');
    if (!list) return;
    /** @type {HTMLButtonElement[]} */
    var tabs = Array.prototype.slice.call(list.querySelectorAll('[role=\"tab\"]'));
    if (!tabs.length) return;

    function panelFor(btn) {
      var pid = btn.getAttribute('aria-controls');
      return pid ? document.getElementById(pid) : null;
    }

    function select(ix) {
      var i = Math.max(0, Math.min(ix, tabs.length - 1));
      tabs.forEach(function (t, ti) {
        var on = ti === i;
        t.setAttribute('aria-selected', on ? 'true' : 'false');
        t.tabIndex = on ? 0 : -1;
        var panel = panelFor(t);
        if (panel) {
          if (on) panel.removeAttribute('hidden');
          else panel.setAttribute('hidden', '');
        }
      });
      if (tabs[i].id === 'cms-tab-body' && typeof onBodyShown === 'function') {
        onBodyShown();
      }
      if (tabs[i].id === 'cms-tab-blocks') {
        hbMountAllPkgFeatEditors();
        if (pageSlug === 'about') {
          initAboutApproachLeadQuill();
        }
      }
    }

    tabs.forEach(function (tab, ix) {
      tab.addEventListener('click', function () {
        select(ix);
        tab.focus();
      });
    });

    list.addEventListener('keydown', function (e) {
      var active = tabs.indexOf(/** @type {HTMLButtonElement} */ (document.activeElement));
      if (active < 0) return;
      if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
        e.preventDefault();
        var next = active + 1 >= tabs.length ? 0 : active + 1;
        select(next);
        tabs[next].focus();
      } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
        e.preventDefault();
        var prev = active === 0 ? tabs.length - 1 : active - 1;
        select(prev);
        tabs[prev].focus();
      } else if (e.key === 'Home') {
        e.preventDefault();
        select(0);
        tabs[0].focus();
      } else if (e.key === 'End') {
        e.preventDefault();
        select(tabs.length - 1);
        tabs[tabs.length - 1].focus();
      }
    });

    var start = tabs.findIndex(function (t) { return t.getAttribute('aria-selected') === 'true'; });
    if (start < 0) start = 0;
    select(start);
  }

  populateBlocksAnchors();
  initCmsTabs(pageSlug === 'about' ? function () {} : initQuill);

  function hbAppendTemplate(tplId, containerId) {
    const tpl = document.getElementById(tplId);
    const c = document.getElementById(containerId);
    if (!tpl || !c || !tpl.content) {
      return;
    }
    c.appendChild(document.importNode(tpl.content, true));
  }

  function hbPkgFeatHtmlIsEmpty(html) {
    var d = document.createElement('div');
    d.innerHTML = html || '';
    var t = (d.textContent || '').replace(/\u00a0/g, ' ').trim();
    return t === '';
  }

  function hbSyncPkgFeatEditorsToHidden() {
    if (!hbRoot || typeof Quill === 'undefined') {
      return;
    }
    hbRoot.querySelectorAll('.js-hb-pkg-row').forEach(function (row) {
      var ta = row.querySelector('.js-pkg-feats-html');
      var mount = row.querySelector('.js-pkg-feats-quill');
      if (!ta || !mount) {
        return;
      }
      try {
        var q = Quill.find(mount);
        if (q) {
          ta.value = q.root.innerHTML;
        }
      } catch (e) {}
    });
  }

  function hbMountPkgFeatEditor(row) {
    if (!row || typeof Quill === 'undefined') {
      return;
    }
    var ta = row.querySelector('.js-pkg-feats-html');
    var mount = row.querySelector('.js-pkg-feats-quill');
    if (!ta || !mount || mount.getAttribute('data-hb-pkg-feats-mounted') === '1') {
      return;
    }
    mount.setAttribute('data-hb-pkg-feats-mounted', '1');
    var q = new Quill(mount, {
      theme: 'snow',
      modules: {
        toolbar: [
          [{ header: [false, 3, 4] }],
          ['bold', 'italic', 'underline'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['link'],
          ['clean'],
        ],
      },
    });
    var seed = ta.value != null ? String(ta.value).trim() : '';
    if (seed !== '') {
      q.root.innerHTML = seed;
    }
    q.on('text-change', function () {
      ta.value = q.root.innerHTML;
    });
    ta.value = q.root.innerHTML;
  }

  function hbMountAllPkgFeatEditors() {
    if (!hbRoot || typeof Quill === 'undefined') {
      return;
    }
    hbRoot.querySelectorAll('.js-hb-pkg-row').forEach(hbMountPkgFeatEditor);
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

    var pkgs = [];
    hbRoot.querySelectorAll('.js-hb-pkg-row').forEach(function (row) {
      var ta = row.querySelector('.js-pkg-feats-html');
      var rawHtml = ta ? String(ta.value || '').trim() : '';
      if (hbPkgFeatHtmlIsEmpty(rawHtml)) {
        rawHtml = '';
      }
      var fp = row.querySelector('.js-pkg-featured');
      var pkg = {
        name: row.querySelector('.js-pkg-name') ? row.querySelector('.js-pkg-name').value.trim() : '',
        price_display: row.querySelector('.js-pkg-price') ? row.querySelector('.js-pkg-price').value.trim() : '',
        featured: fp ? fp.checked : false,
        cta_label: row.querySelector('.js-pkg-cta-l') ? row.querySelector('.js-pkg-cta-l').value.trim() : '',
        cta_href: row.querySelector('.js-pkg-cta-h') ? row.querySelector('.js-pkg-cta-h').value.trim() : '',
      };
      if (rawHtml !== '') {
        pkg.features_html = rawHtml;
      }
      var bkEl = row.querySelector('.js-pkg-booking');
      var bk = bkEl && bkEl.value ? bkEl.value.trim() : '';
      if (bk !== '') {
        pkg.booking_package = bk;
      }
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

  if (pageSlug === 'about') {
    var apDetailsLead = document.getElementById('cms-sec-ab-approach');
    if (apDetailsLead) {
      apDetailsLead.addEventListener('toggle', function () {
        if (apDetailsLead.open) {
          initAboutApproachLeadQuill();
        }
      });
    }
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
    var addPkg = document.getElementById('hb-add-pkg');
    if (addPkg) {
      addPkg.addEventListener('click', function () {
        hbAppendTemplate('hb-tpl-pkg', 'hb-pk-items');
        var list = document.getElementById('hb-pk-items');
        var rows = list ? list.querySelectorAll('.js-hb-pkg-row') : [];
        var last = rows.length ? rows[rows.length - 1] : null;
        if (last) {
          hbMountPkgFeatEditor(last);
        }
      });
    }
    hbMountAllPkgFeatEditors();
    var pkDetails = document.getElementById('cms-sec-hb-packages');
    if (pkDetails) {
      pkDetails.addEventListener('toggle', function () {
        if (pkDetails.open) {
          hbMountAllPkgFeatEditors();
        }
      });
    }
    var addQuote = document.getElementById('hb-add-quote');
    if (addQuote) {
      addQuote.addEventListener('click', function () {
        hbAppendTemplate('hb-tpl-quote', 'hb-quotes');
      });
    }
  }

  document.getElementById('cms-page-form').addEventListener('submit', function (ev) {
    var payload = { html: pageSlug === 'about' ? '' : initQuill().root.innerHTML };
    if (metaEl) {
      payload.meta_description = metaEl.value.trim();
    }
    if (pageSlug === 'home' && hbRoot) {
      hbSyncPkgFeatEditorsToHidden();
      payload.blocks = collectHomeBlocks();
    }
    if (pageSlug === 'about') {
      syncAboutApproachLeadQuill();
    }
    if (pageSlug === 'about' && typeof window.dotseAboutBlocksCollect === 'function') {
      payload.blocks = window.dotseAboutBlocksCollect();
    }
    if (pageSlug === 'services') {
      if (typeof window.dotseServicesBlocksCollect !== 'function') {
        ev.preventDefault();
        alert('Services editor script failed to load. Refresh this page and try again.');
        return;
      }
      if (!document.getElementById('services-blocks-editor')) {
        ev.preventDefault();
        alert('Services structured sections are missing from the page. Refresh and try again.');
        return;
      }
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
