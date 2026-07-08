<?php
require_once __DIR__ . '/_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ld_admin_json_response(['ok' => false, 'error' => 'POST required'], 405);
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '', true);
if (!is_array($payload)) {
    $payload = $_POST;
}

$mode = trim((string) ($payload['mode'] ?? 'owner_support'));
$draft = trim((string) ($payload['draft'] ?? ''));
$contextLang = trim((string) ($payload['lang'] ?? $lang ?? 'en')) ?: 'en';
$clientName = trim((string) ($payload['client_name'] ?? ''));
$topic = trim((string) ($payload['topic'] ?? ''));

$result = ld_ai_compose_message_draft($mode, $draft, $contextLang, [
    'client_name' => $clientName,
    'topic' => $topic,
    'admin_name' => ld_admin_display_name(),
]);

ld_admin_json_response([
    'ok' => $result['ok'],
    'demo' => $result['demo'],
    'subject' => $result['subject'] ?? '',
    'body' => $result['body'] ?? '',
    'error' => $result['error'] ?? '',
], $result['ok'] ? 200 : 400);