<?php
declare(strict_types=1);

function ld_blocks(): array
{
    return ld_settings()['blocks'] ?? ld_default_settings()['blocks'];
}

function ld_block_features(string $lang): array
{
    return ld_localize_list(ld_blocks()['features'] ?? [], $lang, ['title', 'desc']);
}

function ld_block_gallery(string $lang): array
{
    return ld_localize_list(ld_blocks()['gallery'] ?? [], $lang, ['caption']);
}

function ld_block_links(string $lang): array
{
    return ld_localize_list(ld_blocks()['links'] ?? [], $lang, ['label']);
}

function ld_block_cta(string $lang): array
{
    $cta = ld_blocks()['cta'] ?? [];
    return [
        'enabled' => !empty($cta['enabled']),
        'title' => ld_pick($cta['title'] ?? [], $lang),
        'lead' => ld_pick($cta['lead'] ?? [], $lang),
        'phone' => trim((string) ($cta['phone'] ?? '')) ?: (ld_business()['phone'] ?? ''),
    ];
}

function ld_video_embed_src(string $url): string
{
    $url = trim($url);
    if ($url === '') return '';
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    return $url;
}

function ld_render_landing_blocks(string $lang, array $l): void
{
    $blocks = ld_blocks();
    $features = ld_block_features($lang);
    $gallery = ld_block_gallery($lang);
    $cta = ld_block_cta($lang);
    $promo = $blocks['promo'] ?? [];
    $video = $blocks['video'] ?? [];
    $partners = $blocks['partners'] ?? [];

    if (!empty($promo['enabled']) && ld_section_enabled('promo')): ?>
<section class="ld-section ld-promo-banner">
    <div class="ld-container ld-promo-inner">
        <?php if (ld_pick($promo['badge'] ?? [], $lang) !== ''): ?><span class="ld-promo-badge"><?= ld_h(ld_pick($promo['badge'], $lang)) ?></span><?php endif; ?>
        <h2><?= ld_h(ld_pick($promo['title'] ?? [], $lang)) ?></h2>
        <p><?= ld_h(ld_pick($promo['text'] ?? [], $lang)) ?></p>
        <a href="#contact" class="ld-btn ld-btn-primary"><?= ld_h($l['hero_cta'] ?? 'Contact') ?></a>
    </div>
</section>
<?php endif;

    if ($features && ld_section_enabled('features')): ?>
<section class="ld-section ld-section--alt" id="features">
    <div class="ld-container">
        <h2><?= ld_h(ld_section_text('features', 'title', $lang, $l['features_title'] ?? 'Why us')) ?></h2>
        <div class="ld-features-grid">
            <?php foreach ($features as $f): ?>
            <article class="ld-feature-card">
                <div class="ld-feature-icon"><i class="fas <?= ld_h($f['icon'] ?? 'fa-star') ?>"></i></div>
                <h3><?= ld_h($f['title'] ?? '') ?></h3>
                <p><?= ld_h($f['desc'] ?? '') ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif;

    if (!empty($video['enabled']) && ld_section_enabled('video')):
        $embed = ld_video_embed_src((string) ($video['url'] ?? ''));
        if ($embed !== ''): ?>
<section class="ld-section ld-section--alt" id="video">
    <div class="ld-container">
        <h2><?= ld_h(ld_pick($video['title'] ?? [], $lang) ?: 'Video') ?></h2>
        <div class="ld-video-wrap"><iframe src="<?= ld_h($embed) ?>" title="Video" loading="lazy" allowfullscreen></iframe></div>
    </div>
</section>
<?php endif; endif;

    if ($gallery && ld_section_enabled('gallery')): ?>
<section class="ld-section" id="gallery">
    <div class="ld-container">
        <h2><?= ld_h(ld_section_text('gallery', 'title', $lang, $l['gallery_title'] ?? 'Gallery')) ?></h2>
        <div class="ld-gallery-grid">
            <?php foreach ($gallery as $img): ?>
            <figure class="ld-gallery-item">
                <img src="<?= ld_h($img['url'] ?? '') ?>" alt="<?= ld_h($img['caption'] ?? '') ?>" loading="lazy" width="400" height="300">
                <?php if (($img['caption'] ?? '') !== ''): ?>
                <figcaption><?= ld_h($img['caption']) ?></figcaption>
                <?php endif; ?>
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif;

    if (!empty($partners) && ld_section_enabled('partners')): ?>
<section class="ld-section ld-partners" id="partners">
    <div class="ld-container">
        <div class="ld-partners-row">
            <?php foreach ($partners as $p):
                $logo = trim((string) ($p['logo'] ?? ''));
                $pname = trim((string) ($p['name'] ?? ''));
                if ($logo === '' && $pname === '') continue;
                if ($logo !== ''): ?>
            <img src="<?= ld_h($logo) ?>" alt="<?= ld_h($pname) ?>" loading="lazy" height="40">
            <?php else: ?>
            <span class="ld-partner-badge"><?= ld_h($pname) ?></span>
            <?php endif; endforeach; ?>
        </div>
    </div>
</section>
<?php endif;

    if ($cta['enabled'] && ld_section_enabled('contact')):
        $ctaPhone = $cta['phone'];
        $ctaTel = preg_replace('/\s+/', '', $ctaPhone);
    ?>
<section class="ld-section ld-cta-banner" id="callback">
    <div class="ld-container ld-cta-inner">
        <div>
            <h2><?= ld_h($cta['title']) ?></h2>
            <p><?= ld_h($cta['lead']) ?></p>
        </div>
        <?php if ($ctaTel !== ''): ?>
        <a href="tel:<?= ld_h($ctaTel) ?>" class="ld-btn ld-btn-primary ld-btn-lg">
            <i class="fas fa-phone"></i> <?= ld_h($l['call_now'] ?? 'Call now') ?>
        </a>
        <?php else: ?>
        <a href="#contact" class="ld-btn ld-btn-primary ld-btn-lg">
            <i class="fas fa-phone-volume"></i> <?= ld_h($l['form_callback'] ?? 'Request callback') ?>
        </a>
        <?php endif; ?>
    </div>
</section>
<?php endif;
}

function ld_render_social_links(string $lang): void
{
    $links = ld_block_links($lang);
    if (!$links) {
        return;
    }
    ?>
    <div class="ld-social-links">
        <?php foreach ($links as $link): ?>
        <a href="<?= ld_h($link['url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" title="<?= ld_h($link['label'] ?? '') ?>">
            <i class="<?= ld_h($link['icon'] ?? 'fas fa-link') ?>"></i>
            <span><?= ld_h($link['label'] ?? '') ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php
}