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
      function revealAlreadyVisible(el) {
        var r = el.getBoundingClientRect();
        var vh = window.innerHeight || document.documentElement.clientHeight || 0;
        return r.bottom > 40 && r.top < vh - 40;
      }
      /** Home: stacked CMS bands sit flush under a full-viewport hero; IO “visibility” excludes them until scroll (blank strip). Mark those sections revealed immediately (CSS in pages.css aligns). */
      function isHomeMainReveal(el) {
        return (
          document.body &&
          document.body.classList.contains("page-home") &&
          typeof el.matches === "function" &&
          el.matches(".app-shell__main > section[data-reveal]")
        );
      }
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
        if (isHomeMainReveal(el) || revealAlreadyVisible(el)) {
          el.classList.add("is-inview");
        } else {
          revealIo.observe(el);
        }
      });
    } else {
      markRevealInview();
    }
  }

  /** Home intro metrics: count-up when strip scrolls into view (respects reduced motion). */
  var metricStrip = document.querySelector("[data-metric-strip]");
  var metricNodes = metricStrip ? metricStrip.querySelectorAll("[data-metric-count]") : [];
  function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
  }
  function setMetricFinal() {
    metricNodes.forEach(function (el) {
      var target = parseFloat(el.getAttribute("data-target") || "0", 10);
      var suf = el.getAttribute("data-suffix") || "";
      if (!isNaN(target)) {
        el.textContent = Math.round(target) + suf;
      }
    });
  }
  if (metricStrip && metricNodes.length) {
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      setMetricFinal();
    } else if (!("IntersectionObserver" in window)) {
      setMetricFinal();
    } else {
      var metricDone = false;
      var metricIo = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (!entry.isIntersecting || metricDone) {
              return;
            }
            metricDone = true;
            metricIo.unobserve(metricStrip);
            var duration = 1100;
            var startTs = null;
            var targets = Array.prototype.map.call(metricNodes, function (el) {
              return parseFloat(el.getAttribute("data-target") || "0", 10);
            });
            metricNodes.forEach(function (el) {
              var suf = el.getAttribute("data-suffix") || "";
              el.textContent = "0" + suf;
            });
            function frame(now) {
              if (startTs === null) {
                startTs = now;
              }
              var elapsed = now - startTs;
              var p = Math.min(1, elapsed / duration);
              var e = easeOutCubic(p);
              metricNodes.forEach(function (el, i) {
                var tgt = targets[i];
                var suf = el.getAttribute("data-suffix") || "";
                if (isNaN(tgt)) {
                  return;
                }
                var n = Math.round(e * tgt);
                if (p >= 1) {
                  n = Math.round(tgt);
                }
                el.textContent = n + suf;
              });
              if (p < 1) {
                window.requestAnimationFrame(frame);
              }
            }
            window.requestAnimationFrame(frame);
          });
        },
        { root: null, rootMargin: "0px 0px -12% 0px", threshold: 0.2 }
      );
      metricIo.observe(metricStrip);
    }
  }

  document.querySelectorAll("form[data-newsletter-form]").forEach(function (form) {
    var emailEl = form.querySelector('input[name="email"][type="email"]');
    var errEl = form.querySelector("[data-newsletter-error]");
    if (!emailEl || !errEl) {
      return;
    }
    function clearErr() {
      errEl.textContent = "";
      errEl.hidden = true;
      emailEl.removeAttribute("aria-invalid");
    }
    function showErr(msg) {
      errEl.textContent = msg;
      errEl.hidden = false;
      emailEl.setAttribute("aria-invalid", "true");
      try {
        emailEl.focus({ preventScroll: false });
      } catch (_) {
        emailEl.focus();
      }
    }
    emailEl.addEventListener("input", clearErr);
    form.addEventListener("submit", function (e) {
      clearErr();
      var raw = emailEl.value ? String(emailEl.value).trim() : "";
      if (raw === "") {
        e.preventDefault();
        showErr("Please enter your email.");
        return;
      }
      if (typeof emailEl.checkValidity === "function" && !emailEl.checkValidity()) {
        e.preventDefault();
        showErr("Please enter a valid email address.");
        return;
      }
    });
  });
})();
