(function () {
    'use strict';

    document.querySelectorAll('.ld-lang-dd').forEach(function (dd) {
        var btn = dd.querySelector('.ld-lang-btn');
        var menu = dd.querySelector('.ld-lang-menu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = menu.hidden;
            document.querySelectorAll('.ld-lang-menu').forEach(function (m) { m.hidden = true; });
            document.querySelectorAll('.ld-lang-btn').forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
            if (open) {
                menu.hidden = false;
                btn.setAttribute('aria-expanded', 'true');
            }
        });
        menu.addEventListener('click', function (e) { e.stopPropagation(); });
    });
    document.addEventListener('click', function () {
        document.querySelectorAll('.ld-lang-menu').forEach(function (m) { m.hidden = true; });
        document.querySelectorAll('.ld-lang-btn').forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
    });

    function closeMobileNav(btn, nav, backdrop) {
        if (!nav) return;
        nav.hidden = true;
        if (btn) btn.setAttribute('aria-expanded', 'false');
        if (backdrop) {
            backdrop.classList.remove('is-open');
            backdrop.hidden = true;
        }
        document.body.classList.remove('ld-nav-open');
    }

    function openMobileNav(btn, nav, backdrop) {
        if (!nav) return;
        document.querySelectorAll('.ld-mobile-nav').forEach(function (other) {
            if (other !== nav) other.hidden = true;
        });
        document.querySelectorAll('.ld-menu-btn').forEach(function (otherBtn) {
            if (otherBtn !== btn) otherBtn.setAttribute('aria-expanded', 'false');
        });
        nav.hidden = false;
        if (btn) btn.setAttribute('aria-expanded', 'true');
        if (backdrop) {
            backdrop.hidden = false;
            requestAnimationFrame(function () { backdrop.classList.add('is-open'); });
        }
        document.body.classList.add('ld-nav-open');
    }

    function initMobileNav(btnId, navId, backdropId) {
        var menuBtn = document.getElementById(btnId);
        var mobileNav = document.getElementById(navId);
        var backdrop = backdropId ? document.getElementById(backdropId) : null;
        if (!menuBtn || !mobileNav) return;

        menuBtn.addEventListener('click', function () {
            if (mobileNav.hidden) {
                openMobileNav(menuBtn, mobileNav, backdrop);
            } else {
                closeMobileNav(menuBtn, mobileNav, backdrop);
            }
        });

        if (backdrop) {
            backdrop.addEventListener('click', function () {
                closeMobileNav(menuBtn, mobileNav, backdrop);
            });
        }

        mobileNav.querySelectorAll('[data-nav-close]').forEach(function (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closeMobileNav(menuBtn, mobileNav, backdrop);
            });
        });

        mobileNav.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () {
                closeMobileNav(menuBtn, mobileNav, backdrop);
            });
        });
    }

    initMobileNav('ldMenuBtn', 'ldMobileNav', 'ldMobileBackdrop');
    initMobileNav('ldHubMenuBtn', 'ldHubMobileNav', 'ldHubMobileBackdrop');

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        closeMobileNav(
            document.getElementById('ldMenuBtn'),
            document.getElementById('ldMobileNav'),
            document.getElementById('ldMobileBackdrop')
        );
        closeMobileNav(
            document.getElementById('ldHubMenuBtn'),
            document.getElementById('ldHubMobileNav'),
            document.getElementById('ldHubMobileBackdrop')
        );
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            closeMobileNav(
                document.getElementById('ldMenuBtn'),
                document.getElementById('ldMobileNav'),
                document.getElementById('ldMobileBackdrop')
            );
            closeMobileNav(
                document.getElementById('ldHubMenuBtn'),
                document.getElementById('ldHubMobileNav'),
                document.getElementById('ldHubMobileBackdrop')
            );
        }
    });
})();