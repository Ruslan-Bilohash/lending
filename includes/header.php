<?php
$page_title = $page_title ?? ($t['meta']['title'] ?? 'Lending CMS');
$page_desc  = $page_desc ?? ($t['meta']['description'] ?? '');
$body_class = $body_class ?? '';
$template_id = $template_id ?? 0;
$is_landing = !empty($is_landing);
$css_ver = '10';
?>
<!DOCTYPE html>
<html lang="<?= ld_h($lang_meta['html'] ?? 'lt') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ld_h($page_title) ?></title>
    <meta name="description" content="<?= ld_h($page_desc) ?>">
    <meta name="robots" content="index, follow">
    <?php if (!empty($ld_seo_vars) && is_array($ld_seo_vars)): ?>
    <?php ld_render_seo_head($ld_seo_vars, $lang); ld_render_schema($lang); ?>
    <?php endif; ?>
    <link rel="sitemap" type="application/xml" title="Sitemap" href="<?= ld_h(ld_absolute_url('sitemap.xml')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= ld_h(ld_asset('css/base.css')) ?>?v=<?= $css_ver ?>">
    <?php if ($is_landing && $template_id >= 1 && $template_id <= 10): ?>
    <link rel="stylesheet" href="<?= ld_h(ld_asset('css/themes.css')) ?>?v=<?= $css_ver ?>">
    <?php endif; ?>
    <?php if ($is_landing && ld_is_driving_preset()): ?>
    <link rel="stylesheet" href="<?= ld_h(ld_asset('css/driving-premium.css')) ?>?v=<?= $css_ver ?>">
    <?php endif; ?>
    <?php $ldInlineCss = ld_design_inline_css(); if ($ldInlineCss !== ''): ?>
    <style><?= $ldInlineCss ?></style>
    <?php endif; ?>
</head>
<body class="<?= ld_h(trim($body_class . ($is_landing ? ' ld-landing ld-theme-' . str_pad((string)$template_id, 2, '0', STR_PAD_LEFT) . (ld_is_driving_preset() ? ' ld-driving-premium' : '') : ' ld-hub'))) ?>">

<?php if (!$is_landing):
    $h = $t['home'] ?? [];
    $adminLabel = $h['admin_cta_short'] ?? $h['admin_cta'] ?? 'Admin';
    $liveTpl = ld_active_template();
?>
<header class="ld-top" id="ldHubHeader">
    <div class="ld-demo-strip">
        <i class="fas fa-layer-group" aria-hidden="true"></i>
        <span class="ld-demo-strip-text"><?= ld_h($t['nav']['demo_badge'] ?? '') ?> · <?= ld_h($t['home']['demo_disclaimer_short'] ?? '') ?></span>
        <div class="ld-demo-strip-actions">
            <a href="<?= ld_h(ld_admin_url('login.php')) ?>" class="ld-strip-cta"><i class="fas fa-sparkles"></i> <?= ld_h($h['sticky_cta'] ?? $h['admin_cta'] ?? '') ?></a>
            <a href="https://bilohash.com/">bilohash.com →</a>
        </div>
    </div>
    <div class="ld-top-inner">
        <a href="<?= ld_h(ld_url('index.php')) ?>" class="ld-brand">
            <span class="ld-brand-icon"><i class="fas fa-store"></i></span>
            <span class="ld-brand-text"><?= ld_h($t['meta']['site_name'] ?? 'Lending CMS') ?></span>
        </a>
        <div class="ld-top-actions">
            <a href="<?= ld_h(ld_admin_url('login.php')) ?>" class="ld-btn ld-btn-sm ld-btn-primary ld-header-cta" title="<?= ld_h($adminLabel) ?>">
                <i class="fas fa-user-shield" aria-hidden="true"></i>
                <span class="ld-btn-label"><?= ld_h($adminLabel) ?></span>
            </a>
            <?php require __DIR__ . '/lang-dropdown.php'; ?>
            <button type="button" class="ld-menu-btn ld-hub-menu-btn" id="ldHubMenuBtn" aria-expanded="false" aria-controls="ldHubMobileNav" aria-label="<?= ld_h($t['nav']['menu'] ?? 'Menu') ?>">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    <nav class="ld-mobile-nav ld-hub-mobile-nav" id="ldHubMobileNav" hidden aria-label="<?= ld_h($t['nav']['main_nav'] ?? '') ?>">
        <div class="ld-mobile-nav-head">
            <span><?= ld_h($t['nav']['menu'] ?? 'Menu') ?></span>
            <button type="button" class="ld-mobile-nav-close" data-nav-close aria-label="<?= ld_h($t['nav']['menu_close'] ?? 'Close') ?>"><i class="fas fa-times"></i></button>
        </div>
        <a href="<?= ld_h(ld_admin_url('login.php')) ?>"><i class="fas fa-user-shield"></i> <?= ld_h($h['admin_cta'] ?? $adminLabel) ?></a>
        <a href="<?= ld_h(ld_url('template.php', ['t' => $liveTpl])) ?>"><i class="fas fa-eye"></i> <?= ld_h($h['live_cta'] ?? 'Live') ?></a>
        <a href="https://bilohash.com/"><i class="fas fa-globe"></i> <?= ld_h($h['bilohash_home_cta'] ?? 'bilohash.com') ?></a>
        <div class="ld-mobile-lang">
            <span class="ld-mobile-lang-label"><?= ld_h($t['nav']['lang_menu'] ?? 'Language') ?></span>
            <?php foreach (ld_langs() as $code => $info): ?>
            <a href="<?= ld_h(ld_lang_url($code)) ?>" class="<?= $lang === $code ? 'is-active' : '' ?>">
                <span aria-hidden="true"><?= $info['flag'] ?></span> <?= ld_h($info['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </nav>
    <div class="ld-mobile-backdrop" id="ldHubMobileBackdrop" hidden></div>
</header>
<?php else:
    $business = ld_business();
    $meta = ld_templates_meta()[$template_id] ?? ld_templates_meta()[1];
    $l = $t['landing'] ?? [];
    $navServices = ld_section_text('services', 'title', $lang, $l['services_title'] ?? ($t['nav']['services'] ?? ''));
    $navTeam = ld_section_text('team', 'title', $lang, $l['team_title'] ?? ($t['nav']['team'] ?? ''));
    $navReviews = ld_section_text('reviews', 'title', $lang, $l['reviews_title'] ?? ($t['nav']['reviews'] ?? ''));
    $showReviews = ld_has_reviews();
?>
<header class="ld-landing-header" id="ldHeader">
    <?php require __DIR__ . '/demo-banner.php'; ?>
    <div class="ld-landing-header-inner">
        <a href="<?= ld_h(ld_url('template.php', ['t' => $template_id])) ?>" class="ld-landing-logo">
            <i class="fas <?= ld_h($meta['icon']) ?>" aria-hidden="true"></i>
            <span><?= ld_h(ld_pick($business['name'], $lang)) ?></span>
        </a>
        <nav class="ld-landing-nav" aria-label="<?= ld_h($t['nav']['main_nav'] ?? '') ?>">
            <a href="#services"><?= ld_h($navServices) ?></a>
            <a href="#team"><?= ld_h($navTeam) ?></a>
            <?php if ($showReviews): ?><a href="#reviews"><?= ld_h($navReviews) ?></a><?php endif; ?>
            <a href="#faq"><?= ld_h($t['nav']['faq'] ?? '') ?></a>
            <a href="#contact"><?= ld_h($t['nav']['contact'] ?? '') ?></a>
        </nav>
        <div class="ld-landing-tools">
            <?php require __DIR__ . '/lang-dropdown.php'; ?>
            <a href="<?= ld_h(ld_url('index.php')) ?>" class="ld-back-link" title="<?= ld_h($t['nav']['back'] ?? '') ?>"><i class="fas fa-th-large"></i></a>
            <button type="button" class="ld-menu-btn" id="ldMenuBtn" aria-expanded="false" aria-controls="ldMobileNav" aria-label="<?= ld_h($t['nav']['menu'] ?? 'Menu') ?>">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    <nav class="ld-mobile-nav" id="ldMobileNav" hidden aria-label="<?= ld_h($t['nav']['main_nav'] ?? '') ?>">
        <div class="ld-mobile-nav-head">
            <span><?= ld_h($t['nav']['menu'] ?? 'Menu') ?></span>
            <button type="button" class="ld-mobile-nav-close" data-nav-close aria-label="<?= ld_h($t['nav']['menu_close'] ?? 'Close') ?>"><i class="fas fa-times"></i></button>
        </div>
        <a href="#services"><?= ld_h($navServices) ?></a>
        <a href="#team"><?= ld_h($navTeam) ?></a>
        <?php if ($showReviews): ?><a href="#reviews"><?= ld_h($navReviews) ?></a><?php endif; ?>
        <a href="#faq"><?= ld_h($t['nav']['faq'] ?? '') ?></a>
        <a href="#contact"><?= ld_h($t['nav']['contact'] ?? '') ?></a>
        <a href="<?= ld_h(ld_url('index.php')) ?>"><i class="fas fa-th-large"></i> <?= ld_h($t['nav']['back'] ?? '') ?></a>
    </nav>
    <div class="ld-mobile-backdrop" id="ldMobileBackdrop" hidden></div>
</header>
<?php endif; ?>

<main class="ld-main" id="main">