/* Admin: About page structured blocks collector + repeater binds */
(function () {
  "use strict";

  function tpl(id) {
    var t = document.getElementById(id);
    if (!t || !t.content) {
      return null;
    }
    return t.content.cloneNode(true);
  }

  function append(id, containerId) {
    var frag = tpl(id);
    var c = document.getElementById(containerId);
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

  window.dotseAboutBlocksBind = function dotseAboutBlocksBind() {
    var root = document.getElementById("about-blocks-editor");
    if (!root) {
      return;
    }

    root.addEventListener("click", function (ev) {
      var btn = ev.target.closest(".ab-row-remove");
      if (!btn || !root.contains(btn)) {
        return;
      }
      ev.preventDefault();
      var row = btn.closest(".ab-repeat-row");
      if (row && row.parentElement) {
        row.parentElement.removeChild(row);
      }
    });

    var map = [
      ["ab-add-chapter", "ab-tpl-chapter", "ab-story-chapters"],
      ["ab-add-metric", "ab-tpl-metric", "ab-metrics"],
      ["ab-add-img", "ab-tpl-img", "ab-img-rows"],
      ["ab-add-value", "ab-tpl-value", "ab-values"],
      ["ab-add-member", "ab-tpl-member", "ab-members"],
    ];

    map.forEach(function (x) {
      var b = document.getElementById(x[0]);
      if (b) {
        b.addEventListener("click", function () {
          append(x[1], x[2]);
        });
      }
    });
  };

  window.dotseAboutBlocksCollect = function dotseAboutBlocksCollect() {
    var root = document.getElementById("about-blocks-editor");
    if (!root) {
      return {};
    }

    var v = val;
    var c = ck;
    var ver = parseInt(v("ab-version"), 10);

    function metrics() {
      var out = [];
      root.querySelectorAll(".js-ab-metric-row").forEach(function (row) {
        var label = row.querySelector(".js-abm-label");
        var display = row.querySelector(".js-abm-display");
        var target = row.querySelector(".js-abm-target");
        var suf = row.querySelector(".js-abm-suffix");
        var tn = parseInt(target && target.value ? target.value : "0", 10);
        out.push({
          label: label ? label.value.trim() : "",
          display: display ? display.value.trim() : "",
          target: isNaN(tn) ? 0 : tn,
          suffix: suf && suf.value.trim() ? suf.value.trim() : "+",
        });
      });
      return out;
    }

    function chapters() {
      var out = [];
      root.querySelectorAll(".js-ab-ch-row").forEach(function (row) {
        var h = row.querySelector(".js-ab-ch-head");
        var b = row.querySelector(".js-ab-ch-body");
        out.push({
          heading: h ? h.value.trim() : "",
          body_html: b ? b.value.trim() : "",
        });
      });
      return out;
    }

    function images() {
      var out = [];
      root.querySelectorAll(".js-ab-img-row").forEach(function (row) {
        out.push({
          src: row.querySelector(".js-abi-src") ? row.querySelector(".js-abi-src").value.trim() : "",
          alt: row.querySelector(".js-abi-alt") ? row.querySelector(".js-abi-alt").value.trim() : "",
        });
      });
      return out;
    }

    function valueItems() {
      var out = [];
      root.querySelectorAll(".js-ab-val-row").forEach(function (row) {
        out.push({
          title: row.querySelector(".js-abv-title") ? row.querySelector(".js-abv-title").value.trim() : "",
          summary_html: row.querySelector(".js-abv-sum") ? row.querySelector(".js-abv-sum").value.trim() : "",
        });
      });
      return out;
    }

    function members() {
      var out = [];
      root.querySelectorAll(".js-ab-mem-row").forEach(function (row) {
        out.push({
          name: row.querySelector(".js-abm-name") ? row.querySelector(".js-abm-name").value.trim() : "",
          role: row.querySelector(".js-abm-role") ? row.querySelector(".js-abm-role").value.trim() : "",
          photo: row.querySelector(".js-abm-photo") ? row.querySelector(".js-abm-photo").value.trim() : "",
          bio_html: row.querySelector(".js-abm-bio") ? row.querySelector(".js-abm-bio").value.trim() : "",
        });
      });
      return out;
    }

    return {
      version: isNaN(ver) ? 1 : ver,
      hero: {
        enabled: c("ab-hero-en"),
        show_breadcrumbs: c("ab-hero-bc"),
        kicker: v("ab-hero-kicker"),
        title: v("ab-hero-title"),
      },
      story: {
        enabled: c("ab-story-en"),
        eyebrow: v("ab-story-eye"),
        pull_quote: v("ab-story-quote"),
        chapters: chapters(),
        metrics: metrics(),
      },
      approach: {
        enabled: c("ab-ap-en"),
        eyebrow: v("ab-ap-eye"),
        title: v("ab-ap-title"),
        lead_html: v("ab-ap-lead"),
        images: images(),
      },
      values: {
        enabled: c("ab-val-en"),
        eyebrow: v("ab-val-eye"),
        title: v("ab-val-title"),
        items: valueItems(),
      },
      team: {
        enabled: c("ab-team-en"),
        eyebrow: v("ab-team-eye"),
        title: v("ab-team-title"),
        intro_html: v("ab-team-intro"),
        members: members(),
      },
      newsletter_cta: {
        enabled: c("ab-nw-en"),
        title: v("ab-nw-title"),
        text_html: v("ab-nw-text"),
        button_label: v("ab-nw-btn"),
        placeholder: v("ab-nw-ph"),
      },
    };
  };
})();
