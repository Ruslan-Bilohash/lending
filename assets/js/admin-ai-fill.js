(function () {
    var card = document.getElementById('admAiFillCard');
    if (!card) return;
    var btn = document.getElementById('admAiFillBtn');
    var brief = document.getElementById('admAiBrief');
    var status = document.getElementById('admAiFillStatus');
    var api = card.getAttribute('data-api') || '';
    var i18n = window.LD_ADMIN_I18N || {};
    var msgBrief = card.getAttribute('data-msg-brief') || i18n.brief_required || '';
    var msgGenerating = card.getAttribute('data-msg-generating') || i18n.generating || '';
    var msgNetwork = card.getAttribute('data-msg-network') || i18n.network_error || '';
    var msgSaved = card.getAttribute('data-msg-saved') || i18n.saved || '';
    var msgDemoSaved = card.getAttribute('data-msg-demo-saved') || msgSaved;
    var msgError = card.getAttribute('data-msg-error') || i18n.error || '';

    btn && btn.addEventListener('click', function () {
        var text = (brief && brief.value || '').trim();
        if (!text) {
            if (status) {
                status.hidden = false;
                status.textContent = msgBrief;
                status.style.color = 'var(--adm-red)';
            }
            if (window.LD_ADMIN_TOAST) {
                window.LD_ADMIN_TOAST.error(msgBrief);
            }
            return;
        }
        btn.disabled = true;
        if (window.LD_ADMIN_TOAST) {
            window.LD_ADMIN_TOAST.thinking(true, msgGenerating);
        }
        if (status) {
            status.hidden = false;
            status.textContent = msgGenerating;
            status.style.color = 'var(--adm-muted)';
        }
        fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ brief: text, scope: btn.getAttribute('data-scope') || 'all' })
        }).then(function (r) { return r.json(); }).then(function (data) {
            if (status) {
                status.textContent = data.message || (data.ok ? (data.demo ? msgDemoSaved : msgSaved) : msgError);
                status.style.color = data.ok ? 'var(--adm-green)' : 'var(--adm-red)';
            }
            if (data.ok) {
                if (window.LD_ADMIN_TOAST) {
                    window.LD_ADMIN_TOAST.success(data.message || (data.demo ? msgDemoSaved : msgSaved));
                }
                setTimeout(function () { window.location.reload(); }, 1200);
            } else if (window.LD_ADMIN_TOAST) {
                window.LD_ADMIN_TOAST.error(msgError);
            }
        }).catch(function () {
            if (status) {
                status.textContent = msgNetwork;
                status.style.color = 'var(--adm-red)';
            }
            if (window.LD_ADMIN_TOAST) {
                window.LD_ADMIN_TOAST.error(msgNetwork);
            }
        }).finally(function () {
            if (window.LD_ADMIN_TOAST) {
                window.LD_ADMIN_TOAST.thinking(false);
            }
            btn.disabled = false;
        });
    });
})();