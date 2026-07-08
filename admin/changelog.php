<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/changelog.php';
ld_admin_require();

$admin_page = 'changelog';
$page_title = $ta['changelog'] ?? 'Changelog';
$version = ld_version();
$entries = ld_changelog_entries();

require __DIR__ . '/includes/layout.php';
?>

<div class="adm-changelog-head">
    <div class="adm-changelog-version">
        <span class="adm-changelog-version-label"><?= ld_h($ta['changelog_current'] ?? 'Current version') ?></span>
        <span class="adm-changelog-version-num">v<?= ld_h($version) ?></span>
    </div>
    <p class="adm-help adm-changelog-lead"><?= ld_h($ta['changelog_lead'] ?? 'Release history and what\'s new in Business Landing CMS.') ?></p>
</div>

<?php if ($entries === []): ?>
<div class="adm-alert adm-alert-info">
    <i class="fas fa-circle-info"></i> <?= ld_h($ta['changelog_empty'] ?? 'Changelog file not found.') ?>
</div>
<?php else: ?>
<div class="adm-changelog-timeline">
    <?php foreach ($entries as $i => $entry):
        $isLatest = $i === 0;
    ?>
    <article class="adm-card adm-changelog-card<?= $isLatest ? ' adm-changelog-card--latest' : '' ?>">
        <header class="adm-changelog-card-head">
            <div>
                <h2 class="adm-changelog-card-title">
                    <span class="adm-changelog-card-version">v<?= ld_h($entry['version']) ?></span>
                    <?php if ($isLatest): ?>
                    <span class="adm-badge adm-badge-active"><?= ld_h($ta['changelog_latest'] ?? 'Latest') ?></span>
                    <?php endif; ?>
                </h2>
                <time class="adm-changelog-card-date" datetime="<?= ld_h($entry['date']) ?>"><?= ld_h($entry['date']) ?></time>
            </div>
        </header>
        <?php if ($entry['meta'] !== []): ?>
        <div class="adm-changelog-meta">
            <?php foreach ($entry['meta'] as $meta): ?>
            <p class="adm-changelog-meta-row">
                <i class="fas fa-rocket"></i>
                <strong><?= ld_h($meta['label']) ?>:</strong>
                <span><?= ld_changelog_format_line($meta['text']) ?></span>
            </p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if ($entry['items'] !== []): ?>
        <ul class="adm-changelog-list">
            <?php foreach ($entry['items'] as $item): ?>
            <li><?= ld_changelog_format_line($item) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout-end.php'; ?>