<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'ai';
$page_title = $ta['ai'] ?? 'AI Agent';
$settings = ld_settings();
$ai = $settings['ai'] ?? [];
$providers = ld_ai_providers();
$resolved = ld_ai_resolve_config($ai, false);
$resolvedAdmin = ld_ai_resolve_config($ai, true);
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $provider = trim((string) ($_POST['ai_provider'] ?? 'openai'));
    if (!isset($providers[$provider])) {
        $provider = 'openai';
    }
    $preset = $providers[$provider];
    $model = trim((string) ($_POST['ai_model'] ?? ''));
    if ($model === '' && !empty($_POST['ai_model_select'])) {
        $model = trim((string) $_POST['ai_model_select']);
    }
    $adminModel = trim((string) ($_POST['ai_admin_model'] ?? ''));
    if ($adminModel === '' && !empty($_POST['ai_admin_model_select'])) {
        $adminModel = trim((string) $_POST['ai_admin_model_select']);
    }
    $ai['enabled'] = !empty($_POST['ai_enabled']);
    $ai['fill_enabled'] = !empty($_POST['ai_fill_enabled']);
    $ai['provider'] = $provider;
    $ai['api_base'] = rtrim(trim((string) ($_POST['ai_api_base'] ?? $preset['api_base'])), '/');
    $ai['model'] = $model !== '' ? $model : ($preset['models'][0] ?? 'gpt-4o-mini');
    $ai['admin_model'] = $adminModel !== '' ? $adminModel : ($preset['admin_models'][0] ?? 'gpt-4o');
    if (trim((string) ($_POST['ai_api_key'] ?? '')) !== '') {
        $ai['api_key'] = trim((string) $_POST['ai_api_key']);
    }
    foreach (ld_langs_codes() as $code) {
        $ai['welcome'][$code] = trim((string) ($_POST['ai_welcome_' . $code] ?? ''));
    }
    $ai['system_prompt'] = trim((string) ($_POST['ai_system_prompt'] ?? ''));
    $settings['ai'] = $ai;
    if (ld_save_settings($settings)) {
        $saved = true;
        $ai = $settings['ai'];
        $resolved = ld_ai_resolve_config($ai, false);
        $resolvedAdmin = ld_ai_resolve_config($ai, true);
    }
}

$currentProvider = $ai['provider'] ?? 'openai';
$providerModels = $providers[$currentProvider]['models'] ?? [];
$providerAdminModels = $providers[$currentProvider]['admin_models'] ?? $providerModels;

require __DIR__ . '/includes/layout.php';
?>
<?php if ($saved): ?><div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['saved'] ?? '') ?></div><?php endif; ?>
<?php $api_guide_scope = 'ai'; require __DIR__ . '/includes/api-instructions.php'; ?>
<form method="post" class="adm-card">
    <div class="adm-card-head"><h2><i class="fas fa-robot"></i> <?= ld_h($ta['ai'] ?? 'AI Agent') ?></h2></div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta['ai_help'] ?? '') ?></p>
        <div class="adm-form-grid">
            <div class="adm-field-check adm-field-full">
                <label><input type="checkbox" name="ai_enabled" value="1" <?= !empty($ai['enabled']) ? 'checked' : '' ?>> <?= ld_h($ta['ai_enable'] ?? '') ?> (<?= ld_h($ta['ai_chat_label'] ?? 'landing chat') ?>)</label>
            </div>
            <div class="adm-field-check adm-field-full">
                <label><input type="checkbox" name="ai_fill_enabled" value="1" <?= !empty($ai['fill_enabled']) ? 'checked' : '' ?>> <?= ld_h($ta['ai_fill_enable'] ?? '') ?></label>
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['ai_provider'] ?? 'Provider') ?></label>
                <select name="ai_provider" id="aiProvider">
                    <?php foreach ($providers as $key => $preset): ?>
                    <option value="<?= ld_h($key) ?>" <?= $currentProvider === $key ? 'selected' : '' ?>><?= ld_h($preset['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="adm-field adm-field-full">
                <label><?= ld_h($ta['ai_api_base_label'] ?? $ta['ai_api_base'] ?? '') ?></label>
                <input type="url" name="ai_api_base" value="<?= ld_h($ai['api_base'] ?? $providers[$currentProvider]['api_base'] ?? '') ?>">
            </div>
            <div class="adm-field adm-field-full">
                <label><?= ld_h($ta['ai_api_key_label'] ?? $ta['ai_api_key'] ?? '') ?></label>
                <input type="password" name="ai_api_key" placeholder="sk-..." autocomplete="new-password">
                <p class="adm-field-hint"><?= ld_h($ta['ai_key_hint'] ?? '') ?></p>
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['ai_chat_model'] ?? 'Chat model') ?></label>
                <select name="ai_model_select">
                    <?php foreach ($providerModels as $m): ?>
                    <option value="<?= ld_h($m) ?>" <?= ($ai['model'] ?? '') === $m ? 'selected' : '' ?>><?= ld_h($m) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="ai_model" value="<?= ld_h($ai['model'] ?? '') ?>" placeholder="<?= ld_h($ta['ai_custom_model'] ?? 'custom model') ?>">
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['ai_admin_model'] ?? 'Admin fill model') ?></label>
                <select name="ai_admin_model_select">
                    <?php foreach ($providerAdminModels as $m): ?>
                    <option value="<?= ld_h($m) ?>" <?= ($ai['admin_model'] ?? '') === $m ? 'selected' : '' ?>><?= ld_h($m) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="ai_admin_model" value="<?= ld_h($ai['admin_model'] ?? '') ?>" placeholder="gpt-4o">
            </div>
        </div>
        <p class="adm-field-hint"><?= ld_h($ta['ai_active_models'] ?? 'Active') ?>: chat <strong><?= ld_h($resolved['model']) ?></strong>, fill <strong><?= ld_h($resolvedAdmin['model']) ?></strong></p>
        <?php foreach (ld_lang_labels() as $code => $label): ?>
        <div class="adm-field adm-field-full"><label><?= ld_h($ta['ai_welcome_label'] ?? 'Welcome') ?> (<?= $label ?>)</label><input type="text" name="ai_welcome_<?= $code ?>" value="<?= ld_h($ai['welcome'][$code] ?? '') ?>"></div>
        <?php endforeach; ?>
        <div class="adm-field adm-field-full"><label><?= ld_h($ta['ai_system_prompt'] ?? 'System prompt (chat)') ?></label><textarea name="ai_system_prompt" rows="4"><?= ld_h($ai['system_prompt'] ?? '') ?></textarea></div>
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h($ta['save'] ?? '') ?></button>
    </div>
</form>
<?php require __DIR__ . '/includes/layout-end.php'; ?>