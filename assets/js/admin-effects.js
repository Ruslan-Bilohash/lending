(function () {
    'use strict';
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    document.body.classList.add('adm-loaded');

    document.querySelectorAll('.adm-content > .adm-card, .adm-content > .adm-alert, .adm-content > .adm-stats > .adm-stat').forEach(function (el, i) {
        el.classList.add('adm-animate-in');
        el.style.setProperty('--adm-delay', Math.min(i * 0.05, 0.35) + 's');
    });

    document.querySelectorAll('.adm-btn-primary, .adm-ai-fill-card .adm-btn-primary').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var rect = btn.getBoundingClientRect();
            var ripple = document.createElement('span');
            ripple.className = 'adm-ripple';
            var size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
            btn.classList.add('adm-btn-ripple-wrap');
            btn.appendChild(ripple);
            setTimeout(function () { ripple.remove(); }, 600);
        });
    });

    document.querySelectorAll('.adm-preset-card, .adm-template-pick').forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            card.classList.add('adm-hover-glow');
        });
        card.addEventListener('mouseleave', function () {
            card.classList.remove('adm-hover-glow');
        });
    });
})();