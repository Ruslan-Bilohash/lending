<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'leads';
$page_title = ld_admin_t('leads');
$leads = ld_load_leads();

require __DIR__ . '/includes/layout.php';
?>

<?php if (isset($_GET['invoice']) && $_GET['invoice'] === 'error'): ?>
<div class="adm-alert adm-alert-warning"><i class="fas fa-times"></i> <?= ld_h(ld_admin_t('invoice_create_error')) ?></div>
<?php endif; ?>

<p class="adm-help"><?= ld_h(ld_admin_t('leads_help')) ?></p>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><i class="fas fa-inbox"></i> <?= ld_h(ld_admin_t('leads')) ?> (<?= count($leads) ?>)</h2>
        <?php if (ld_faktura_available() && !empty(ld_faktura_integration()['enabled'])): ?>
        <a href="<?= ld_h(ld_admin_url('invoices.php')) ?>" class="adm-btn adm-btn-sm adm-btn-primary">
            <i class="fas fa-file-invoice-dollar"></i> <?= ld_h(ld_admin_t('create_invoice')) ?>
        </a>
        <?php endif; ?>
    </div>
    <div class="adm-card-body" style="overflow-x:auto">
        <?php if (!$leads): ?>
        <p class="adm-empty"><?= ld_h(ld_admin_t('no_leads')) ?></p>
        <?php else: ?>
        <table class="adm-table adm-table--cards">
            <thead>
                <tr>
                    <th><?= ld_h(ld_admin_t('lead_name')) ?></th>
                    <th><?= ld_h(ld_admin_t('lead_phone')) ?></th>
                    <th><?= ld_h(ld_admin_t('lead_email')) ?></th>
                    <th><?= ld_h(ld_admin_t('lead_type')) ?></th>
                    <th><?= ld_h(ld_admin_t('lead_service', ld_admin_t('lead_course'))) ?></th>
                    <th><?= ld_h(ld_admin_t('lead_date')) ?></th>
                    <th><?= ld_h(ld_admin_t('lead_status')) ?></th>
                    <th><?= ld_h(ld_admin_t('invoice')) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $lead):
                    $leadSt = (string) ($lead['status'] ?? 'new');
                    $leadStLabel = $ta['lead_status_' . $leadSt] ?? $leadSt;
                    $isCallback = !empty($lead['callback']) || $leadSt === 'callback';
                    $typeLabel = $isCallback ? ld_admin_t('lead_type_callback') : ld_admin_t('lead_type_contact');
                    $phone = (string) ($lead['phone'] ?? '');
                    $phoneHref = preg_replace('/[^\d+]/', '', $phone);
                ?>
                <tr>
                    <td data-label="<?= ld_h(ld_admin_t('lead_name')) ?>">
                        <strong><?= ld_h($lead['name'] ?? '') ?></strong>
                    </td>
                    <td data-label="<?= ld_h(ld_admin_t('lead_phone')) ?>">
                        <?php if ($phoneHref !== ''): ?>
                        <a href="tel:<?= ld_h($phoneHref) ?>" class="adm-phone-link"><?= ld_h($phone) ?></a>
                        <?php else: ?>
                        <?= ld_h($phone) ?>
                        <?php endif; ?>
                    </td>
                    <td data-label="<?= ld_h(ld_admin_t('lead_email')) ?>">
                        <?php if (!empty($lead['email'])): ?>
                        <a href="mailto:<?= ld_h($lead['email']) ?>"><?= ld_h($lead['email']) ?></a>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td data-label="<?= ld_h(ld_admin_t('lead_type')) ?>">
                        <?php if ($isCallback): ?>
                        <span class="adm-badge adm-badge-callback"><i class="fas fa-phone-volume"></i> <?= ld_h($typeLabel) ?></span>
                        <?php else: ?>
                        <span class="adm-badge"><?= ld_h($typeLabel) ?></span>
                        <?php endif; ?>
                    </td>
                    <td data-label="<?= ld_h(ld_admin_t('lead_service')) ?>"><?= ld_h($lead['service'] ?? $lead['course'] ?? '—') ?></td>
                    <td data-label="<?= ld_h(ld_admin_t('lead_date')) ?>"><?= ld_h(substr($lead['created_at'] ?? '', 0, 16)) ?></td>
                    <td data-label="<?= ld_h(ld_admin_t('lead_status')) ?>"><span class="adm-badge"><?= ld_h($leadStLabel) ?></span></td>
                    <td data-label="<?= ld_h(ld_admin_t('invoice')) ?>">
                        <?php if (!empty($lead['invoice_url'])): ?>
                        <a href="<?= ld_h($lead['invoice_url']) ?>" class="adm-btn adm-btn-sm adm-btn-outline" target="_blank"><?= ld_h(ld_admin_t('pdf')) ?></a>
                        <?php elseif (ld_faktura_available() && !empty(ld_faktura_integration()['enabled'])): ?>
                        <a href="<?= ld_h(ld_admin_url('create-invoice.php?lead=' . urlencode((string)($lead['id'] ?? '')))) ?>" class="adm-btn adm-btn-sm adm-btn-primary"><?= ld_h(ld_admin_t('create_invoice')) ?></a>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>