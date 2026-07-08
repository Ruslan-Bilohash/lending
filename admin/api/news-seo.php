<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__, 2) . '/init.php';
ld_admin_require();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

$lang = trim((string) ($_POST['lang'] ?? ($GLOBALS['lang'] ?? 'en')));
$fields = [
    'title' => trim((string) ($_POST['title'] ?? '')),
    'excerpt' => trim((string) ($_POST['excerpt'] ?? '')),
    'body' => trim((string) ($_POST['body'] ?? '')),
    'seo_title' => trim((string) ($_POST['seo_title'] ?? '')),
    'seo_description' => trim((string) ($_POST['seo_description'] ?? '')),
    'seo_keywords' => trim((string) ($_POST['seo_keywords'] ?? '')),
];
$result = ld_news_seo_analyze($lang, $fields);
echo json_encode($result, JSON_UNESCAPED_UNICODE);