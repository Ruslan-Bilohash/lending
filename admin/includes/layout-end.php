        </div>
    </main>
</div>
<?php
require __DIR__ . '/admin-toast-shell.php';
$admFlash = ld_admin_detect_flash();
if ($admFlash === null && !empty($saved)) {
    $admFlash = ['type' => 'success', 'message' => ld_admin_t('notify_saved', ld_admin_t('saved'))];
}
?>
<script>window.LD_ADMIN_I18N = <?= json_encode(ld_admin_i18n_js(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;</script>
<script>window.LD_ADMIN_NOTIFY = <?= json_encode(ld_admin_notify_js(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;</script>
<script>window.LD_ADMIN_FLASH = <?= json_encode($admFlash, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;</script>
<script src="<?= ld_h(ld_asset('js/admin-toast.js')) ?>?v=1"></script>
<script>
(function () {
    var btn = document.getElementById('admMenuBtn');
    var sidebar = document.getElementById('admSidebar');
    var overlay = document.getElementById('admOverlay');
    if (btn && sidebar) {
        function closeNav() { sidebar.classList.remove('open'); if (overlay) overlay.hidden = true; }
        btn.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            if (overlay) overlay.hidden = !sidebar.classList.contains('open');
        });
        if (overlay) overlay.addEventListener('click', closeNav);
    }

    document.querySelectorAll('[data-adm-lang-dd]').forEach(function (dd) {
        var toggle = dd.querySelector('.adm-lang-btn');
        var menu = dd.querySelector('.adm-lang-menu');
        if (!toggle || !menu) return;
        menu.querySelectorAll('a[href]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        });
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var open = menu.hidden;
            document.querySelectorAll('.adm-lang-menu').forEach(function (m) { m.hidden = true; });
            document.querySelectorAll('.adm-lang-btn').forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
            if (open) {
                menu.hidden = false;
                toggle.setAttribute('aria-expanded', 'true');
            }
        });
    });
    document.addEventListener('click', function () {
        document.querySelectorAll('.adm-lang-menu').forEach(function (m) { m.hidden = true; });
        document.querySelectorAll('.adm-lang-btn').forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.adm-lang-menu').forEach(function (m) { m.hidden = true; });
        document.querySelectorAll('.adm-lang-btn').forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
    });
})();
</script>
<script src="<?= ld_h(ld_asset('js/admin-effects.js')) ?>?v=1" defer></script>
</body>
</html>