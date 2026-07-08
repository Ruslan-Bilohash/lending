<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__, 2) . '/init.php';
ld_admin_require();

$lang = $GLOBALS['lang'] ?? 'lt';
$result = ld_ai_seo_analyze($lang);
echo json_encode($result, JSON_UNESCAPED_UNICODE);