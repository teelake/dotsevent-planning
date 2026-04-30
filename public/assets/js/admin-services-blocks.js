/* Admin: Services page structured blocks */
(function () {
  "use strict";

  function tpl(id) {
    var t = document.getElementById(id);
    return t && t.content ? t.content.cloneNode(true) : null;
  }

  function append(tplId, go) {
    var frag = tpl(tplId);
    var c = document.getElementById(go);
    if (!frag || !c) {
      return;
    }
    c.appendChild(frag);
  }

  function val(id) {
    var el = document.getElementById(id);
    return el ? String(el.value || "").trim() : "";
  }

  function ck(id) {
    var el = document.getElementById(id);
    return el ? el.checked : false;
  }

  window.dotseServicesBlocksBind = function () {
    var root = document.getElementById("services-blocks-editor");
    if (!root) {
      return;
    }
    root.addEventListener("click", function (ev) {
      var btn = ev.target.closest(".svc-row-remove");
      if (!btn || !root.contains(btn)) {
        return;
      }
      ev.preventDefault();
      var row = btn.closest(".svc-repeat-row");
      if (row && row.parentElement) {
        row.parentElement.removeChild(row);
      }
    });
    var pairs = [
      ["svc-add-offer", "svc-tpl-offer", "svc-offer-rows"],
      ["svc-add-metric", "svc-tpl-metric", "svc-metrics"],
      ["svc-add-faq", "svc-tpl-faq", "svc-faq-rows"],
    ];
    pairs.forEach(function (p) {
      var b = document.getElementById(p[0]);
      if (b) {
        b.addEventListener("click", function () {
          append(p[1], p[2]);
        });
      }
    });
  };

  window.dotseServicesBlocksCollect = function () {
    var root = document.getElementById("services-blocks-editor");
    if (!root) {
      return {};
    }
    var ver = parseInt(val("svc-version"), 10);
    var v = val;
    var c = ck;

    function offerings() {
      var out = [];
      root.querySelectorAll(".js-svc-off-row").forEach(function (row) {
        var o = {
          title: row.querySelector(".js-svc-off-title") ? row.querySelector(".js-svc-off-title").value.trim() : "",
          summary_html: row.querySelector(".js-svc-off-sum") ? row.querySelector(".js-svc-off-sum").value.trim() : "",
          href: row.querySelector(".js-svc-off-href") ? row.querySelector(".js-svc-off-href").value.trim() : "",
        };
        var a = row.querySelector(".js-svc-off-accent");
        var m = row.querySelector(".js-svc-off-muted");
        if (a && a.checked) {
          o.accent = true;
        }
        if (m && m.checked) {
          o.muted = true;
        }
        out.push(o);
      });
      return out;
    }

    function metrics() {
      var out = [];
      root.querySelectorAll(".js-svc-metric-row").forEach(function (row) {
        var label = row.querySelector(".js-scm-label");
        var display = row.querySelector(".js-scm-display");
        var targetEl = row.querySelector(".js-scm-target");
        var suf = row.querySelector(".js-scm-suffix");
        var tn = parseInt(targetEl && targetEl.value ? targetEl.value : "0", 10);
        out.push({
          label: label ? label.value.trim() : "",
          display: display ? display.value.trim() : "",
          target: isNaN(tn) ? 0 : tn,
          suffix: suf && suf.value.trim() !== "" ? suf.value.trim() : "+",
        });
      });
      return out;
    }

    function faqItems() {
      var out = [];
      root.querySelectorAll(".js-svc-faq-row").forEach(function (row) {
        out.push({
          question: row.querySelector(".js-scf-q") ? row.querySelector(".js-scf-q").value.trim() : "",
          answer_html: row.querySelector(".js-scf-a") ? row.querySelector(".js-scf-a").value.trim() : "",
        });
      });
      return out;
    }

    return {
      version: isNaN(ver) ? 1 : ver,
      hero: {
        enabled: c("svc-hero-en"),
        show_breadcrumbs: c("svc-hero-bc"),
        kicker: v("svc-hero-kicker"),
        title: v("svc-hero-title"),
      },
      intro: {
        enabled: c("svc-in-en"),
        eyebrow: v("svc-in-eye"),
        title: v("svc-in-title"),
        lead_html: v("svc-in-lead"),
      },
      offerings: {
        enabled: c("svc-of-en"),
        home_teaser_enabled: c("svc-of-home-teaser-en"),
        eyebrow: v("svc-of-eye"),
        section_title: v("svc-of-stitle"),
        home_teaser_cta_label: v("svc-of-home-cta-label"),
        home_teaser_cta_href: v("svc-of-home-cta-href"),
        items: offerings(),
      },
      partnership: {
        enabled: c("svc-pa-en"),
        title: v("svc-pa-title"),
        lead_html: v("svc-pa-lead"),
        cta_label: v("svc-pa-cta"),
        cta_href: v("svc-pa-href"),
        metrics: metrics(),
      },
      faq: {
        enabled: c("svc-faq-en"),
        eyebrow: v("svc-faq-eye"),
        title: v("svc-faq-title"),
        lead_html: v("svc-faq-lead"),
        open_first: c("svc-faq-openfirst"),
        items: faqItems(),
      },
      newsletter_cta: {
        enabled: c("svc-nw-en"),
        title: v("svc-nw-title"),
        text_html: v("svc-nw-text"),
        button_label: v("svc-nw-btn"),
        placeholder: v("svc-nw-ph"),
      },
    };
  };
})();
