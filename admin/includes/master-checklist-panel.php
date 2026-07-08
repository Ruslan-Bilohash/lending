<?php
/** @var array $ta */
require_once dirname(__DIR__, 2) . '/includes/admin-master-checklist.php';
$items = ld_admin_master_checklist_items();
$pendingGroups = ld_admin_master_checklist_pending_by_group($items);
$pendingCount = count(ld_admin_checklist_pending($items));
$prog = ld_admin_checklist_progress($items);
$progressText = str_replace(
    ['{done}', '{total}'],
    [(string) $prog['done'], (string) $prog['total']],
    (string) ($ta['tasks_progress'] ?? '{done} / {total}')
);
$groupLabels = [
    'content' => $ta['tasks_group_content'] ?? '',
    'seo' => $ta['tasks_group_seo'] ?? '',
    'setup' => $ta['tasks_group_setup'] ?? '',
];
$goLabels = [
    'seo' => $ta['tasks_go_seo'] ?? $ta['seo_checklist_go_seo'] ?? '',
    'content' => $ta['tasks_go_content'] ?? $ta['checklist_go_fill'] ?? '',
    'news' => $ta['tasks_go_news'] ?? $ta['seo_checklist_go_news'] ?? '',
    'blocks' => $ta['tasks_go_blocks'] ?? $ta['seo_checklist_go_blocks'] ?? '',
    'ai' => $ta['tasks_go_ai'] ?? '',
    'integrations' => $ta['tasks_go_integrations'] ?? '',
];
?>
<div class="adm-card adm-checklist-card adm-master-tasks-card">
    <div class="adm-card-head">
        <h2><i class="fas fa-list-check"></i> <?= ld_h($ta['tasks_title'] ?? '') ?></h2>
        <span class="adm-badge <?= $prog['pct'] >= 100 ? 'adm-badge-active' : '' ?>"><?= (int) $prog['pct'] ?>%</span>
    </div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta['tasks_intro'] ?? '') ?></p>
        <p class="adm-checklist-progress"><strong><?= ld_h($progressText) ?></strong></p>

        <?php if ($pendingCount === 0): ?>
        <div class="adm-alert adm-alert-success adm-checklist-done-msg">
            <i class="fas fa-circle-check"></i> <?= ld_h($ta['tasks_all_done'] ?? '') ?>
        </div>
        <?php else: ?>
        <div class="adm-checklist-todo">
            <h3 class="adm-subhead"><i class="fas fa-pen"></i> <?= ld_h($ta['tasks_pending_title'] ?? '') ?> (<?= (int) $pendingCount ?>)</h3>
            <?php foreach ($pendingGroups as $groupKey => $groupItems): ?>
            <?php if ($groupItems === []) {
                continue;
            } ?>
            <div class="adm-tasks-group">
                <h4 class="adm-tasks-group-title">
                    <?php
                    $groupIcon = match ($groupKey) {
                        'seo' => 'fa-google',
                        'setup' => 'fa-sliders',
                        default => 'fa-file-lines',
                    };
                    ?>
                    <i class="fas <?= ld_h($groupIcon) ?>"></i>
                    <?= ld_h($groupLabels[$groupKey] ?? $groupKey) ?>
                    <span class="adm-tasks-group-count"><?= count($groupItems) ?></span>
                </h4>
                <ul class="adm-checklist adm-checklist--todo">
                    <?php foreach ($groupItems as $item): ?>
                    <?php
                    $target = (string) ($item['target'] ?? 'content');
                    $goLabel = $goLabels[$target] ?? $goLabels['content'];
                    ?>
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
                            <a href="<?= ld_h($item['link'] . ($item['anchor'] ?? '')) ?>" class="adm-btn adm-btn-sm adm-btn-primary">
                                <i class="fas fa-arrow-right"></i>
                                <?= ld_h($goLabel) ?>
                            </a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($prog['done'] > 0): ?>
        <details class="adm-checklist-done-wrap" <?= $pendingCount === 0 ? 'open' : '' ?>>
            <summary><?= ld_h(str_replace('{n}', (string) $prog['done'], $ta['tasks_done_summary'] ?? '')) ?></summary>
            <ul class="adm-checklist adm-checklist--done adm-master-done-list">
                <?php foreach ($items as $item): ?>
                <?php if (empty($item['done'])) {
                    continue;
                } ?>
                <li class="adm-checklist-item is-done">
                    <span class="adm-checklist-icon" aria-hidden="true"><i class="fas fa-circle-check"></i></span>
                    <div class="adm-checklist-body">
                        <span class="adm-tasks-done-group"><?= ld_h($groupLabels[$item['group'] ?? 'content'] ?? '') ?></span>
                        <strong><?= ld_h($item['label']) ?></strong>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </details>
        <?php endif; ?>
    </div>
</div>