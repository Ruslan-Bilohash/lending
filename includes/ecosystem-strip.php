<?php
declare(strict_types=1);

function ld_ecosystem_join_url(): string
{
    global $lang;
    $base = 'https://bilohash.com/ecosystem/join.php';
    $joinLang = ($lang ?? 'en') === 'uk' ? 'ua' : ($lang ?? 'en');

    return $joinLang === 'en' ? $base : $base . '?lang=' . rawurlencode($joinLang);
}

function ld_render_ecosystem_strip(bool $hubBlock = false): void
{
    global $t;
    if (empty($t['ecosystem']['items'])) {
        return;
    }
    $eco = $t['ecosystem'];
    $h = $t['home'] ?? [];
    $homeTitle = $h['bilohash_home_title'] ?? ($eco['title'] ?? '');
    $homeLead = $h['bilohash_home_lead'] ?? ($eco['subtitle'] ?? '');
    $homeCta = $h['bilohash_home_cta'] ?? 'bilohash.com';
    $homeJoin = $h['bilohash_home_join'] ?? ($eco['strip_label'] ?? 'BILOHASH');
    ?>
<section class="ld-ecosystem<?= $hubBlock ? ' ld-ecosystem--hub' : '' ?>" id="ecosystem">
    <div class="ld-container">
        <div class="ld-ecosystem-head"<?= $hubBlock ? ' data-reveal' : '' ?>>
            <p class="ld-ecosystem-badge"><i class="fas fa-layer-group"></i> <?= ld_h($eco['strip_label'] ?? 'BILOHASH') ?></p>
            <h2><?= ld_h($hubBlock ? $homeTitle : ($eco['title'] ?? '')) ?></h2>
            <p><?= ld_h($hubBlock ? $homeLead : ($eco['subtitle'] ?? '')) ?></p>
            <?php if ($hubBlock): ?>
            <div class="ld-ecosystem-home-cta">
                <a href="https://bilohash.com/" class="ld-btn ld-btn-primary ld-btn-lg" rel="author">
                    <i class="fas fa-globe"></i> <?= ld_h($homeCta) ?>
                </a>
                <a href="https://bilohash.com/ecosystem/join.php" class="ld-btn ld-btn-ghost ld-btn-lg">
                    <i class="fas fa-puzzle-piece"></i> <?= ld_h($homeJoin) ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
        <div class="ld-ecosystem-grid">
            <?php foreach (array_slice($eco['items'], 0, 8) as $item): ?>
            <article class="ld-ecosystem-card">
                <div class="ld-ecosystem-icon" aria-hidden="true">
                    <?php if (($item['icon'] ?? '') === 'wordpress'): ?>
                    <i class="fab fa-wordpress"></i>
                    <?php else: ?>
                    <i class="fas fa-<?= ld_h($item['icon'] ?? 'cube') ?>"></i>
                    <?php endif; ?>
                </div>
                <h3><?= ld_h($item['name'] ?? '') ?></h3>
                <p><?= ld_h($item['desc'] ?? '') ?></p>
                <div class="ld-ecosystem-links">
                    <a href="<?= ld_h($item['url'] ?? '#') ?>" class="ld-btn ld-btn-sm ld-btn-ghost" rel="related" target="_blank"><?= ld_h($eco['product_btn'] ?? '') ?></a>
                    <a href="<?= ld_h(ld_ecosystem_join_url()) ?>" class="ld-btn ld-btn-sm ld-btn-primary" rel="related"><?= ld_h($eco['demo_btn'] ?? 'Demo') ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <p class="ld-ecosystem-join">
            <a href="https://bilohash.com/ecosystem/join.php"><?= ld_h($eco['strip_label'] ?? 'BILOHASH ecosystem') ?> →</a>
        </p>
    </div>
</section>
    <?php
}