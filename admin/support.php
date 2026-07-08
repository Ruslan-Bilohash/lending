<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/admin-support.php';
ld_admin_require();

$admin_page = 'support';
$ta = $t['admin'] ?? [];
$sp = $ta['support_page'] ?? [];
$page_title = $sp['title'] ?? 'Support & mail';

$apiOwner = ld_admin_url('api/owner-message.php');
$apiMessages = ld_admin_url('api/messages.php');
$apiMessageFile = ld_admin_url('api/message-file.php');
$apiAi = ld_admin_url('api/ai-compose-message.php');
$inboxId = isset($_GET['id']) ? trim((string) $_GET['id']) : '';
$initialTab = isset($_GET['tab']) && $_GET['tab'] === 'inbox' ? 'inbox' : 'owner';
$aiThinking = ld_admin_t('notify_agent_thinking', 'Thinking…');

require __DIR__ . '/includes/layout.php';
?>

<div class="adm-support-page">
    <div class="adm-leads-hero">
        <div class="adm-leads-hero-text">
            <h2 class="adm-leads-title"><i class="fas fa-headset"></i> <?= ld_h($page_title) ?></h2>
            <p><?= ld_h($sp['help'] ?? '') ?></p>
        </div>
        <a href="<?= ld_h(ld_faktura_support_url()) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener">
            <i class="fas fa-file-invoice-dollar"></i> <?= ld_h($ta['faktura_support_link'] ?? 'Faktura support') ?>
        </a>
    </div>

    <div class="adm-support-tabs" role="tablist">
        <button type="button" class="adm-support-tab<?= $initialTab === 'inbox' ? ' is-active' : '' ?>" data-tab="inbox" role="tab" aria-selected="<?= $initialTab === 'inbox' ? 'true' : 'false' ?>">
            <i class="fas fa-inbox"></i> <?= ld_h($sp['tab_inbox'] ?? 'Conversations') ?>
            <span class="adm-support-tab-badge hidden" id="inbox_unread_badge"></span>
        </button>
        <button type="button" class="adm-support-tab<?= $initialTab === 'owner' ? ' is-active' : '' ?>" data-tab="owner" role="tab" aria-selected="<?= $initialTab === 'owner' ? 'true' : 'false' ?>">
            <i class="fas fa-comments"></i> <?= ld_h($sp['tab_owner'] ?? 'General ecosystem chat') ?>
        </button>
        <button type="button" class="adm-support-tab" data-tab="client" role="tab" aria-selected="false">
            <i class="fas fa-user"></i> <?= ld_h($sp['tab_client'] ?? 'Email to customer') ?>
        </button>
    </div>

    <div class="adm-card adm-support-panel adm-support-inbox" id="admSupportInbox" data-panel="inbox"<?= $initialTab !== 'inbox' ? ' hidden' : '' ?>>
        <div class="adm-card-body padded">
            <p class="adm-muted adm-support-hint"><?= ld_h($sp['inbox_hint'] ?? '') ?></p>
            <div class="adm-inbox-layout" id="adm_inbox_layout">
                <aside class="adm-inbox-list-wrap" id="adm_inbox_list_wrap">
                    <div class="adm-inbox-list" id="adm_inbox_list"></div>
                    <p class="adm-muted adm-inbox-empty hidden" id="adm_inbox_empty"><?= ld_h($sp['inbox_empty'] ?? '') ?></p>
                </aside>
                <div class="adm-inbox-detail-wrap" id="adm_inbox_detail_wrap">
                    <button type="button" class="adm-inbox-back hidden" id="adm_inbox_back">
                        <i class="fas fa-arrow-left"></i> <?= ld_h($sp['inbox_back'] ?? 'Back') ?>
                    </button>
                    <div class="adm-inbox-detail-empty" id="adm_inbox_detail_empty">
                        <p class="adm-muted"><?= ld_h($sp['inbox_select'] ?? '') ?></p>
                    </div>
                    <div class="adm-inbox-detail hidden" id="adm_inbox_detail">
                        <h3 class="adm-inbox-subject" id="adm_inbox_subject"></h3>
                        <div class="adm-inbox-thread" id="adm_inbox_thread"></div>
                        <form class="adm-inbox-reply" id="adm_inbox_reply_form">
                            <label class="adm-label" for="adm_inbox_reply_body"><?= ld_h($sp['inbox_reply'] ?? 'Reply') ?></label>
                            <textarea id="adm_inbox_reply_body" class="adm-input" rows="4"></textarea>
                            <div class="adm-form-group">
                                <label class="adm-label" for="adm_inbox_files"><?= ld_h($sp['inbox_attach'] ?? 'Screenshots') ?></label>
                                <input type="file" id="adm_inbox_files" class="adm-input" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
                                <p class="adm-hint"><?= ld_h($sp['inbox_attach_hint'] ?? '') ?></p>
                            </div>
                            <button type="submit" class="adm-btn adm-btn-primary" id="adm_inbox_send_btn">
                                <i class="fas fa-paper-plane"></i> <?= ld_h($sp['inbox_send'] ?? 'Send') ?>
                            </button>
                        </form>
                        <p class="adm-support-status" id="adm_inbox_status" hidden></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="adm-card adm-support-panel" id="admSupportOwner" data-panel="owner"<?= $initialTab !== 'owner' ? ' hidden' : '' ?>>
        <div class="adm-card-body padded">
            <p class="adm-muted adm-support-hint"><?= ld_h($sp['owner_hint'] ?? '') ?></p>
            <div class="adm-form-grid adm-form-grid--2">
                <div>
                    <label class="adm-label" for="owner_category"><?= ld_h($sp['category'] ?? 'Category') ?></label>
                    <select id="owner_category" class="adm-input">
                        <option value="support"><?= ld_h($sp['cat_support'] ?? 'Support') ?></option>
                        <option value="bug"><?= ld_h($sp['cat_bug'] ?? 'Bug') ?></option>
                        <option value="billing"><?= ld_h($sp['cat_billing'] ?? 'Billing') ?></option>
                        <option value="feature"><?= ld_h($sp['cat_feature'] ?? 'Feature') ?></option>
                        <option value="other"><?= ld_h($sp['cat_other'] ?? 'Other') ?></option>
                    </select>
                </div>
                <div>
                    <label class="adm-label" for="owner_email"><?= ld_h($sp['your_email'] ?? 'Your email') ?></label>
                    <input type="email" id="owner_email" class="adm-input" placeholder="you@example.com">
                    <p class="adm-hint"><?= ld_h($sp['your_email_hint'] ?? '') ?></p>
                </div>
            </div>
            <div class="adm-form-group">
                <label class="adm-label" for="owner_draft"><?= ld_h($sp['draft_notes'] ?? 'Draft') ?></label>
                <textarea id="owner_draft" class="adm-input" rows="3" placeholder="<?= ld_h($sp['draft_notes'] ?? '') ?>"></textarea>
            </div>
            <div class="adm-support-ai-panel" id="owner_ai_panel">
                <div class="adm-support-ai-actions">
                    <button type="button" class="adm-btn adm-btn-primary adm-btn-ai-generate" id="owner_ai_btn"
                            data-thinking="<?= ld_h($aiThinking) ?>"
                            data-label-default="<?= ld_h($sp['ai_compose'] ?? 'Improve with AI') ?>">
                        <i class="fas fa-wand-magic-sparkles adm-ai-btn-icon" aria-hidden="true"></i>
                        <span class="adm-ai-btn-label"><?= ld_h($sp['ai_compose'] ?? 'Improve with AI') ?></span>
                    </button>
                    <p class="adm-ai-status adm-ai-status--block" id="owner_ai_status" hidden role="status"></p>
                </div>
            </div>
            <div class="adm-form-group">
                <label class="adm-label" for="owner_subject"><?= ld_h($sp['subject'] ?? 'Subject') ?></label>
                <input type="text" id="owner_subject" class="adm-input" required>
            </div>
            <div class="adm-form-group">
                <label class="adm-label" for="owner_body"><?= ld_h($sp['message'] ?? 'Message') ?></label>
                <textarea id="owner_body" class="adm-input" rows="8" required></textarea>
            </div>
            <div class="adm-form-actions adm-support-send-row">
                <button type="button" class="adm-btn adm-btn-primary adm-btn-support-send" id="owner_send_btn">
                    <i class="fas fa-paper-plane"></i> <?= ld_h($sp['send_owner'] ?? 'Send') ?>
                </button>
            </div>
            <p class="adm-support-status" id="owner_status" hidden></p>
        </div>
    </div>

    <div class="adm-card adm-support-panel" id="admSupportClient" data-panel="client" hidden>
        <div class="adm-card-body padded">
            <p class="adm-muted adm-support-hint"><?= ld_h($sp['client_hint'] ?? '') ?></p>
            <div class="adm-form-grid adm-form-grid--2">
                <div>
                    <label class="adm-label" for="client_name"><?= ld_h($sp['client_name'] ?? 'Customer') ?></label>
                    <input type="text" id="client_name" class="adm-input" placeholder="Anna K.">
                </div>
                <div>
                    <label class="adm-label" for="client_topic"><?= ld_h($sp['client_topic'] ?? 'Topic') ?></label>
                    <input type="text" id="client_topic" class="adm-input" placeholder="Order #1042">
                </div>
            </div>
            <div class="adm-form-group">
                <label class="adm-label" for="client_draft"><?= ld_h($sp['draft_notes'] ?? 'Notes') ?></label>
                <textarea id="client_draft" class="adm-input" rows="4"></textarea>
            </div>
            <div class="adm-support-ai-panel" id="client_ai_panel">
                <div class="adm-support-ai-actions">
                    <button type="button" class="adm-btn adm-btn-primary adm-btn-ai-generate" id="client_ai_btn"
                            data-thinking="<?= ld_h($aiThinking) ?>"
                            data-label-default="<?= ld_h($sp['ai_compose_client'] ?? 'Draft with AI') ?>">
                        <i class="fas fa-wand-magic-sparkles adm-ai-btn-icon" aria-hidden="true"></i>
                        <span class="adm-ai-btn-label"><?= ld_h($sp['ai_compose_client'] ?? 'Draft with AI') ?></span>
                    </button>
                    <p class="adm-ai-status adm-ai-status--block" id="client_ai_status" hidden role="status"></p>
                </div>
            </div>
            <div class="adm-form-group">
                <label class="adm-label" for="client_subject"><?= ld_h($sp['subject'] ?? 'Subject') ?></label>
                <input type="text" id="client_subject" class="adm-input">
            </div>
            <div class="adm-form-group">
                <label class="adm-label" for="client_body"><?= ld_h($sp['message'] ?? 'Message') ?></label>
                <textarea id="client_body" class="adm-input" rows="8"></textarea>
            </div>
            <div class="adm-form-actions adm-support-client-actions">
                <button type="button" class="adm-btn adm-btn-outline adm-btn-support-secondary" id="client_copy_btn">
                    <i class="fas fa-copy"></i> <?= ld_h($sp['copy_draft'] ?? 'Copy') ?>
                </button>
                <button type="button" class="adm-btn adm-btn-primary adm-btn-support-send" id="client_mailto_btn">
                    <i class="fas fa-envelope"></i> <?= ld_h($sp['mailto_client'] ?? 'Mail app') ?>
                </button>
            </div>
            <p class="adm-support-status" id="client_status" hidden></p>
        </div>
    </div>
</div>

<script>
(function () {
    const LANG = <?= json_encode($lang, JSON_UNESCAPED_UNICODE) ?>;
    const API_OWNER = <?= json_encode($apiOwner) ?>;
    const API_MESSAGES = <?= json_encode($apiMessages) ?>;
    const API_MESSAGE_FILE = <?= json_encode($apiMessageFile) ?>;
    const API_AI = <?= json_encode($apiAi) ?>;
    const INBOX_ID = <?= json_encode($inboxId) ?>;
    const MSG = {
        sent_ok: <?= json_encode($sp['sent_ok'] ?? 'Sent.') ?>,
        sent_error: <?= json_encode($sp['sent_error'] ?? 'Error.') ?>,
        ai_error: <?= json_encode($sp['ai_error'] ?? 'AI error.') ?>,
        copied: <?= json_encode($sp['copied'] ?? 'Copied.') ?>,
        inbox_sent_ok: <?= json_encode($sp['inbox_sent_ok'] ?? 'Reply sent.') ?>,
        inbox_sent_error: <?= json_encode($sp['inbox_sent_error'] ?? 'Error.') ?>,
        inbox_unread: <?= json_encode($sp['inbox_unread'] ?? 'New reply') ?>,
        inbox_author_owner: <?= json_encode($sp['inbox_author_owner'] ?? 'BILOHASH') ?>,
        inbox_author_you: <?= json_encode($sp['inbox_author_you'] ?? 'You') ?>,
    };

    document.querySelectorAll('.adm-support-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            const id = tab.getAttribute('data-tab');
            document.querySelectorAll('.adm-support-tab').forEach(function (t) {
                t.classList.toggle('is-active', t === tab);
                t.setAttribute('aria-selected', t === tab ? 'true' : 'false');
            });
            document.querySelectorAll('.adm-support-panel').forEach(function (p) {
                p.hidden = p.getAttribute('data-panel') !== id;
            });
        });
    });

    async function aiCompose(mode, draft, extra) {
        const res = await fetch(API_AI, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify(Object.assign({ mode: mode, draft: draft, lang: LANG }, extra || {})),
        });
        return res.json();
    }

    function setAiLoading(btn, panel, statusEl, loading) {
        if (!btn) return;
        btn.classList.toggle('is-loading', loading);
        btn.disabled = loading;
        if (panel) panel.classList.toggle('is-ai-thinking', loading);
        var icon = btn.querySelector('.adm-ai-btn-icon');
        var label = btn.querySelector('.adm-ai-btn-label');
        if (icon) {
            icon.className = loading ? 'fas fa-brain adm-ai-btn-icon' : 'fas fa-wand-magic-sparkles adm-ai-btn-icon';
        }
        if (label) {
            label.textContent = loading
                ? (btn.getAttribute('data-thinking') || 'Thinking…')
                : (btn.getAttribute('data-label-default') || label.textContent);
        }
        if (statusEl) {
            if (loading) {
                statusEl.hidden = false;
                statusEl.className = 'adm-ai-status adm-ai-status--block adm-ai-status--loading';
                statusEl.innerHTML = (btn.getAttribute('data-thinking') || 'Thinking…')
                    + ' <span class="adm-ai-thinking-dots" aria-hidden="true"><span></span><span></span><span></span></span>';
            } else {
                statusEl.hidden = true;
                statusEl.textContent = '';
            }
        }
    }

    document.getElementById('owner_ai_btn').addEventListener('click', async function () {
        const btn = this;
        const panel = document.getElementById('owner_ai_panel');
        const statusEl = document.getElementById('owner_ai_status');
        setAiLoading(btn, panel, statusEl, true);
        try {
            const data = await aiCompose('owner_support', document.getElementById('owner_draft').value, {});
            if (!data.ok) throw new Error(data.error || MSG.ai_error);
            if (data.subject) document.getElementById('owner_subject').value = data.subject;
            if (data.body) document.getElementById('owner_body').value = data.body;
        } catch (e) {
            alert(e.message || MSG.ai_error);
        } finally {
            setAiLoading(btn, panel, statusEl, false);
        }
    });

    document.getElementById('owner_send_btn').addEventListener('click', async function () {
        const status = document.getElementById('owner_status');
        const subject = document.getElementById('owner_subject').value.trim();
        const body = document.getElementById('owner_body').value.trim();
        if (!subject || !body) return;
        const btn = this;
        btn.disabled = true;
        status.hidden = true;
        try {
            const res = await fetch(API_OWNER, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({
                    subject: subject,
                    body: body,
                    category: document.getElementById('owner_category').value,
                    from_email: document.getElementById('owner_email').value.trim(),
                    lang: LANG,
                }),
            });
            const data = await res.json();
            if (!data.ok) throw new Error(data.error || MSG.sent_error);
            status.textContent = MSG.sent_ok;
            status.className = 'adm-support-status is-ok';
            status.hidden = false;
            document.getElementById('owner_draft').value = '';
        } catch (e) {
            status.textContent = e.message || MSG.sent_error;
            status.className = 'adm-support-status is-err';
            status.hidden = false;
        } finally {
            btn.disabled = false;
        }
    });

    document.getElementById('client_ai_btn').addEventListener('click', async function () {
        const btn = this;
        const panel = document.getElementById('client_ai_panel');
        const statusEl = document.getElementById('client_ai_status');
        setAiLoading(btn, panel, statusEl, true);
        try {
            const data = await aiCompose('client_email', document.getElementById('client_draft').value, {
                client_name: document.getElementById('client_name').value.trim(),
                topic: document.getElementById('client_topic').value.trim(),
            });
            if (!data.ok) throw new Error(data.error || MSG.ai_error);
            if (data.subject) document.getElementById('client_subject').value = data.subject;
            if (data.body) document.getElementById('client_body').value = data.body;
        } catch (e) {
            alert(e.message || MSG.ai_error);
        } finally {
            setAiLoading(btn, panel, statusEl, false);
        }
    });

    document.getElementById('client_copy_btn').addEventListener('click', function () {
        const text = (document.getElementById('client_subject').value ? 'Subject: ' + document.getElementById('client_subject').value + '\n\n' : '')
            + document.getElementById('client_body').value;
        navigator.clipboard.writeText(text).then(function () {
            const st = document.getElementById('client_status');
            st.textContent = MSG.copied;
            st.className = 'adm-support-status is-ok';
            st.hidden = false;
        });
    });

    document.getElementById('client_mailto_btn').addEventListener('click', function () {
        const sub = encodeURIComponent(document.getElementById('client_subject').value || '');
        const body = encodeURIComponent(document.getElementById('client_body').value || '');
        window.location.href = 'mailto:?subject=' + sub + '&body=' + body;
    });

    var inboxThreads = [];
    var inboxActiveId = INBOX_ID || '';

    function inboxEsc(s) {
        var d = document.createElement('div');
        d.textContent = s == null ? '' : String(s);
        return d.innerHTML;
    }

    function inboxFileUrl(msgId, postId, attId) {
        return API_MESSAGE_FILE + '?message_id=' + encodeURIComponent(msgId) + '&post_id=' + encodeURIComponent(postId) + '&att_id=' + encodeURIComponent(attId);
    }

    function renderInboxAttachments(msgId, post) {
        var atts = post.attachments || [];
        if (!atts.length) return '';
        var html = '<div class="adm-inbox-atts">';
        atts.forEach(function (att) {
            var url = inboxFileUrl(msgId, post.id, att.id);
            if ((att.mime || '').indexOf('image/') === 0) {
                html += '<a href="' + inboxEsc(url) + '" target="_blank" rel="noopener"><img src="' + inboxEsc(url) + '" alt="" class="adm-inbox-img" loading="lazy"></a>';
            } else {
                html += '<a href="' + inboxEsc(url) + '" target="_blank" rel="noopener">' + inboxEsc(att.name || 'file') + '</a>';
            }
        });
        return html + '</div>';
    }

    function renderInboxThread(thread) {
        var el = document.getElementById('adm_inbox_thread');
        if (!el) return;
        var posts = (thread && thread.thread) || [];
        var html = '';
        posts.forEach(function (post) {
            var isOwner = post.author === 'owner';
            var author = isOwner ? MSG.inbox_author_owner : (post.author_name || MSG.inbox_author_you);
            html += '<div class="adm-inbox-bubble' + (isOwner ? ' is-owner' : ' is-client') + '">' +
                '<div class="adm-inbox-bubble-head"><span>' + inboxEsc(author) + '</span><span>' + inboxEsc(post.ts_label || '') + '</span></div>';
            if (post.body) html += '<div class="adm-inbox-bubble-body">' + inboxEsc(post.body) + '</div>';
            html += renderInboxAttachments(thread.id, post) + '</div>';
        });
        el.innerHTML = html;
        el.scrollTop = el.scrollHeight;
    }

    function selectInboxThread(id, pushUrl) {
        var thread = inboxThreads.find(function (t) { return t.id === id; });
        if (!thread) return;
        inboxActiveId = id;
        document.getElementById('adm_inbox_detail_empty').classList.add('hidden');
        document.getElementById('adm_inbox_detail').classList.remove('hidden');
        document.getElementById('adm_inbox_subject').textContent = thread.subject || '—';
        renderInboxThread(thread);
        document.querySelectorAll('.adm-inbox-item').forEach(function (btn) {
            btn.classList.toggle('is-active', btn.getAttribute('data-id') === id);
        });
        if (window.matchMedia('(max-width: 767px)').matches) {
            document.getElementById('adm_inbox_list_wrap').classList.add('is-hidden');
            document.getElementById('adm_inbox_back').classList.remove('hidden');
        }
        if (pushUrl && window.history && window.history.replaceState) {
            var url = new URL(window.location.href);
            url.searchParams.set('tab', 'inbox');
            url.searchParams.set('id', id);
            window.history.replaceState({}, '', url.toString());
        }
        fetch(API_MESSAGES + '?id=' + encodeURIComponent(id), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.ok && data.thread) {
                    var idx = inboxThreads.findIndex(function (t) { return t.id === id; });
                    if (idx >= 0) inboxThreads[idx] = data.thread;
                    renderInboxThread(data.thread);
                    renderInboxList();
                }
            });
    }

    function renderInboxList() {
        var list = document.getElementById('adm_inbox_list');
        var empty = document.getElementById('adm_inbox_empty');
        var badge = document.getElementById('inbox_unread_badge');
        if (!list) return;
        list.innerHTML = '';
        var unread = 0;
        inboxThreads.forEach(function (t) { if (t.client_unread) unread++; });
        if (badge) {
            badge.textContent = unread > 0 ? String(unread) : '';
            badge.classList.toggle('hidden', unread === 0);
        }
        if (inboxThreads.length === 0) {
            empty.classList.remove('hidden');
            document.getElementById('adm_inbox_detail_empty').classList.remove('hidden');
            document.getElementById('adm_inbox_detail').classList.add('hidden');
            return;
        }
        empty.classList.add('hidden');
        inboxThreads.forEach(function (t) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'adm-inbox-item' + (t.id === inboxActiveId ? ' is-active' : '') + (t.client_unread ? ' is-unread' : '');
            btn.setAttribute('data-id', t.id);
            var preview = t.preview || t.body || '';
            btn.innerHTML = '<div class="adm-inbox-item-top"><strong>' + inboxEsc(t.subject || '—') + '</strong><span>' + inboxEsc(t.ts_label || '') + '</span></div>' +
                '<div class="adm-inbox-item-preview">' + inboxEsc(preview) + '</div>' +
                (t.client_unread ? '<span class="adm-inbox-unread-pill">' + inboxEsc(MSG.inbox_unread) + '</span>' : '');
            btn.addEventListener('click', function () { selectInboxThread(t.id, true); });
            list.appendChild(btn);
        });
        if (!inboxActiveId && inboxThreads.length) {
            selectInboxThread(inboxThreads[0].id, false);
        } else if (inboxActiveId) {
            selectInboxThread(inboxActiveId, false);
        }
    }

    function loadInboxThreads() {
        return fetch(API_MESSAGES, { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.ok) return;
                inboxThreads = data.threads || [];
                renderInboxList();
            });
    }

    loadInboxThreads();

    document.getElementById('adm_inbox_back').addEventListener('click', function () {
        document.getElementById('adm_inbox_list_wrap').classList.remove('is-hidden');
        this.classList.add('hidden');
    });

    document.getElementById('adm_inbox_reply_form').addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!inboxActiveId) return;
        var body = document.getElementById('adm_inbox_reply_body').value.trim();
        var files = document.getElementById('adm_inbox_files').files;
        if (!body && (!files || !files.length)) return;
        var btn = document.getElementById('adm_inbox_send_btn');
        var status = document.getElementById('adm_inbox_status');
        btn.disabled = true;
        status.hidden = true;
        try {
            var fd = new FormData();
            fd.append('message_id', inboxActiveId);
            fd.append('body', body);
            for (var i = 0; i < files.length; i++) fd.append('attachments[]', files[i]);
            var res = await fetch(API_MESSAGES, { method: 'POST', body: fd, credentials: 'same-origin' });
            var data = await res.json();
            if (!data.ok) throw new Error(data.error || MSG.inbox_sent_error);
            if (data.thread) {
                var idx = inboxThreads.findIndex(function (t) { return t.id === inboxActiveId; });
                if (idx >= 0) inboxThreads[idx] = data.thread;
                renderInboxThread(data.thread);
                renderInboxList();
            }
            document.getElementById('adm_inbox_reply_body').value = '';
            document.getElementById('adm_inbox_files').value = '';
            status.textContent = MSG.inbox_sent_ok;
            status.className = 'adm-support-status is-ok';
            status.hidden = false;
        } catch (err) {
            status.textContent = err.message || MSG.inbox_sent_error;
            status.className = 'adm-support-status is-err';
            status.hidden = false;
        } finally {
            btn.disabled = false;
        }
    });
})();
</script>

<?php require __DIR__ . '/includes/layout-end.php'; ?>