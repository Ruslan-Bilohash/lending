<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__) . '/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'POST required']);
    exit;
}

if (!ld_ai_enabled()) {
    echo json_encode(['ok' => false, 'error' => 'AI disabled']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: '', true);
$message = trim((string) ($data['message'] ?? $_POST['message'] ?? ''));
$chatLang = trim((string) ($data['lang'] ?? $_POST['lang'] ?? $lang));
if (!in_array($chatLang, ld_langs_codes(), true)) {
    $chatLang = $lang;
}

$result = ld_ai_chat($message, $chatLang);
echo json_encode([
    'ok' => $result['ok'],
    'text' => $result['text'],
    'demo' => $result['demo'] ?? false,
], JSON_UNESCAPED_UNICODE);