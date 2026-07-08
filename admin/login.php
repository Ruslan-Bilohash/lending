<?php
require_once __DIR__ . '/init.php';

if (ld_admin_logged()) {
    header('Location: ' . ld_admin_url('index.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (ld_admin_login(trim($_POST['username'] ?? ''), $_POST['password'] ?? '')) {
        header('Location: ' . ld_admin_url('index.php'));
        exit;
    }
    $error = $ta['login_error'] ?? 'Invalid username or password';
}
?>
<!DOCTYPE html>
<html lang="<?= ld_h($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= ld_h($ta['login_title'] ?? 'Admin') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= ld_h(ld_asset('css/admin.css')) ?>?v=5">
</head>
<body>
<div class="adm-login-wrap">
    <div class="adm-login-lang">
        <?php require __DIR__ . '/includes/lang-dropdown.php'; ?>
    </div>
    <div class="adm-login-box">
        <div class="logo">
            <div class="icon">L</div>
            <h1><?= ld_h($ta['login_title'] ?? 'Lending CMS') ?></h1>
            <p class="sub"><?= ld_h($ta['login_sub'] ?? '') ?></p>
        </div>
        <div class="adm-demo-hint"><i class="fas fa-info-circle"></i> <?= ld_h($ta['demo_creds'] ?? '') ?></div>
        <div class="adm-demo-hint adm-demo-disclaimer"><i class="fas fa-triangle-exclamation"></i> <?= ld_h($ta['demo_disclaimer_admin'] ?? '') ?></div>
        <?php if ($error): ?>
        <div class="adm-login-error"><?= ld_h($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="adm-field">
                <label for="username"><?= ld_h($ta['username'] ?? '') ?></label>
                <input type="text" id="username" name="username" required autocomplete="username" value="demo">
            </div>
            <div class="adm-field">
                <label for="password"><?= ld_h($ta['password'] ?? '') ?></label>
                <input type="password" id="password" name="password" required autocomplete="current-password" value="bilolending2026">
            </div>
            <button type="submit" class="adm-btn adm-btn-primary" style="width:100%;justify-content:center;padding:12px;margin-top:8px">
                <i class="fas fa-sign-in-alt"></i> <?= ld_h($ta['login_btn'] ?? '') ?>
            </button>
        </form>
        <p style="text-align:center;margin-top:20px;font-size:12px">
            <a href="<?= ld_h(ld_url('index.php')) ?>">← <?= ld_h($t['nav']['home'] ?? 'Home') ?></a>
        </p>
    </div>
</div>
<script>
(function () {
    document.querySelectorAll('[data-adm-lang-dd]').forEach(function (dd) {
        var toggle = dd.querySelector('.adm-lang-btn');
        var menu = dd.querySelector('.adm-lang-menu');
        if (!toggle || !menu) return;
        menu.querySelectorAll('a[href]').forEach(function (link) {
            link.addEventListener('click', function (e) { e.stopPropagation(); });
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
})();
</script>
</body>
</html>