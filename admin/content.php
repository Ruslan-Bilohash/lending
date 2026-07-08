<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/content-form.php';
require_once __DIR__ . '/includes/content-fields.php';
ld_admin_require();
$admin_page = 'content';
$page_title = ld_admin_t('content');

$settings = ld_settings();
$business = $settings['business'] ?? [];
$hero = $settings['hero'] ?? [];
$sections = $settings['sections'] ?? [];
$stats = $settings['stats'] ?? [];
$services = $settings['services'] ?? [];
$team = $settings['team'] ?? [];
$faq = $settings['faq'] ?? [];
$google = $settings['google'] ?? [];
$reviews = $settings['reviews'] ?? [];
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = ld_parse_content_post();
    if (ld_save_settings($settings)) {
        $saved = true;
        $business = $settings['business'];
        $hero = $settings['hero'];
        $sections = $settings['sections'];
        $stats = $settings['stats'];
        $services = $settings['services'];
        $team = $settings['team'];
        $faq = $settings['faq'];
        $google = $settings['google'];
        $reviews = $settings['reviews'];
    }
}

require __DIR__ . '/includes/layout.php';
?>

<?php if ($saved): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['saved'] ?? '') ?></div>
<?php endif; ?>

<p class="adm-help"><?= ld_h($ta['content_help'] ?? '') ?></p>

<?php require __DIR__ . '/includes/content-checklist-panel.php'; ?>

<?php
$ai_fill_scope = 'all';
require __DIR__ . '/includes/ai-fill-panel.php';
?>

<form method="post" class="adm-settings-form" id="contentForm">
    <div class="adm-card" id="adm-business">
        <div class="adm-card-head"><h2><i class="fas fa-building"></i> <?= ld_h($ta['business_settings'] ?? '') ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-form-grid">
                <?php foreach (ld_lang_labels() as $code => $label): ?>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($label) ?> — <?= ld_h(ld_admin_t('business_name')) ?></label>
                    <input type="text" name="business_name_<?= $code ?>" value="<?= ld_h($business['name'][$code] ?? '') ?>">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($label) ?> — <?= ld_h(ld_admin_t('tagline')) ?></label>
                    <input type="text" name="business_tagline_<?= $code ?>" value="<?= ld_h($business['tagline'][$code] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= ld_h($label) ?> — <?= ld_h(ld_admin_t('city')) ?></label>
                    <input type="text" name="business_city_<?= $code ?>" value="<?= ld_h($business['city'][$code] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= ld_h($label) ?> — <?= ld_h(ld_admin_t('address')) ?></label>
                    <input type="text" name="business_address_<?= $code ?>" value="<?= ld_h($business['address'][$code] ?? '') ?>">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($label) ?> — <?= ld_h(ld_admin_t('hours')) ?></label>
                    <input type="text" name="business_hours_<?= $code ?>" value="<?= ld_h($business['hours'][$code] ?? '') ?>">
                </div>
                <?php endforeach; ?>
                <div class="adm-field">
                    <label><?= ld_h($ta['phone'] ?? '') ?></label>
                    <input type="text" name="business_phone" value="<?= ld_h($business['phone'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= ld_h($ta['email'] ?? '') ?></label>
                    <input type="email" name="business_email" value="<?= ld_h($business['email'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= ld_h(ld_admin_t('currency')) ?></label>
                    <input type="text" name="currency" value="<?= ld_h($settings['currency'] ?? '€') ?>" maxlength="6">
                </div>
                <div class="adm-field">
                    <label><?= ld_h($ta['active_template'] ?? '') ?></label>
                    <select name="active_template">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?= $i ?>" <?= (int)($settings['active_template'] ?? 1) === $i ? 'selected' : '' ?>>#<?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="adm-card" id="adm-hero">
        <div class="adm-card-head"><h2><i class="fas fa-bolt"></i> <?= ld_h(ld_admin_t('hero_settings')) ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-form-grid">
                <?php ld_admin_i18n_fields('hero_cta', $hero['cta'] ?? [], ld_admin_t('hero_cta')); ?>
                <?php ld_admin_i18n_fields('hero_cta2', $hero['cta2'] ?? [], ld_admin_t('hero_cta2')); ?>
                <div class="adm-field">
                    <label><?= ld_h(ld_admin_t('hero_icon')) ?></label>
                    <input type="text" name="hero_visual_icon" value="<?= ld_h($hero['visual_icon'] ?? 'fa-star') ?>" placeholder="fa-star">
                </div>
                <?php ld_admin_i18n_fields('hero_visual_label', $hero['visual_label'] ?? [], ld_admin_t('hero_visual_label')); ?>
                <?php ld_admin_i18n_fields('hero_visual_sub', $hero['visual_sub'] ?? [], ld_admin_t('hero_visual_sub')); ?>
            </div>
        </div>
    </div>

    <div class="adm-card" id="adm-sections">
        <div class="adm-card-head"><h2><i class="fas fa-heading"></i> <?= ld_h(ld_admin_t('section_titles')) ?></h2></div>
        <div class="adm-card-body padded">
            <h3 class="adm-subhead"><?= ld_h(ld_admin_t('services')) ?></h3>
            <div class="adm-form-grid">
                <?php ld_admin_i18n_fields('section_services_title', $sections['services']['title'] ?? [], ld_admin_t('label_title')); ?>
                <?php ld_admin_i18n_textarea('section_services_lead', $sections['services']['lead'] ?? [], ld_admin_t('label_lead')); ?>
            </div>
            <h3 class="adm-subhead"><?= ld_h(ld_admin_t('team')) ?></h3>
            <div class="adm-form-grid">
                <?php ld_admin_i18n_fields('section_team_title', $sections['team']['title'] ?? [], ld_admin_t('label_title')); ?>
                <?php ld_admin_i18n_textarea('section_team_lead', $sections['team']['lead'] ?? [], ld_admin_t('label_lead')); ?>
            </div>
            <h3 class="adm-subhead"><?= ld_h(ld_admin_t('faq')) ?></h3>
            <div class="adm-form-grid">
                <?php ld_admin_i18n_fields('section_faq_title', $sections['faq']['title'] ?? [], ld_admin_t('label_title')); ?>
            </div>
            <h3 class="adm-subhead"><?= ld_h(ld_admin_t('contact')) ?></h3>
            <div class="adm-form-grid">
                <?php ld_admin_i18n_fields('section_contact_title', $sections['contact']['title'] ?? [], ld_admin_t('label_title')); ?>
                <?php ld_admin_i18n_textarea('section_contact_lead', $sections['contact']['lead'] ?? [], ld_admin_t('label_lead')); ?>
            </div>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head">
            <h2><i class="fas fa-chart-bar"></i> <?= ld_h(ld_admin_t('stats')) ?></h2>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline" data-repeat-add="stats"><i class="fas fa-plus"></i></button>
        </div>
        <div class="adm-card-body padded" data-repeat-list="stats">
            <?php
            if (!$stats) {
                $stats = [['value' => '', 'label' => []]];
            }
            foreach ($stats as $i => $row) {
                ld_admin_stat_row($i, $row);
            }
            ?>
        </div>
    </div>

    <div class="adm-card" id="adm-services">
        <div class="adm-card-head">
            <h2><i class="fas fa-briefcase"></i> <?= ld_h(ld_admin_t('services')) ?></h2>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline" data-repeat-add="services"><i class="fas fa-plus"></i></button>
        </div>
        <div class="adm-card-body padded" data-repeat-list="services">
            <?php
            if (!$services) {
                $services = [['icon' => 'fa-star', 'name' => [], 'desc' => [], 'price' => '', 'badge' => null]];
            }
            foreach ($services as $i => $row) {
                ld_admin_service_row($i, $row);
            }
            ?>
        </div>
    </div>

    <div class="adm-card" id="adm-team">
        <div class="adm-card-head">
            <h2><i class="fas fa-users"></i> <?= ld_h(ld_admin_t('team')) ?></h2>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline" data-repeat-add="team"><i class="fas fa-plus"></i></button>
        </div>
        <div class="adm-card-body padded" data-repeat-list="team">
            <?php
            if (!$team) {
                $team = [['name' => '', 'role' => [], 'years' => '', 'initials' => '']];
            }
            foreach ($team as $i => $row) {
                ld_admin_team_row($i, $row);
            }
            ?>
        </div>
    </div>

    <div class="adm-card" id="adm-google">
        <div class="adm-card-head"><h2><i class="fab fa-google"></i> <?= ld_h(ld_admin_t('google_settings')) ?></h2></div>
        <div class="adm-card-body padded">
            <p class="adm-field-hint"><?= ld_h($ta['google_help'] ?? '') ?></p>
            <div class="adm-form-grid">
                <div class="adm-field adm-field-full">
                    <label><?= ld_h(ld_admin_t('google_maps_embed')) ?></label>
                    <textarea name="google_maps_embed" rows="3" placeholder="https://www.google.com/maps/embed?pb=..."><?= ld_h($google['maps_embed'] ?? '') ?></textarea>
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h(ld_admin_t('google_maps_link')) ?></label>
                    <input type="url" name="google_maps_link" value="<?= ld_h($google['maps_link'] ?? '') ?>" placeholder="https://maps.google.com/...">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h(ld_admin_t('google_reviews_url')) ?></label>
                    <input type="url" name="google_reviews_url" value="<?= ld_h($google['reviews_url'] ?? '') ?>" placeholder="https://g.page/.../review">
                </div>
                <div class="adm-field">
                    <label><?= ld_h(ld_admin_t('google_rating')) ?></label>
                    <input type="text" name="google_rating" value="<?= ld_h($google['rating'] ?? '') ?>" placeholder="4.9">
                </div>
                <div class="adm-field">
                    <label><?= ld_h(ld_admin_t('google_review_count')) ?></label>
                    <input type="text" name="google_review_count" value="<?= ld_h($google['review_count'] ?? '') ?>" placeholder="127">
                </div>
            </div>
            <h3 class="adm-subhead"><?= ld_h(ld_admin_t('section_titles')) ?> — <?= ld_h(ld_admin_t('reviews')) ?></h3>
            <div class="adm-form-grid">
                <?php ld_admin_i18n_fields('section_reviews_title', $sections['reviews']['title'] ?? [], ld_admin_t('label_title')); ?>
                <?php ld_admin_i18n_textarea('section_reviews_lead', $sections['reviews']['lead'] ?? [], ld_admin_t('label_lead')); ?>
                <?php ld_admin_i18n_fields('section_map_title', $sections['map']['title'] ?? [], ld_admin_t('map_title')); ?>
            </div>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head">
            <h2><i class="fas fa-star"></i> <?= ld_h(ld_admin_t('google_reviews')) ?></h2>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline" data-repeat-add="reviews"><i class="fas fa-plus"></i></button>
        </div>
        <div class="adm-card-body padded" data-repeat-list="reviews">
            <?php
            if (!$reviews) {
                $reviews = [['author' => '', 'rating' => '5', 'date' => '', 'text' => []]];
            }
            foreach ($reviews as $i => $row) {
                ld_admin_review_row($i, $row);
            }
            ?>
        </div>
    </div>

    <div class="adm-card" id="adm-faq">
        <div class="adm-card-head">
            <h2><i class="fas fa-circle-question"></i> <?= ld_h(ld_admin_t('faq')) ?></h2>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline" data-repeat-add="faq"><i class="fas fa-plus"></i></button>
        </div>
        <div class="adm-card-body padded" data-repeat-list="faq">
            <?php
            if (!$faq) {
                $faq = [['q' => [], 'a' => []]];
            }
            foreach ($faq as $i => $row) {
                ld_admin_faq_row($i, $row);
            }
            ?>
        </div>
    </div>

    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h(ld_admin_t('save_all', ld_admin_t('save'))) ?></button>
    </div>
</form>

<template id="tpl-stats"><?php ob_start(); ld_admin_stat_row('__INDEX__', ['value' => '', 'label' => []]); echo ob_get_clean(); ?></template>
<template id="tpl-services"><?php ob_start(); ld_admin_service_row('__INDEX__', ['icon' => 'fa-star', 'name' => [], 'desc' => [], 'price' => '', 'badge' => null]); echo ob_get_clean(); ?></template>
<template id="tpl-team"><?php ob_start(); ld_admin_team_row('__INDEX__', ['name' => '', 'role' => [], 'years' => '', 'initials' => '']); echo ob_get_clean(); ?></template>
<template id="tpl-faq"><?php ob_start(); ld_admin_faq_row('__INDEX__', ['q' => [], 'a' => []]); echo ob_get_clean(); ?></template>
<template id="tpl-reviews"><?php ob_start(); ld_admin_review_row('__INDEX__', ['author' => '', 'rating' => '5', 'date' => '', 'text' => []]); echo ob_get_clean(); ?></template>

<script src="<?= ld_h(ld_asset('js/admin-content.js')) ?>?v=2" defer></script>

<?php require __DIR__ . '/includes/layout-end.php'; ?>