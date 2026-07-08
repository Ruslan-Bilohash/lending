<?php
declare(strict_types=1);

function ld_leads_notify_settings(): array
{
    $n = ld_settings()['notifications'] ?? [];

    return [
        'enabled' => array_key_exists('leads_enabled', $n) ? !empty($n['leads_enabled']) : true,
        'email'   => trim((string) ($n['leads_email'] ?? '')),
    ];
}

function ld_leads_notify_email(): string
{
    $cfg = ld_leads_notify_settings();
    if ($cfg['email'] !== '' && filter_var($cfg['email'], FILTER_VALIDATE_EMAIL)) {
        return $cfg['email'];
    }

    $fallback = trim((string) (ld_business()['email'] ?? ''));
    return filter_var($fallback, FILTER_VALIDATE_EMAIL) ? $fallback : '';
}

function ld_notify_lead_email(array $lead): bool
{
    $cfg = ld_leads_notify_settings();
    if (!$cfg['enabled']) {
        return false;
    }

    $to = ld_leads_notify_email();
    if ($to === '') {
        return false;
    }

    $mailPath = dirname(__DIR__, 2) . '/includes/bh-mail.php';
    if (!is_file($mailPath)) {
        return false;
    }
    require_once $mailPath;

    global $site_url;
    $business = ld_pick(ld_business()['name'], (string) ($lead['lang'] ?? 'en'));
    $subject = 'New lead — ' . $business;
    $fields = [
        'Name'     => $lead['name'] ?? '',
        'Phone'    => $lead['phone'] ?? '',
        'Email'    => $lead['email'] ?? '',
        'Service'  => $lead['service'] ?? $lead['course'] ?? '',
        'Callback' => !empty($lead['callback']) ? 'Yes' : 'No',
        'Language' => strtoupper((string) ($lead['lang'] ?? '')),
        'Template' => '#' . (int) ($lead['template'] ?? 1),
        'Lead ID'  => $lead['id'] ?? '',
    ];
    $adminUrl = rtrim((string) $site_url, '/') . '/admin/leads.php';
    $html = bh_mail_notification_html('New training / service request', $fields, $adminUrl);
    $replyTo = filter_var((string) ($lead['email'] ?? ''), FILTER_VALIDATE_EMAIL)
        ? (string) $lead['email']
        : null;

    return bh_send_mail($to, $subject, $html, $replyTo, (string) ($lead['name'] ?? ''));
}