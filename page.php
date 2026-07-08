<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/pages.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$page = $slug !== '' ? ld_get_page_by_slug($slug) : null;

if (!$page || ($page['status'] ?? '') !== 'published') {
    http_response_code(404);
    $page_title = $t['pages']['not_found_title'] ?? 'Page not found';
    $page_desc = '';
    $body_class = 'ld-page ld-page--404';
    require __DIR__ . '/includes/header.php';
    echo '<section class="ld-page-wrap"><div class="ld-container">';
    echo '<h1>' . ld_h($page_title) . '</h1>';
    echo '<p><a href="' . ld_h(ld_url('index.php')) . '">' . ld_h($t['pages']['back_home'] ?? 'Home') . '</a></p>';
    echo '</div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$localized = ld_page_localize($page, $lang);
$title = trim((string) ($localized['seo_title'] ?? ''));
if ($title === '') {
    $title = trim((string) ($localized['title'] ?? ''));
}
$desc = trim((string) ($localized['seo_description'] ?? ''));
if ($desc === '') {
    $bodyText = trim((string) ($localized['body'] ?? ''));
    $desc = mb_substr(preg_replace('/\s+/', ' ', $bodyText) ?? '', 0, 160);
}

global $site_url;
$canonical = ld_absolute_url('page.php', array_filter([
    'slug' => $page['slug'],
    'lang' => $lang !== 'no' ? $lang : null,
]));

$ld_seo_vars = [
    'title' => $title,
    'description' => $desc,
    'keywords' => trim((string) ($localized['seo_keywords'] ?? '')),
    'og_image' => trim((string) (ld_seo()['og_image'] ?? '')),
    'canonical' => $canonical,
    'site_name' => ld_pick(ld_business()['name'], $lang) ?: ($t['meta']['site_name'] ?? 'Lending CMS'),
];

$page_title = $title;
$page_desc = $desc;
$body_class = 'ld-page ld-page--service';
$is_landing = false;

require __DIR__ . '/includes/header.php';
$p = $t['pages'] ?? [];
?>
<section class="ld-page-wrap">
    <div class="ld-container ld-page-inner">
        <nav class="ld-page-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= ld_h(ld_url('index.php')) ?>"><?= ld_h($p['breadcrumb_home'] ?? 'Home') ?></a>
            <span aria-hidden="true">/</span>
            <span><?= ld_h($localized['title'] ?? '') ?></span>
        </nav>
        <article class="ld-page-article">
            <h1><?= ld_h($localized['title'] ?? '') ?></h1>
            <div class="ld-page-body"><?= nl2br(ld_h($localized['body'] ?? '')) ?></div>
        </article>
        <p class="ld-page-back">
            <a href="<?= ld_h(ld_url('template.php', ['t' => ld_active_template()])) ?>"><i class="fas fa-arrow-left"></i> <?= ld_h($p['back_landing'] ?? 'Back to landing') ?></a>
        </p>
    </div>
</section>
<?php
require __DIR__ . '/includes/footer.php';