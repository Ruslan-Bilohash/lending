(function () {
    var card = document.getElementById('admSeoAiCard');
    if (!card) return;
    var btn = document.getElementById('admSeoAnalyzeBtn');
    var api = card.getAttribute('data-api') || '';
    var scoreEl = document.getElementById('admSeoScore');
    var gradeEl = document.getElementById('admSeoGrade');
    var tipsEl = document.getElementById('admSeoTips');
    var sugBox = document.getElementById('admSeoSuggestions');
    var statusEl = document.getElementById('admSeoStatus');

    function msg(key, fallback) {
        return card.getAttribute('data-msg-' + key) || fallback;
    }

    function run() {
        btn.disabled = true;
        if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.analyzing(true);
        if (statusEl) statusEl.textContent = msg('generating', '…');
        fetch(api).then(function (r) { return r.json(); }).then(function (res) {
            var d = res.data || {};
            if (scoreEl) scoreEl.textContent = (d.score != null ? d.score : '—');
            if (gradeEl) {
                gradeEl.textContent = d.grade || '—';
                gradeEl.className = 'adm-badge adm-badge-active';
            }
            if (tipsEl && d.tips) {
                tipsEl.innerHTML = d.tips.map(function (t) {
                    return '<li><i class="fas fa-check-circle"></i> ' + t + '</li>';
                }).join('');
            }
            if (sugBox) {
                sugBox.hidden = false;
                var t = document.getElementById('admSeoSugTitle');
                var de = document.getElementById('admSeoSugDesc');
                var k = document.getElementById('admSeoSugKw');
                if (t) t.textContent = d.title_suggestion || '';
                if (de) de.textContent = d.description_suggestion || '';
                if (k) k.textContent = d.keywords_suggestion || '';
            }
            var doneMsg = res.demo ? msg('seo-demo', '') : msg('seo-ai-ok', '');
            if (statusEl) statusEl.textContent = doneMsg;
            if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.success(doneMsg);
        }).catch(function () {
            var errMsg = msg('error', '');
            if (statusEl) statusEl.textContent = errMsg;
            if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.error(errMsg);
        }).finally(function () {
            if (window.LD_ADMIN_TOAST) window.LD_ADMIN_TOAST.analyzing(false);
            btn.disabled = false;
        });
    }

    btn && btn.addEventListener('click', run);
})();