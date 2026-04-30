(function () {
  'use strict';

  var cfg = window.__DOTS_ACTION_LOG__;
  if (!cfg || typeof cfg.url !== 'string' || cfg.url === '' || typeof cfg.csrf !== 'string') {
    return;
  }

  var MAX_Q = 100;
  var FLUSH_INTERVAL_MS = 5000;
  var queue = [];
  var flushed = 0;

  function stamp() {
    return typeof performance !== 'undefined' && performance.now ? Math.round(performance.now()) : Date.now();
  }

  function pathOnly(full) {
    if (!full || typeof full !== 'string') return '';
    try {
      var u = new URL(full, window.location.origin);
      return u.pathname.slice(0, 260);
    } catch (_) {
      return full.slice(0, 260);
    }
  }

  function enqueue(type, detail, extra) {
    if (queue.length >= MAX_Q) {
      queue.shift();
    }
    var ev = { type: type, t: stamp(), surface: cfg.surface };
    if (detail) ev.detail = detail;
    if (extra && typeof extra.path === 'string') ev.path = extra.path;
    queue.push(ev);
    if (queue.length >= 25) {
      flushSoon();
    }
  }

  var timer = null;
  function flushSoon() {
    if (timer !== null) return;
    timer = window.setTimeout(function () {
      timer = null;
      flush();
    }, 80);
  }

  function flush() {
    if (queue.length === 0 || flushed >= 40) return;
    var batch = queue.splice(0, Math.min(80, queue.length));
    if (batch.length === 0) return;
    var body = JSON.stringify({ _csrf: cfg.csrf, events: batch });
    flushed += 1;
    try {
      if (navigator.sendBeacon) {
        var blob = new Blob([body], { type: 'application/json;charset=UTF-8' });
        navigator.sendBeacon(cfg.url, blob);
      } else {
        fetch(cfg.url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': cfg.csrf },
          credentials: 'same-origin',
          keepalive: true,
          body: body,
        }).catch(function () {});
      }
    } catch (_) {
      queue = batch.concat(queue);
      flushed -= 1;
    }
  }

  enqueue('pageview', document.title ? document.title.slice(0, 200) : '', {
    path: pathOnly(window.location.pathname + window.location.search),
  });

  document.addEventListener(
    'click',
    function (ev) {
      var t = ev.target;
      if (!t || !t.closest) return;
      var el = t.closest('a,button,[role="button"],input[type="submit"],label');
      if (!el || el.closest('[data-log-skip="1"]')) return;
      var tag = el.tagName;
      var det = tag;
      var href = '';
      if (tag === 'A') {
        href = el.getAttribute('href') || '';
        det += '|' + pathOnly(el.href || href || '');
      }
      if (tag === 'INPUT' || tag === 'BUTTON') {
        var bt = el.getAttribute('type') || '';
        if (bt) det += '|type=' + bt;
        var vv = el.getAttribute('name') || el.getAttribute('id') || '';
        if (vv) det += '|' + vv;
      }
      var lab = '';
      try {
        lab = ('innerText' in el && typeof el.innerText === 'string' ? el.innerText : '')
          .replace(/\s+/g, ' ')
          .trim()
          .slice(0, 80);
      } catch (_) {}
      if (lab) det += '|' + lab;
      enqueue('click', det.slice(0, 400));
    },
    true
  );

  document.addEventListener(
    'submit',
    function (ev) {
      var form = ev.target;
      if (!form || !form.tagName || form.tagName !== 'FORM' || form.closest('[data-log-skip="1"]')) return;
      var act = pathOnly(form.getAttribute('action') || window.location.href);
      var name = form.getAttribute('id') || form.getAttribute('name') || '(form)';
      var method = (form.getAttribute('method') || 'get').toUpperCase();
      enqueue('submit', [method, name, act].join('|'));
    },
    true
  );

  window.setInterval(function () {
    if (queue.length) flush();
  }, FLUSH_INTERVAL_MS);

  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'hidden') flush();
  });
  window.addEventListener('pagehide', flush);
})();
