<?php
/** @var array $ta */
require_once dirname(__DIR__, 2) . '/includes/admin-checklist.php';
$tips = ld_admin_seo_tip_lines();
?>
<div class="adm-card adm-seo-tips-card">
    <div class="adm-card-head">
        <h2><i class="fas fa-lightbulb"></i> <?= ld_h($ta['seo_tips_title'] ?? '') ?></h2>
    </div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta['seo_tips_intro'] ?? '') ?></p>
        <ul class="adm-seo-tips-list">
            <?php foreach ($tips as $tip): ?>
            <li><i class="fas fa-check"></i> <?= ld_h($tip) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>