<?php
/** @var string $admin_page @var array $ta @var string $page_title */
$layout_title = $page_title ?? ($ta['dashboard'] ?? 'Admin');
require_once dirname(__DIR__, 2) . '/includes/changelog.php';
$layout_version = ld_version();
?>
<!DOCTYPE html>
<html lang="<?= ld_h($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= ld_h($layout_title) ?> — <?= ld_h(ld_admin_t('brand_product', 'Business Landing CMS')) ?> <?= ld_h(ld_admin_t('page_title_admin', 'Admin')) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= ld_h(ld_asset('css/admin.css')) ?>?v=19">
</head>
<body class="adm-body">
<div class="adm-sidebar-overlay" id="admOverlay" hidden></div>
<div class="adm-layout">
    <aside class="adm-sidebar" id="admSidebar">
        <a href="<?= ld_h(ld_admin_url('index.php')) ?>" class="adm-sidebar-brand">
            <div class="icon">L</div>
            <div>
                <span><?= ld_h(ld_admin_t('brand_product', 'Business Landing CMS')) ?></span>
                <small><?= ld_h(ld_admin_t('title', 'Admin')) ?></small>
            </div>
        </a>
        <nav class="adm-nav" aria-label="<?= ld_h($ta['nav_main'] ?? 'Admin navigation') ?>">
            <?php
            require_once __DIR__ . '/admin-menu.php';
            ld_render_admin_sidebar_nav($ta, (string) ($admin_page ?? ''));
            ?>
        </nav>
        <div class="adm-sidebar-foot">
            <a href="<?= ld_h(ld_admin_url('changelog.php')) ?>" class="adm-version-link<?= ($admin_page ?? '') === 'changelog' ? ' adm-version-link--active' : '' ?>">
                <i class="fas fa-tag"></i> v<?= ld_h($layout_version) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('logout.php')) ?>" class="adm-logout-link">
                <i class="fas fa-sign-out-alt"></i> <?= ld_h($ta['logout'] ?? '') ?>
            </a>
        </div>
    </aside>
    <main class="adm-main">
        <header class="adm-topbar">
            <div class="adm-topbar-start">
                <button type="button" class="adm-menu-btn adm-menu-toggle" id="admMenuBtn" aria-label="<?= ld_h(ld_admin_t('menu_btn')) ?>"><i class="fas fa-bars"></i></button>
                <h1><?= ld_h($layout_title) ?></h1>
            </div>
            <div class="adm-topbar-actions">
                <?php require __DIR__ . '/lang-dropdown.php'; ?>
            </div>
        </header>
        <div class="adm-content">
        <?php if (!empty(ld_admin_t('demo_disclaimer_admin'))): ?>
        <div class="adm-alert adm-alert-info adm-demo-banner">
            <i class="fas fa-flask"></i> <?= ld_h(ld_admin_t('demo_disclaimer_admin')) ?>
        </div>
        <?php endif; ?>