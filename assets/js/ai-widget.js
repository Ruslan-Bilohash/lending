(function () {
    var root = document.getElementById('ldAiWidget');
    if (!root) return;
    var panel = document.getElementById('ldAiPanel');
    var toggle = document.getElementById('ldAiToggle');
    var closeBtn = document.getElementById('ldAiClose');
    var form = document.getElementById('ldAiForm');
    var input = document.getElementById('ldAiInput');
    var log = document.getElementById('ldAiLog');
    var apiUrl = root.getAttribute('data-api') || '';
    var lang = root.getAttribute('data-lang') || 'lt';

    function append(role, text) {
        var div = document.createElement('div');
        div.className = 'ld-ai-msg ld-ai-msg--' + role;
        div.textContent = text;
        log.appendChild(div);
        log.scrollTop = log.scrollHeight;
    }

    toggle && toggle.addEventListener('click', function () {
        panel.hidden = !panel.hidden;
        if (!panel.hidden) input.focus();
    });
    closeBtn && closeBtn.addEventListener('click', function () { panel.hidden = true; });

    form && form.addEventListener('submit', function (e) {
        e.preventDefault();
        var msg = (input.value || '').trim();
        if (!msg) return;
        append('user', msg);
        input.value = '';
        append('bot', '…');
        var pending = log.lastChild;
        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: msg, lang: lang })
        }).then(function (r) { return r.json(); }).then(function (data) {
            pending.textContent = data.text || (data.error || 'Error');
        }).catch(function () {
            pending.textContent = 'Network error';
        });
    });
})();