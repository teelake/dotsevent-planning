/* Admin: Portfolio page structured blocks */
(function () {
  "use strict";

  function v(id) {
    var el = document.getElementById(id);
    return el ? String(el.value || "").trim() : "";
  }
  function c(id) {
    var el = document.getElementById(id);
    return el ? !!el.checked : false;
  }
  function append(templateId, targetId, cls) {
    var tpl = document.getElementById(templateId);
    var tgt = document.getElementById(targetId);
    if (!tpl || !tpl.content || !tgt) return;
    var frag = tpl.content.cloneNode(true);
    var row = frag.querySelector(".js-pf-item");
    if (row && cls) {
      row.classList.remove("js-pf-item");
      row.classList.add(cls);
    }
    tgt.appendChild(frag);
  }

  function collectRows(sel) {
    var out = [];
    document.querySelectorAll(sel).forEach(function (row) {
      out.push({
        title: row.querySelector(".js-pf-title") ? row.querySelector(".js-pf-title").value.trim() : "",
        tag: row.querySelector(".js-pf-tag") ? row.querySelector(".js-pf-tag").value.trim() : "",
        summary: row.querySelector(".js-pf-summary") ? row.querySelector(".js-pf-summary").value.trim() : "",
        image_path: row.querySelector(".js-pf-image") ? row.querySelector(".js-pf-image").value.trim() : "",
        alt: row.querySelector(".js-pf-alt") ? row.querySelector(".js-pf-alt").value.trim() : "",
      });
    });
    return out;
  }

  window.dotsePortfolioBlocksBind = function () {
    var root = document.getElementById("portfolio-blocks-editor");
    if (!root) return;
    root.addEventListener("click", function (ev) {
      var rm = ev.target.closest(".pf-row-remove");
      if (rm) {
        ev.preventDefault();
        var row = rm.closest(".pf-repeat-row");
        if (row && row.parentElement) row.parentElement.removeChild(row);
      }
    });
    var af = document.getElementById("pf-add-featured");
    if (af) af.addEventListener("click", function () { append("pf-tpl-item", "pf-featured-items", "js-pf-featured-item"); });
    var ag = document.getElementById("pf-add-gallery");
    if (ag) ag.addEventListener("click", function () { append("pf-tpl-item", "pf-gallery-items", "js-pf-gallery-item"); });
  };

  window.dotsePortfolioBlocksCollect = function () {
    return {
      version: 1,
      hero: {
        enabled: c("pf-hero-en"),
        show_breadcrumbs: c("pf-hero-bc"),
        kicker: v("pf-hero-kicker"),
      },
      intro: {
        enabled: true,
        eyebrow: v("pf-intro-eye"),
        title: v("pf-intro-title"),
        lead_html: v("pf-intro-lead"),
      },
      controls: {
        enabled: c("pf-ctrl-en"),
        show_search: c("pf-ctrl-search"),
        show_sort: c("pf-ctrl-sort"),
        default_sort: v("pf-ctrl-default"),
      },
      featured: {
        enabled: c("pf-ft-en"),
        title: v("pf-ft-title"),
        subtitle: v("pf-ft-subtitle"),
        items: collectRows(".js-pf-featured-item"),
      },
      gallery: {
        enabled: c("pf-gal-en"),
        title: v("pf-gal-title"),
        items: collectRows(".js-pf-gallery-item"),
      },
      newsletter_cta: {
        enabled: c("pf-nw-en"),
        title: v("pf-nw-title"),
        text_html: v("pf-nw-text"),
        button_label: v("pf-nw-btn"),
        placeholder: v("pf-nw-ph"),
      },
    };
  };
})();

