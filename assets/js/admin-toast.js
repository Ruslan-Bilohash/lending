(function () {
    'use strict';
    var notify = window.LD_ADMIN_NOTIFY || {};
    var thinkingEl = document.getElementById('admAgentThinking');
    var thinkingText = thinkingEl ? thinkingEl.querySelector('.adm-agent-thinking-text') : null;
    var stack = document.getElementById('admToastStack');
    var thinkingCount = 0;

    function esc(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function icon(type) {
        if (type === 'success') return 'fa-circle-check';
        if (type === 'error') return 'fa-circle-xmark';
        return 'fa-circle-info';
    }

    function showToast(type, message, duration) {
        if (!stack || !message) return;
        var el = document.createElement('div');
        el.className = 'adm-toast adm-toast--' + type;
        el.setAttribute('role', 'status');
        el.innerHTML = '<i class="fas ' + icon(type) + '" aria-hidden="true"></i><span>' + esc(message) + '</span>';
        stack.appendChild(el);
        requestAnimationFrame(function () {
            el.classList.add('is-visible');
        });
        window.setTimeout(function () {
            el.classList.remove('is-visible');
            window.setTimeout(function () {
                if (el.parentNode) el.parentNode.removeChild(el);
            }, 320);
        }, duration || 4200);
    }

    function setThinking(show, message) {
        thinkingCount += show ? 1 : -1;
        if (thinkingCount < 0) thinkingCount = 0;
        if (!thinkingEl) return;
        if (thinkingCount > 0) {
            if (thinkingText) {
                thinkingText.textContent = message || notify.agent_thinking || '';
            }
            thinkingEl.hidden = false;
            document.body.classList.add('adm-agent-busy');
        } else {
            thinkingEl.hidden = true;
            document.body.classList.remove('adm-agent-busy');
        }
    }

    window.LD_ADMIN_TOAST = {
        success: function (message) {
            showToast('success', message || notify.saved || '');
        },
        error: function (message) {
            showToast('error', message || notify.error || '');
        },
        thinking: function (on, message) {
            setThinking(!!on, message);
        },
        analyzing: function (on) {
            setThinking(!!on, notify.agent_analyzing || notify.agent_thinking || '');
        },
        writing: function (on) {
            setThinking(!!on, notify.agent_writing || notify.agent_thinking || '');
        },
    };

    var flash = window.LD_ADMIN_FLASH;
    if (flash && flash.message) {
        showToast(flash.type || 'success', flash.message, 5000);
    }
})();