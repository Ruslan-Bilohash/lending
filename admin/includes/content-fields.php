<?php
declare(strict_types=1);

function ld_admin_i18n_fields(string $nameBase, array $values, string $label, bool $nested = false): void
{
    foreach (ld_lang_labels() as $code => $langLabel):
        $fieldName = $nested ? $nameBase . '_' . $code . ']' : $nameBase . '_' . $code;
        ?>
    <div class="adm-field">
        <label><?= ld_h($label) ?> (<?= ld_h($langLabel) ?>)</label>
        <input type="text" name="<?= ld_h($fieldName) ?>" value="<?= ld_h($values[$code] ?? '') ?>">
    </div>
    <?php endforeach;
}

function ld_admin_i18n_textarea(string $nameBase, array $values, string $label, bool $nested = false): void
{
    foreach (ld_lang_labels() as $code => $langLabel):
        $fieldName = $nested ? $nameBase . '_' . $code . ']' : $nameBase . '_' . $code;
        ?>
    <div class="adm-field adm-field-full">
        <label><?= ld_h($label) ?> (<?= ld_h($langLabel) ?>)</label>
        <textarea name="<?= ld_h($fieldName) ?>" rows="2"><?= ld_h($values[$code] ?? '') ?></textarea>
    </div>
    <?php endforeach;
}

function ld_admin_row_num(string|int $index): string
{
    if ($index === '__INDEX__') {
        return '#';
    }
    return '#' . ((int) $index + 1);
}

function ld_admin_stat_row(string|int $index, array $row): void
{
    $label = is_array($row['label'] ?? null) ? $row['label'] : [];
    ?>
    <div class="adm-repeat-row" data-repeat="stats">
        <div class="adm-repeat-head">
            <strong><?= ld_h(ld_admin_row_num($index)) ?></strong>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline adm-repeat-remove" title="<?= ld_h(ld_admin_t('btn_remove_row')) ?>"><i class="fas fa-trash"></i></button>
        </div>
        <div class="adm-form-grid">
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_value')) ?></label>
                <input type="text" name="stats[<?= $index ?>][value]" value="<?= ld_h($row['value'] ?? '') ?>">
            </div>
            <?php ld_admin_i18n_fields('stats[' . $index . '][label', $label, ld_admin_t('label_stat_label'), true); ?>
        </div>
    </div>
    <?php
}

function ld_admin_service_row(string|int $index, array $row): void
{
    $name = is_array($row['name'] ?? null) ? $row['name'] : [];
    $desc = is_array($row['desc'] ?? null) ? $row['desc'] : [];
    $badge = is_array($row['badge'] ?? null) ? $row['badge'] : [];
    ?>
    <div class="adm-repeat-row" data-repeat="services">
        <div class="adm-repeat-head">
            <strong><?= ld_h(ld_admin_row_num($index)) ?></strong>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline adm-repeat-remove" title="<?= ld_h(ld_admin_t('btn_remove_row')) ?>"><i class="fas fa-trash"></i></button>
        </div>
        <div class="adm-form-grid">
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_icon_fa')) ?></label>
                <input type="text" name="services[<?= $index ?>][icon]" value="<?= ld_h($row['icon'] ?? 'fa-star') ?>" placeholder="fa-star">
            </div>
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_price')) ?></label>
                <input type="text" name="services[<?= $index ?>][price]" value="<?= ld_h($row['price'] ?? '') ?>">
            </div>
            <?php
            foreach (ld_lang_labels() as $code => $langLabel): ?>
            <div class="adm-field adm-field-full">
                <label><?= ld_h(ld_admin_t('label_name')) ?> (<?= ld_h($langLabel) ?>)</label>
                <input type="text" name="services[<?= $index ?>][name_<?= $code ?>]" value="<?= ld_h($name[$code] ?? '') ?>">
            </div>
            <div class="adm-field adm-field-full">
                <label><?= ld_h(ld_admin_t('label_desc')) ?> (<?= ld_h($langLabel) ?>)</label>
                <textarea name="services[<?= $index ?>][desc_<?= $code ?>]" rows="2"><?= ld_h($desc[$code] ?? '') ?></textarea>
            </div>
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_badge')) ?> (<?= ld_h($langLabel) ?>)</label>
                <input type="text" name="services[<?= $index ?>][badge_<?= $code ?>]" value="<?= ld_h($badge[$code] ?? '') ?>">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

function ld_admin_team_row(string|int $index, array $row): void
{
    $role = is_array($row['role'] ?? null) ? $row['role'] : [];
    ?>
    <div class="adm-repeat-row" data-repeat="team">
        <div class="adm-repeat-head">
            <strong><?= ld_h(ld_admin_row_num($index)) ?></strong>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline adm-repeat-remove" title="<?= ld_h(ld_admin_t('btn_remove_row')) ?>"><i class="fas fa-trash"></i></button>
        </div>
        <div class="adm-form-grid">
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_name')) ?></label>
                <input type="text" name="team[<?= $index ?>][name]" value="<?= ld_h($row['name'] ?? '') ?>">
            </div>
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_initials')) ?></label>
                <input type="text" name="team[<?= $index ?>][initials]" value="<?= ld_h($row['initials'] ?? '') ?>" placeholder="AB">
            </div>
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_years')) ?></label>
                <input type="text" name="team[<?= $index ?>][years]" value="<?= ld_h($row['years'] ?? '') ?>">
            </div>
            <?php ld_admin_i18n_fields('team[' . $index . '][role', $role, ld_admin_t('label_role'), true); ?>
        </div>
    </div>
    <?php
}

function ld_admin_review_row(string|int $index, array $row): void
{
    $text = is_array($row['text'] ?? null) ? $row['text'] : [];
    ?>
    <div class="adm-repeat-row" data-repeat="reviews">
        <div class="adm-repeat-head">
            <strong><?= ld_h(ld_admin_row_num($index)) ?></strong>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline adm-repeat-remove" title="<?= ld_h(ld_admin_t('btn_remove_row')) ?>"><i class="fas fa-trash"></i></button>
        </div>
        <div class="adm-form-grid">
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_author')) ?></label>
                <input type="text" name="reviews[<?= $index ?>][author]" value="<?= ld_h($row['author'] ?? '') ?>">
            </div>
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_rating')) ?></label>
                <input type="number" name="reviews[<?= $index ?>][rating]" min="1" max="5" value="<?= ld_h((string) ($row['rating'] ?? '5')) ?>">
            </div>
            <div class="adm-field">
                <label><?= ld_h(ld_admin_t('label_date')) ?></label>
                <input type="text" name="reviews[<?= $index ?>][date]" value="<?= ld_h($row['date'] ?? '') ?>" placeholder="2025-09">
            </div>
            <?php
            foreach (ld_lang_labels() as $code => $langLabel): ?>
            <div class="adm-field adm-field-full">
                <label><?= ld_h(ld_admin_t('label_review')) ?> (<?= ld_h($langLabel) ?>)</label>
                <textarea name="reviews[<?= $index ?>][text_<?= $code ?>]" rows="2"><?= ld_h($text[$code] ?? '') ?></textarea>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

function ld_admin_faq_row(string|int $index, array $row): void
{
    $q = is_array($row['q'] ?? null) ? $row['q'] : [];
    $a = is_array($row['a'] ?? null) ? $row['a'] : [];
    ?>
    <div class="adm-repeat-row" data-repeat="faq">
        <div class="adm-repeat-head">
            <strong><?= ld_h(ld_admin_row_num($index)) ?></strong>
            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline adm-repeat-remove" title="<?= ld_h(ld_admin_t('btn_remove_row')) ?>"><i class="fas fa-trash"></i></button>
        </div>
        <?php
        foreach (ld_lang_labels() as $code => $langLabel): ?>
        <div class="adm-field adm-field-full">
            <label><?= ld_h(ld_admin_t('label_question')) ?> (<?= ld_h($langLabel) ?>)</label>
            <input type="text" name="faq[<?= $index ?>][q_<?= $code ?>]" value="<?= ld_h($q[$code] ?? '') ?>">
        </div>
        <div class="adm-field adm-field-full">
            <label><?= ld_h(ld_admin_t('label_answer')) ?> (<?= ld_h($langLabel) ?>)</label>
            <textarea name="faq[<?= $index ?>][a_<?= $code ?>]" rows="2"><?= ld_h($a[$code] ?? '') ?></textarea>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}