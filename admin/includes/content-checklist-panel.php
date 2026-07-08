<?php
/** @var array $ta */
require_once dirname(__DIR__, 2) . '/includes/admin-checklist.php';
$items = ld_admin_content_checklist_items();
$pending = ld_admin_checklist_pending($items);
$prog = ld_admin_checklist_progress($items);
$progressText = str_replace(
    ['{done}', '{total}'],
    [(string) $prog['done'], (string) $prog['total']],
    (string) ($ta['checklist_progress'] ?? '{done} / {total}')
);
?>
<div class="adm-card adm-checklist-card">
    <div class="adm-card-head">
        <h2><i class="fas fa-list-check"></i> <?= ld_h($ta['checklist_title'] ?? '') ?></h2>
        <span class="adm-badge <?= $prog['pct'] >= 100 ? 'adm-badge-active' : '' ?>"><?= (int) $prog['pct'] ?>%</span>
    </div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta['checklist_intro'] ?? '') ?></p>
        <p class="adm-checklist-progress"><strong><?= ld_h($progressText) ?></strong></p>

        <?php if ($pending === []): ?>
        <div class="adm-alert adm-alert-success adm-checklist-done-msg">
            <i class="fas fa-circle-check"></i> <?= ld_h($ta['checklist_all_done'] ?? '') ?>
        </div>
        <?php else: ?>
        <div class="adm-checklist-todo">
            <h3 class="adm-subhead"><i class="fas fa-pen"></i> <?= ld_h($ta['checklist_pending_title'] ?? '') ?></h3>
            <ul class="adm-checklist adm-checklist--todo">
                <?php foreach ($pending as $item): ?>
                <li class="adm-checklist-item is-pending">
                    <span class="adm-checklist-icon" aria-hidden="true"><i class="fas fa-circle-exclamation"></i></span>
                    <div class="adm-checklist-body">
                        <strong><?= ld_h($item['label']) ?></strong>
                        <?php if (!empty($item['missing'])): ?>
                        <ul class="adm-checklist-missing">
                            <?php foreach ($item['missing'] as $line): ?>
                            <li><?= ld_h($line) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <a href="<?= ld_h($item['link'] . ($item['anchor'] ?? '')) ?>" class="adm-checklist-link adm-btn adm-btn-sm adm-btn-primary">
                            <i class="fas fa-arrow-right"></i>
                            <?= ld_h($item['key'] === 'seo' ? ($ta['checklist_go_seo'] ?? '') : ($ta['checklist_go_fill'] ?? $ta['checklist_go_content'] ?? '')) ?>
                        </a>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($prog['done'] > 0): ?>
        <details class="adm-checklist-done-wrap" <?= $pending === [] ? 'open' : '' ?>>
            <summary><?= ld_h(str_replace('{n}', (string) $prog['done'], $ta['checklist_done_summary'] ?? '')) ?></summary>
            <ul class="adm-checklist adm-checklist--done">
                <?php foreach ($items as $item): ?>
                <?php if (empty($item['done'])) {
                    continue;
                } ?>
                <li class="adm-checklist-item is-done">
                    <span class="adm-checklist-icon" aria-hidden="true"><i class="fas fa-circle-check"></i></span>
                    <div class="adm-checklist-body">
                        <strong><?= ld_h($item['label']) ?></strong>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </details>
        <?php endif; ?>
    </div>
</div>