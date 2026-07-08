<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__, 2) . '/init.php';
require_once dirname(__DIR__, 2) . '/includes/image-upload.php';
ld_admin_require();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

$action = trim((string) ($_POST['action'] ?? 'upload'));

if ($action === 'delete') {
    $url = trim((string) ($_POST['url'] ?? ''));
    $deleted = ld_delete_uploaded_file($url);
    echo json_encode(['ok' => $deleted, 'error' => $deleted ? '' : 'delete_failed']);
    exit;
}

if (empty($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'] ?? '')) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'no_file']);
    exit;
}

$maxBytes = 8 * 1024 * 1024;
if (($_FILES['image']['size'] ?? 0) > $maxBytes) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'too_large']);
    exit;
}

$result = ld_process_uploaded_image((string) $_FILES['image']['tmp_name'], 'news');
if (!$result['ok']) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $result['error'] ?? 'upload_failed']);
    exit;
}

echo json_encode([
    'ok' => true,
    'url' => $result['url'],
    'format' => $result['format'] ?? 'webp',
    'width' => $result['width'] ?? 0,
    'height' => $result['height'] ?? 0,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);