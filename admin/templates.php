<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'templates';
$page_title = $ta['templates'] ?? 'Templates';

$active = ld_active_template();
$names = ld_template_names($lang);
$meta = ld_templates_meta();
$recommended = ld_recommended_template_for_preset(ld_business_preset_id());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_active'])) {
    $id = max(1, min(10, (int) $_POST['set_active']));
    $settings = ld_settings();
    $settings['active_template'] = $id;
    ld_save_settings($settings);
    header('Location: ' . ld_admin_url('templates.php'), true, 302);
    exit;
}

require __DIR__ . '/includes/layout.php';
?>

<div class="adm-template-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px">
    <?php for ($i = 1; $i <= 10; $i++):
        $m = $meta[$i];
    ?>
    <article class="adm-card" style="margin:0">
        <div class="adm-card-body padded">
            <h3 style="margin:0 0 8px">#<?= $i ?> <?= ld_h($names[$i] ?? '') ?></h3>
            <p style="margin:0 0 12px;font-size:12px;color:var(--adm-muted)"><?= ld_h($m['layout']) ?> · <?= ld_h($m['slug']) ?></p>
            <?php if ($i === $active): ?>
            <span class="adm-badge adm-badge-active" style="margin-bottom:6px;display:inline-block"><?= ld_h($ta['template_active'] ?? 'Active') ?></span>
            <?php endif; ?>
            <?php if ($i === $recommended): ?>
            <span class="adm-badge" style="background:var(--adm-accent-light);color:var(--adm-accent-dark);margin-bottom:10px;display:inline-block"><i class="fas fa-star"></i> <?= ld_h($ta['template_recommended'] ?? 'Recommended') ?></span>
            <?php endif; ?>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
                <a href="<?= ld_h(ld_url('template.php', ['t' => $i])) ?>" class="adm-btn adm-btn-sm" target="_blank"><?= ld_h($ta['open_template'] ?? '') ?></a>
                <?php if ($i !== $active): ?>
                <form method="post" style="margin:0">
                    <input type="hidden" name="set_active" value="<?= $i ?>">
                    <button type="submit" class="adm-btn adm-btn-sm adm-btn-primary"><?= ld_h($ta['set_active'] ?? '') ?></button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </article>
    <?php endfor; ?>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>