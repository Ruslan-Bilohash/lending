<?php
require_once __DIR__ . '/init.php';
ld_admin_require();

$id = trim((string) ($_GET['id'] ?? ''));
$inv = $id !== '' ? ld_demo_invoice_by_id($id) : null;
if (!$inv) {
    http_response_code(404);
    echo ld_h($ta['invoice_not_found'] ?? 'Invoice not found');
    exit;
}

$business = ld_business();
$name = ld_pick($business['name'], $lang);
$address = ld_pick($business['address'], $lang);
$city = ld_pick($business['city'], $lang);
$phone = (string) ($business['phone'] ?? '');
$email = (string) ($business['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="<?= ld_h($lang_meta['html'] ?? 'lt') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ld_h((string) ($inv['invoice_no'] ?? 'Invoice')) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; margin: 0; padding: 24px; color: #1e293b; background: #f1f5f9; }
        .inv { max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; border: 1px solid #e2e8f0; }
        .inv-head { display: flex; justify-content: space-between; gap: 24px; margin-bottom: 32px; flex-wrap: wrap; }
        .inv h1 { margin: 0 0 8px; font-size: 1.75rem; color: #059669; }
        .inv-meta { font-size: 14px; color: #64748b; }
        .inv-parties { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
        .inv-parties h3 { margin: 0 0 8px; font-size: 12px; text-transform: uppercase; letter-spacing: .05em; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; font-size: 14px; }
        th { background: #f8fafc; }
        .inv-total { text-align: right; font-size: 1.25rem; font-weight: 700; }
        .inv-demo { margin-top: 20px; padding: 12px; background: #fffbeb; border: 1px solid #fde68a; font-size: 13px; color: #92400e; }
        .inv-actions { max-width: 800px; margin: 16px auto 0; display: flex; gap: 10px; flex-wrap: wrap; }
        .inv-actions a, .inv-actions button { padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; border: none; }
        .btn-print { background: #10b981; color: #fff; }
        .btn-back { background: #fff; color: #334155; border: 1px solid #e2e8f0 !important; }
        @media print {
            body { background: #fff; padding: 0; }
            .inv-actions, .inv-demo { display: none !important; }
            .inv { border: none; padding: 20px; }
        }
    </style>
</head>
<body>
<div class="inv-actions">
    <button type="button" class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> <?= ld_h($ta['students_print_invoice'] ?? 'Print') ?></button>
    <a href="<?= ld_h(ld_admin_url('students.php')) ?>" class="btn-back"><?= ld_h($ta['students'] ?? 'Students') ?></a>
</div>
<article class="inv">
    <div class="inv-head">
        <div>
            <h1><?= ld_h($name) ?></h1>
            <p class="inv-meta"><?= ld_h($address) ?><br><?= ld_h($city) ?></p>
            <p class="inv-meta"><?= ld_h($phone) ?> · <?= ld_h($email) ?></p>
        </div>
        <div style="text-align:right">
            <p class="inv-meta"><strong><?= ld_h($ta['invoice_no'] ?? 'No.') ?>:</strong> <?= ld_h((string) ($inv['invoice_no'] ?? '')) ?></p>
            <p class="inv-meta"><strong><?= ld_h($ta['lead_date'] ?? 'Date') ?>:</strong> <?= ld_h((string) ($inv['created_at'] ?? date('Y-m-d'))) ?></p>
        </div>
    </div>
    <div class="inv-parties">
        <div>
            <h3><?= ld_h($ta['invoice_buyer'] ?? 'Buyer') ?></h3>
            <p><strong><?= ld_h((string) ($inv['buyer_name'] ?? '')) ?></strong></p>
        </div>
        <div>
            <h3><?= ld_h($ta['invoice_seller'] ?? 'Seller') ?></h3>
            <p><strong><?= ld_h($name) ?></strong></p>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th><?= ld_h($ta['invoice_service'] ?? 'Service') ?></th>
                <th><?= ld_h($ta['invoice_qty'] ?? 'Qty') ?></th>
                <th><?= ld_h($ta['student_monthly_price'] ?? 'Price') ?></th>
                <th><?= ld_h($ta['invoice_amount'] ?? 'Amount') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= ld_h((string) ($inv['service'] ?? '')) ?></td>
                <td>1 <?= ld_h(ld_invoice_unit_month($lang)) ?></td>
                <td><?= ld_h(number_format((float) ($inv['amount'] ?? 0), 2, '.', ' ')) ?> <?= ld_h((string) ($inv['currency'] ?? 'EUR')) ?></td>
                <td><?= ld_h(number_format((float) ($inv['amount'] ?? 0), 2, '.', ' ')) ?> <?= ld_h((string) ($inv['currency'] ?? 'EUR')) ?></td>
            </tr>
        </tbody>
    </table>
    <p class="inv-total"><?= ld_h($ta['invoice_total'] ?? 'Total') ?>: <?= ld_h(number_format((float) ($inv['amount'] ?? 0), 2, '.', ' ')) ?> <?= ld_h((string) ($inv['currency'] ?? 'EUR')) ?></p>
    <p class="inv-demo"><i class="fas fa-info-circle"></i> <?= ld_h($ta['demo_disclaimer_admin'] ?? '') ?></p>
</article>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</body>
</html>