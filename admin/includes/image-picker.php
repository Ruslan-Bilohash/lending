<?php
/**
 * Single image picker (upload, drag-drop, URL).
 *
 * @var string $picker_id
 * @var string $picker_name
 * @var string $picker_value
 * @var string $picker_subdir blocks|news
 * @var string $picker_label
 * @var string|null $picker_hint
 */
$picker_id = $picker_id ?? 'ldImagePicker';
$picker_name = $picker_name ?? 'image';
$picker_value = trim((string) ($picker_value ?? ''));
$picker_subdir = in_array(($picker_subdir ?? 'blocks'), ['blocks', 'news'], true) ? $picker_subdir : 'blocks';
$picker_label = $picker_label ?? ($ta['image_picker_label'] ?? '');
$picker_hint = $picker_hint ?? ($ta['block_hero_hint'] ?? '');
$uploadApi = ld_admin_url('api/upload-image.php');
?>
<div class="adm-field adm-field-full">
    <label><?= ld_h($picker_label) ?></label>
    <div class="adm-img-picker adm-img-picker--single" id="<?= ld_h($picker_id) ?>" data-adm-image-picker
        data-upload-url="<?= ld_h($uploadApi) ?>"
        data-subdir="<?= ld_h($picker_subdir) ?>"
        data-uploading="<?= ld_h($ta['image_picker_uploading'] ?? '') ?>"
        data-upload-ok="<?= ld_h($ta['image_picker_upload_ok'] ?? '') ?>"
        data-upload-error="<?= ld_h($ta['image_picker_upload_error'] ?? '') ?>"
        data-invalid-url="<?= ld_h($ta['image_picker_invalid_url'] ?? '') ?>">
        <input type="hidden" name="<?= ld_h($picker_name) ?>" id="<?= ld_h($picker_id) ?>Value" data-picker-value value="<?= ld_h($picker_value) ?>">
        <figure class="adm-img-single-preview" data-picker-preview <?= $picker_value === '' ? 'hidden' : '' ?>>
            <img src="<?= ld_h($picker_value) ?>" alt="">
            <button type="button" class="adm-img-gallery-remove" data-picker-remove aria-label="<?= ld_h($ta['image_picker_remove'] ?? '') ?>">
                <i class="fas fa-trash"></i>
            </button>
        </figure>
        <div class="adm-img-dropzone" tabindex="0" role="button">
            <i class="fas fa-cloud-arrow-up"></i>
            <span><?= ld_h($ta['image_picker_drop'] ?? '') ?></span>
            <small><?= ld_h($ta['image_picker_drop_hint'] ?? '') ?></small>
            <input type="file" class="adm-img-file-input" accept="image/jpeg,image/png,image/gif,image/webp" hidden>
        </div>
        <div class="adm-img-url-row">
            <input type="url" data-picker-url placeholder="<?= ld_h($ta['image_picker_url_placeholder'] ?? 'https://…') ?>">
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline" data-picker-url-add>
                <i class="fas fa-plus"></i> <?= ld_h($ta['image_picker_url_add'] ?? '') ?>
            </button>
        </div>
        <p class="adm-img-status" hidden></p>
    </div>
    <?php if ($picker_hint !== ''): ?>
    <p class="adm-field-hint"><?= ld_h($picker_hint) ?></p>
    <?php endif; ?>
</div>