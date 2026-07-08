<?php
require_once __DIR__ . '/init.php';

$template_id = ld_template_id();
$names = ld_template_names($lang);
$business = ld_business();

$is_landing = true;
$ld_seo_vars = ld_seo_page_vars($lang, true, $template_id);
$page_title = $ld_seo_vars['title'];
$page_desc = $ld_seo_vars['description'];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/landing-body.php';
require __DIR__ . '/includes/footer.php';