<?php
/** @var string $ai_fill_scope @var array $ta */
$scope = $ai_fill_scope ?? 'all';
$ai = ld_ai();
$resolved = ld_ai_resolve_config($ai, true);
$hasKey = trim((string) ($ai['api_key'] ?? '')) !== '';
?>
<?php
$helpKey = $scope === 'seo' ? 'ai_fill_help_seo' : 'ai_fill_help';
?>
<div class="adm-card adm-ai-fill-card" id="admAiFillCard"
    data-scope="<?= ld_h($scope) ?>"
    data-api="<?= ld_h(ld_admin_url('api/ai-fill.php')) ?>"
    data-msg-brief="<?= ld_h($ta['ai_fill_brief_required'] ?? '') ?>"
    data-msg-generating="<?= ld_h($ta['ai_fill_generating'] ?? '') ?>"
    data-msg-network="<?= ld_h($ta['ai_fill_network_error'] ?? '') ?>"
    data-msg-saved="<?= ld_h($ta['ai_fill_saved'] ?? $ta['saved'] ?? '') ?>"
    data-msg-demo-saved="<?= ld_h($ta['ai_fill_demo_saved'] ?? '') ?>"
    data-msg-error="<?= ld_h($ta['js_error'] ?? '') ?>">
    <div class="adm-card-head">
        <h2><i class="fas fa-wand-magic-sparkles"></i> <?= ld_h($ta['ai_fill_title'] ?? 'AI Agent — auto fill') ?></h2>
        <span class="adm-badge <?= $hasKey ? 'adm-badge-active' : '' ?>"><?= ld_h($hasKey ? ($ta['ai_key_set'] ?? 'API OK') : ($ta['ai_key_missing'] ?? 'Demo mode')) ?></span>
    </div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta[$helpKey] ?? $ta['ai_fill_help'] ?? '') ?></p>
        <div class="adm-field adm-field-full">
            <label><?= ld_h($ta['ai_fill_brief'] ?? 'Business brief') ?></label>
            <textarea id="admAiBrief" rows="4" placeholder="<?= ld_h($ta['ai_fill_placeholder'] ?? '') ?>"><?= ld_h($ai_fill_brief_default ?? ld_pick(ld_business()['tagline'] ?? [], $lang)) ?></textarea>
        </div>
        <p class="adm-field-hint">
            <?= ld_h($ta['ai_fill_model'] ?? 'Admin model') ?>: <strong><?= ld_h($resolved['model']) ?></strong>
            (<?= ld_h($resolved['provider']) ?>)
            · <a href="<?= ld_h(ld_admin_url('ai.php')) ?>"><?= ld_h($ta['ai_settings_link'] ?? 'AI settings') ?></a>
        </p>
        <div class="adm-quick-actions">
            <button type="button" class="adm-btn adm-btn-primary" id="admAiFillBtn" data-scope="<?= ld_h($scope) ?>">
                <i class="fas fa-bolt"></i> <?= ld_h($ta['ai_fill_btn'] ?? 'Generate & save') ?>
            </button>
        </div>
        <p id="admAiFillStatus" class="adm-field-hint" hidden></p>
    </div>
</div>
<script src="<?= ld_h(ld_asset('js/admin-ai-fill.js')) ?>?v=4" defer></script>