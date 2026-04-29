/* Admin: Contact page structured blocks */
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
  function n(id, fallback) {
    var el = document.getElementById(id);
    if (!el) return fallback;
    var val = parseInt(String(el.value || fallback), 10);
    return isNaN(val) ? fallback : val;
  }

  window.dotseContactBlocksBind = function () {};

  window.dotseContactBlocksCollect = function () {
    return {
      version: 1,
      hero: {
        enabled: c("ct-hero-en"),
        show_breadcrumbs: c("ct-hero-bc"),
        kicker: v("ct-hero-kicker"),
      },
      intro: {
        enabled: c("ct-intro-en"),
        title: v("ct-intro-title"),
        lead_html: v("ct-intro-lead"),
      },
      contact_form: {
        enabled: true,
        heading: v("ct-form-heading"),
        submit_label: v("ct-form-submit"),
      },
      newsletter_cta: {
        enabled: true,
        title: v("ct-nw-title"),
        button_label: v("ct-nw-btn"),
        description_html: v("ct-nw-desc"),
      },
      trust: {
        enabled: true,
        star_count: n("ct-trust-stars", 5),
        microcopy: v("ct-trust-copy"),
      },
    };
  };
})();

