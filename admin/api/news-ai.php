<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__, 2) . '/init.php';
ld_admin_require();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

$brief = trim((string) ($_POST['brief'] ?? ''));
$lang = trim((string) ($_POST['lang'] ?? ($GLOBALS['lang'] ?? 'en')));
$result = ld_news_ai_write($lang, $brief);
echo json_encode($result, JSON_UNESCAPED_UNICODE);