<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'seo';
$page_title = $ta['seo'] ?? 'SEO';
$settings = ld_settings();
$seo = $settings['seo'] ?? [];
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (ld_langs_codes() as $code) {
        $seo['title'][$code] = trim((string) ($_POST['seo_title_' . $code] ?? ''));
        $seo['description'][$code] = trim((string) ($_POST['seo_description_' . $code] ?? ''));
        $seo['keywords'][$code] = trim((string) ($_POST['seo_keywords_' . $code] ?? ''));
    }
    $seo['og_image'] = trim((string) ($_POST['seo_og_image'] ?? ''));
    $settings['seo'] = $seo;
    if (ld_save_settings($settings)) {
        $saved = true;
    }
}

require __DIR__ . '/includes/layout.php';
?>
<?php if ($saved): ?><div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['saved'] ?? '') ?></div><?php endif; ?>
<?php require __DIR__ . '/includes/seo-checklist-panel.php'; ?>
<?php require __DIR__ . '/includes/seo-tips-panel.php'; ?>
<?php
$ai_fill_scope = 'seo';
require __DIR__ . '/includes/ai-fill-panel.php';
require __DIR__ . '/includes/seo-ai-panel.php';
?>
<form method="post" class="adm-card">
    <div class="adm-card-head"><h2><i class="fas fa-search"></i> <?= ld_h($ta['seo'] ?? 'SEO') ?></h2></div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta['seo_help'] ?? '') ?></p>
        <?php foreach (ld_lang_labels() as $code => $label): ?>
        <h3 class="adm-subhead"><?= ld_h($label) ?></h3>
        <div class="adm-field adm-field-full"><label><?= ld_h($ta['seo_field_title'] ?? 'Title') ?></label><input type="text" name="seo_title_<?= $code ?>" value="<?= ld_h($seo['title'][$code] ?? '') ?>"></div>
        <div class="adm-field adm-field-full"><label><?= ld_h($ta['seo_field_description'] ?? 'Description') ?></label><textarea name="seo_description_<?= $code ?>" rows="2"><?= ld_h($seo['description'][$code] ?? '') ?></textarea></div>
        <div class="adm-field adm-field-full"><label><?= ld_h($ta['seo_field_keywords'] ?? 'Keywords') ?></label><input type="text" name="seo_keywords_<?= $code ?>" value="<?= ld_h($seo['keywords'][$code] ?? '') ?>"></div>
        <?php endforeach; ?>
        <?php
        $picker_id = 'ldOgImagePicker';
        $picker_name = 'seo_og_image';
        $picker_value = $seo['og_image'] ?? '';
        $picker_subdir = 'blocks';
        $picker_label = $ta['seo_og_image_pick'] ?? $ta['seo_og_image'] ?? 'OG Image';
        $picker_hint = $ta['seo_og_image_hint'] ?? '';
        require __DIR__ . '/includes/image-picker.php';
        ?>
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h($ta['save'] ?? '') ?></button>
    </div>
</form>
<script src="<?= ld_h(ld_asset('js/admin-image-picker.js')) ?>?v=1" defer></script>
<?php require __DIR__ . '/includes/layout-end.php'; ?>