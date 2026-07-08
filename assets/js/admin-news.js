(function () {
    var aiCard = document.getElementById('admNewsAiCard');
    var aiBtn = document.getElementById('admNewsAiBtn');
    var aiBrief = document.getElementById('admNewsAiBrief');
    var aiStatus = document.getElementById('admNewsAiStatus');
    var form = document.getElementById('admNewsForm');
    var seoBtn = document.getElementById('admNewsSeoBtn');
    var seoStatus = document.getElementById('admNewsSeoStatus');
    var seoTips = document.getElementById('admNewsSeoTips');
    var seoGrade = document.getElementById('admNewsSeoGrade');
    var seoScoreEl = document.getElementById('admNewsSeoScore');
    var seoScoreInput = document.getElementById('newsSeoScoreInput');
    var seoSugBox = document.getElementById('admNewsSeoSuggestions');
    var applySeoBtn = document.getElementById('admNewsApplySeo');
    var lastSeo = null;
    var activeLang = '';

    function cardMsg(el, key, fallback) {
        if (!el) return fallback;
        return el.getAttribute('data-msg-' + key) || fallback;
    }

    function getActiveLang() {
        var tab = document.querySelector('.adm-lang-tab.active');
        return tab ? tab.getAttribute('data-lang') : 'en';
    }

    function setField(lang, field, value) {
        var node = document.querySelector('[data-news-field="' + field + '"][data-lang="' + lang + '"]');
        if (node && value) node.value = value;
    }

    function getField(lang, field) {
        var node = document.querySelector('[data-news-field="' + field + '"][data-lang="' + lang + '"]');
        return node ? node.value.trim() : '';
    }

    document.querySelectorAll('.adm-lang-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var lang = tab.getAttribute('data-lang');
            document.querySelectorAll('.adm-lang-tab').forEach(function (t) { t.classList.remove('active'); });
            document.querySelectorAll('.adm-lang-panel').forEach(function (p) { p.hidden = true; });
            tab.classList.add('active');
            var panel = document.querySelector('[data-lang-panel="' + lang + '"]');
            if (panel) panel.hidden = false;
            activeLang = lang;
        });
    });
    activeLang = getActiveLang();

    if (aiBtn && aiCard) {
        aiBtn.addEventListener('click', function () {
            var brief = aiBrief ? aiBrief.value.trim() : '';
            if (!brief) {
                if (aiStatus) aiStatus.textContent = cardMsg(aiCard, 'brief', '');
                return;
            }
            aiBtn.disabled = true;
            var genMsg = cardMsg(aiCard, 'generating', '');
            if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.writing(true);
            if (aiStatus) aiStatus.textContent = genMsg || '…';
            var fd = new FormData();
            fd.append('brief', brief);
            fd.append('lang', activeLang || getActiveLang());
            fetch(aiCard.getAttribute('data-api') || '', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (!res.ok) {
                        if (aiStatus) aiStatus.textContent = res.error || cardMsg(aiCard, 'error', '');
                        return;
                    }
                    var data = res.data || {};
                    if (data.slug) {
                        var slugEl = document.getElementById('newsSlug');
                        if (slugEl && !slugEl.value) slugEl.value = data.slug;
                    }
                    ['lt', 'uk', 'ru', 'en'].forEach(function (code) {
                        var row = data[code];
                        if (!row) return;
                        ['title', 'excerpt', 'body', 'seo_title', 'seo_description', 'seo_keywords'].forEach(function (f) {
                            setField(code, f, row[f] || '');
                        });
                    });
                    var okMsg = res.demo
                        ? cardMsg(aiCard, 'ai-demo', '')
                        : cardMsg(aiCard, 'ai-ok', '');
                    if (aiStatus) aiStatus.textContent = okMsg;
                    if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.success(okMsg);
                })
                .catch(function () {
                    var errMsg = cardMsg(aiCard, 'network', '');
                    if (aiStatus) aiStatus.textContent = errMsg;
                    if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.error(errMsg);
                })
                .finally(function () {
                    if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.writing(false);
                    aiBtn.disabled = false;
                });
        });
    }

    if (seoBtn && form) {
        seoBtn.addEventListener('click', function () {
            var lang = activeLang || getActiveLang();
            seoBtn.disabled = true;
            if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.analyzing(true);
            if (seoStatus) seoStatus.textContent = cardMsg(form, 'generating', '…');
            var fd = new FormData();
            fd.append('lang', lang);
            fd.append('title', getField(lang, 'title'));
            fd.append('excerpt', getField(lang, 'excerpt'));
            fd.append('body', getField(lang, 'body'));
            fd.append('seo_title', getField(lang, 'seo_title'));
            fd.append('seo_description', getField(lang, 'seo_description'));
            fd.append('seo_keywords', getField(lang, 'seo_keywords'));
            fetch(form.getAttribute('data-seo-api') || '', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    var d = res.data || {};
                    lastSeo = { lang: lang, data: d };
                    if (seoScoreEl) seoScoreEl.textContent = d.score != null ? d.score : '—';
                    if (seoScoreInput && d.score != null) seoScoreInput.value = d.score;
                    if (seoGrade) {
                        seoGrade.textContent = d.grade || '—';
                        seoGrade.className = 'adm-badge adm-badge-active';
                    }
                    if (seoTips && d.tips) {
                        seoTips.innerHTML = d.tips.map(function (t) {
                            return '<li><i class="fas fa-check-circle"></i> ' + t + '</li>';
                        }).join('');
                    }
                    if (seoSugBox) seoSugBox.hidden = false;
                    var seoMsg = res.demo
                        ? cardMsg(form, 'seo-demo', '')
                        : cardMsg(form, 'seo-ok', '');
                    if (seoStatus) seoStatus.textContent = seoMsg;
                    if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.success(seoMsg);
                })
                .catch(function () {
                    var errMsg = cardMsg(form, 'error', '');
                    if (seoStatus) seoStatus.textContent = errMsg;
                    if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.error(errMsg);
                })
                .finally(function () {
                    if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.analyzing(false);
                    seoBtn.disabled = false;
                });
        });
    }

    if (applySeoBtn) {
        applySeoBtn.addEventListener('click', function () {
            if (!lastSeo || !lastSeo.data) return;
            var d = lastSeo.data;
            var lang = lastSeo.lang;
            if (d.seo_title_suggestion) setField(lang, 'seo_title', d.seo_title_suggestion);
            if (d.seo_description_suggestion) setField(lang, 'seo_description', d.seo_description_suggestion);
            if (d.seo_keywords_suggestion) setField(lang, 'seo_keywords', d.seo_keywords_suggestion);
            if (d.title_suggestion) setField(lang, 'title', d.title_suggestion);
            if (d.excerpt_suggestion) setField(lang, 'excerpt', d.excerpt_suggestion);
        });
    }
})();