<?php
require_once __DIR__ . '/_bootstrap.php';

$rootIncludes = dirname(__DIR__, 3) . '/includes/ecosystem-owner-messages.php';
if (!is_file($rootIncludes)) {
    ld_admin_json_response(['ok' => false, 'error' => 'Messaging module missing'], 500);
}
require_once $rootIncludes;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ld_admin_json_response(['ok' => false, 'error' => 'POST required'], 405);
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '', true);
if (!is_array($payload)) {
    $payload = $_POST;
}

$subject = trim((string) ($payload['subject'] ?? ''));
$body = trim((string) ($payload['body'] ?? ''));
$category = trim((string) ($payload['category'] ?? 'support'));
$fromEmail = strtolower(trim((string) ($payload['from_email'] ?? '')));

if ($subject === '' || $body === '') {
    ld_admin_json_response(['ok' => false, 'error' => 'subject_body_required'], 400);
}

if ($fromEmail !== '' && !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
    ld_admin_json_response(['ok' => false, 'error' => 'invalid_email'], 400);
}

$id = ecosystem_owner_messages_add([
    'subject' => $subject,
    'body' => $body,
    'category' => $category,
    'from_user' => ld_admin_display_name(),
    'from_name' => ld_admin_display_name(),
    'from_role' => ld_admin_role(),
    'from_email' => $fromEmail,
    'shop_url' => ld_admin_support_shop_url(),
    'lang' => trim((string) ($payload['lang'] ?? $lang ?? 'en')),
    'ip' => ld_admin_client_ip(),
]);

if ($id === null) {
    ld_admin_json_response(['ok' => false, 'error' => 'save_failed'], 500);
}

ld_admin_json_response(['ok' => true, 'id' => $id]);