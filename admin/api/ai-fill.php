<?php
header('Content-Type: application/json; charset=utf-8');
require_once dirname(__DIR__, 2) . '/init.php';
require_once dirname(__DIR__, 2) . '/includes/admin-auth.php';
$t = ld_admin_reload_i18n();
$ta = $t['admin'] ?? [];
ld_admin_require();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'POST required']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: '', true);
if (!is_array($data)) {
    $data = $_POST;
}

$brief = trim((string) ($data['brief'] ?? ''));
$scopeRaw = $data['scope'] ?? 'all';
$scopes = is_array($scopeRaw) ? $scopeRaw : explode(',', (string) $scopeRaw);
$scopes = array_values(array_filter(array_map('trim', $scopes)));

if ($brief === '') {
    echo json_encode(['ok' => false, 'error' => 'brief_required']);
    exit;
}

if (!$scopes) {
    $scopes = ['all'];
}

$fill = ld_ai_fill_from_brief($brief, $scopes);
if (!$fill['ok'] || !is_array($fill['data'])) {
    echo json_encode([
        'ok' => false,
        'error' => $fill['error'] ?? 'fill_failed',
        'demo' => $fill['demo'] ?? false,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$apply = ld_ai_apply_fill($fill['data'], $scopes);
echo json_encode([
    'ok' => $apply['ok'],
    'demo' => $fill['demo'] ?? false,
    'error' => $apply['ok'] ? '' : 'save_failed',
    'message' => ($fill['demo'] ?? false)
        ? ($ta['ai_fill_demo_saved'] ?? 'Demo data saved')
        : ($ta['ai_fill_saved'] ?? $ta['saved'] ?? 'Saved'),
], JSON_UNESCAPED_UNICODE);