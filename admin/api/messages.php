<?php
require_once __DIR__ . '/_bootstrap.php';

$rootIncludes = dirname(__DIR__, 3) . '/includes/ecosystem-owner-messages.php';
if (!is_file($rootIncludes)) {
    ld_admin_json_response(['ok' => false, 'error' => 'Messaging module missing'], 500);
}
require_once $rootIncludes;

$adminUrl = ld_admin_support_shop_url();
$user = ld_admin_display_name();
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = trim((string) ($_GET['id'] ?? ''));
    if ($id !== '') {
        if (!ecosystem_message_client_can_access($id, $user, $email, $adminUrl)) {
            ld_admin_json_response(['ok' => false, 'error' => 'forbidden'], 403);
        }
        $row = ecosystem_owner_messages_by_id($id);
        if ($row === null) {
            ld_admin_json_response(['ok' => false, 'error' => 'not_found'], 404);
        }
        ecosystem_message_client_mark_read($id, $user, $email, ecosystem_message_shop_host($adminUrl));
        ld_admin_json_response(['ok' => true, 'thread' => ecosystem_message_row_for_ui($row, $lang ?? 'en')]);
    }

    $threads = ecosystem_message_threads_for_client($user, $email, $adminUrl);
    $unread = 0;
    foreach ($threads as $t) {
        if (!empty($t['client_unread'])) {
            $unread++;
        }
    }
    ld_admin_json_response(['ok' => true, 'threads' => $threads, 'unread' => $unread]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ld_admin_json_response(['ok' => false, 'error' => 'method_not_allowed'], 405);
}

$messageId = trim((string) ($_POST['message_id'] ?? ''));
$body = trim((string) ($_POST['body'] ?? ''));

if ($messageId === '') {
    ld_admin_json_response(['ok' => false, 'error' => 'message_id_required'], 400);
}
if (!ecosystem_message_client_can_access($messageId, $user, $email, $adminUrl)) {
    ld_admin_json_response(['ok' => false, 'error' => 'forbidden'], 403);
}

$uploads = [];
if (isset($_FILES['attachments']) && is_array($_FILES['attachments']['name'] ?? null)) {
    $names = $_FILES['attachments']['name'];
    $tmp = $_FILES['attachments']['tmp_name'];
    $errs = $_FILES['attachments']['error'];
    $sizes = $_FILES['attachments']['size'];
    foreach ($names as $i => $name) {
        if (($errs[$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            continue;
        }
        $uploads[] = [
            'name' => (string) $name,
            'tmp_name' => (string) ($tmp[$i] ?? ''),
            'error' => (int) ($errs[$i] ?? UPLOAD_ERR_NO_FILE),
            'size' => (int) ($sizes[$i] ?? 0),
        ];
    }
}

if ($body === '' && $uploads === []) {
    ld_admin_json_response(['ok' => false, 'error' => 'body_required'], 400);
}

$ok = ecosystem_message_add_post($messageId, 'client', $body, [
    'author_name' => ld_admin_display_name(),
    'author_user' => $user,
], $uploads !== [] ? $uploads : null);

if (!$ok) {
    ld_admin_json_response(['ok' => false, 'error' => 'save_failed'], 500);
}

$row = ecosystem_owner_messages_by_id($messageId);
ld_admin_json_response([
    'ok' => true,
    'thread' => $row !== null ? ecosystem_message_row_for_ui($row, $lang ?? 'en') : null,
]);