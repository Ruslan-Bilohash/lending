<?php
require_once __DIR__ . '/init.php';
ld_admin_require();

$admin_page = 'help';
$page_title = $ta['help_nav'] ?? ($ta['api_guide_title'] ?? 'Help');

require __DIR__ . '/includes/layout.php';
?>

<p class="adm-help"><?= ld_h($ta['help_lead'] ?? '') ?></p>

<div class="adm-help-stack">
    <?php
    foreach (['ai', 'recaptcha', 'faktura'] as $scope) {
        $api_guide_scope = $scope;
        $api_guide_inline = false;
        require __DIR__ . '/includes/api-instructions.php';
        if ($scope === 'faktura'): ?>
    <p class="adm-help-actions">
        <a href="<?= ld_h(ld_faktura_support_url()) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener">
            <i class="fas fa-headset"></i> <?= ld_h($ta['faktura_support_link'] ?? 'Faktura support') ?>
        </a>
        <a href="<?= ld_h(ld_faktura_base_url() . '/admin/') ?>" class="adm-btn adm-btn-ghost adm-btn-sm" target="_blank" rel="noopener">
            <i class="fas fa-file-invoice-dollar"></i> <?= ld_h($ta['faktura_admin_link'] ?? 'Faktura admin') ?>
        </a>
    </p>
        <?php endif;
    }
    ?>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>