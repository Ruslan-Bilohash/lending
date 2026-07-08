(function () {
    'use strict';
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    document.querySelectorAll('[data-reveal]').forEach(function (el, i) {
        el.style.setProperty('--reveal-delay', (i % 8) * 0.06 + 's');
        el.classList.add('ld-reveal');
    });

    var reveals = document.querySelectorAll('.ld-reveal');
    if (reveals.length && 'IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('ld-reveal--in');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(function (el) { io.observe(el); });
    }

    var sticky = document.getElementById('ldStickyAdmin');
    if (sticky) {
        var hero = document.querySelector('.ld-sell-hero');
        window.addEventListener('scroll', function () {
            var show = hero ? window.scrollY > hero.offsetHeight * 0.6 : window.scrollY > 400;
            sticky.classList.toggle('ld-sticky-admin--show', show);
        }, { passive: true });
    }

    document.querySelectorAll('[data-count]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count'), 10);
        if (!target || !('IntersectionObserver' in window)) return;
        var obs = new IntersectionObserver(function (entries) {
            if (!entries[0].isIntersecting) return;
            var start = 0;
            var dur = 1200;
            var t0 = performance.now();
            function tick(now) {
                var p = Math.min(1, (now - t0) / dur);
                el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
            obs.disconnect();
        }, { threshold: 0.5 });
        obs.observe(el);
    });
})();