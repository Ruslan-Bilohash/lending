<?php
declare(strict_types=1);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/pages.php';
require_once __DIR__ . '/includes/sitemap.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');
echo ld_render_sitemap_xml();