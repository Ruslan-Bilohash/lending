<?php
/** @var array $ta */
$api = ld_admin_url('api/ai-seo-analyze.php');
?>
<div class="adm-card adm-seo-ai-card" id="admSeoAiCard" data-api="<?= ld_h($api) ?>" tabindex="-1"
    data-msg-error="<?= ld_h(ld_admin_t('js_error')) ?>"
    data-msg-generating="<?= ld_h(ld_admin_t('notify_agent_analyzing', ld_admin_t('js_generating'))) ?>"
    data-msg-seo-demo="<?= ld_h(ld_admin_t('js_seo_demo')) ?>"
    data-msg-seo-ai-ok="<?= ld_h(ld_admin_t('js_seo_ai_ok')) ?>">
    <div class="adm-card-head">
        <h2><i class="fas fa-chart-line"></i> <?= ld_h($ta['seo_ai_title'] ?? 'AI SEO analysis') ?></h2>
        <span class="adm-badge" id="admSeoGrade">—</span>
    </div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta['seo_ai_help'] ?? '') ?></p>
        <div class="adm-seo-score-wrap">
            <div class="adm-seo-score" id="admSeoScore">—</div>
            <span><?= ld_h($ta['seo_score'] ?? 'Score') ?></span>
        </div>
        <ul class="adm-seo-tips" id="admSeoTips"></ul>
        <div id="admSeoSuggestions" class="adm-seo-suggestions" hidden>
            <h3 class="adm-subhead"><?= ld_h($ta['seo_suggestions'] ?? 'AI suggestions') ?></h3>
            <p class="adm-field-hint"><strong><?= ld_h(ld_admin_t('seo_field_title')) ?>:</strong> <span id="admSeoSugTitle"></span></p>
            <p class="adm-field-hint"><strong><?= ld_h(ld_admin_t('seo_field_description')) ?>:</strong> <span id="admSeoSugDesc"></span></p>
            <p class="adm-field-hint"><strong><?= ld_h(ld_admin_t('seo_field_keywords')) ?>:</strong> <span id="admSeoSugKw"></span></p>
        </div>
        <button type="button" class="adm-btn adm-btn-primary" id="admSeoAnalyzeBtn">
            <i class="fas fa-wand-magic-sparkles"></i> <?= ld_h($ta['seo_analyze_btn'] ?? 'Analyze SEO') ?>
        </button>
        <p class="adm-field-hint" id="admSeoStatus"></p>
    </div>
</div>
<script src="<?= ld_h(ld_asset('js/admin-seo-ai.js')) ?>?v=3" defer></script>