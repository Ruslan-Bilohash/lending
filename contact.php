<?php
require_once __DIR__ . '/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ld_url('index.php'), true, 302);
    exit;
}

$name     = trim((string) ($_POST['name'] ?? ''));
$phone    = trim((string) ($_POST['phone'] ?? ''));
$email    = trim((string) ($_POST['email'] ?? ''));
$service  = trim((string) ($_POST['service'] ?? $_POST['course'] ?? ''));
$callback = !empty($_POST['callback']);
$consent  = !empty($_POST['consent']);
$template = (int) ($_POST['template'] ?? ld_active_template());
$redirect = (string) ($_POST['redirect'] ?? ld_url('template.php', ['t' => $template]));
$sep = str_contains($redirect, '?') ? '&' : '?';

if ($name === '' || $phone === '') {
    header('Location: ' . $redirect . $sep . 'lead=error', true, 302);
    exit;
}

$legal = ld_settings()['legal'] ?? [];
$consentRequired = !empty($legal['consent_required']) || !empty($legal['consent']);
if ($consentRequired && !$consent) {
    header('Location: ' . $redirect . $sep . 'lead=error', true, 302);
    exit;
}

if (!ld_verify_recaptcha($_POST['g-recaptcha-response'] ?? null)) {
    header('Location: ' . $redirect . $sep . 'lead=captcha', true, 302);
    exit;
}

$lead = [
    'name'     => $name,
    'phone'    => $phone,
    'email'    => $email,
    'service'  => $service,
    'course'   => $service,
    'callback' => $callback,
    'consent'  => $consent,
    'template' => max(1, min(10, $template)),
    'lang'     => $lang,
    'ip'       => $_SERVER['REMOTE_ADDR'] ?? '',
    'status'   => $callback ? 'callback' : 'new',
];

ld_add_lead($lead);
$leads = ld_load_leads();
$leadId = $leads[0]['id'] ?? '';
$lead['id'] = $leadId;
ld_notify_lead_email($lead);

$integration = ld_faktura_integration();
if (!empty($integration['auto_invoice']) && $leadId !== '') {
    $fresh = ld_get_lead($leadId);
    if ($fresh) {
        $invoice = ld_lead_to_faktura_invoice($fresh, $lang);
        if ($invoice) {
            ld_update_lead($leadId, [
                'invoice_id' => $invoice['id'],
                'invoice_no' => $invoice['invoice_no'],
                'invoice_url' => $invoice['view_url'],
            ]);
        }
    }
}

header('Location: ' . $redirect . $sep . 'lead=ok', true, 302);
exit;