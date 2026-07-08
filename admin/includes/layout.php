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
    <link rel="stylesheet" href="<?= ld_h(ld_asset('css/admin.css')) ?>?v=18">
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
        <nav class="adm-nav">
            <a href="<?= ld_h(ld_admin_url('index.php')) ?>" class="<?= ($admin_page ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i> <?= ld_h($ta['dashboard'] ?? '') ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('settings.php')) ?>" class="<?= ($admin_page ?? '') === 'settings' ? 'active' : '' ?>">
                <i class="fas fa-briefcase"></i> <?= ld_h(ld_admin_t('settings')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('content.php')) ?>" class="<?= ($admin_page ?? '') === 'content' ? 'active' : '' ?>">
                <i class="fas fa-pen-to-square"></i> <?= ld_h($ta['content'] ?? '') ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('leads.php')) ?>" class="<?= ($admin_page ?? '') === 'leads' ? 'active' : '' ?>">
                <i class="fas fa-inbox"></i> <?= ld_h($ta['leads'] ?? '') ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('students.php')) ?>" class="<?= ($admin_page ?? '') === 'students' ? 'active' : '' ?>">
                <i class="fas fa-user-graduate"></i> <?= ld_h(ld_admin_t('students')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('news.php')) ?>" class="<?= ($admin_page ?? '') === 'news' ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i> <?= ld_h(ld_admin_t('news')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('pages.php')) ?>" class="<?= ($admin_page ?? '') === 'pages' ? 'active' : '' ?>">
                <i class="fas fa-file-lines"></i> <?= ld_h($ta['pages_nav'] ?? 'Service pages') ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('invoices.php')) ?>" class="<?= ($admin_page ?? '') === 'invoices' ? 'active' : '' ?>">
                <i class="fas fa-file-invoice-dollar"></i> <?= ld_h(ld_admin_t('invoices')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('design.php')) ?>" class="<?= ($admin_page ?? '') === 'design' ? 'active' : '' ?>">
                <i class="fas fa-palette"></i> <?= ld_h(ld_admin_t('design')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('blocks.php')) ?>" class="<?= ($admin_page ?? '') === 'blocks' ? 'active' : '' ?>">
                <i class="fas fa-cubes"></i> <?= ld_h(ld_admin_t('blocks')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('seo.php')) ?>" class="<?= ($admin_page ?? '') === 'seo' ? 'active' : '' ?>">
                <i class="fas fa-search"></i> <?= ld_h(ld_admin_t('seo')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('integrations.php')) ?>" class="<?= ($admin_page ?? '') === 'integrations' ? 'active' : '' ?>">
                <i class="fas fa-plug"></i> <?= ld_h($ta['integrations'] ?? '') ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('ai.php')) ?>" class="<?= ($admin_page ?? '') === 'ai' ? 'active' : '' ?>">
                <i class="fas fa-robot"></i> <?= ld_h(ld_admin_t('ai')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('templates.php')) ?>" class="<?= ($admin_page ?? '') === 'templates' ? 'active' : '' ?>">
                <i class="fas fa-palette"></i> <?= ld_h($ta['templates'] ?? '') ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('help.php')) ?>" class="<?= ($admin_page ?? '') === 'help' ? 'active' : '' ?>">
                <i class="fas fa-book"></i> <?= ld_h($ta['help_nav'] ?? ($ta['api_guide_title'] ?? 'Help')) ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('support.php')) ?>" class="<?= ($admin_page ?? '') === 'support' ? 'active' : '' ?>">
                <i class="fas fa-headset"></i> <?= ld_h($ta['support_owner'] ?? 'Support & mail') ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('changelog.php')) ?>" class="<?= ($admin_page ?? '') === 'changelog' ? 'active' : '' ?>">
                <i class="fas fa-clock-rotate-left"></i> <?= ld_h($ta['changelog'] ?? 'Changelog') ?>
            </a>
            <a href="<?= ld_h(ld_url('index.php')) ?>" target="_blank">
                <i class="fas fa-external-link-alt"></i> <?= ld_h($ta['view_site'] ?? '') ?>
            </a>
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