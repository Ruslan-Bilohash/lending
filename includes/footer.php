</main>

<?php if (empty($is_landing)): ?>
<footer class="ld-footer">
    <p><?= ld_h($t['landing']['footer_copy'] ?? '') ?></p>
    <p class="ld-footer-links">
        <?php foreach (ld_pages_footer() as $fp):
            $fpLoc = ld_page_localize($fp, $lang);
            if (trim((string) ($fpLoc['title'] ?? '')) === '') {
                continue;
            }
        ?><a href="<?= ld_h(ld_page_url((string) ($fp['slug'] ?? ''))) ?>"><?= ld_h($fpLoc['title']) ?></a> · <?php endforeach; ?>
        <a href="<?= ld_h(ld_absolute_url('sitemap.xml')) ?>"><?= ld_h($t['pages']['sitemap'] ?? 'Sitemap') ?></a> ·
        <a href="https://bilohash.com/">BILOHASH</a> ·
        <a href="https://bilohash.com/ecosystem/join.php"><?= ld_h($t['home']['cta'] ?? '') ?></a> ·
        <a href="<?= ld_h(ld_admin_url('login.php')) ?>"><?= ld_h($t['home']['admin_cta'] ?? 'Admin') ?></a>
    </p>
</footer>
<?php else:
    $l = $t['landing'] ?? [];
?>
<footer class="ld-landing-footer">
    <?php
    $tplCrossId = (int) ($template_id ?? 0);
    if ($tplCrossId >= 1 && $tplCrossId <= 10):
        $tplNames = ld_template_names($lang);
        $tplCross = ld_template_cross_links($lang, $tplCrossId);
    ?>
    <section class="ld-landing-crosslinks">
        <div class="ld-container">
            <h3><?= ld_h($l['other_templates'] ?? '') ?></h3>
            <p class="ld-crosslinks-lead"><?= ld_h($l['other_templates_lead'] ?? '') ?></p>
            <nav class="ld-crosslinks-grid ld-crosslinks-grid--compact" aria-label="<?= ld_h($l['other_templates'] ?? '') ?>">
                <?php foreach ($tplCross as $cross): ?>
                <a href="<?= ld_h($cross['url']) ?>" class="ld-crosslink-card">
                    <span class="ld-crosslink-num">#<?= (int) $cross['id'] ?></span>
                    <strong><?= ld_h($cross['label']) ?></strong>
                    <span><?= ld_h($cross['business']) ?></span>
                </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </section>
    <?php endif; ?>
    <?php ld_render_social_links($lang); ?>
    <p><?= ld_h($l['footer_copy'] ?? '') ?></p>
    <p class="ld-footer-links">
        <?php foreach (ld_pages_footer() as $fp):
            $fpLoc = ld_page_localize($fp, $lang);
            if (trim((string) ($fpLoc['title'] ?? '')) === '') {
                continue;
            }
        ?><a href="<?= ld_h(ld_page_url((string) ($fp['slug'] ?? ''))) ?>"><?= ld_h($fpLoc['title']) ?></a> · <?php endforeach; ?>
        <a href="<?= ld_h(ld_absolute_url('sitemap.xml')) ?>"><?= ld_h($t['pages']['sitemap'] ?? 'Sitemap') ?></a> ·
        <a href="<?= ld_h(ld_url('index.php')) ?>"><?= ld_h($t['nav']['back'] ?? '') ?></a> ·
        <a href="https://bilohash.com/">BILOHASH</a>
    </p>
</footer>
<?php
    $bizPhone = ld_business()['phone'] ?? '';
    $fabTel = preg_replace('/\s+/', '', $bizPhone);
    if (ld_is_driving_preset() && $fabTel !== ''): ?>
<a href="#contact" class="ld-callback-fab" aria-label="<?= ld_h($l['form_callback'] ?? 'Callback') ?>">
    <i class="fas fa-phone-volume" aria-hidden="true"></i>
    <span><?= ld_h($l['sticky_callback'] ?? $l['form_callback'] ?? '') ?></span>
</a>
<?php endif; ?>
<?php if (ld_ai_enabled()): ?>
<div class="ld-ai-widget" id="ldAiWidget" data-api="<?= ld_h(ld_url('api/ai-chat.php')) ?>" data-lang="<?= ld_h($lang) ?>">
    <div class="ld-ai-panel" id="ldAiPanel" hidden>
        <div class="ld-ai-head">
            <strong><i class="fas fa-robot"></i> AI</strong>
            <button type="button" id="ldAiClose" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <div class="ld-ai-log" id="ldAiLog"><div class="ld-ai-msg ld-ai-msg--bot"><?= ld_h(ld_ai_welcome($lang)) ?></div></div>
        <form id="ldAiForm" class="ld-ai-form">
            <input type="text" id="ldAiInput" placeholder="<?= ld_h($l['ai_placeholder'] ?? '') ?>" autocomplete="off">
            <button type="submit"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
    <button type="button" class="ld-ai-toggle" id="ldAiToggle" aria-label="AI chat"><i class="fas fa-robot"></i></button>
</div>
<script src="<?= ld_h(ld_asset('js/ai-widget.js')) ?>?v=1" defer></script>
<?php endif; ?>
<?php endif; ?>

<script src="<?= ld_h(ld_asset('js/main.js')) ?>?v=3" defer></script>
<?php if (empty($is_landing)): ?>
<script src="<?= ld_h(ld_asset('js/hub-effects.js')) ?>?v=1" defer></script>
<?php endif; ?>
</body>
</html>