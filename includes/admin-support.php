<?php
declare(strict_types=1);

function ld_admin_json_response(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function ld_admin_display_name(): string
{
    return (string) ($_SESSION['ld_admin_user'] ?? 'demo');
}

function ld_admin_role(): string
{
    return 'demo';
}

function ld_admin_client_ip(): string
{
    return trim((string) ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? ''));
}

function ld_admin_support_shop_url(): string
{
    global $site_url;

    return rtrim((string) ($site_url ?? ''), '/') . '/admin/';
}

/**
 * @param array<string, mixed> $context
 * @return array{ok:bool,demo:bool,subject:string,body:string,error:string}
 */
function ld_ai_compose_message_draft(string $mode, string $draft, string $lang, array $context = []): array
{
    $draft = trim($draft);
    $mode = $mode === 'client_email' ? 'client_email' : 'owner_support';
    $adminName = trim((string) ($context['admin_name'] ?? 'Business Landing admin'));
    $clientName = trim((string) ($context['client_name'] ?? 'Customer'));
    $topic = trim((string) ($context['topic'] ?? ''));

    $ai = ld_ai();
    $hasKey = trim((string) ($ai['api_key'] ?? '')) !== '';
    if (!$hasKey) {
        return ld_ai_compose_message_fallback($mode, $draft, $lang, $context, true, '');
    }

    if ($mode === 'client_email') {
        $prompt = 'You help a Business Landing CMS user write a professional email to their customer. '
            . 'Admin: ' . $adminName . '. Customer: ' . $clientName . '. '
            . ($topic !== '' ? 'Topic: ' . $topic . '. ' : '')
            . 'Draft notes: "' . ($draft !== '' ? $draft : 'Follow-up about their enquiry') . '". '
            . 'Language: ' . $lang . '. '
            . 'Return ONLY valid JSON {"subject":"...","body":"..."} — plain text body, friendly business tone.';
    } else {
        $prompt = 'You help a Business Landing CMS admin write a clear support message to BILOHASH (Ruslan). '
            . 'Admin: ' . $adminName . '. Language: ' . $lang . '. '
            . 'Draft: "' . ($draft !== '' ? $draft : 'Need help with Business Landing CMS') . '". '
            . 'Return ONLY valid JSON {"subject":"...","body":"..."} — concise subject, problem, context, what they need.';
    }

    $result = ld_ai_call_api(
        $ai,
        'You are a professional business email assistant. Return valid JSON only.',
        $prompt,
        900,
        true
    );
    if (empty($result['ok'])) {
        return ld_ai_compose_message_fallback($mode, $draft, $lang, $context, false, (string) ($result['error'] ?? 'AI error'));
    }

    $raw = trim((string) ($result['text'] ?? ''));
    if (preg_match('/```(?:json)?\s*([\s\S]*?)```/i', $raw, $m)) {
        $raw = trim($m[1]);
    }
    $parsed = json_decode($raw, true);
    $subject = trim((string) ($parsed['subject'] ?? ''));
    $body = trim((string) ($parsed['body'] ?? ''));
    if ($subject === '' || $body === '') {
        return ld_ai_compose_message_fallback($mode, $draft, $lang, $context, false, 'Invalid JSON from AI');
    }

    return ['ok' => true, 'demo' => false, 'subject' => $subject, 'body' => $body, 'error' => ''];
}

/**
 * @param array<string, mixed> $context
 * @return array{ok:bool,demo:bool,subject:string,body:string,error:string}
 */
function ld_ai_compose_message_fallback(string $mode, string $draft, string $lang, array $context, bool $demo, string $error): array
{
    $adminName = trim((string) ($context['admin_name'] ?? 'Business Landing admin'));
    $clientName = trim((string) ($context['client_name'] ?? 'Customer'));
    $draft = $draft !== '' ? $draft : ($mode === 'client_email'
        ? 'Thank you for your enquiry — we will follow up shortly.'
        : 'I need assistance with Business Landing CMS setup.');

    if ($mode === 'client_email') {
        return [
            'ok' => true,
            'demo' => $demo,
            'subject' => 'Regarding your request — ' . $clientName,
            'body' => "Hello {$clientName},\n\n{$draft}\n\nBest regards,\n{$adminName}",
            'error' => $error,
        ];
    }

    return [
        'ok' => true,
        'demo' => $demo,
        'subject' => 'Business Landing CMS — support request',
        'body' => "Hello BILOHASH team,\n\n{$draft}\n\n— {$adminName}",
        'error' => $error,
    ];
}