/**
 * In Chromium, cross-page navigations use View Transitions when @view-transition
 * { navigation: auto; } is set in CSS (see base.css). Other browsers get the same
 * HTML/CSS; transitions simply instant-load as usual.
 */
(function () {
  "use strict";

  function inEditableField(el) {
    if (!el || !el.closest) {
      return false;
    }
    return Boolean(el.closest("input, textarea, select, [contenteditable='true']"));
  }

  // Mobile nav — toggle, Escape, scroll lock, focus return
  var header = document.querySelector("[data-header]");
  var toggle = document.querySelector("[data-nav-toggle]");
  var nav = document.querySelector("[data-nav]");

  function setNavOpen(open) {
    if (!nav || !toggle) {
      return;
    }
    nav.classList.toggle("is-open", open);
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
    document.body.classList.toggle("is-nav-open", open);
    if (open) {
      var first = nav.querySelector("a[href]");
      if (first) {
        first.focus({ preventScroll: true });
      }
    } else {
      toggle.focus({ preventScroll: true });
    }
  }

  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var open = !nav.classList.contains("is-open");
      setNavOpen(open);
    });
    document.querySelectorAll(".site-nav__link").forEach(function (link) {
      link.addEventListener("click", function () {
        if (window.matchMedia("(max-width: 959px)").matches) {
          setNavOpen(false);
        }
      });
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && nav.classList.contains("is-open")) {
        setNavOpen(false);
      }
    });
  }

  // Inner pages: header gains depth after scroll (home keeps transparent hero)
  var headerScroll = document.querySelector("[data-header-scroll]");
  if (headerScroll && !document.body.classList.contains("page-home")) {
    function onHeaderScroll() {
      headerScroll.classList.toggle("site-header--scrolled", window.scrollY > 4);
    }
    onHeaderScroll();
    window.addEventListener("scroll", onHeaderScroll, { passive: true });
  }

  // Hero slider (home only)
  var root = document.querySelector("[data-hero-slider]");
  if (root) {
    var slides = root.querySelectorAll("[data-hero-slide]");
    var prev = root.querySelector("[data-hero-prev]");
    var next = root.querySelector("[data-hero-next]");
    var dotContainer = root.querySelector("[data-hero-dots]");
    var dots = dotContainer ? dotContainer.querySelectorAll("[data-hero-dot]") : [];
    var live = document.getElementById("hero-aria-live");
    var total = slides.length;

    if (total > 0) {
      var i = 0;
      var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      var autoplayMs = reduceMotion ? 0 : 8000;
      var timer = null;

      function announceSlide() {
        if (!live) {
          return;
        }
        var el = slides[i];
        var label = el.getAttribute("data-hero-label") || "";
        live.textContent = "Slide " + (i + 1) + " of " + total + ". " + label;
      }

      function go(index) {
        if (index < 0) index = total - 1;
        if (index >= total) index = 0;
        i = index;
        slides.forEach(function (el, n) {
          var active = n === i;
          el.classList.toggle("is-active", active);
          el.setAttribute("aria-hidden", active ? "false" : "true");
        });
        dots.forEach(function (d, n) {
          d.classList.toggle("is-active", n === i);
          d.setAttribute("aria-selected", n === i ? "true" : "false");
        });
        announceSlide();
      }

      function nextSlide() {
        go(i + 1);
      }

      function prevSlide() {
        go(i - 1);
      }

      function startAutoplay() {
        if (autoplayMs < 1) return;
        stopAutoplay();
        timer = window.setInterval(nextSlide, autoplayMs);
      }

      function stopAutoplay() {
        if (timer) {
          clearInterval(timer);
          timer = null;
        }
      }

      if (next) next.addEventListener("click", function () { stopAutoplay(); nextSlide(); startAutoplay(); });
      if (prev) prev.addEventListener("click", function () { stopAutoplay(); prevSlide(); startAutoplay(); });

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

      root.addEventListener("mouseenter", stopAutoplay);
      root.addEventListener("mouseleave", startAutoplay);
      root.addEventListener("focusin", stopAutoplay);
      root.addEventListener("focusout", function (e) {
        if (!root.contains(e.relatedTarget)) startAutoplay();
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

  // Dismiss flash banners
  document.querySelectorAll("[data-flash-dismiss]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var row = btn.closest("[data-flash]");
      if (row && row.parentNode) {
        row.parentNode.removeChild(row);
      }
    });
  });

  // Scroll-triggered section reveals (respects reduced motion)
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
