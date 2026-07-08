<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'settings';
$page_title = $ta['settings'] ?? 'Settings';

$presets = ld_business_presets();
$current = ld_business_preset_id();
$names = ld_template_names($lang);
$applied = isset($_GET['applied']) ? (string) $_GET['applied'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_preset'])) {
    $id = trim((string) $_POST['apply_preset']);
    $result = ld_apply_business_preset($id);
    if ($result['ok']) {
        header('Location: ' . ld_admin_url('settings.php') . '?applied=' . rawurlencode($id), true, 302);
        exit;
    }
}

$currentPreset = $presets[$current] ?? null;
$activeTpl = ld_active_template();

require __DIR__ . '/includes/layout.php';
?>

<?php if ($applied !== '' && isset($presets[$applied])): ?>
<div class="adm-alert adm-alert-success">
    <i class="fas fa-check"></i>
    <?= ld_h($ta['preset_applied'] ?? 'Preset applied') ?>:
    <strong><?= ld_h(ld_pick($presets[$applied]['label'], $lang)) ?></strong>
    · <?= ld_h($ta['design'] ?? 'Design') ?> #<?= (int) ($presets[$applied]['template'] ?? 1) ?>
    <?= ld_h($names[(int) ($presets[$applied]['template'] ?? 1)] ?? '') ?>
</div>
<?php endif; ?>

<p class="adm-help"><?= ld_h($ta['settings_help'] ?? '') ?></p>

<?php
$ai_fill_scope = 'all';
$ai_fill_brief_default = $currentPreset ? ld_pick($currentPreset['label'], $lang) . ' — ' . ($currentPreset['brief'] ?? '') : '';
require __DIR__ . '/includes/ai-fill-panel.php';
?>

<div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card-head">
        <h2><i class="fas fa-store"></i> <?= ld_h($ta['current_business'] ?? 'Current business') ?></h2>
        <?php if ($currentPreset): ?>
        <span class="adm-badge adm-badge-active"><?= ld_h(ld_pick($currentPreset['label'], $lang)) ?></span>
        <?php endif; ?>
    </div>
    <div class="adm-card-body padded">
        <p style="margin:0 0 12px;color:var(--adm-muted)">
            <?= ld_h(ld_pick(ld_business()['name'], $lang)) ?> — <?= ld_h(ld_pick(ld_business()['city'], $lang)) ?>
        </p>
        <p style="margin:0 0 12px;font-size:13px">
            <i class="fas fa-palette" style="color:var(--adm-accent)"></i>
            <?= ld_h($ta['active_template'] ?? 'Template') ?>:
            <strong>#<?= $activeTpl ?> <?= ld_h($names[$activeTpl] ?? '') ?></strong>
        </p>
        <a href="<?= ld_h(ld_admin_url('content.php')) ?>" class="adm-btn adm-btn-outline" style="margin-right:8px">
            <i class="fas fa-pen-to-square"></i> <?= ld_h($ta['edit_content'] ?? $ta['content'] ?? '') ?>
        </a>
        <a href="<?= ld_h(ld_url('template.php', ['t' => $activeTpl])) ?>" class="adm-btn adm-btn-primary" target="_blank">
            <i class="fas fa-external-link-alt"></i> <?= ld_h($ta['view_site'] ?? '') ?>
        </a>
    </div>
</div>

<h2 class="adm-subhead" style="margin-top:0"><i class="fas fa-briefcase"></i> <?= ld_h($ta['business_presets'] ?? 'Business presets') ?></h2>
<p class="adm-help"><?= ld_h($ta['presets_help'] ?? '') ?></p>

<div class="adm-preset-grid">
    <?php foreach ($presets as $id => $preset):
        $isActive = $id === $current;
        $tplId = (int) ($preset['template'] ?? 1);
    ?>
    <article class="adm-card adm-preset-card<?= $isActive ? ' adm-preset-card--active' : '' ?>">
        <div class="adm-card-body padded">
            <div class="adm-preset-icon"><i class="fas <?= ld_h($preset['icon'] ?? 'fa-store') ?>"></i></div>
            <h3><?= ld_h(ld_pick($preset['label'], $lang)) ?></h3>
            <p class="adm-preset-desc"><?= ld_h(ld_pick($preset['desc'], $lang)) ?></p>
            <p class="adm-field-hint">
                <i class="fas fa-palette"></i> #<?= $tplId ?> <?= ld_h($names[$tplId] ?? '') ?>
            </p>
            <?php if ($isActive): ?>
            <span class="adm-badge adm-badge-active"><?= ld_h($ta['preset_active'] ?? 'Active') ?></span>
            <?php else: ?>
            <form method="post" class="adm-preset-form" onsubmit="return confirm('<?= ld_h($ta['preset_confirm'] ?? 'Apply this business preset? Current content will be replaced.') ?>')">
                <input type="hidden" name="apply_preset" value="<?= ld_h($id) ?>">
                <button type="submit" class="adm-btn adm-btn-sm adm-btn-primary">
                    <i class="fas fa-download"></i> <?= ld_h($ta['preset_apply'] ?? 'Apply preset') ?>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </article>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>