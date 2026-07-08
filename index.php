<?php
require_once __DIR__ . '/init.php';

$h = $t['home'] ?? [];
$names = ld_template_names($lang);
$meta = ld_templates_meta();
$business = ld_business();
$adminDemo = ld_admin_url('login.php');
$liveTpl = ld_active_template();

$ld_seo_vars = ld_seo_page_vars($lang, false);
$page_title = $ld_seo_vars['title'];
$page_desc = $ld_seo_vars['description'];

require __DIR__ . '/includes/header.php';
?>

<a href="<?= ld_h($adminDemo) ?>" class="ld-sticky-admin" id="ldStickyAdmin" aria-label="<?= ld_h($h['sticky_cta'] ?? 'Admin') ?>">
    <span class="ld-sticky-admin-pulse"></span>
    <i class="fas fa-wand-magic-sparkles"></i>
    <span><?= ld_h($h['sticky_cta'] ?? '') ?></span>
    <i class="fas fa-arrow-right"></i>
</a>

<section class="ld-sell-hero ld-sell-hero--fx">
    <div class="ld-hero-orbs" aria-hidden="true">
        <span class="ld-orb ld-orb-1"></span>
        <span class="ld-orb ld-orb-2"></span>
        <span class="ld-orb ld-orb-3"></span>
    </div>
    <div class="ld-container">
        <p class="ld-hub-badge ld-badge-glow" data-reveal><i class="fas fa-rocket"></i> <?= ld_h($h['badge'] ?? '') ?></p>
        <h1 class="ld-title-gradient" data-reveal><?= ld_h($h['title'] ?? '') ?></h1>
        <p class="ld-hub-lead ld-lead-glow" data-reveal><?= ld_h($h['lead'] ?? '') ?></p>
        <p class="ld-hub-sub" data-reveal><?= ld_h($h['sublead'] ?? '') ?></p>
        <p class="ld-hub-hint" data-reveal><?= ld_h($h['hint'] ?? '') ?></p>
        <div class="ld-sell-cta-row" data-reveal>
            <a href="<?= ld_h($adminDemo) ?>" class="ld-btn ld-btn-primary ld-btn-lg ld-btn-pulse">
                <i class="fas fa-user-shield"></i> <?= ld_h($h['admin_cta'] ?? '') ?>
            </a>
            <a href="<?= ld_h(ld_url('template.php', ['t' => $liveTpl])) ?>" class="ld-btn ld-btn-ghost ld-btn-lg ld-btn-shine" target="_blank">
                <i class="fas fa-globe"></i> <?= ld_h($h['live_cta'] ?? '') ?> #<?= $liveTpl ?>
            </a>
        </div>
        <p class="ld-demo-creds ld-creds-box" data-reveal>
            <i class="fas fa-key"></i> <?= ld_h($t['admin']['demo_creds'] ?? '') ?>
            <span class="ld-creds-arrow">→ <?= ld_h($h['creds_action'] ?? '') ?></span>
        </p>
    </div>
</section>

<section class="ld-sell-stats">
    <div class="ld-container ld-stats-row">
        <?php foreach ($h['stats'] ?? [] as $st): ?>
        <div class="ld-stat-pill" data-reveal>
            <strong data-count="<?= (int) ($st['value'] ?? 0) ?>">0</strong>
            <span><?= ld_h($st['label'] ?? '') ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="ld-admin-showcase">
    <div class="ld-container ld-admin-showcase-grid">
        <div class="ld-admin-showcase-copy" data-reveal>
            <p class="ld-section-eyebrow"><i class="fas fa-sliders"></i> <?= ld_h($h['admin_showcase_eyebrow'] ?? '') ?></p>
            <h2><?= ld_h($h['admin_showcase_title'] ?? '') ?></h2>
            <p><?= ld_h($h['admin_showcase_lead'] ?? '') ?></p>
            <ul class="ld-fx-list">
                <?php foreach ($h['admin_effects'] ?? [] as $fx): ?>
                <li><i class="fas <?= ld_h($fx['icon'] ?? 'fa-sparkles') ?>"></i> <?= ld_h($fx['text'] ?? '') ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= ld_h($adminDemo) ?>" class="ld-btn ld-btn-primary ld-btn-lg ld-btn-pulse">
                <i class="fas fa-door-open"></i> <?= ld_h($h['admin_showcase_cta'] ?? '') ?>
            </a>
        </div>
        <div class="ld-admin-mock" data-reveal aria-hidden="true">
            <div class="ld-admin-mock-window">
                <div class="ld-admin-mock-bar">
                    <span></span><span></span><span></span>
                    <em>Business Landing · Admin</em>
                </div>
                <div class="ld-admin-mock-body">
                    <div class="ld-mock-sidebar">
                        <div class="ld-mock-nav ld-mock-nav--on"></div>
                        <div class="ld-mock-nav"></div>
                        <div class="ld-mock-nav"></div>
                        <div class="ld-mock-nav ld-mock-nav--ai"></div>
                    </div>
                    <div class="ld-mock-main">
                        <div class="ld-mock-card ld-mock-shimmer"></div>
                        <div class="ld-mock-card ld-mock-card--sm"></div>
                        <div class="ld-mock-grid">
                            <div class="ld-mock-tile"></div>
                            <div class="ld-mock-tile"></div>
                            <div class="ld-mock-tile"></div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="ld-mock-caption"><?= ld_h($h['admin_mock_caption'] ?? '') ?></p>
        </div>
    </div>
</section>

<section class="ld-sell-features">
    <div class="ld-container">
        <h2 data-reveal><?= ld_h($h['features_title'] ?? '') ?></h2>
        <p class="ld-section-lead-center" data-reveal><?= ld_h($h['features_lead'] ?? '') ?></p>
        <div class="ld-sell-grid">
            <?php foreach ($h['features'] ?? [] as $feat): ?>
            <article class="ld-sell-card ld-card-hover" data-reveal>
                <div class="ld-sell-icon"><i class="fas <?= ld_h($feat['icon'] ?? 'fa-star') ?>"></i></div>
                <h3><?= ld_h($feat['title'] ?? '') ?></h3>
                <p><?= ld_h($feat['desc'] ?? '') ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="ld-sell-steps">
    <div class="ld-container ld-container--narrow">
        <h2 data-reveal><?= ld_h($h['steps_title'] ?? '') ?></h2>
        <ol class="ld-sell-steps-list">
            <?php foreach ($h['steps'] ?? [] as $i => $step): ?>
            <li data-reveal>
                <span class="ld-step-num ld-step-pulse"><?= $i + 1 ?></span>
                <div>
                    <strong><?= ld_h($step['title'] ?? '') ?></strong>
                    <p><?= ld_h($step['desc'] ?? '') ?></p>
                    <?php if ($i === 0): ?>
                    <a href="<?= ld_h($adminDemo) ?>" class="ld-step-link"><i class="fas fa-arrow-right"></i> <?= ld_h($h['step1_cta'] ?? '') ?></a>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ol>
        <p class="ld-sell-prompt ld-prompt-glow" data-reveal><i class="fas fa-robot"></i> <?= ld_h($h['ai_prompt_hint'] ?? '') ?></p>
    </div>
</section>

<section class="ld-hub-hero ld-hub-hero--compact">
    <div class="ld-container">
        <h2 data-reveal><?= ld_h($h['templates_title'] ?? '') ?></h2>
        <p class="ld-hub-lead" data-reveal><?= ld_h($h['templates_lead'] ?? '') ?></p>
    </div>
</section>

<section class="ld-container ld-template-gallery">
    <div class="ld-template-grid">
        <?php for ($i = 1; $i <= 10; $i++):
            $m = $meta[$i];
            $themeClass = 'ld-preview-' . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
        ?>
        <article class="ld-template-card ld-card-hover" data-reveal>
            <a href="<?= ld_h(ld_url('template.php', ['t' => $i])) ?>" class="ld-template-preview <?= ld_h($themeClass) ?> ld-preview-hover" aria-label="<?= ld_h($names[$i] ?? '') ?>">
                <div class="ld-preview-mock">
                    <span class="ld-preview-icon"><i class="fas <?= ld_h($m['icon']) ?>"></i></span>
                    <span class="ld-preview-title"><?= ld_h(ld_template_demo_name($i, $lang)) ?></span>
                </div>
            </a>
            <div class="ld-template-meta">
                <h2><span class="ld-template-num">#<?= $i ?></span> <?= ld_h($names[$i] ?? '') ?></h2>
                <p><?= ld_h($m['layout']) ?> · <?= ld_h($m['slug']) ?></p>
                <p class="ld-template-biz"><?= ld_h(ld_template_demo_name($i, $lang)) ?></p>
                <a href="<?= ld_h(ld_url('template.php', ['t' => $i])) ?>" class="ld-btn ld-btn-sm ld-btn-primary">
                    <?= ld_h($t['nav']['open'] ?? 'Open') ?> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </article>
        <?php endfor; ?>
    </div>
</section>

<section class="ld-container ld-template-crosslinks" data-reveal>
    <h2><?= ld_h($h['crosslinks_title'] ?? '') ?></h2>
    <p class="ld-hub-lead"><?= ld_h($h['crosslinks_lead'] ?? '') ?></p>
    <nav class="ld-crosslinks-grid" aria-label="<?= ld_h($h['crosslinks_title'] ?? '') ?>">
        <?php for ($ci = 1; $ci <= 10; $ci++): ?>
        <a href="<?= ld_h(ld_url('template.php', ['t' => $ci])) ?>" class="ld-crosslink-card">
            <span class="ld-crosslink-num">#<?= $ci ?></span>
            <strong><?= ld_h($names[$ci] ?? '') ?></strong>
            <span><?= ld_h(ld_template_demo_name($ci, $lang)) ?></span>
        </a>
        <?php endfor; ?>
    </nav>
</section>

<?php ld_render_ecosystem_strip(true); ?>

<section class="ld-sell-final ld-sell-final--fx">
    <div class="ld-container" data-reveal>
        <h2><?= ld_h($h['final_title'] ?? '') ?></h2>
        <p><?= ld_h($h['final_lead'] ?? '') ?></p>
        <div class="ld-sell-cta-row">
            <a href="<?= ld_h($adminDemo) ?>" class="ld-btn ld-btn-primary ld-btn-lg ld-btn-pulse">
                <i class="fas fa-play"></i> <?= ld_h($h['final_cta'] ?? '') ?>
            </a>
        </div>
        <p class="ld-final-note"><?= ld_h($h['final_note'] ?? '') ?></p>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>