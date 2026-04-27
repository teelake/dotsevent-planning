(function () {
  "use strict";

  // Mobile nav
  var header = document.querySelector("[data-header]");
  var toggle = document.querySelector("[data-nav-toggle]");
  var nav = document.querySelector("[data-nav]");

  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var open = nav.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
    document.querySelectorAll(".site-nav__link").forEach(function (link) {
      link.addEventListener("click", function () {
        if (window.matchMedia("(max-width: 959px)").matches) {
          nav.classList.remove("is-open");
          toggle.setAttribute("aria-expanded", "false");
        }
      });
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
  if (!root) return;

  var slides = root.querySelectorAll("[data-hero-slide]");
  var prev = root.querySelector("[data-hero-prev]");
  var next = root.querySelector("[data-hero-next]");
  var dotContainer = root.querySelector("[data-hero-dots]");
  var dots = dotContainer ? dotContainer.querySelectorAll("[data-hero-dot]") : [];
  var total = slides.length;
  if (total < 1) return;

  var i = 0;
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var autoplayMs = reduceMotion ? 0 : 8000;
  var timer = null;

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
    if (e.key === "ArrowRight") {
      stopAutoplay();
      nextSlide();
      startAutoplay();
    } else if (e.key === "ArrowLeft") {
      stopAutoplay();
      prevSlide();
      startAutoplay();
    }
  });

  startAutoplay();
})();
