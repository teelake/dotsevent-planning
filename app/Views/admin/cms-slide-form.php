<?php
declare(strict_types=1);
/** @var array<string, mixed>|null $slide */
/** @var string $upload_action */
/** @var string $csrf_token */
$slide = $slide ?? null;
$upload_action = $upload_action ?? app_url('admin/media/upload');
$csrf_token = $csrf_token ?? \App\Core\Csrf::token();

$id = $slide !== null ? (int) ($slide['id'] ?? 0) : 0;
$isLive = $slide === null ? true : !empty($slide['is_live']);
$badge = $slide !== null ? (string) ($slide['badge'] ?? '') : '';
$headline = $slide !== null ? (string) ($slide['headline'] ?? '') : '';
$supporting = $slide !== null ? (string) ($slide['supporting'] ?? '') : '';
$imageAlt = $slide !== null ? (string) ($slide['image_alt'] ?? '') : '';
$deskPath = $slide !== null ? (string) ($slide['image_desktop_path'] ?? '') : '';
$mobPath = $slide !== null ? trim((string) ($slide['image_mobile_path'] ?? '')) : '';
$pLabel = $slide !== null ? (string) ($slide['btn_primary_label'] ?? '') : '';
$pHref = $slide !== null ? (string) ($slide['btn_primary_href'] ?? '') : '';
$sLabel = $slide !== null ? (string) ($slide['btn_secondary_label'] ?? '') : '';
$sHref = $slide !== null ? (string) ($slide['btn_secondary_href'] ?? '') : '';
$startsRaw = $slide !== null && !empty($slide['starts_at']) ? (string) $slide['starts_at'] : '';
$endsRaw = $slide !== null && !empty($slide['ends_at']) ? (string) $slide['ends_at'] : '';
$toLocal = static function (string $sql): string {
    $t = strtotime($sql);

    return $t !== false ? date('Y-m-d\TH:i', $t) : '';
};
$startsLocal = $startsRaw !== '' ? $toLocal($startsRaw) : '';
$endsLocal = $endsRaw !== '' ? $toLocal($endsRaw) : '';

$deskPreview = $deskPath !== '' ? app_url(ltrim($deskPath, '/')) : '';
$mobPreview = $mobPath !== '' ? app_url(ltrim($mobPath, '/')) : '';
?>
<section class="section--tight slide-form-page" style="padding-top: 0;">
    <header class="slide-form-page__header">
        <div>
            <h2 class="slide-form-page__title"><?= $id > 0 ? 'Edit slide' : 'New slide' ?></h2>
            <p class="slide-form-page__subtitle">Add a full-bleed headline, optional badge, buttons, and a primary desktop photo. Leave schedule empty to show whenever the slide is live.</p>
        </div>
        <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/slides')) ?>">All slides</a>
    </header>

    <form class="slide-form" method="post" action="<?= e(app_url('admin/cms/slide/save')) ?>" id="slide-form">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">

        <div class="slide-form__grid">
            <div class="slide-form__main">
                <div class="slide-form-card">
                    <div class="slide-form-card__head">
                        <span class="slide-form-card__icon" aria-hidden="true">◉</span>
                        <div>
                            <h3 class="slide-form-card__title">Visibility</h3>
                            <p class="slide-form-card__hint">Draft slides stay in the admin; they never show on the storefront.</p>
                        </div>
                    </div>
                    <label class="slide-form-toggle">
                        <input type="checkbox" name="is_live" value="1"<?= $isLive ? ' checked' : '' ?>>
                        <span>Show this slide on the live site (when scheduled)</span>
                    </label>
                </div>

                <div class="slide-form-card">
                    <div class="slide-form-card__head">
                        <span class="slide-form-card__icon" aria-hidden="true">≡</span>
                        <div>
                            <h3 class="slide-form-card__title">Story &amp; copy</h3>
                            <p class="slide-form-card__hint">Keep lines tight for the hero layout.</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="sf-badge">Badge <span class="text-muted">(optional · max 24)</span></label>
                        <input class="input" id="sf-badge" name="badge" type="text" maxlength="24" value="<?= e($badge) ?>" placeholder="e.g. Saint John &amp; beyond" data-count="24">
                        <span class="slide-form-count" data-for="sf-badge">0 / 24</span>
                    </div>
                    <div class="form-row">
                        <label for="sf-headline">Headline <span class="text-muted">(required · max 160)</span></label>
                        <input class="input" id="sf-headline" name="headline" type="text" maxlength="160" required value="<?= e($headline) ?>" placeholder="Primary message" data-count="160">
                        <span class="slide-form-count" data-for="sf-headline">0 / 160</span>
                    </div>
                    <div class="form-row">
                        <label for="sf-supporting">Supporting line <span class="text-muted">(optional · max 200)</span></label>
                        <textarea class="input input--textarea" id="sf-supporting" name="supporting" rows="3" maxlength="200" placeholder="One or two sentences" data-count="200"><?= e($supporting) ?></textarea>
                        <span class="slide-form-count" data-for="sf-supporting">0 / 200</span>
                    </div>
                    <div class="form-row">
                        <label for="sf-alt">Image description (alt text) <span class="text-muted">(optional)</span></label>
                        <input class="input" id="sf-alt" name="image_alt" type="text" maxlength="255" value="<?= e($imageAlt) ?>" placeholder="Describe the photo for accessibility">
                    </div>
                </div>

                <div class="slide-form-card">
                    <div class="slide-form-card__head">
                        <span class="slide-form-card__icon" aria-hidden="true">↗</span>
                        <div>
                            <h3 class="slide-form-card__title">Buttons</h3>
                            <p class="slide-form-card__hint">Use paths like <code>/book</code> or full <code>https://</code> URLs. Leave a label empty to hide that button.</p>
                        </div>
                    </div>
                    <div class="slide-form-two-col">
                        <div>
                            <h4 class="slide-form-card__sub">Primary</h4>
                            <div class="form-row">
                                <label for="sf-p-label">Label <span class="text-muted">(max 24)</span></label>
                                <input class="input" id="sf-p-label" name="btn_primary_label" type="text" maxlength="24" value="<?= e($pLabel) ?>" placeholder="e.g. Start with a call" data-count="24">
                                <span class="slide-form-count" data-for="sf-p-label">0 / 24</span>
                            </div>
                            <div class="form-row">
                                <label for="sf-p-href">Link</label>
                                <input class="input" id="sf-p-href" name="btn_primary_href" type="text" value="<?= e($pHref) ?>" placeholder="/book">
                            </div>
                        </div>
                        <div>
                            <h4 class="slide-form-card__sub">Secondary</h4>
                            <div class="form-row">
                                <label for="sf-s-label">Label <span class="text-muted">(max 24)</span></label>
                                <input class="input" id="sf-s-label" name="btn_secondary_label" type="text" maxlength="24" value="<?= e($sLabel) ?>" placeholder="e.g. View services" data-count="24">
                                <span class="slide-form-count" data-for="sf-s-label">0 / 24</span>
                            </div>
                            <div class="form-row">
                                <label for="sf-s-href">Link</label>
                                <input class="input" id="sf-s-href" name="btn_secondary_href" type="text" value="<?= e($sHref) ?>" placeholder="/services">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="slide-form__aside">
                <div class="slide-form-card">
                    <div class="slide-form-card__head">
                        <span class="slide-form-card__icon" aria-hidden="true">▣</span>
                        <div>
                            <h3 class="slide-form-card__title">Imagery</h3>
                            <p class="slide-form-card__hint">Wide landscape works best. Files go to <code>/public/uploads/</code>.</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <label>Desktop <span class="text-muted">(required)</span></label>
                        <div class="slide-dropzone" data-dropzone>
                            <input type="file" class="slide-dropzone__input" accept="image/*" data-upload-target="image_desktop_path" aria-label="Upload desktop image">
                            <input type="hidden" name="image_desktop_path" id="image_desktop_path" value="<?= e($deskPath) ?>" required>
                            <div class="slide-dropzone__ui">
                                <?php if ($deskPreview !== ''): ?>
                                    <img src="<?= e($deskPreview) ?>" alt="" class="slide-dropzone__preview" data-preview-desk>
                                <?php else: ?>
                                    <span class="slide-dropzone__placeholder" data-preview-desk-wrap>Upload desktop image · JPG, PNG, or WEBP</span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn--secondary slide-dropzone__pick">Pick file</button>
                        </div>
                    </div>
                    <div class="form-row">
                        <label>Mobile <span class="text-muted">(optional)</span></label>
                        <p class="slide-form-card__hint" style="margin:0 0 0.5rem;">If you skip this, the desktop image is used on all breakpoints.</p>
                        <div class="slide-dropzone slide-dropzone--compact" data-dropzone>
                            <input type="file" class="slide-dropzone__input" accept="image/*" data-upload-target="image_mobile_path" aria-label="Upload mobile image">
                            <input type="hidden" name="image_mobile_path" id="image_mobile_path" value="<?= e($mobPath) ?>">
                            <div class="slide-dropzone__ui">
                                <?php if ($mobPreview !== ''): ?>
                                    <img src="<?= e($mobPreview) ?>" alt="" class="slide-dropzone__preview" data-preview-mob>
                                <?php else: ?>
                                    <span class="slide-dropzone__placeholder" data-preview-mob-wrap>Portrait or tight crop</span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn--secondary slide-dropzone__pick">Pick file</button>
                        </div>
                    </div>
                </div>

                <div class="slide-form-card">
                    <div class="slide-form-card__head">
                        <span class="slide-form-card__icon" aria-hidden="true">◐</span>
                        <div>
                            <h3 class="slide-form-card__title">Schedule</h3>
                            <p class="slide-form-card__hint">Leave both empty to run whenever the slide is live. End must be after start.</p>
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="sf-starts">Starts</label>
                        <input class="input" id="sf-starts" name="starts_at" type="datetime-local" value="<?= e($startsLocal) ?>">
                    </div>
                    <div class="form-row">
                        <label for="sf-ends">Ends</label>
                        <input class="input" id="sf-ends" name="ends_at" type="datetime-local" value="<?= e($endsLocal) ?>">
                    </div>
                </div>
            </aside>
        </div>

        <div class="slide-form__footer">
            <p class="slide-form__footer-note text-muted">Saving updates the storefront on the next page load.</p>
            <div class="slide-form__footer-actions">
                <a class="btn btn--secondary" href="<?= e(app_url('admin/cms/slides')) ?>">Cancel</a>
                <button class="btn btn--primary" type="submit">Save changes</button>
            </div>
        </div>
    </form>
</section>

<script>
(function () {
  const uploadUrl = <?= json_encode($upload_action, JSON_THROW_ON_ERROR) ?>;
  const csrf = <?= json_encode($csrf_token, JSON_THROW_ON_ERROR) ?>;

  function len(s) {
    return s ? s.length : 0;
  }
  document.querySelectorAll('[data-count]').forEach(function (el) {
    var max = parseInt(el.getAttribute('data-count'), 10);
    var counter = document.querySelector('.slide-form-count[data-for="' + el.id + '"]');
    function refresh() {
      if (counter) counter.textContent = len(el.value) + ' / ' + max;
    }
    el.addEventListener('input', refresh);
    refresh();
  });

  document.querySelectorAll('[data-dropzone]').forEach(function (zone) {
    var input = zone.querySelector('.slide-dropzone__input');
    var pick = zone.querySelector('.slide-dropzone__pick');
    var targetId = input.getAttribute('data-upload-target');
    var hidden = document.getElementById(targetId);
    if (pick) pick.addEventListener('click', function () { input.click(); });
    input.addEventListener('change', async function () {
      if (!input.files || !input.files[0]) return;
      var fd = new FormData();
      fd.append('file', input.files[0]);
      fd.append('_csrf', csrf);
      var res = await fetch(uploadUrl, { method: 'POST', body: fd, credentials: 'same-origin' });
      var data = await res.json().catch(function () { return null; });
      if (!data || !data.ok || !data.path) {
        alert((data && data.error) ? data.error : 'Upload failed');
        return;
      }
      hidden.value = data.path;
      var img = zone.querySelector('.slide-dropzone__preview');
      if (img) {
        img.src = data.url;
      } else {
        var ui = zone.querySelector('.slide-dropzone__ui');
        var ph = zone.querySelector('.slide-dropzone__placeholder');
        if (ph) ph.remove();
        var n = document.createElement('img');
        n.className = 'slide-dropzone__preview';
        n.alt = '';
        n.src = data.url;
        ui.insertBefore(n, ui.firstChild);
      }
    });
  });
})();
</script>
