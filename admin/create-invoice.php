<?php
require_once __DIR__ . '/init.php';
ld_admin_require();

$leadId = trim((string) ($_GET['lead'] ?? $_POST['lead'] ?? ''));
$lead = $leadId !== '' ? ld_get_lead($leadId) : null;

if (!$lead) {
    header('Location: ' . ld_admin_url('invoices.php'), true, 302);
    exit;
}

$priceOverride = null;
if (isset($_POST['price']) && trim((string) $_POST['price']) !== '') {
    $priceOverride = (float) str_replace([',', ' '], ['.', ''], (string) $_POST['price']);
}

$invoice = ld_lead_to_faktura_invoice($lead, $lang, $priceOverride);
if ($invoice) {
    ld_update_lead($leadId, [
        'invoice_id' => $invoice['id'],
        'invoice_no' => $invoice['invoice_no'],
        'invoice_url' => $invoice['view_url'],
        'status' => 'invoiced',
    ]);
    header('Location: ' . $invoice['view_url'], true, 302);
    exit;
}

header('Location: ' . ld_admin_url('invoices.php?error=create_failed'), true, 302);
exit;