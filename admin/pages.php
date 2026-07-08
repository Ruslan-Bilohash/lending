<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'pages';
$page_title = $ta['pages_nav'] ?? 'Service pages';

$editId = trim((string) ($_GET['id'] ?? ''));
$isNew = isset($_GET['new']);
$editing = $isNew || $editId !== '';
$item = $editId !== '' ? ld_get_page($editId) : null;
$saved = false;
$deleted = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_page']) && $editId !== '') {
        if (ld_pages_delete($editId)) {
            header('Location: ' . ld_admin_url('pages.php', ['deleted' => '1']));
            exit;
        }
        $error = 'delete_failed';
    } else {
        $input = $_POST;
        if ($editId !== '') {
            $input['id'] = $editId;
        }
        $result = ld_pages_upsert($input);
        if (!empty($result['ok'])) {
            header('Location: ' . ld_admin_url('pages.php', ['id' => $result['id'], 'saved' => '1']));
            exit;
        }
        $error = (string) ($result['error'] ?? 'save_failed');
    }
}

if (isset($_GET['saved'])) {
    $saved = true;
}
if (isset($_GET['deleted'])) {
    $deleted = true;
}

$pageList = ld_load_pages();

require __DIR__ . '/includes/layout.php';
?>

<?php if ($saved): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['pages_saved'] ?? 'Saved.') ?></div>
<?php endif; ?>
<?php if ($deleted): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['pages_deleted'] ?? 'Deleted.') ?></div>
<?php endif; ?>
<?php if ($error === 'delete_failed'): ?>
<div class="adm-alert adm-alert-warning"><i class="fas fa-times"></i> <?= ld_h($ta['pages_delete_error'] ?? 'Could not delete.') ?></div>
<?php elseif ($error === 'slug_taken'): ?>
<div class="adm-alert adm-alert-warning"><i class="fas fa-times"></i> <?= ld_h($ta['pages_slug_taken'] ?? 'This URL slug is already in use.') ?></div>
<?php elseif ($error === 'save_failed'): ?>
<div class="adm-alert adm-alert-warning"><i class="fas fa-times"></i> <?= ld_h($ta['pages_save_failed'] ?? 'Could not save.') ?></div>
<?php endif; ?>

<?php if (!$editing): ?>
<p class="adm-help"><?= ld_h($ta['pages_help'] ?? '') ?></p>
<div class="adm-card-head-row">
    <a href="<?= ld_h(ld_admin_url('pages.php', ['new' => '1'])) ?>" class="adm-btn adm-btn-primary">
        <i class="fas fa-plus"></i> <?= ld_h($ta['pages_add'] ?? 'Add page') ?>
    </a>
    <a href="<?= ld_h(ld_absolute_url('sitemap.xml')) ?>" class="adm-btn adm-btn-outline" target="_blank" rel="noopener">
        <i class="fas fa-sitemap"></i> <?= ld_h($ta['pages_sitemap'] ?? 'View sitemap') ?>
    </a>
</div>

<?php if ($pageList === []): ?>
<div class="adm-card">
    <div class="adm-card-body padded">
        <p class="adm-muted"><?= ld_h($ta['pages_no_items'] ?? 'No pages yet.') ?></p>
    </div>
</div>
<?php else: ?>
<div class="adm-card">
    <div class="adm-card-head"><h2><i class="fas fa-file-lines"></i> <?= ld_h($ta['pages_list'] ?? 'Service pages') ?></h2></div>
    <div class="adm-card-body" style="overflow-x:auto">
        <table class="adm-table">
            <thead>
                <tr>
                    <th><?= ld_h($ta['pages_title'] ?? 'Title') ?></th>
                    <th><?= ld_h($ta['pages_slug'] ?? 'Slug') ?></th>
                    <th><?= ld_h($ta['pages_status'] ?? 'Status') ?></th>
                    <th><?= ld_h($ta['pages_show_footer'] ?? 'Footer') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pageList as $row):
                    $title = ld_pick($row['title'] ?? [], $lang) ?: ld_pick($row['title'] ?? [], 'no');
                    $st = (string) ($row['status'] ?? 'draft');
                    $slug = (string) ($row['slug'] ?? '');
                ?>
                <tr>
                    <td>
                        <strong><?= ld_h($title) ?></strong>
                        <?php if (!empty($row['is_system'])): ?><span class="adm-badge"><?= ld_h($ta['pages_system'] ?? 'System') ?></span><?php endif; ?>
                    </td>
                    <td><code><?= ld_h($slug) ?></code></td>
                    <td><span class="adm-badge <?= $st === 'published' ? 'adm-badge-active' : '' ?>"><?= ld_h($ta['pages_' . $st] ?? $st) ?></span></td>
                    <td><?= !empty($row['show_in_footer']) ? '<i class="fas fa-check"></i>' : '—' ?></td>
                    <td class="adm-table-actions">
                        <?php if ($st === 'published' && $slug !== ''): ?>
                        <a href="<?= ld_h(ld_page_url($slug)) ?>" class="adm-btn adm-btn-sm adm-btn-ghost" target="_blank" rel="noopener"><?= ld_h($ta['pages_view'] ?? 'View') ?></a>
                        <?php endif; ?>
                        <a href="<?= ld_h(ld_admin_url('pages.php', ['id' => $row['id'] ?? ''])) ?>" class="adm-btn adm-btn-sm adm-btn-outline"><?= ld_h($ta['pages_edit'] ?? 'Edit') ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php else:
    if ($editId !== '' && !$item) {
        echo '<div class="adm-alert adm-alert-warning">' . ld_h($ta['pages_not_found'] ?? 'Page not found.') . '</div>';
        require __DIR__ . '/includes/layout-end.php';
        return;
    }
    $row = $item ?? [];
?>
<p class="adm-help"><a href="<?= ld_h(ld_admin_url('pages.php')) ?>"><i class="fas fa-arrow-left"></i> <?= ld_h($ta['pages_list'] ?? 'Service pages') ?></a></p>

<form method="post" class="adm-pages-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><i class="fas fa-pen-to-square"></i> <?= ld_h($isNew ? ($ta['pages_add'] ?? 'Add') : ($ta['pages_edit'] ?? 'Edit')) ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-form-grid adm-pages-meta">
                <div class="adm-field">
                    <label><?= ld_h($ta['pages_slug'] ?? 'URL slug') ?></label>
                    <input type="text" name="slug" value="<?= ld_h($row['slug'] ?? '') ?>" pattern="[a-z0-9-]+" placeholder="privacy">
                    <p class="adm-field-hint"><?= ld_h($ta['pages_slug_hint'] ?? 'Lowercase letters, numbers and hyphens only.') ?></p>
                </div>
                <div class="adm-field">
                    <label><?= ld_h($ta['pages_status'] ?? 'Status') ?></label>
                    <select name="status">
                        <option value="draft" <?= ($row['status'] ?? '') === 'draft' ? 'selected' : '' ?>><?= ld_h($ta['pages_draft'] ?? 'Draft') ?></option>
                        <option value="published" <?= ($row['status'] ?? '') === 'published' ? 'selected' : '' ?>><?= ld_h($ta['pages_published'] ?? 'Published') ?></option>
                    </select>
                </div>
                <div class="adm-field">
                    <label><?= ld_h($ta['pages_sort_order'] ?? 'Sort order') ?></label>
                    <input type="number" name="sort_order" value="<?= (int) ($row['sort_order'] ?? 0) ?>" min="0" max="999">
                </div>
                <div class="adm-field adm-field-check">
                    <label>
                        <input type="checkbox" name="show_in_footer" value="1" <?= !empty($row['show_in_footer']) ? 'checked' : '' ?>>
                        <?= ld_h($ta['pages_show_footer'] ?? 'Show link in site footer') ?>
                    </label>
                </div>
            </div>

            <div class="adm-lang-tabs" role="tablist">
                <?php foreach (ld_langs_codes() as $i => $code):
                    $meta = ld_langs()[$code] ?? [];
                ?>
                <button type="button" class="adm-lang-tab<?= $i === 0 ? ' active' : '' ?>" data-lang-tab="<?= ld_h($code) ?>" role="tab">
                    <?= $meta['flag'] ?? '' ?> <?= ld_h($meta['label'] ?? strtoupper($code)) ?>
                </button>
                <?php endforeach; ?>
            </div>

            <?php foreach (ld_langs_codes() as $i => $code): ?>
            <div class="adm-lang-panel" data-lang-panel="<?= ld_h($code) ?>"<?= $i === 0 ? '' : ' hidden' ?>>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['pages_title'] ?? 'Title') ?> (<?= ld_h(strtoupper($code)) ?>)</label>
                    <input type="text" name="title_<?= ld_h($code) ?>" value="<?= ld_h($row['title'][$code] ?? '') ?>">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['pages_body'] ?? 'Content') ?> (<?= ld_h(strtoupper($code)) ?>)</label>
                    <textarea name="body_<?= ld_h($code) ?>" rows="10"><?= ld_h($row['body'][$code] ?? '') ?></textarea>
                </div>
                <details class="adm-seo-details">
                    <summary><?= ld_h($ta['seo'] ?? 'SEO') ?> (<?= ld_h(strtoupper($code)) ?>)</summary>
                    <div class="adm-field adm-field-full">
                        <label><?= ld_h($ta['seo_field_title'] ?? 'Title') ?></label>
                        <input type="text" name="seo_title_<?= ld_h($code) ?>" value="<?= ld_h($row['seo_title'][$code] ?? '') ?>">
                    </div>
                    <div class="adm-field adm-field-full">
                        <label><?= ld_h($ta['seo_field_description'] ?? 'Description') ?></label>
                        <textarea name="seo_description_<?= ld_h($code) ?>" rows="2"><?= ld_h($row['seo_description'][$code] ?? '') ?></textarea>
                    </div>
                    <div class="adm-field adm-field-full">
                        <label><?= ld_h($ta['seo_field_keywords'] ?? 'Keywords') ?></label>
                        <input type="text" name="seo_keywords_<?= ld_h($code) ?>" value="<?= ld_h($row['seo_keywords'][$code] ?? '') ?>">
                    </div>
                </details>
            </div>
            <?php endforeach; ?>

            <div class="adm-form-actions">
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h($ta['save'] ?? 'Save') ?></button>
                <?php if ($editId !== '' && empty($row['is_system'])): ?>
                <button type="submit" name="delete_page" value="1" class="adm-btn adm-btn-danger" onclick="return confirm('<?= ld_h($ta['pages_delete_confirm'] ?? 'Delete this page?') ?>')">
                    <i class="fas fa-trash"></i> <?= ld_h($ta['pages_delete'] ?? 'Delete') ?>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>
<script>
document.querySelectorAll('.adm-lang-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var code = btn.getAttribute('data-lang-tab');
        document.querySelectorAll('.adm-lang-tab').forEach(function(b) { b.classList.remove('active'); });
        document.querySelectorAll('.adm-lang-panel').forEach(function(p) { p.hidden = true; });
        btn.classList.add('active');
        var panel = document.querySelector('.adm-lang-panel[data-lang-panel="' + code + '"]');
        if (panel) panel.hidden = false;
    });
});
</script>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout-end.php'; ?>