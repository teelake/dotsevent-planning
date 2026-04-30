/* Admin — Rentals blocks editor bind + collect */
(function () {
    'use strict';

    window.dotseRentalsBlocksBind = function () {
        var root = document.getElementById('rentals-blocks-editor');
        if (!root) return;

        // Generic repeater remove
        root.addEventListener('click', function (ev) {
            var btn = ev.target.closest('.hb-row-remove');
            if (!btn || !root.contains(btn)) return;
            ev.preventDefault();
            var row = btn.closest('.hb-repeat-row');
            if (row && row.parentElement) {
                row.parentElement.removeChild(row);
            }
        });

        function bindAdd(btnId, tplId, listId) {
            var btn = document.getElementById(btnId);
            var tpl = document.getElementById(tplId);
            var lst = document.getElementById(listId);
            if (!btn || !tpl || !lst || !tpl.content) return;
            btn.addEventListener('click', function () {
                lst.appendChild(document.importNode(tpl.content, true));
            });
        }

        bindAdd('rb-add-cat',       'rb-tpl-cat',      'rb-cat-items');
        bindAdd('rb-add-hiw-step',  'rb-tpl-hiw-step', 'rb-hiw-steps');
        bindAdd('rb-add-log',       'rb-tpl-log',      'rb-log-items');
    };

    window.dotseRentalsBlocksCollect = function () {
        function v(id) {
            var el = document.getElementById(id);
            return el ? String(el.value || '').trim() : '';
        }
        function ck(id) {
            var el = document.getElementById(id);
            return el ? el.checked : false;
        }

        // Categories
        var catItems = [];
        document.querySelectorAll('#rb-cat-items .hb-repeat-row').forEach(function (row) {
            var key   = row.querySelector('.rb-cat-key');
            var label = row.querySelector('.rb-cat-label');
            catItems.push({
                key:   key   ? key.value.trim()   : '',
                label: label ? label.value.trim()  : '',
                icon:  '',
            });
        });

        // How it works steps
        var hiwSteps = [];
        document.querySelectorAll('#rb-hiw-steps .hb-repeat-row').forEach(function (row) {
            var num   = row.querySelector('.rb-hiw-num');
            var title = row.querySelector('.rb-hiw-step-title');
            var desc  = row.querySelector('.rb-hiw-step-desc');
            hiwSteps.push({
                number:      num   ? num.value.trim()   : '',
                title:       title ? title.value.trim()  : '',
                description: desc  ? desc.value.trim()   : '',
            });
        });

        // Logistics items
        var logItems = [];
        document.querySelectorAll('#rb-log-items .hb-repeat-row').forEach(function (row) {
            var icon  = row.querySelector('.rb-log-icon');
            var label = row.querySelector('.rb-log-label');
            logItems.push({
                icon:  icon  ? icon.value.trim()  : '',
                label: label ? label.value.trim() : '',
            });
        });

        return {
            hero: {
                enabled:           ck('rb-hero-enabled'),
                kicker:            v('rb-hero-kicker'),
                title:             v('rb-hero-title'),
                subtitle:          v('rb-hero-subtitle'),
                cta_primary_label: v('rb-hero-cta-label'),
                cta_primary_href:  v('rb-hero-cta-href'),
                cta_secondary_label: v('rb-hero-cta2-label'),
                cta_secondary_href:  v('rb-hero-cta2-href'),
                bg_image_path:     v('rb-hero-bg'),
            },
            categories: {
                enabled:   ck('rb-cat-enabled'),
                all_label: v('rb-cat-all-label'),
                items:     catItems,
            },
            controls: {
                enabled:             true,
                show_search:         true,
                search_placeholder:  v('rb-ctrl-search-ph'),
                result_label_plural: v('rb-ctrl-result-label'),
            },
            how_it_works: {
                enabled:   ck('rb-hiw-enabled'),
                title:     v('rb-hiw-title'),
                cta_label: v('rb-hiw-cta-label'),
                cta_href:  v('rb-hiw-cta-href'),
                steps:     hiwSteps,
            },
            logistics: {
                enabled: ck('rb-log-enabled'),
                items:   logItems,
            },
            newsletter_cta: {
                enabled:      ck('rb-nw-enabled'),
                title:        v('rb-nw-title'),
                text_html:    v('rb-nw-text'),
                button_label: v('rb-nw-btn'),
                placeholder:  v('rb-nw-ph'),
            },
        };
    };
})();
