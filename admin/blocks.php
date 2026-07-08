<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'blocks';
$page_title = $ta['blocks'] ?? 'Blocks';
$settings = ld_settings();
$blocks = $settings['blocks'] ?? [];
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blocks['hero_image'] = trim((string) ($_POST['hero_image'] ?? ''));

    $gallery = [];
    $gUrls = $_POST['gallery_url'] ?? [];
    if (is_array($gUrls)) {
        foreach ($gUrls as $i => $url) {
            $url = trim((string) $url);
            if ($url === '') continue;
            $cap = [];
            foreach (ld_langs_codes() as $code) {
                $cap[$code] = trim((string) ($_POST['gallery_caption_' . $code][$i] ?? ''));
            }
            $gallery[] = ['url' => $url, 'caption' => $cap];
        }
    }
    $blocks['gallery'] = $gallery;

    $features = [];
    $fIcons = $_POST['feature_icon'] ?? [];
    if (is_array($fIcons)) {
        foreach ($fIcons as $i => $icon) {
            $title = [];
            $desc = [];
            foreach (ld_langs_codes() as $code) {
                $title[$code] = trim((string) ($_POST['feature_title_' . $code][$i] ?? ''));
                $desc[$code] = trim((string) ($_POST['feature_desc_' . $code][$i] ?? ''));
            }
            if ($title['en'] === '' && $title['lt'] === '') continue;
            $features[] = ['icon' => trim((string) $icon) ?: 'fa-star', 'title' => $title, 'desc' => $desc];
        }
    }
    $blocks['features'] = $features;

    $links = [];
    $lIcons = $_POST['link_icon'] ?? [];
    if (is_array($lIcons)) {
        foreach ($lIcons as $i => $icon) {
            $url = trim((string) ($_POST['link_url'][$i] ?? ''));
            if ($url === '') continue;
            $label = [];
            foreach (ld_langs_codes() as $code) {
                $label[$code] = trim((string) ($_POST['link_label_' . $code][$i] ?? ''));
            }
            $links[] = ['icon' => trim((string) $icon), 'url' => $url, 'label' => $label];
        }
    }
    $blocks['links'] = $links;

    $cta = $blocks['cta'] ?? [];
    $cta['enabled'] = !empty($_POST['cta_enabled']);
    $cta['phone'] = trim((string) ($_POST['cta_phone'] ?? ''));
    foreach (ld_langs_codes() as $code) {
        $cta['title'][$code] = trim((string) ($_POST['cta_title_' . $code] ?? ''));
        $cta['lead'][$code] = trim((string) ($_POST['cta_lead_' . $code] ?? ''));
    }
    $blocks['cta'] = $cta;

    $video = $blocks['video'] ?? [];
    $video['enabled'] = !empty($_POST['video_enabled']);
    $video['url'] = trim((string) ($_POST['video_url'] ?? ''));
    foreach (ld_langs_codes() as $code) {
        $video['title'][$code] = trim((string) ($_POST['video_title_' . $code] ?? ''));
    }
    $blocks['video'] = $video;

    $promo = $blocks['promo'] ?? [];
    $promo['enabled'] = !empty($_POST['promo_enabled']);
    foreach (ld_langs_codes() as $code) {
        $promo['badge'][$code] = trim((string) ($_POST['promo_badge_' . $code] ?? ''));
        $promo['title'][$code] = trim((string) ($_POST['promo_title_' . $code] ?? ''));
        $promo['text'][$code] = trim((string) ($_POST['promo_text_' . $code] ?? ''));
    }
    $blocks['promo'] = $promo;

    $partners = [];
    $pNames = $_POST['partner_name'] ?? [];
    if (is_array($pNames)) {
        foreach ($pNames as $i => $name) {
            $name = trim((string) $name);
            $logo = trim((string) ($_POST['partner_logo'][$i] ?? ''));
            if ($name === '' && $logo === '') continue;
            $partners[] = ['name' => $name, 'logo' => $logo];
        }
    }
    $blocks['partners'] = $partners;

    $settings['blocks'] = $blocks;
    if (ld_save_settings($settings)) {
        $saved = true;
    }
}

$gallery = $blocks['gallery'] ?? [];
$features = $blocks['features'] ?? [];
$links = $blocks['links'] ?? [];
$cta = $blocks['cta'] ?? [];
$video = $blocks['video'] ?? [];
$promo = $blocks['promo'] ?? [];
$partners = $blocks['partners'] ?? [];

require __DIR__ . '/includes/layout.php';
?>
<?php if ($saved): ?><div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['saved'] ?? '') ?></div><?php endif; ?>

<p class="adm-help"><?= ld_h($ta['blocks_help'] ?? '') ?></p>
<div class="adm-card adm-constructor-tip"><div class="adm-card-body padded"><p><i class="fas fa-lightbulb"></i> <?= ld_h($ta['blocks_tip'] ?? '') ?></p></div></div>

<form method="post" class="adm-settings-form">
    <div class="adm-card" id="adm-hero-image">
        <div class="adm-card-head"><h2><i class="fas fa-image"></i> <?= ld_h($ta['block_hero'] ?? 'Hero') ?> <?= ld_h($ta['block_image'] ?? 'image') ?></h2></div>
        <div class="adm-card-body padded">
            <?php
            $picker_id = 'ldHeroImagePicker';
            $picker_name = 'hero_image';
            $picker_value = $blocks['hero_image'] ?? '';
            $picker_subdir = 'blocks';
            $picker_label = $ta['block_hero_pick'] ?? $ta['block_hero'] ?? 'Hero image';
            $picker_hint = $ta['block_hero_hint'] ?? '';
            require __DIR__ . '/includes/image-picker.php';
            ?>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-bullhorn"></i> <?= ld_h($ta['block_promo'] ?? 'Promo banner') ?></h2>
            <label class="adm-field-check"><input type="checkbox" name="promo_enabled" value="1" <?= !empty($promo['enabled']) ? 'checked' : '' ?>></label>
        </div>
        <div class="adm-card-body padded">
            <?php foreach (ld_lang_labels() as $code => $lbl): ?>
            <div class="adm-form-grid">
                <div class="adm-field"><label><?= ld_h($ta['label_badge'] ?? 'Badge') ?> <?= $lbl ?></label><input name="promo_badge_<?= $code ?>" value="<?= ld_h($promo['badge'][$code] ?? '') ?>"></div>
                <div class="adm-field"><label><?= ld_h($ta['label_title'] ?? 'Title') ?> <?= $lbl ?></label><input name="promo_title_<?= $code ?>" value="<?= ld_h($promo['title'][$code] ?? '') ?>"></div>
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_text'] ?? 'Text') ?> <?= $lbl ?></label><input name="promo_text_<?= $code ?>" value="<?= ld_h($promo['text'][$code] ?? '') ?>"></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-star"></i> <?= ld_h($ta['block_features'] ?? 'Features') ?></h2></div>
        <div class="adm-card-body padded" id="featuresRepeater">
            <?php foreach ($features as $i => $f): ?>
            <div class="adm-repeat-row">
                <div class="adm-field"><label><?= ld_h($ta['label_icon'] ?? 'Icon') ?></label><input name="feature_icon[]" value="<?= ld_h($f['icon'] ?? 'fa-star') ?>"></div>
                <?php foreach (ld_langs_codes() as $code): ?>
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_title'] ?? 'Title') ?> <?= strtoupper($code) ?></label><input name="feature_title_<?= $code ?>[]" value="<?= ld_h($f['title'][$code] ?? '') ?>"></div>
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_desc'] ?? 'Description') ?> <?= strtoupper($code) ?></label><input name="feature_desc_<?= $code ?>[]" value="<?= ld_h($f['desc'][$code] ?? '') ?>"></div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <?php if (!$features): ?>
            <div class="adm-repeat-row">
                <div class="adm-field"><label><?= ld_h($ta['label_icon'] ?? 'Icon') ?></label><input name="feature_icon[]" value="fa-check"></div>
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_title'] ?? 'Title') ?> EN</label><input name="feature_title_en[]" placeholder="<?= ld_h($ta['placeholder_why_choose'] ?? 'Why choose us') ?>"></div>
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_desc'] ?? 'Description') ?> EN</label><input name="feature_desc_en[]"></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-images"></i> <?= ld_h($ta['block_gallery'] ?? 'Gallery') ?></h2></div>
        <div class="adm-card-body padded">
            <?php foreach ($gallery as $i => $img): ?>
            <div class="adm-repeat-row">
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_url'] ?? 'URL') ?></label><input type="url" name="gallery_url[]" value="<?= ld_h($img['url'] ?? '') ?>"></div>
                <?php foreach (ld_langs_codes() as $code): ?>
                <div class="adm-field"><label><?= ld_h($ta['label_caption'] ?? 'Caption') ?> <?= strtoupper($code) ?></label><input name="gallery_caption_<?= $code ?>[]" value="<?= ld_h($img['caption'][$code] ?? '') ?>"></div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <div class="adm-repeat-row">
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_url_new'] ?? 'URL (new)') ?></label><input type="url" name="gallery_url[]" placeholder="https://..."></div>
            </div>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-video"></i> <?= ld_h($ta['block_video'] ?? 'Video') ?></h2>
            <label class="adm-field-check"><input type="checkbox" name="video_enabled" value="1" <?= !empty($video['enabled']) ? 'checked' : '' ?>></label>
        </div>
        <div class="adm-card-body padded">
            <div class="adm-field adm-field-full"><label><?= ld_h($ta['block_video_url'] ?? 'YouTube / embed URL') ?></label><input type="url" name="video_url" value="<?= ld_h($video['url'] ?? '') ?>"></div>
            <?php foreach (ld_langs_codes() as $code): ?>
            <div class="adm-field"><label><?= ld_h($ta['label_title'] ?? 'Title') ?> <?= strtoupper($code) ?></label><input name="video_title_<?= $code ?>" value="<?= ld_h($video['title'][$code] ?? '') ?>"></div>
            <?php endforeach; ?>
            <p class="adm-field-hint"><?= ld_h($ta['block_video_hint'] ?? 'Enable section in Design → Page sections') ?></p>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-handshake"></i> <?= ld_h($ta['block_partners'] ?? 'Partners') ?></h2></div>
        <div class="adm-card-body padded">
            <?php foreach ($partners as $p): ?>
            <div class="adm-form-grid adm-repeat-row">
                <div class="adm-field"><label><?= ld_h($ta['label_name'] ?? 'Name') ?></label><input name="partner_name[]" value="<?= ld_h($p['name'] ?? '') ?>"></div>
                <div class="adm-field"><label><?= ld_h($ta['label_logo_url'] ?? 'Logo URL') ?></label><input type="url" name="partner_logo[]" value="<?= ld_h($p['logo'] ?? '') ?>"></div>
            </div>
            <?php endforeach; ?>
            <div class="adm-form-grid adm-repeat-row">
                <div class="adm-field"><label><?= ld_h($ta['label_name'] ?? 'Name') ?></label><input name="partner_name[]"></div>
                <div class="adm-field"><label><?= ld_h($ta['label_logo_url'] ?? 'Logo URL') ?></label><input type="url" name="partner_logo[]"></div>
            </div>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-phone-volume"></i> <?= ld_h($ta['block_cta'] ?? 'CTA / Callback') ?></h2>
            <label class="adm-field-check"><input type="checkbox" name="cta_enabled" value="1" <?= !empty($cta['enabled']) ? 'checked' : '' ?>></label>
        </div>
        <div class="adm-card-body padded adm-form-grid">
            <div class="adm-field"><label><?= ld_h($ta['block_cta_phone'] ?? 'Phone override') ?></label><input type="text" name="cta_phone" value="<?= ld_h($cta['phone'] ?? '') ?>"></div>
            <?php foreach (ld_langs_codes() as $code): ?>
            <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_title'] ?? 'Title') ?> <?= strtoupper($code) ?></label><input name="cta_title_<?= $code ?>" value="<?= ld_h($cta['title'][$code] ?? '') ?>"></div>
            <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_lead'] ?? 'Lead') ?> <?= strtoupper($code) ?></label><input name="cta_lead_<?= $code ?>" value="<?= ld_h($cta['lead'][$code] ?? '') ?>"></div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-share-nodes"></i> <?= ld_h($ta['block_social'] ?? 'Social links') ?></h2></div>
        <div class="adm-card-body padded">
            <?php foreach ($links as $i => $link): ?>
            <div class="adm-repeat-row adm-form-grid">
                <div class="adm-field"><label><?= ld_h($ta['label_icon'] ?? 'Icon') ?></label><input name="link_icon[]" value="<?= ld_h($link['icon'] ?? '') ?>"></div>
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_url'] ?? 'URL') ?></label><input type="url" name="link_url[]" value="<?= ld_h($link['url'] ?? '') ?>"></div>
                <div class="adm-field"><label><?= ld_h($ta['label_label'] ?? 'Label') ?> EN</label><input name="link_label_en[]" value="<?= ld_h($link['label']['en'] ?? '') ?>"></div>
            </div>
            <?php endforeach; ?>
            <div class="adm-repeat-row adm-form-grid">
                <div class="adm-field"><label><?= ld_h($ta['label_icon'] ?? 'Icon') ?></label><input name="link_icon[]" value="fab fa-instagram"></div>
                <div class="adm-field adm-field-full"><label><?= ld_h($ta['label_url'] ?? 'URL') ?></label><input type="url" name="link_url[]"></div>
                <div class="adm-field"><label><?= ld_h($ta['label_label'] ?? 'Label') ?> EN</label><input name="link_label_en[]"></div>
            </div>
        </div>
    </div>

    <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h($ta['save'] ?? '') ?></button>
    <a href="<?= ld_h(ld_admin_url('design.php')) ?>" class="adm-btn adm-btn-outline"><i class="fas fa-palette"></i> <?= ld_h($ta['design'] ?? 'Design') ?></a>
</form>
<script src="<?= ld_h(ld_asset('js/admin-image-picker.js')) ?>?v=1" defer></script>
<?php require __DIR__ . '/includes/layout-end.php'; ?>