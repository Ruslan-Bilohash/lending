<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'news';
$page_title = $ta['news'] ?? 'News';

$editId = trim((string) ($_GET['id'] ?? ''));
$isNew = isset($_GET['new']);
$editing = $isNew || $editId !== '';
$item = $editId !== '' ? ld_get_news($editId) : null;
$saved = false;
$deleted = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_news']) && $editId !== '') {
        if (ld_news_delete($editId)) {
            header('Location: ' . ld_admin_url('news.php', ['deleted' => '1']));
            exit;
        }
        $error = 'delete_failed';
    } else {
        $input = $_POST;
        if ($editId !== '') {
            $input['id'] = $editId;
        }
        $result = ld_news_upsert($input);
        if (!empty($result['ok'])) {
            header('Location: ' . ld_admin_url('news.php', ['id' => $result['id'], 'saved' => '1']));
            exit;
        }
        $error = 'save_failed';
    }
}

if (isset($_GET['saved'])) {
    $saved = true;
}
if (isset($_GET['deleted'])) {
    $deleted = true;
}

$newsList = ld_load_news();
$aiApi = ld_admin_url('api/news-ai.php');
$seoApi = ld_admin_url('api/news-seo.php');

require __DIR__ . '/includes/layout.php';
?>

<?php if ($saved): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['news_saved'] ?? 'Saved.') ?></div>
<?php endif; ?>
<?php if ($deleted): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['news_deleted'] ?? 'Deleted.') ?></div>
<?php endif; ?>
<?php if ($error === 'delete_failed'): ?>
<div class="adm-alert adm-alert-warning"><i class="fas fa-times"></i> <?= ld_h(ld_admin_t('news_delete_error')) ?></div>
<?php elseif ($error === 'save_failed'): ?>
<div class="adm-alert adm-alert-warning"><i class="fas fa-times"></i> <?= ld_h(ld_admin_t('news_save_failed')) ?></div>
<?php endif; ?>

<?php if (!$editing): ?>
<p class="adm-help"><?= ld_h($ta['news_help'] ?? '') ?></p>
<div class="adm-card-head-row">
    <a href="<?= ld_h(ld_admin_url('news.php', ['new' => '1'])) ?>" class="adm-btn adm-btn-primary">
        <i class="fas fa-plus"></i> <?= ld_h($ta['news_add'] ?? 'Add article') ?>
    </a>
</div>

<?php if ($newsList === []): ?>
<div class="adm-card">
    <div class="adm-card-body padded">
        <p class="adm-muted"><?= ld_h($ta['news_no_items'] ?? 'No news yet.') ?></p>
    </div>
</div>
<?php else: ?>
<div class="adm-card adm-news-list-card">
    <div class="adm-card-head"><h2><i class="fas fa-newspaper"></i> <?= ld_h($ta['news_list'] ?? 'School news') ?></h2></div>
    <div class="adm-card-body" style="overflow-x:auto">
        <table class="adm-table">
            <thead>
                <tr>
                    <th><?= ld_h($ta['news_title'] ?? 'Title') ?></th>
                    <th><?= ld_h($ta['news_status'] ?? 'Status') ?></th>
                    <th><?= ld_h($ta['news_published_at'] ?? 'Date') ?></th>
                    <th><?= ld_h($ta['news_seo_score'] ?? 'SEO') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsList as $row):
                    $title = ld_pick($row['title'] ?? [], $lang) ?: ld_pick($row['title'] ?? [], 'lt');
                    $st = (string) ($row['status'] ?? 'draft');
                    $seo = (int) ($row['seo_score'] ?? 0);
                ?>
                <tr>
                    <td><strong><?= ld_h($title) ?></strong><br><small class="adm-muted"><?= ld_h($row['slug'] ?? '') ?></small></td>
                    <td><span class="adm-badge <?= $st === 'published' ? 'adm-badge-active' : '' ?>"><?= ld_h($ta['news_' . $st] ?? $st) ?></span></td>
                    <td><?= ld_h(substr((string) ($row['published_at'] ?? ''), 0, 10)) ?></td>
                    <td><?php if ($seo > 0): ?><span class="adm-seo-pill adm-seo-pill--<?= $seo >= 75 ? 'good' : ($seo >= 60 ? 'mid' : 'low') ?>"><?= $seo ?></span><?php else: ?>—<?php endif; ?></td>
                    <td><a href="<?= ld_h(ld_admin_url('news.php', ['id' => $row['id'] ?? ''])) ?>" class="adm-btn adm-btn-sm adm-btn-outline"><?= ld_h($ta['news_edit'] ?? 'Edit') ?></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php else:
    if ($editId !== '' && !$item) {
        echo '<div class="adm-alert adm-alert-warning">' . ld_h($ta['news_not_found'] ?? 'Article not found.') . '</div>';
        require __DIR__ . '/includes/layout-end.php';
        return;
    }
    $row = $item ?? [];
    $newsImages = [];
    if (!empty($row['images']) && is_array($row['images'])) {
        foreach ($row['images'] as $imgUrl) {
            $imgUrl = trim((string) $imgUrl);
            if ($imgUrl !== '') {
                $newsImages[] = $imgUrl;
            }
        }
    } elseif (!empty($row['image'])) {
        $newsImages = [trim((string) $row['image'])];
    }
    $newsImageApi = ld_admin_url('api/news-image.php');
?>
<p class="adm-help"><a href="<?= ld_h(ld_admin_url('news.php')) ?>"><i class="fas fa-arrow-left"></i> <?= ld_h($ta['news_list'] ?? 'News') ?></a></p>

<div class="adm-card adm-news-ai-card" id="admNewsAiCard" data-api="<?= ld_h($aiApi) ?>"
    data-msg-brief="<?= ld_h(ld_admin_t('js_brief_required')) ?>"
    data-msg-error="<?= ld_h(ld_admin_t('js_error')) ?>"
    data-msg-network="<?= ld_h(ld_admin_t('js_network_error')) ?>"
    data-msg-generating="<?= ld_h(ld_admin_t('js_generating')) ?>"
    data-msg-ai-demo="<?= ld_h(ld_admin_t('js_ai_demo_article')) ?>"
    data-msg-ai-ok="<?= ld_h(ld_admin_t('js_ai_article_ok')) ?>"
    data-msg-seo-demo="<?= ld_h(ld_admin_t('js_seo_demo')) ?>"
    data-msg-seo-ok="<?= ld_h(ld_admin_t('js_seo_ok')) ?>">
    <div class="adm-card-head"><h2><i class="fas fa-wand-magic-sparkles"></i> <?= ld_h($ta['news_ai_title'] ?? 'AI writing assistant') ?></h2></div>
    <div class="adm-card-body padded">
        <p class="adm-help"><?= ld_h($ta['news_ai_help'] ?? '') ?></p>
        <div class="adm-field adm-field-full">
            <label><?= ld_h($ta['news_ai_brief'] ?? 'Brief') ?></label>
            <textarea id="admNewsAiBrief" rows="2" placeholder="<?= ld_h($ta['news_ai_placeholder'] ?? '') ?>"></textarea>
        </div>
        <button type="button" class="adm-btn adm-btn-primary" id="admNewsAiBtn">
            <i class="fas fa-robot"></i> <?= ld_h($ta['news_ai_btn'] ?? 'Generate article') ?>
        </button>
        <p class="adm-field-hint" id="admNewsAiStatus"></p>
    </div>
</div>

<form method="post" class="adm-news-form" id="admNewsForm" data-seo-api="<?= ld_h($seoApi) ?>"
    data-msg-generating="<?= ld_h(ld_admin_t('js_generating')) ?>"
    data-msg-error="<?= ld_h(ld_admin_t('js_error')) ?>"
    data-msg-seo-demo="<?= ld_h(ld_admin_t('js_seo_demo')) ?>"
    data-msg-seo-ok="<?= ld_h(ld_admin_t('js_seo_ok')) ?>">
    <div class="adm-card">
        <div class="adm-card-head">
            <h2><i class="fas fa-pen-to-square"></i> <?= ld_h($isNew ? ($ta['news_add'] ?? 'Add') : ($ta['news_edit'] ?? 'Edit')) ?></h2>
            <span class="adm-seo-score adm-seo-score--inline" id="admNewsSeoScore"><?= (int) ($row['seo_score'] ?? 0) ?: '—' ?></span>
        </div>
        <div class="adm-card-body padded">
            <div class="adm-form-grid adm-news-meta">
                <div class="adm-field">
                    <label><?= ld_h($ta['news_slug'] ?? 'Slug') ?></label>
                    <input type="text" name="slug" id="newsSlug" value="<?= ld_h($row['slug'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= ld_h($ta['news_published_at'] ?? 'Publish date') ?></label>
                    <input type="date" name="published_at" value="<?= ld_h(substr((string) ($row['published_at'] ?? date('Y-m-d')), 0, 10)) ?>">
                </div>
                <div class="adm-field">
                    <label><?= ld_h($ta['news_status'] ?? 'Status') ?></label>
                    <select name="status">
                        <option value="draft" <?= ($row['status'] ?? '') === 'draft' ? 'selected' : '' ?>><?= ld_h($ta['news_draft'] ?? 'Draft') ?></option>
                        <option value="published" <?= ($row['status'] ?? '') === 'published' ? 'selected' : '' ?>><?= ld_h($ta['news_published'] ?? 'Published') ?></option>
                    </select>
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['news_images_label'] ?? $ta['news_image'] ?? 'Images') ?></label>
                    <div class="adm-img-gallery adm-news-images" id="ldNewsImages"
                        data-upload-url="<?= ld_h($newsImageApi) ?>"
                        data-uploading="<?= ld_h($ta['news_images_uploading'] ?? '') ?>"
                        data-upload-ok="<?= ld_h($ta['news_images_upload_ok'] ?? '') ?>"
                        data-upload-error="<?= ld_h($ta['news_images_upload_error'] ?? '') ?>"
                        data-invalid-url="<?= ld_h($ta['news_images_invalid_url'] ?? '') ?>"
                        data-drag="<?= ld_h($ta['news_images_drag'] ?? '') ?>"
                        data-remove="<?= ld_h($ta['news_images_remove'] ?? '') ?>">
                        <input type="hidden" name="images_json" id="ldNewsImagesJson" value="<?= ld_h(json_encode($newsImages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) ?>">
                        <input type="hidden" name="image" id="ldNewsImageCover" value="<?= ld_h($newsImages[0] ?? '') ?>">
                        <ul class="adm-img-gallery-list">
                            <?php foreach ($newsImages as $imgUrl): ?>
                            <li class="adm-img-gallery-item" data-url="<?= ld_h($imgUrl) ?>" draggable="true">
                                <div class="adm-img-gallery-thumb">
                                    <img src="<?= ld_h($imgUrl) ?>" alt="" loading="lazy">
                                    <span class="adm-img-gallery-drag" title="<?= ld_h($ta['news_images_drag'] ?? '') ?>"><i class="fas fa-grip-vertical"></i></span>
                                </div>
                                <button type="button" class="adm-img-gallery-remove" aria-label="<?= ld_h($ta['news_images_remove'] ?? '') ?>"><i class="fas fa-trash"></i></button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="adm-img-dropzone" tabindex="0" role="button">
                            <i class="fas fa-cloud-arrow-up"></i>
                            <span><?= ld_h($ta['news_images_drop'] ?? '') ?></span>
                            <small><?= ld_h($ta['news_images_drop_hint'] ?? '') ?></small>
                            <input type="file" class="adm-img-file-input" accept="image/jpeg,image/png,image/gif,image/webp" multiple hidden>
                        </div>
                        <div class="adm-img-url-row">
                            <input type="url" id="ldNewsImageUrlInput" placeholder="<?= ld_h($ta['news_images_url_placeholder'] ?? 'https://…') ?>">
                            <button type="button" class="adm-btn adm-btn-sm adm-btn-outline" id="ldNewsImageUrlAdd">
                                <i class="fas fa-plus"></i> <?= ld_h($ta['news_images_url_add'] ?? '') ?>
                            </button>
                        </div>
                        <p class="adm-img-status" hidden></p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="seo_score" id="newsSeoScoreInput" value="<?= (int) ($row['seo_score'] ?? 0) ?>">

            <div class="adm-lang-tabs" role="tablist">
                <?php foreach (ld_lang_labels() as $code => $label): ?>
                <button type="button" class="adm-lang-tab<?= $code === $lang ? ' active' : '' ?>" data-lang="<?= ld_h($code) ?>" role="tab"><?= ld_h($label) ?></button>
                <?php endforeach; ?>
            </div>

            <?php foreach (ld_lang_labels() as $code => $label):
                $hidden = $code !== $lang ? ' hidden' : '';
            ?>
            <div class="adm-lang-panel<?= $hidden ?>" data-lang-panel="<?= ld_h($code) ?>">
                <h3 class="adm-subhead"><?= ld_h($label) ?></h3>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['news_title'] ?? 'Title') ?></label>
                    <input type="text" name="title_<?= $code ?>" data-news-field="title" data-lang="<?= $code ?>" value="<?= ld_h($row['title'][$code] ?? '') ?>">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['news_excerpt'] ?? 'Excerpt') ?></label>
                    <textarea name="excerpt_<?= $code ?>" rows="2" data-news-field="excerpt" data-lang="<?= $code ?>"><?= ld_h($row['excerpt'][$code] ?? '') ?></textarea>
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['news_body'] ?? 'Body') ?></label>
                    <textarea name="body_<?= $code ?>" rows="6" data-news-field="body" data-lang="<?= $code ?>"><?= ld_h($row['body'][$code] ?? '') ?></textarea>
                </div>
                <h4 class="adm-subhead adm-subhead--seo"><i class="fas fa-search"></i> SEO — <?= ld_h($label) ?></h4>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['seo_field_title'] ?? 'SEO Title') ?></label>
                    <input type="text" name="seo_title_<?= $code ?>" data-news-field="seo_title" data-lang="<?= $code ?>" value="<?= ld_h($row['seo_title'][$code] ?? '') ?>">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['seo_field_description'] ?? 'SEO Description') ?></label>
                    <textarea name="seo_description_<?= $code ?>" rows="2" data-news-field="seo_description" data-lang="<?= $code ?>"><?= ld_h($row['seo_description'][$code] ?? '') ?></textarea>
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= ld_h($ta['seo_field_keywords'] ?? 'SEO Keywords') ?></label>
                    <input type="text" name="seo_keywords_<?= $code ?>" data-news-field="seo_keywords" data-lang="<?= $code ?>" value="<?= ld_h($row['seo_keywords'][$code] ?? '') ?>">
                </div>
            </div>
            <?php endforeach; ?>

            <div class="adm-card adm-seo-ai-card adm-news-seo-card" id="admNewsSeoCard">
                <div class="adm-card-head">
                    <h3><i class="fas fa-chart-line"></i> <?= ld_h($ta['news_seo_title'] ?? 'SEO analysis') ?></h3>
                    <span class="adm-badge" id="admNewsSeoGrade">—</span>
                </div>
                <div class="adm-card-body padded">
                    <p class="adm-help"><?= ld_h($ta['news_seo_help'] ?? '') ?></p>
                    <ul class="adm-seo-tips" id="admNewsSeoTips"></ul>
                    <div id="admNewsSeoSuggestions" class="adm-seo-suggestions" hidden>
                        <button type="button" class="adm-btn adm-btn-sm adm-btn-outline" id="admNewsApplySeo">
                            <i class="fas fa-check"></i> <?= ld_h($ta['news_apply_seo'] ?? 'Apply suggestions') ?>
                        </button>
                    </div>
                    <button type="button" class="adm-btn adm-btn-primary" id="admNewsSeoBtn">
                        <i class="fas fa-wand-magic-sparkles"></i> <?= ld_h($ta['news_seo_analyze'] ?? 'Analyze SEO') ?>
                    </button>
                    <p class="adm-field-hint" id="admNewsSeoStatus"></p>
                </div>
            </div>

            <div class="adm-form-actions">
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h($ta['save'] ?? 'Save') ?></button>
                <?php if ($editId !== ''): ?>
                <button type="submit" name="delete_news" value="1" class="adm-btn adm-btn-danger" onclick="return confirm('<?= ld_h($ta['news_delete_confirm'] ?? 'Delete this article?') ?>')">
                    <i class="fas fa-trash"></i> <?= ld_h($ta['news_delete'] ?? 'Delete') ?>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>
<script src="<?= ld_h(ld_asset('js/admin-news.js')) ?>?v=3" defer></script>
<script src="<?= ld_h(ld_asset('js/admin-news-images.js')) ?>?v=1" defer></script>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout-end.php'; ?>