/**
 * Horarios e Circuitos - com_horarios
 * Leo Costa - www.leocostadeveloper.com (@leocostadeveloper)
 *
 * Self-contained widgets (accordion, tabs, carousel, search filter) using
 * only horarios-* classes and plain JS - deliberately independent from the
 * host site's own Bootstrap build, so the component always looks the same
 * regardless of how the active template restyles generic Bootstrap classes.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initCarousel();
        initSearch();
        initAccordion();
        initTabs();
    });

    function initCarousel() {
        var track = document.getElementById('horariosCarouselTrack');

        if (!track) {
            return;
        }

        var prevBtn = document.querySelector('.horarios-carousel-prev');
        var nextBtn = document.querySelector('.horarios-carousel-next');
        var scrollAmount = 200;

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }
    }

    function initSearch() {
        var input = document.getElementById('horariosSearchInput');
        var select = document.getElementById('horariosSearchSelect');
        var button = document.getElementById('horariosSearchBtn');

        if (select) {
            select.addEventListener('change', function () {
                var option = select.options[select.selectedIndex];
                var url = option ? option.getAttribute('data-url') : '';

                if (url) {
                    window.location.href = url;
                }
            });
        }

        function applyFilter() {
            var term = (input && input.value ? input.value : '').trim().toLowerCase();

            var sidebarLinks = document.querySelectorAll('.horarios-sidebar-link[data-title]');
            sidebarLinks.forEach(function (link) {
                var match = term === '' || link.getAttribute('data-title').indexOf(term) !== -1;
                var li = link.closest('li');
                if (li) {
                    li.hidden = !match;
                }
            });

            var carouselItems = document.querySelectorAll('.horarios-carousel-item[data-title]');
            carouselItems.forEach(function (item) {
                var match = term === '' || item.getAttribute('data-title').indexOf(term) !== -1;
                item.hidden = !match;
            });
        }

        if (input) {
            input.addEventListener('keyup', applyFilter);
        }

        if (button) {
            button.addEventListener('click', applyFilter);
        }
    }

    function initAccordion() {
        document.querySelectorAll('.horarios-acc-header').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var item = btn.closest('.horarios-acc-item');

                if (!item) {
                    return;
                }

                var body = item.querySelector(':scope > .horarios-acc-body');
                var expanded = btn.getAttribute('aria-expanded') === 'true';

                btn.setAttribute('aria-expanded', String(!expanded));
                item.classList.toggle('is-open', !expanded);

                if (body) {
                    body.hidden = expanded;
                }
            });
        });
    }

    function initTabs() {
        document.querySelectorAll('.horarios-tabs').forEach(function (group) {
            var buttons = group.querySelectorAll(':scope > .horarios-tab-nav > .horarios-tab-btn');
            var panels = group.querySelectorAll(':scope > .horarios-tab-panels > .horarios-tab-panel');

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var target = btn.getAttribute('data-tab-target');

                    buttons.forEach(function (b) {
                        b.classList.remove('is-active');
                        b.setAttribute('aria-selected', 'false');
                    });
                    btn.classList.add('is-active');
                    btn.setAttribute('aria-selected', 'true');

                    panels.forEach(function (panel) {
                        panel.classList.toggle('is-active', panel.id === target);
                    });
                });
            });
        });
    }
})();
