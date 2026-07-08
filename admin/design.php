<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'design';
$page_title = $ta['design'] ?? 'Design';

$settings = ld_settings();
$design = $settings['design'] ?? ld_default_design();
$names = ld_template_names($lang);
$meta = ld_templates_meta();
$presets = ld_design_demo_presets();
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['apply_design_preset'])) {
        $pid = trim((string) $_POST['apply_design_preset']);
        $r = ld_apply_design_preset($pid);
        if ($r['ok']) {
            header('Location: ' . ld_admin_url('design.php?preset=' . rawurlencode($pid)), true, 302);
            exit;
        }
    }

    $design['accent'] = trim((string) ($_POST['design_accent'] ?? ''));
    $design['button_style'] = in_array($_POST['design_button_style'] ?? '', ['rounded', 'pill', 'square'], true)
        ? $_POST['design_button_style'] : 'rounded';
    $design['font_scale'] = (string) max(90, min(115, (int) ($_POST['design_font_scale'] ?? 100)));
    $design['hero_style'] = in_array($_POST['design_hero_style'] ?? '', ['default', 'minimal', 'fullscreen'], true)
        ? $_POST['design_hero_style'] : 'default';

    $sectionKeys = array_keys(ld_default_design()['sections']);
    $sections = [];
    foreach ($sectionKeys as $sk) {
        $sections[$sk] = !empty($_POST['section_' . $sk]);
    }
    $design['sections'] = $sections;

    if (isset($_POST['active_template'])) {
        $settings['active_template'] = max(1, min(10, (int) $_POST['active_template']));
    }

    $settings['design'] = $design;
    if (ld_save_settings($settings)) {
        $saved = true;
        $design = $settings['design'];
    }
}

$active = ld_active_template();
$presetApplied = isset($_GET['preset']) ? (string) $_GET['preset'] : '';

require __DIR__ . '/includes/layout.php';
?>

<?php if ($saved): ?><div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['saved'] ?? '') ?></div><?php endif; ?>
<?php if ($presetApplied !== '' && isset($presets[$presetApplied])): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-palette"></i> <?= ld_h($ta['design_preset_applied'] ?? 'Design preset applied') ?>: <?= ld_h(ld_pick($presets[$presetApplied]['label'], $lang)) ?></div>
<?php endif; ?>

<p class="adm-help"><?= ld_h($ta['design_help'] ?? '') ?></p>

<div class="adm-card adm-constructor-tip">
    <div class="adm-card-body padded">
        <p><i class="fas fa-lightbulb"></i> <strong><?= ld_h($ta['constructor_tip'] ?? 'Tip') ?>:</strong> <?= ld_h($ta['design_tip_text'] ?? '') ?></p>
    </div>
</div>

<h2 class="adm-subhead"><i class="fas fa-swatchbook"></i> <?= ld_h($ta['design_demos'] ?? 'Demo designs') ?></h2>
<p class="adm-help"><?= ld_h($ta['design_demos_help'] ?? '') ?></p>
<div class="adm-preset-grid adm-design-preset-grid">
    <?php foreach ($presets as $pid => $preset): ?>
    <article class="adm-card adm-preset-card">
        <div class="adm-card-body padded">
            <h3><?= ld_h(ld_pick($preset['label'], $lang)) ?></h3>
            <p class="adm-preset-desc"><?= ld_h(ld_pick($preset['hint'], $lang)) ?></p>
            <p class="adm-field-hint">#<?= (int) $preset['template'] ?> <?= ld_h($names[(int) $preset['template']] ?? '') ?>
                <?php if (!empty($preset['accent'])): ?> · <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:<?= ld_h($preset['accent']) ?>;vertical-align:middle"></span><?php endif; ?>
            </p>
            <form method="post">
                <input type="hidden" name="apply_design_preset" value="<?= ld_h($pid) ?>">
                <button type="submit" class="adm-btn adm-btn-sm adm-btn-primary"><i class="fas fa-magic"></i> <?= ld_h($ta['design_apply'] ?? 'Apply') ?></button>
            </form>
        </div>
    </article>
    <?php endforeach; ?>
</div>

<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-palette"></i> <?= ld_h($ta['templates'] ?? 'Templates') ?> (#<?= $active ?>)</h2></div>
        <div class="adm-card-body padded">
            <div class="adm-template-pick-grid">
                <?php for ($i = 1; $i <= 10; $i++): $m = $meta[$i]; ?>
                <label class="adm-template-pick<?= $i === $active ? ' adm-template-pick--on' : '' ?>">
                    <input type="radio" name="active_template" value="<?= $i ?>" <?= $i === $active ? 'checked' : '' ?>>
                    <span class="adm-template-pick-preview ld-preview-<?= str_pad((string) $i, 2, '0', STR_PAD_LEFT) ?>"><i class="fas <?= ld_h($m['icon']) ?>"></i></span>
                    <span class="adm-template-pick-label">#<?= $i ?> <?= ld_h($names[$i] ?? '') ?></span>
                </label>
                <?php endfor; ?>
            </div>
            <p class="adm-field-hint"><?= ld_h($ta['design_template_hint'] ?? '') ?></p>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-sliders"></i> <?= ld_h($ta['design_options'] ?? 'Style options') ?></h2></div>
        <div class="adm-card-body padded adm-form-grid">
            <div class="adm-field">
                <label><?= ld_h($ta['design_accent'] ?? 'Accent color') ?></label>
                <input type="color" name="design_accent" value="<?= ld_h(($design['accent'] ?? '') !== '' ? $design['accent'] : '#1d4ed8') ?>">
                <p class="adm-field-hint"><?= ld_h($ta['design_accent_hint'] ?? 'Overrides theme primary on live site') ?></p>
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['design_buttons'] ?? 'Buttons') ?></label>
                <select name="design_button_style">
                    <option value="rounded" <?= ($design['button_style'] ?? '') === 'rounded' ? 'selected' : '' ?>><?= ld_h($ta['design_btn_rounded'] ?? 'Rounded') ?></option>
                    <option value="pill" <?= ($design['button_style'] ?? '') === 'pill' ? 'selected' : '' ?>><?= ld_h($ta['design_btn_pill'] ?? 'Pill') ?></option>
                    <option value="square" <?= ($design['button_style'] ?? '') === 'square' ? 'selected' : '' ?>><?= ld_h($ta['design_btn_square'] ?? 'Square') ?></option>
                </select>
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['design_font_scale'] ?? 'Font scale') ?> (%)</label>
                <input type="number" name="design_font_scale" min="90" max="115" value="<?= ld_h((string) ($design['font_scale'] ?? '100')) ?>">
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['design_hero'] ?? 'Hero style') ?></label>
                <select name="design_hero_style">
                    <option value="default" <?= ($design['hero_style'] ?? '') === 'default' ? 'selected' : '' ?>><?= ld_h($ta['design_hero_default'] ?? 'Default') ?></option>
                    <option value="minimal" <?= ($design['hero_style'] ?? '') === 'minimal' ? 'selected' : '' ?>><?= ld_h($ta['design_hero_minimal'] ?? 'Minimal') ?></option>
                    <option value="fullscreen" <?= ($design['hero_style'] ?? '') === 'fullscreen' ? 'selected' : '' ?>><?= ld_h($ta['design_hero_full'] ?? 'Full width') ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-layer-group"></i> <?= ld_h($ta['design_sections'] ?? 'Page sections') ?></h2></div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= ld_h($ta['design_sections_help'] ?? '') ?></p>
            <div class="adm-section-toggles">
                <?php
                $sectionLabels = [
                    'stats' => $ta['stats'] ?? 'Stats',
                    'services' => $ta['services'] ?? 'Services',
                    'features' => $ta['block_features'] ?? 'Features',
                    'gallery' => $ta['block_gallery'] ?? 'Gallery',
                    'video' => $ta['block_video'] ?? 'Video',
                    'partners' => $ta['block_partners'] ?? 'Partners',
                    'promo' => $ta['block_promo'] ?? 'Promo banner',
                    'team' => $ta['team'] ?? 'Team',
                    'reviews' => $ta['google_reviews'] ?? 'Reviews',
                    'map' => $ta['map_title'] ?? 'Map',
                    'faq' => 'FAQ',
                    'contact' => $ta['contact'] ?? 'Contact',
                ];
                foreach ($design['sections'] ?? ld_default_design()['sections'] as $key => $on):
                ?>
                <label class="adm-section-toggle">
                    <input type="checkbox" name="section_<?= ld_h($key) ?>" value="1" <?= !empty($on) ? 'checked' : '' ?>>
                    <span><?= ld_h($sectionLabels[$key] ?? $key) ?></span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h($ta['save'] ?? '') ?></button>
    <a href="<?= ld_h(ld_url('template.php', ['t' => $active])) ?>" class="adm-btn adm-btn-outline" target="_blank"><i class="fas fa-eye"></i> <?= ld_h($ta['view_site'] ?? '') ?></a>
</form>

<?php require __DIR__ . '/includes/layout-end.php'; ?>