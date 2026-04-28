/**
 * View Transitions supported in Chromium when @view-transition { navigation: auto; }
 * is set in CSS (see base.css).
 */
(function () {
  "use strict";

  function inEditableField(el) {
    if (!el || !el.closest) {
      return false;
    }
    return Boolean(el.closest("input, textarea, select, [contenteditable='true']"));
  }

  var drawerToggle = document.querySelector("[data-drawer-toggle]");
  var drawerRoot = document.querySelector("[data-drawer]");
  var backdrop = drawerRoot ? drawerRoot.querySelector("[data-drawer-backdrop]") : null;
  var closeBtn = drawerRoot ? drawerRoot.querySelector("[data-drawer-close]") : null;
  var drawerPanel = drawerRoot ? drawerRoot.querySelector(".app-drawer__panel") : null;

  function setDrawer(open) {
    if (!drawerRoot || !drawerToggle) {
      return;
    }
    drawerRoot.classList.toggle("is-open", open);
    drawerToggle.setAttribute("aria-expanded", open ? "true" : "false");
    drawerRoot.setAttribute("aria-hidden", open ? "false" : "true");
    document.body.classList.toggle("is-drawer-open", open);
    if (open && closeBtn && typeof closeBtn.focus === "function") {
      window.setTimeout(function () {
        closeBtn.focus({ preventScroll: true });
      }, 50);
    } else if (!open) {
      drawerToggle.focus({ preventScroll: true });
    }
  }

  if (drawerRoot && drawerToggle) {
    drawerToggle.addEventListener("click", function () {
      var open = !drawerRoot.classList.contains("is-open");
      setDrawer(open);
    });
    if (backdrop) {
      backdrop.addEventListener("click", function () {
        setDrawer(false);
      });
    }
    if (closeBtn) {
      closeBtn.addEventListener("click", function () {
        setDrawer(false);
      });
    }
    drawerRoot.querySelectorAll("a.app-drawer__link").forEach(function (link) {
      link.addEventListener("click", function () {
        setDrawer(false);
      });
    });
    document.addEventListener("keydown", function (e) {
      if (e.key !== "Escape") {
        return;
      }
      if (document.body.classList.contains("is-drawer-open")) {
        setDrawer(false);
      }
    });
  }

  var toggle = document.querySelector("[data-nav-toggle]");
  var nav = document.querySelector("[data-nav]");

  function setLegacyNavOpen(open) {
    if (!nav || !toggle) {
      return;
    }
    nav.classList.toggle("is-open", open);
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
    document.body.classList.toggle("is-nav-open", open);
    if (open) {
      var first = nav.querySelector("a[href]");
      if (first && typeof first.focus === "function") {
        first.focus({ preventScroll: true });
      }
    } else {
      toggle.focus({ preventScroll: true });
    }
  }

  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var open = !nav.classList.contains("is-open");
      setLegacyNavOpen(open);
    });
    document.querySelectorAll(".site-nav__link").forEach(function (link) {
      link.addEventListener("click", function () {
        if (window.matchMedia("(max-width: 959px)").matches) {
          setLegacyNavOpen(false);
        }
      });
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && nav.classList.contains("is-open")) {
        setLegacyNavOpen(false);
      }
    });
  }

  var headerScroll = document.querySelector("[data-header-scroll]");
  if (headerScroll && document.body && document.body.classList.contains("page-home") === false) {
    function onHeaderScroll() {
      headerScroll.classList.toggle("site-header--scrolled", window.scrollY > 4);
    }
    onHeaderScroll();
    window.addEventListener("scroll", onHeaderScroll, { passive: true });
  }

  var rootHero = document.querySelector("[data-hero-slider]");
  if (rootHero) {
    var slides = rootHero.querySelectorAll("[data-hero-slide]");
    var prev = rootHero.querySelector("[data-hero-prev]");
    var next = rootHero.querySelector("[data-hero-next]");
    var dotContainer = rootHero.querySelector("[data-hero-dots]");
    var dots = dotContainer ? dotContainer.querySelectorAll("[data-hero-dot]") : [];
    var live = document.getElementById("hero-aria-live");
    var total = slides.length;

    if (total > 0) {
      var iSlide = 0;
      var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      var autoplayMs = reduceMotion ? 0 : 8000;
      var timer = null;

      function announceSlide() {
        if (!live) {
          return;
        }
        var el = slides[iSlide];
        var label = el.getAttribute("data-hero-label") || "";
        live.textContent = "Slide " + (iSlide + 1) + " of " + total + ". " + label;
      }

      function go(index) {
        if (index < 0) {
          index = total - 1;
        }
        if (index >= total) {
          index = 0;
        }
        iSlide = index;
        slides.forEach(function (el, n) {
          var active = n === iSlide;
          el.classList.toggle("is-active", active);
          el.setAttribute("aria-hidden", active ? "false" : "true");
        });
        dots.forEach(function (d, n) {
          d.classList.toggle("is-active", n === iSlide);
          d.setAttribute("aria-selected", n === iSlide ? "true" : "false");
        });
        announceSlide();
      }

      function nextSlide() {
        go(iSlide + 1);
      }

      function prevSlide() {
        go(iSlide - 1);
      }

      function startAutoplay() {
        if (autoplayMs < 1) {
          return;
        }
        stopAutoplay();
        timer = window.setInterval(nextSlide, autoplayMs);
      }

      function stopAutoplay() {
        if (timer) {
          clearInterval(timer);
          timer = null;
        }
      }

      if (next) {
        next.addEventListener("click", function () {
          stopAutoplay();
          nextSlide();
          startAutoplay();
        });
      }
      if (prev) {
        prev.addEventListener("click", function () {
          stopAutoplay();
          prevSlide();
          startAutoplay();
        });
      }

      dots.forEach(function (d) {
        d.addEventListener("click", function () {
          var n = parseInt(d.getAttribute("data-hero-dot") || "0", 10);
          if (!isNaN(n)) {
            stopAutoplay();
            go(n);
            startAutoplay();
          }
        });
      });

      rootHero.addEventListener("mouseenter", stopAutoplay);
      rootHero.addEventListener("mouseleave", startAutoplay);
      rootHero.addEventListener("focusin", stopAutoplay);
      rootHero.addEventListener("focusout", function (e) {
        if (!rootHero.contains(e.relatedTarget)) {
          startAutoplay();
        }
      });

      document.addEventListener("keydown", function (e) {
        if (e.key !== "ArrowRight" && e.key !== "ArrowLeft") {
          return;
        }
        if (inEditableField(e.target)) {
          return;
        }
        e.preventDefault();
        stopAutoplay();
        if (e.key === "ArrowRight") {
          nextSlide();
        } else {
          prevSlide();
        }
        startAutoplay();
      });

      announceSlide();
      startAutoplay();
    }
  }

  document.querySelectorAll("[data-flash-dismiss]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var row = btn.closest("[data-flash]");
      if (row && row.parentNode) {
        row.parentNode.removeChild(row);
      }
    });
  });

  var revealNodes = document.querySelectorAll("[data-reveal]");
  function markRevealInview() {
    revealNodes.forEach(function (el) {
      el.classList.add("is-inview");
    });
  }
  if (revealNodes.length) {
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      markRevealInview();
    } else if ("IntersectionObserver" in window) {
      var revealIo = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add("is-inview");
              revealIo.unobserve(entry.target);
            }
          });
        },
        { root: null, rootMargin: "0px 0px -6% 0px", threshold: 0.06 }
      );
      revealNodes.forEach(function (el) {
        revealIo.observe(el);
      });
    } else {
      markRevealInview();
    }
  }
})();
