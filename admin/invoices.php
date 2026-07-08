<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'invoices';
$page_title = $ta['invoices'] ?? 'Invoices';

$integration = ld_faktura_integration();
$fakturaOk = ld_faktura_available();
$fakturaOn = $fakturaOk && !empty($integration['enabled']);
$services = ld_services($lang);
$leads = ld_load_leads();
$pendingLeads = array_values(array_filter($leads, static fn(array $l): bool => empty($l['invoice_url'])));
$invoicedLeads = array_values(array_filter($leads, static fn(array $l): bool => !empty($l['invoice_url'])));
$allInvoices = ld_all_invoices_list();
$error = '';
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_invoice'])) {
    if (!$fakturaOn) {
        $error = 'faktura_disabled';
    } else {
        $leadId = trim((string) ($_POST['lead_id'] ?? ''));
        $buyerName = trim((string) ($_POST['buyer_name'] ?? ''));
        $buyerPhone = trim((string) ($_POST['buyer_phone'] ?? ''));
        $buyerEmail = trim((string) ($_POST['buyer_email'] ?? ''));
        $service = trim((string) ($_POST['service'] ?? ''));
        $price = trim((string) ($_POST['price'] ?? ''));

        if ($leadId !== '') {
            $lead = ld_get_lead($leadId);
            if ($lead) {
                $buyerName = $buyerName !== '' ? $buyerName : (string) ($lead['name'] ?? '');
                $buyerPhone = $buyerPhone !== '' ? $buyerPhone : (string) ($lead['phone'] ?? '');
                $buyerEmail = $buyerEmail !== '' ? $buyerEmail : (string) ($lead['email'] ?? '');
                $service = $service !== '' ? $service : (string) ($lead['service'] ?? $lead['course'] ?? '');
            }
        }

        if ($buyerName === '') {
            $error = 'buyer_required';
        } else {
            $invoice = ld_create_faktura_invoice([
                'buyer_name' => $buyerName,
                'buyer_phone' => $buyerPhone,
                'buyer_email' => $buyerEmail,
                'service' => $service,
                'price' => $price !== '' ? $price : null,
                'lead_id' => $leadId,
            ], $lang);

            if ($invoice) {
                if ($leadId !== '') {
                    ld_update_lead($leadId, [
                        'invoice_id' => $invoice['id'],
                        'invoice_no' => $invoice['invoice_no'],
                        'invoice_url' => $invoice['view_url'],
                        'status' => 'invoiced',
                    ]);
                }
                header('Location: ' . $invoice['view_url'], true, 302);
                exit;
            }
            $error = 'create_failed';
        }
    }
}

if (isset($_GET['error'])) {
    $error = (string) $_GET['error'];
}

require __DIR__ . '/includes/layout.php';
?>

<?php if (!$fakturaOk): ?>
<div class="adm-alert adm-alert-warning">
    <i class="fas fa-triangle-exclamation"></i> <?= ld_h($ta['faktura_missing'] ?? 'Faktura Creator is not installed on this server.') ?>
</div>
<?php elseif (!$fakturaOn): ?>
<div class="adm-alert adm-alert-warning">
    <i class="fas fa-toggle-off"></i> <?= ld_h($ta['faktura_off'] ?? 'Enable Faktura in Integrations.') ?>
    <a href="<?= ld_h(ld_admin_url('integrations.php')) ?>" class="adm-btn adm-btn-sm adm-btn-outline" style="margin-left:10px"><?= ld_h($ta['integrations'] ?? '') ?></a>
</div>
<?php else: ?>
<div class="adm-alert adm-alert-info">
    <i class="fas fa-file-invoice-dollar"></i> <?= ld_h($ta['invoices_help'] ?? '') ?>
</div>
<?php endif; ?>

<?php if ($error === 'buyer_required'): ?>
<div class="adm-alert adm-alert-warning"><i class="fas fa-user"></i> <?= ld_h($ta['invoice_buyer_required'] ?? 'Enter client name.') ?></div>
<?php elseif ($error === 'create_failed' || $error === 'faktura_disabled'): ?>
<div class="adm-alert adm-alert-warning"><i class="fas fa-times"></i> <?= ld_h($ta['invoice_create_error'] ?? 'Could not create invoice.') ?></div>
<?php endif; ?>

<div class="adm-card adm-invoice-create-card">
    <div class="adm-card-head">
        <h2><i class="fas fa-plus-circle"></i> <?= ld_h($ta['invoice_create_title'] ?? 'Create invoice') ?></h2>
        <a href="<?= ld_h(ld_faktura_base_url() . '/create.php') ?>" class="adm-btn adm-btn-sm adm-btn-outline" target="_blank" rel="noopener">
            <i class="fas fa-external-link-alt"></i> <?= ld_h($ta['faktura_external'] ?? 'Faktura') ?>
        </a>
    </div>
    <div class="adm-card-body padded">
        <form method="post" class="adm-form-grid" id="invoiceCreateForm">
            <input type="hidden" name="create_invoice" value="1">
            <div class="adm-field adm-field-full">
                <label><?= ld_h($ta['invoice_from_lead'] ?? 'From lead (optional)') ?></label>
                <select name="lead_id" id="invLeadSelect">
                    <option value="">— <?= ld_h($ta['invoice_manual'] ?? 'Manual entry') ?> —</option>
                    <?php foreach ($pendingLeads as $lead): ?>
                    <option value="<?= ld_h((string) ($lead['id'] ?? '')) ?>"
                        data-name="<?= ld_h($lead['name'] ?? '') ?>"
                        data-phone="<?= ld_h($lead['phone'] ?? '') ?>"
                        data-email="<?= ld_h($lead['email'] ?? '') ?>"
                        data-service="<?= ld_h($lead['service'] ?? $lead['course'] ?? '') ?>">
                        <?= ld_h(($lead['name'] ?? '') . ' · ' . ($lead['phone'] ?? '') . ' · ' . substr($lead['created_at'] ?? '', 0, 10)) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['lead_name'] ?? 'Name') ?> *</label>
                <input type="text" name="buyer_name" id="invBuyerName" required>
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['lead_phone'] ?? 'Phone') ?></label>
                <input type="text" name="buyer_phone" id="invBuyerPhone">
            </div>
            <div class="adm-field adm-field-full">
                <label><?= ld_h($ta['email'] ?? 'Email') ?></label>
                <input type="email" name="buyer_email" id="invBuyerEmail">
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['lead_service'] ?? 'Service') ?></label>
                <select name="service" id="invService">
                    <option value="">—</option>
                    <?php foreach ($services as $svc): ?>
                    <option value="<?= ld_h($svc['name'] ?? '') ?>" data-price="<?= ld_h((string) ($svc['price'] ?? '')) ?>">
                        <?= ld_h(($svc['name'] ?? '') . ($svc['price'] !== '' ? ' — ' . $svc['price'] . ' ' . ld_currency() : '')) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="service" id="invServiceText" placeholder="<?= ld_h($ta['invoice_service_custom'] ?? 'Or type custom service') ?>" style="margin-top:6px">
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['invoice_price'] ?? 'Price') ?> (<?= ld_h(ld_currency()) ?>)</label>
                <input type="text" name="price" id="invPrice" inputmode="decimal" placeholder="0">
                <p class="adm-field-hint"><?= ld_h($ta['invoice_price_hint'] ?? 'Empty = price from services list') ?></p>
            </div>
            <div class="adm-field adm-field-full adm-form-actions">
                <button type="submit" class="adm-btn adm-btn-primary" <?= $fakturaOn ? '' : 'disabled' ?>>
                    <i class="fas fa-file-pdf"></i> <?= ld_h($ta['create_invoice'] ?? 'Create PDF invoice') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($allInvoices !== []): ?>
<div class="adm-card">
    <div class="adm-card-head">
        <h2><i class="fas fa-folder-open"></i> <?= ld_h($ta['invoices_list'] ?? 'Created invoices') ?></h2>
        <?php if ($fakturaOk): ?>
        <a href="<?= ld_h(ld_faktura_base_url() . '/admin/invoices.php') ?>" class="adm-btn adm-btn-sm adm-btn-outline" target="_blank" rel="noopener">
            <?= ld_h($ta['faktura_all'] ?? 'All in Faktura') ?>
        </a>
        <?php endif; ?>
    </div>
    <div class="adm-card-body" style="overflow-x:auto">
        <table class="adm-table">
            <thead>
                <tr>
                    <th><?= ld_h($ta['invoice_no'] ?? 'No.') ?></th>
                    <th><?= ld_h($ta['lead_name'] ?? 'Client') ?></th>
                    <th><?= ld_h($ta['invoice_service'] ?? 'Service') ?></th>
                    <th><?= ld_h($ta['invoice_amount'] ?? 'Amount') ?></th>
                    <th><?= ld_h($ta['lead_date'] ?? 'Date') ?></th>
                    <th><?= ld_h($ta['invoice_source'] ?? 'Source') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allInvoices as $inv):
                    $source = (string) ($inv['source'] ?? 'demo');
                    $srcLabel = $ta['invoice_source_' . $source] ?? $source;
                    $amount = (float) ($inv['amount'] ?? 0);
                    $cur = (string) ($inv['currency'] ?? 'EUR');
                    $pdf = (string) ($inv['pdf_url'] ?? '');
                ?>
                <tr>
                    <td><code><?= ld_h((string) ($inv['invoice_no'] ?? '')) ?></code></td>
                    <td><?= ld_h((string) ($inv['buyer_name'] ?? '—')) ?></td>
                    <td><?= ld_h((string) ($inv['service'] ?? '')) ?></td>
                    <td><?= $amount > 0 ? ld_h(number_format($amount, 2, '.', ' ') . ' ' . $cur) : '—' ?></td>
                    <td><?= ld_h(substr((string) ($inv['created_at'] ?? ''), 0, 10)) ?></td>
                    <td><span class="adm-badge adm-badge-source"><?= ld_h($srcLabel) ?></span></td>
                    <td>
                        <?php if ($pdf !== '' && $pdf !== '#'): ?>
                        <a href="<?= ld_h($pdf) ?>" class="adm-btn adm-btn-sm adm-btn-primary" target="_blank" rel="noopener"><?= ld_h($ta['pdf'] ?? 'PDF') ?></a>
                        <?php else: ?>
                        <span class="adm-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
(function () {
    var leadSel = document.getElementById('invLeadSelect');
    var nameEl = document.getElementById('invBuyerName');
    var phoneEl = document.getElementById('invBuyerPhone');
    var emailEl = document.getElementById('invBuyerEmail');
    var svcSel = document.getElementById('invService');
    var svcText = document.getElementById('invServiceText');
    var priceEl = document.getElementById('invPrice');
    if (!leadSel) return;
    leadSel.addEventListener('change', function () {
        var opt = leadSel.options[leadSel.selectedIndex];
        if (!opt || !opt.value) return;
        if (nameEl) nameEl.value = opt.getAttribute('data-name') || '';
        if (phoneEl) phoneEl.value = opt.getAttribute('data-phone') || '';
        if (emailEl) emailEl.value = opt.getAttribute('data-email') || '';
        var svc = opt.getAttribute('data-service') || '';
        if (svcSel && svc) {
            for (var i = 0; i < svcSel.options.length; i++) {
                if (svcSel.options[i].value === svc) {
                    svcSel.selectedIndex = i;
                    if (priceEl) priceEl.value = svcSel.options[i].getAttribute('data-price') || '';
                    break;
                }
            }
        }
        if (svcText) svcText.value = svc;
    });
    if (svcSel) {
        svcSel.addEventListener('change', function () {
            var opt = svcSel.options[svcSel.selectedIndex];
            if (svcText) svcText.value = opt ? opt.value : '';
            if (priceEl && opt) priceEl.value = opt.getAttribute('data-price') || '';
        });
    }
    var form = document.getElementById('invoiceCreateForm');
    if (form) {
        form.addEventListener('submit', function () {
            if (svcText && svcText.value.trim() !== '') {
                if (svcSel) svcSel.removeAttribute('name');
                svcText.setAttribute('name', 'service');
            } else if (svcSel) {
                svcSel.setAttribute('name', 'service');
                if (svcText) svcText.removeAttribute('name');
            }
        });
    }
})();
</script>

<?php require __DIR__ . '/includes/layout-end.php'; ?>