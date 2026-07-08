<?php
/** @var array $ta */
require_once dirname(__DIR__, 2) . '/includes/admin-seo-health.php';
$health = ld_admin_seo_health_summary();
?>
<div class="adm-card adm-seo-health-card">
    <div class="adm-card-head">
        <h2><i class="fas fa-heart-pulse"></i> <?= ld_h($ta['seo_health_title'] ?? '') ?></h2>
        <span class="adm-badge adm-seo-health-grade <?= ld_h($health['pill']) ?>"><?= ld_h($health['grade']) ?></span>
    </div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta['seo_health_intro'] ?? '') ?></p>
        <div class="adm-seo-health-main">
            <div class="adm-seo-health-score-block">
                <div class="adm-seo-score adm-seo-score--hero"><?= (int) $health['avg'] ?></div>
                <div class="adm-seo-health-meta">
                    <span><?= ld_h($ta['seo_health_avg'] ?? '') ?></span>
                    <span class="adm-seo-pill <?= ld_h($health['pill']) ?>"><?= ld_h($health['grade']) ?></span>
                </div>
            </div>
            <div class="adm-seo-health-langs">
                <p class="adm-field-hint" style="margin:0 0 8px;font-weight:600;color:var(--adm-text)"><?= ld_h($ta['seo_health_lang'] ?? '') ?></p>
                <ul class="adm-seo-health-lang-list">
                    <?php foreach ($health['langs'] as $row): ?>
                    <li>
                        <span class="adm-seo-health-lang-label">
                            <span aria-hidden="true"><?= ld_h($row['flag']) ?></span>
                            <?= ld_h($row['label']) ?>
                        </span>
                        <span class="adm-seo-pill <?= ld_h($row['pill']) ?>"><?= (int) $row['score'] ?> · <?= ld_h($row['grade']) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php if ($health['tips'] !== []): ?>
        <div class="adm-seo-health-tips">
            <h3 class="adm-subhead"><i class="fas fa-wrench"></i> <?= ld_h($ta['seo_health_tips_title'] ?? '') ?></h3>
            <ul class="adm-seo-tips">
                <?php foreach ($health['tips'] as $tip): ?>
                <li><i class="fas fa-circle-exclamation"></i> <?= ld_h($tip) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <div class="adm-quick-actions" style="margin-top:12px">
            <a href="<?= ld_h(ld_admin_url('seo.php')) ?>" class="adm-btn adm-btn-primary adm-btn-sm">
                <i class="fas fa-search"></i> <?= ld_h($ta['seo_health_fix'] ?? '') ?>
            </a>
            <a href="<?= ld_h(ld_admin_url('seo.php')) ?>#admSeoAiCard" class="adm-btn adm-btn-outline adm-btn-sm">
                <i class="fas fa-wand-magic-sparkles"></i> <?= ld_h($ta['seo_health_analyze'] ?? '') ?>
            </a>
        </div>
    </div>
</div>