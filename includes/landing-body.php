<?php
/** @var int $template_id @var string $lang @var array $t */
$business = ld_business();
$hero = ld_hero();
$l = $t['landing'] ?? [];
$services = ld_services($lang);
$team = ld_team($lang);
$faq = ld_faq($lang);
$stats = ld_stats_for($lang);
$currency = ld_currency();
$meta = ld_templates_meta()[$template_id] ?? ld_templates_meta()[1];
$layout = $meta['layout'] ?? 'center';
$name = ld_pick($business['name'], $lang);
$tagline = ld_pick($business['tagline'], $lang);
$city = ld_pick($business['city'], $lang);
$address = ld_pick($business['address'], $lang);
$hours = ld_pick($business['hours'], $lang);
$heroCta = ld_pick($hero['cta'] ?? [], $lang) ?: ($l['hero_cta'] ?? '');
$heroCta2 = ld_pick($hero['cta2'] ?? [], $lang) ?: ($l['hero_cta2'] ?? '');
$heroIcon = trim((string) ($hero['visual_icon'] ?? 'fa-star'));
$heroLabel = ld_pick($hero['visual_label'] ?? [], $lang);
$heroSub = ld_pick($hero['visual_sub'] ?? [], $lang);
$servicesTitle = ld_section_text('services', 'title', $lang, $l['services_title'] ?? '');
$servicesLead = ld_section_text('services', 'lead', $lang, $l['services_lead'] ?? '');
$teamTitle = ld_section_text('team', 'title', $lang, $l['team_title'] ?? '');
$teamLead = ld_section_text('team', 'lead', $lang, $l['team_lead'] ?? '');
$faqTitle = ld_section_text('faq', 'title', $lang, $l['faq_title'] ?? '');
$contactTitle = ld_section_text('contact', 'title', $lang, $l['contact_title'] ?? '');
$contactLead = ld_section_text('contact', 'lead', $lang, $l['contact_lead'] ?? '');
$reviewsTitle = ld_section_text('reviews', 'title', $lang, $l['reviews_title'] ?? '');
$reviewsLead = ld_section_text('reviews', 'lead', $lang, $l['reviews_lead'] ?? '');
$mapTitle = ld_section_text('map', 'title', $lang, $l['map_title'] ?? '');
$google = ld_google();
$reviewItems = ld_reviews($lang);
$mapsEmbed = ld_maps_embed_src();
$mapsLink = ld_maps_link();
$reviewsUrl = ld_google_reviews_url();
$googleRating = trim((string) ($google['rating'] ?? ''));
$googleReviewCount = trim((string) ($google['review_count'] ?? ''));
$heroImage = trim((string) (ld_blocks()['hero_image'] ?? ''));
$legal = ld_effective_settings()['legal'] ?? [];
$consentRequired = !empty($legal['consent_required']) || !empty($legal['consent']);
$consentText = $consentRequired ? ld_pick($legal['consent'] ?? [], $lang) : '';
$privacyUrl = ld_privacy_url();
$phoneTel = preg_replace('/\s+/', '', $business['phone'] ?? '');
$drivingPremium = ld_is_driving_preset();
$processSteps = $drivingPremium ? ld_block_process($lang) : [];
$showHeroVisual = $layout === 'split' || ($drivingPremium && ($heroImage !== '' || $heroLabel !== ''));
$showHeroStats = $stats && ld_section_enabled('stats') && ($layout === 'stats' || $drivingPremium);
$heroClass = 'ld-hero ld-hero--' . $layout . ($drivingPremium ? ' ld-hero--premium' : '');
?>

<section class="<?= ld_h($heroClass) ?>">
    <div class="ld-container ld-hero-grid">
        <div class="ld-hero-content">
            <p class="ld-hero-badge"><i class="fas fa-location-dot"></i> <?= ld_h($city) ?></p>
            <h1><?= ld_h($name) ?></h1>
            <p class="ld-hero-lead"><?= ld_h($tagline) ?></p>
            <div class="ld-hero-cta">
                <a href="#contact" class="ld-btn ld-btn-primary<?= $drivingPremium ? ' ld-btn-callback' : '' ?>">
                    <i class="fas fa-phone-volume"></i> <?= ld_h($heroCta) ?>
                </a>
                <a href="<?= $drivingPremium ? '#services' : '#services' ?>" class="ld-btn ld-btn-ghost"><?= ld_h($heroCta2) ?></a>
            </div>
            <?php if ($showHeroStats && $layout !== 'stats'): ?>
            <div class="ld-stats-bar ld-stats-bar--inline">
                <?php foreach ($stats as $st): ?>
                <div class="ld-stat">
                    <strong><?= ld_h($st['value']) ?></strong>
                    <span><?= ld_h($st['label']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($showHeroVisual): ?>
        <div class="ld-hero-visual" aria-hidden="true">
            <?php if ($heroImage !== ''): ?>
            <img class="ld-hero-photo" src="<?= ld_h($heroImage) ?>" alt="<?= ld_h($name) ?>" loading="eager" width="520" height="390">
            <?php else: ?>
            <div class="ld-hero-card<?= $drivingPremium ? ' ld-hero-card-premium' : '' ?>">
                <i class="fas <?= ld_h($heroIcon) ?>"></i>
                <?php if ($heroLabel !== ''): ?><strong><?= ld_h($heroLabel) ?></strong><?php endif; ?>
                <?php if ($heroSub !== ''): ?><small><?= ld_h($heroSub) ?></small><?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php if ($showHeroStats && $layout === 'stats'): ?>
    <div class="ld-container">
        <div class="ld-stats-bar">
            <?php foreach ($stats as $st): ?>
            <div class="ld-stat">
                <strong><?= ld_h($st['value']) ?></strong>
                <span><?= ld_h($st['label']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<?php if ($drivingPremium): ?>
<div class="ld-trust-bar">
    <div class="ld-trust-bar__inner">
        <span class="ld-trust-item"><i class="fas fa-id-card"></i> <?= ld_h($l['trust_regitra'] ?? 'Regitra prep') ?></span>
        <span class="ld-trust-item"><i class="fab fa-google"></i> <?= ld_h($l['trust_rating'] ?? '4.9 Google') ?></span>
        <span class="ld-trust-item"><i class="fas fa-language"></i> <?= ld_h($l['trust_langs'] ?? 'LT · UA · EN') ?></span>
        <span class="ld-trust-item"><i class="fas fa-phone-volume"></i> <?= ld_h($l['trust_callback'] ?? 'We call you back in 15 min') ?></span>
    </div>
</div>
<?php endif; ?>

<?php if ($processSteps): ?>
<section class="ld-section ld-process" id="process">
    <div class="ld-container">
        <h2><?= ld_h($l['process_title'] ?? 'How it works') ?></h2>
        <p class="ld-section-lead"><?= ld_h($l['process_lead'] ?? '') ?></p>
        <div class="ld-process-grid">
            <?php foreach ($processSteps as $step): ?>
            <article class="ld-process-step">
                <?php if (!empty($step['icon'])): ?><i class="fas <?= ld_h($step['icon']) ?> ld-process-icon"></i><?php endif; ?>
                <h3><?= ld_h($step['title'] ?? '') ?></h3>
                <p><?= ld_h($step['desc'] ?? '') ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (ld_section_enabled('services')): ?>
<section class="ld-section" id="services">
    <div class="ld-container">
        <h2><?= ld_h($servicesTitle) ?></h2>
        <p class="ld-section-lead"><?= ld_h($servicesLead) ?></p>
        <div class="ld-course-grid">
            <?php foreach ($services as $service): ?>
            <article class="ld-course-card">
                <?php if (!empty($service['badge'])): ?>
                <span class="ld-course-badge"><?= ld_h($service['badge']) ?></span>
                <?php endif; ?>
                <div class="ld-course-icon"><i class="fas <?= ld_h($service['icon']) ?>"></i></div>
                <h3><?= ld_h($service['name']) ?></h3>
                <p><?= ld_h($service['desc']) ?></p>
                <?php if (($service['price'] ?? '') !== ''): ?>
                <p class="ld-course-price"><?= ld_h($l['from'] ?? '') ?> <strong><?= ld_h($service['price']) ?> <?= ld_h($currency) ?></strong></p>
                <?php endif; ?>
                <a href="#contact" class="ld-btn ld-btn-sm ld-btn-primary"><?= ld_h($heroCta) ?></a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php ld_render_landing_blocks($lang, $l); ?>

<?php
$newsItems = function_exists('ld_news_published') ? ld_news_published($lang, 3) : [];
if ($newsItems !== []):
    $newsTitle = $l['news_title'] ?? 'News';
    $newsLead = $l['news_lead'] ?? '';
?>
<section class="ld-section ld-section--alt ld-news" id="news">
    <div class="ld-container">
        <h2><?= ld_h($newsTitle) ?></h2>
        <?php if ($newsLead !== ''): ?><p class="ld-section-lead"><?= ld_h($newsLead) ?></p><?php endif; ?>
        <div class="ld-news-grid">
            <?php foreach ($newsItems as $article):
                $article = ld_news_localize($article, $lang);
                $date = substr((string) ($article['published_at'] ?? ''), 0, 10);
                $newsImgs = $article['images'] ?? [];
                if (!is_array($newsImgs) || $newsImgs === []) {
                    $coverImg = trim((string) ($article['image'] ?? ''));
                    $newsImgs = $coverImg !== '' ? [$coverImg] : [];
                }
                $coverImg = trim((string) ($newsImgs[0] ?? ''));
            ?>
            <article class="ld-news-card">
                <?php if ($coverImg !== ''): ?>
                <figure class="ld-news-thumb">
                    <img src="<?= ld_h($coverImg) ?>" alt="<?= ld_h($article['title'] ?? '') ?>" loading="lazy">
                </figure>
                <?php endif; ?>
                <time class="ld-news-date" datetime="<?= ld_h($date) ?>"><?= ld_h($date) ?></time>
                <h3><?= ld_h($article['title'] ?? '') ?></h3>
                <p><?= ld_h($article['excerpt'] ?? '') ?></p>
                <?php if (($article['body'] ?? '') !== ''): ?>
                <details class="ld-news-more">
                    <summary><?= ld_h($l['news_read_more'] ?? 'Read more') ?></summary>
                    <div class="ld-news-body"><?= nl2br(ld_h($article['body'] ?? '')) ?></div>
                    <?php if (count($newsImgs) > 1): ?>
                    <div class="ld-news-gallery">
                        <?php foreach (array_slice($newsImgs, 1) as $extraImg): ?>
                        <img src="<?= ld_h((string) $extraImg) ?>" alt="" loading="lazy">
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </details>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (ld_section_enabled('team')): ?>
<section class="ld-section ld-section--alt" id="team">
    <div class="ld-container">
        <h2><?= ld_h($teamTitle) ?></h2>
        <p class="ld-section-lead"><?= ld_h($teamLead) ?></p>
        <div class="ld-instructor-grid">
            <?php foreach ($team as $member): ?>
            <article class="ld-instructor-card">
                <div class="ld-avatar"><?= ld_h($member['initials']) ?></div>
                <h3><?= ld_h($member['name']) ?></h3>
                <p class="ld-instructor-role"><?= ld_h($member['role']) ?></p>
                <?php if (($member['years'] ?? '') !== ''): ?>
                <p class="ld-instructor-years"><?= ld_h($member['years']) ?> <?= ld_h($l['years'] ?? '') ?></p>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (ld_has_reviews() && ld_section_enabled('reviews')): ?>
<section class="ld-section ld-section--alt" id="reviews">
    <div class="ld-container">
        <div class="ld-reviews-head">
            <div>
                <h2><?= ld_h($reviewsTitle) ?></h2>
                <p class="ld-section-lead"><?= ld_h($reviewsLead) ?></p>
            </div>
            <?php if ($googleRating !== ''): ?>
            <div class="ld-google-badge">
                <img src="https://www.gstatic.com/images/branding/product/1x/googleg_48dp.png" alt="Google" width="24" height="24" loading="lazy">
                <div>
                    <strong><?= ld_h($googleRating) ?></strong>
                    <?= ld_render_stars((int) round((float) str_replace(',', '.', $googleRating))) ?>
                    <?php if ($googleReviewCount !== ''): ?>
                    <span><?= ld_h($googleReviewCount) ?> <?= ld_h($l['reviews_count'] ?? '') ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($reviewItems): ?>
        <div class="ld-reviews-grid">
            <?php foreach ($reviewItems as $review): ?>
            <article class="ld-review-card">
                <div class="ld-review-top">
                    <div class="ld-review-avatar" aria-hidden="true"><?= ld_h(mb_strtoupper(mb_substr((string) ($review['author'] ?? 'G'), 0, 1))) ?></div>
                    <div>
                        <strong><?= ld_h($review['author'] ?? '') ?></strong>
                        <?php if (($review['date'] ?? '') !== ''): ?>
                        <span class="ld-review-date"><?= ld_h($review['date']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?= ld_render_stars((int) ($review['rating'] ?? 5)) ?>
                </div>
                <p><?= ld_h($review['text'] ?? '') ?></p>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if ($reviewsUrl !== ''): ?>
        <p class="ld-reviews-cta">
            <a href="<?= ld_h($reviewsUrl) ?>" class="ld-btn ld-btn-outline" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-google"></i> <?= ld_h($l['reviews_all'] ?? '') ?>
            </a>
        </p>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if (ld_section_enabled('faq')): ?>
<section class="ld-section" id="faq">
    <div class="ld-container ld-container--narrow">
        <h2><?= ld_h($faqTitle) ?></h2>
        <div class="ld-faq-list">
            <?php foreach ($faq as $item): ?>
            <details class="ld-faq-item">
                <summary><?= ld_h($item['q']) ?></summary>
                <p><?= ld_h($item['a']) ?></p>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (ld_section_enabled('contact')): ?>
<section class="ld-section ld-section--contact" id="contact">
    <div class="ld-container ld-contact-grid">
        <div>
            <h2><?= ld_h($contactTitle) ?></h2>
            <p class="ld-section-lead"><?= ld_h($contactLead) ?></p>
            <?php if ($mapsEmbed !== '' && ld_section_enabled('map')): ?>
            <div class="ld-map-wrap" id="map">
                <h3 class="ld-map-title"><?= ld_h($mapTitle) ?></h3>
                <iframe
                    src="<?= ld_h($mapsEmbed) ?>"
                    title="<?= ld_h($mapTitle) ?>"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                ></iframe>
                <?php if ($mapsLink !== ''): ?>
                <a href="<?= ld_h($mapsLink) ?>" class="ld-map-link" target="_blank" rel="noopener noreferrer">
                    <i class="fas fa-map-marked-alt"></i> <?= ld_h($l['map_open'] ?? '') ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <ul class="ld-contact-list">
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <?php if ($mapsLink !== ''): ?>
                    <a href="<?= ld_h($mapsLink) ?>" target="_blank" rel="noopener noreferrer"><?= ld_h($address) ?></a>
                    <?php else: ?>
                    <?= ld_h($address) ?>
                    <?php endif; ?>
                </li>
                <li><i class="fas fa-phone"></i> <a href="tel:<?= ld_h(preg_replace('/\s+/', '', $business['phone'])) ?>"><?= ld_h($business['phone']) ?></a></li>
                <li><i class="fas fa-envelope"></i> <a href="mailto:<?= ld_h($business['email']) ?>"><?= ld_h($business['email']) ?></a></li>
                <li><i class="fas fa-clock"></i> <?= ld_h($hours) ?></li>
            </ul>
        </div>
        <?php
        $leadFlash = $_GET['lead'] ?? '';
        if ($leadFlash === 'ok'): ?>
        <p class="ld-form-success"><i class="fas fa-check-circle"></i> <?= ld_h($l['form_success'] ?? '') ?></p>
        <?php elseif ($leadFlash === 'error'): ?>
        <p class="ld-form-error"><i class="fas fa-exclamation-circle"></i> <?= ld_h($l['form_error'] ?? '') ?></p>
        <?php elseif ($leadFlash === 'captcha'): ?>
        <p class="ld-form-error"><i class="fas fa-exclamation-circle"></i> <?= ld_h($l['form_captcha'] ?? '') ?></p>
        <?php endif; ?>
        <form class="ld-form" action="<?= ld_h(ld_url('contact.php')) ?>" method="post" id="ldLeadForm">
            <input type="hidden" name="template" value="<?= (int) $template_id ?>">
            <input type="hidden" name="redirect" value="<?= ld_h(ld_url('template.php', ['t' => $template_id])) ?>">
            <?php if ($drivingPremium): ?>
            <h3 class="ld-form-title"><?= ld_h($l['form_title'] ?? $contactTitle) ?></h3>
            <div class="ld-form-callback-promo">
                <i class="fas fa-headset" aria-hidden="true"></i>
                <div>
                    <strong><?= ld_h($l['form_callback_promo_title'] ?? '') ?></strong>
                    <span><?= ld_h($l['form_callback_promo'] ?? '') ?></span>
                </div>
            </div>
            <?php endif; ?>
            <label><?= ld_h($l['form_name'] ?? '') ?><input type="text" name="name" required autocomplete="name"></label>
            <label><?= ld_h($l['form_phone'] ?? '') ?><input type="tel" name="phone" required autocomplete="tel" inputmode="tel"></label>
            <label><?= ld_h($l['form_email'] ?? 'Email') ?><input type="email" name="email" autocomplete="email"></label>
            <label class="ld-field-check<?= $drivingPremium ? ' ld-field-check--callback' : '' ?>">
                <input type="checkbox" name="callback" value="1"<?= $drivingPremium ? ' checked' : '' ?>>
                <span><?= ld_h($l['form_callback'] ?? '') ?></span>
            </label>
            <?php if ($services): ?>
            <label><?= ld_h($l['form_service'] ?? '') ?>
                <select name="service">
                    <?php foreach ($services as $service): ?>
                    <option><?= ld_h($service['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php endif; ?>
            <?php if ($consentText !== ''): ?>
            <label class="ld-field-check ld-consent">
                <input type="checkbox" name="consent" value="1" required>
                <span><?= ld_h($consentText) ?> <?php if ($privacyUrl !== ''): ?><a href="<?= ld_h($privacyUrl) ?>"<?= preg_match('#^https?://#i', $privacyUrl) ? ' target="_blank" rel="noopener"' : '' ?>><?= ld_h($l['privacy_link'] ?? '') ?></a><?php endif; ?></span>
            </label>
            <?php endif; ?>
            <?php ld_recaptcha_widget(); ?>
            <button type="submit" class="ld-btn ld-btn-primary ld-btn-block"><?= ld_h($l['form_submit'] ?? '') ?></button>
            <?php if ($phoneTel !== ''): ?>
            <p class="ld-form-call"><a href="tel:<?= ld_h($phoneTel) ?>"><i class="fas fa-phone"></i> <?= ld_h($l['call_now'] ?? '') ?>: <?= ld_h($business['phone']) ?></a></p>
            <?php endif; ?>
            <p class="ld-form-note"><?= ld_h($l['form_note'] ?? '') ?></p>
        </form>
    </div>
</section>
<?php endif; ?>