<?php
require_once __DIR__ . '/_bootstrap.php';

$rootIncludes = dirname(__DIR__, 3) . '/includes/ecosystem-owner-messages.php';
if (!is_file($rootIncludes)) {
    http_response_code(500);
    exit('Messaging module missing');
}
require_once $rootIncludes;

$adminUrl = ld_admin_support_shop_url();
$user = ld_admin_display_name();
$email = '';

$messageId = trim((string) ($_GET['message_id'] ?? ''));
$postId = trim((string) ($_GET['post_id'] ?? ''));
$attId = trim((string) ($_GET['att_id'] ?? ''));

if ($messageId === '' || $postId === '' || $attId === '') {
    http_response_code(400);
    exit('Bad request');
}

if (!ecosystem_message_client_can_access($messageId, $user, $email, $adminUrl)) {
    http_response_code(403);
    exit('Forbidden');
}

$res = ecosystem_message_attachment_resolve($messageId, $postId, $attId);
if (!$res['ok'] || empty($res['path'])) {
    http_response_code(404);
    exit('Not found');
}

$mime = (string) ($res['mime'] ?? 'application/octet-stream');
$name = (string) ($res['name'] ?? 'file');
$path = (string) $res['path'];

header('Content-Type: ' . $mime);
header('Content-Length: ' . (string) filesize($path));
header('Content-Disposition: inline; filename="' . str_replace('"', '', $name) . '"');
header('Cache-Control: private, max-age=3600');
readfile($path);
exit;