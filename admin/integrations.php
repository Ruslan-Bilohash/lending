<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'integrations';
$page_title = $ta['integrations'] ?? 'Integrations';
$settings = ld_settings();
$rc = $settings['recaptcha'] ?? [];
$faktura = $settings['integrations']['faktura'] ?? [];
$notify = $settings['notifications'] ?? [];
$legal = $settings['legal'] ?? ld_default_settings()['legal'];
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notify['leads_enabled'] = !empty($_POST['leads_notify_enabled']);
    $notify['leads_email'] = trim((string) ($_POST['leads_email'] ?? ''));
    $rc['enabled'] = !empty($_POST['recaptcha_enabled']);
    $rc['site_key'] = trim((string) ($_POST['recaptcha_site_key'] ?? ''));
    if (trim((string) ($_POST['recaptcha_secret_key'] ?? '')) !== '') {
        $rc['secret_key'] = trim((string) $_POST['recaptcha_secret_key']);
    }
    $faktura['enabled'] = !empty($_POST['faktura_enabled']);
    $faktura['auto_invoice'] = !empty($_POST['faktura_auto']);
    $faktura['country_id'] = trim((string) ($_POST['faktura_country'] ?? 'lt'));
    $faktura['print_design'] = trim((string) ($_POST['faktura_design'] ?? 'classic-blue'));
    $faktura['print_format'] = trim((string) ($_POST['faktura_format'] ?? 'a4'));
    $legal['consent_required'] = !empty($_POST['legal_consent_required']);
    $legal['privacy_slug'] = ld_page_slugify(trim((string) ($_POST['legal_privacy_slug'] ?? 'privacy')));
    $legal['privacy_url'] = trim((string) ($_POST['legal_privacy_url'] ?? ''));
    foreach (ld_langs_codes() as $code) {
        $key = 'legal_consent_' . $code;
        if (isset($_POST[$key])) {
            $legal['consent'][$code] = trim((string) $_POST[$key]);
        }
    }
    $settings['notifications'] = $notify;
    $settings['recaptcha'] = $rc;
    $settings['integrations']['faktura'] = $faktura;
    $settings['legal'] = $legal;
    if (ld_save_settings($settings)) {
        $saved = true;
        $notify = $settings['notifications'];
        $rc = $settings['recaptcha'];
        $faktura = $settings['integrations']['faktura'];
        $legal = $settings['legal'];
    }
}
$leadsEmail = (string) ($notify['leads_email'] ?? '');
$leadsEmailFallback = ld_leads_notify_email();

require __DIR__ . '/includes/layout.php';
?>
<?php if ($saved): ?><div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['saved'] ?? '') ?></div><?php endif; ?>
<form method="post">
    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-envelope"></i> <?= ld_h($ta['notifications_title'] ?? 'Lead notifications') ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-field-check">
                <label>
                    <input type="checkbox" name="leads_notify_enabled" value="1" <?= !isset($notify['leads_enabled']) || !empty($notify['leads_enabled']) ? 'checked' : '' ?>>
                    <?= ld_h($ta['leads_notify_enable'] ?? 'Email on new lead') ?>
                </label>
            </div>
            <div class="adm-field">
                <label for="leads_email"><?= ld_h($ta['leads_email_label'] ?? 'Notification email') ?></label>
                <input type="email" id="leads_email" name="leads_email" autocomplete="email"
                       placeholder="<?= ld_h($ta['leads_email_placeholder'] ?? 'office@example.com') ?>"
                       value="<?= ld_h($leadsEmail) ?>">
            </div>
            <p class="adm-help"><?= ld_h($ta['leads_email_help'] ?? '') ?></p>
            <?php if ($leadsEmail === '' && $leadsEmailFallback !== ''): ?>
            <p class="adm-field-hint"><i class="fas fa-info-circle"></i> <?= ld_h($ta['leads_email_fallback'] ?? 'Fallback') ?>: <strong><?= ld_h($leadsEmailFallback) ?></strong></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-user-shield"></i> <?= ld_h($ta['legal_settings'] ?? 'Privacy & consent') ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-field-check">
                <label>
                    <input type="checkbox" name="legal_consent_required" value="1" <?= !empty($legal['consent_required']) ? 'checked' : '' ?>>
                    <?= ld_h($ta['legal_consent_enable'] ?? 'Require privacy consent on contact form') ?>
                </label>
            </div>
            <div class="adm-form-grid">
                <div class="adm-field">
                    <label for="legal_privacy_slug"><?= ld_h($ta['legal_privacy_slug'] ?? 'Privacy page slug') ?></label>
                    <input type="text" id="legal_privacy_slug" name="legal_privacy_slug" value="<?= ld_h($legal['privacy_slug'] ?? 'privacy') ?>">
                </div>
                <div class="adm-field">
                    <label for="legal_privacy_url"><?= ld_h($ta['legal_privacy_url'] ?? 'External privacy URL (optional)') ?></label>
                    <input type="url" id="legal_privacy_url" name="legal_privacy_url" placeholder="https://…" value="<?= ld_h($legal['privacy_url'] ?? '') ?>">
                </div>
            </div>
            <p class="adm-help"><?= ld_h($ta['legal_privacy_hint'] ?? 'Leave URL empty to use the local service page. Edit content under Service pages.') ?>
                <a href="<?= ld_h(ld_admin_url('pages.php')) ?>"><?= ld_h($ta['pages_nav'] ?? 'Service pages') ?></a>
            </p>
            <?php foreach (ld_langs_codes() as $code): ?>
            <div class="adm-field adm-field-full">
                <label><?= ld_h($ta['legal_consent_text'] ?? 'Consent checkbox text') ?> (<?= ld_h(strtoupper($code)) ?>)</label>
                <input type="text" name="legal_consent_<?= ld_h($code) ?>" value="<?= ld_h($legal['consent'][$code] ?? '') ?>">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-shield-halved"></i> <?= ld_h($ta['recaptcha_title'] ?? 'reCAPTCHA') ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-field-check"><label><input type="checkbox" name="recaptcha_enabled" value="1" <?= !empty($rc['enabled']) ? 'checked' : '' ?>> <?= ld_h($ta['recaptcha_enable'] ?? '') ?></label></div>
            <div class="adm-field"><label><?= ld_h($ta['recaptcha_site_key_label'] ?? $ta['recaptcha_site_key'] ?? '') ?></label><input type="text" name="recaptcha_site_key" value="<?= ld_h($rc['site_key'] ?? '') ?>"></div>
            <div class="adm-field"><label><?= ld_h($ta['recaptcha_secret_key_label'] ?? $ta['recaptcha_secret_key'] ?? '') ?></label><input type="password" name="recaptcha_secret_key" placeholder="••••••" autocomplete="new-password"></div>
            <p class="adm-help"><?= ld_h($ta['recaptcha_help'] ?? '') ?></p>
            <?php $api_guide_scope = 'recaptcha'; $api_guide_inline = true; require __DIR__ . '/includes/api-instructions.php'; ?>
        </div>
    </div>
    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-file-invoice-dollar"></i> <?= ld_h($ta['faktura_title'] ?? 'Faktura Creator') ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-field-check"><label><input type="checkbox" name="faktura_enabled" value="1" <?= !empty($faktura['enabled']) ? 'checked' : '' ?>> <?= ld_h($ta['faktura_enable'] ?? '') ?></label></div>
            <div class="adm-field-check"><label><input type="checkbox" name="faktura_auto" value="1" <?= !empty($faktura['auto_invoice']) ? 'checked' : '' ?>> <?= ld_h($ta['faktura_auto'] ?? '') ?></label></div>
            <div class="adm-form-grid">
                <div class="adm-field"><label><?= ld_h($ta['faktura_country_id_label'] ?? $ta['faktura_country_id'] ?? '') ?></label><input type="text" name="faktura_country" value="<?= ld_h($faktura['country_id'] ?? 'lt') ?>"></div>
                <div class="adm-field"><label><?= ld_h($ta['faktura_print_design'] ?? 'Print design') ?></label><input type="text" name="faktura_design" value="<?= ld_h($faktura['print_design'] ?? 'classic-blue') ?>"></div>
                <div class="adm-field"><label><?= ld_h($ta['faktura_format'] ?? 'Format') ?></label><select name="faktura_format"><option value="a4" <?= ($faktura['print_format'] ?? '') === 'a4' ? 'selected' : '' ?>><?= ld_h($ta['faktura_format_a4'] ?? 'A4 PDF') ?></option></select></div>
            </div>
            <p class="adm-help"><?= ld_h($ta['faktura_help'] ?? '') ?></p>
            <?php $api_guide_scope = 'faktura'; $api_guide_inline = true; require __DIR__ . '/includes/api-instructions.php'; ?>
            <div class="adm-quick-actions" style="margin-top:12px">
                <a href="<?= ld_h(ld_admin_url('invoices.php')) ?>" class="adm-btn adm-btn-primary adm-btn-sm"><i class="fas fa-file-invoice-dollar"></i> <?= ld_h($ta['invoices'] ?? 'Invoices') ?></a>
                <a href="<?= ld_h(ld_faktura_base_url() . '/admin/') ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener"><?= ld_h($ta['faktura_admin_link'] ?? 'Faktura admin') ?></a>
                <a href="<?= ld_h(ld_faktura_support_url()) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener"><i class="fas fa-headset"></i> <?= ld_h($ta['faktura_support_link'] ?? 'Faktura support') ?></a>
            </div>
        </div>
    </div>
    <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h($ta['save'] ?? '') ?></button>
</form>
<?php require __DIR__ . '/includes/layout-end.php'; ?>