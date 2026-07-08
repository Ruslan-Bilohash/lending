<?php
declare(strict_types=1);

function ld_ai_seo_analyze(string $lang): array
{
    $seo = ld_seo();
    $business = ld_business();
    $title = ld_pick($seo['title'] ?? [], $lang);
    $desc = ld_pick($seo['description'] ?? [], $lang);
    $keywords = ld_pick($seo['keywords'] ?? [], $lang);
    $og = trim((string) ($seo['og_image'] ?? ''));
    $bizName = ld_pick($business['name'], $lang);

    $ai = ld_ai();
    $apiKey = trim((string) ($ai['api_key'] ?? ''));

    if ($apiKey !== '' && !empty($ai['fill_enabled'])) {
        $prompt = "Analyze SEO for a business landing page. Language: {$lang}. Business: {$bizName}.\n"
            . "Title ({$lang}): {$title}\nDescription: {$desc}\nKeywords: {$keywords}\nOG image: " . ($og !== '' ? 'yes' : 'no')
            . "\nReturn JSON only: {\"score\":0-100,\"grade\":\"A-F\",\"tips\":[\"...\"],\"title_suggestion\":\"\",\"description_suggestion\":\"\",\"keywords_suggestion\":\"\"}";
        $result = ld_ai_call_api($ai, ld_ai_seo_system_prompt(), $prompt, 2000, true);
        if ($result['ok']) {
            $raw = trim($result['text']);
            if (preg_match('/```(?:json)?\s*([\s\S]*?)```/i', $raw, $m)) {
                $raw = trim($m[1]);
            }
            $parsed = json_decode($raw, true);
            if (is_array($parsed)) {
                return ['ok' => true, 'demo' => false, 'data' => $parsed];
            }
        }
    }

    return ['ok' => true, 'demo' => true, 'data' => ld_ai_seo_demo_analysis($lang, $title, $desc, $keywords, $og, $bizName)];
}

function ld_ai_seo_system_prompt(): string
{
    return 'You are an SEO expert for local business landing pages (Google, Schema.org). Return valid JSON only.';
}

function ld_ai_seo_demo_analysis(string $lang, string $title, string $desc, string $keywords, string $og, string $bizName): array
{
    $score = 50;
    $tips = [];

    $tLen = mb_strlen($title);
    if ($tLen < 30) {
        $tips[] = ld_seo_tip($lang, 'title_short');
        $score -= 10;
    } elseif ($tLen > 65) {
        $tips[] = ld_seo_tip($lang, 'title_long');
        $score -= 5;
    } else {
        $score += 10;
    }

    $dLen = mb_strlen($desc);
    if ($dLen < 120) {
        $tips[] = ld_seo_tip($lang, 'desc_short');
        $score -= 10;
    } elseif ($dLen > 165) {
        $tips[] = ld_seo_tip($lang, 'desc_long');
        $score -= 5;
    } else {
        $score += 15;
    }

    if ($keywords === '') {
        $tips[] = ld_seo_tip($lang, 'keywords_missing');
        $score -= 10;
    } else {
        $score += 10;
    }

    if ($og === '') {
        $tips[] = ld_seo_tip($lang, 'og_missing');
        $score -= 8;
    } else {
        $score += 8;
    }

    if (!str_contains(mb_strtolower($title), mb_strtolower($bizName)) && $bizName !== '') {
        $tips[] = ld_seo_tip($lang, 'name_in_title');
        $score -= 5;
    }

    $score = max(0, min(100, $score));
    $grade = match (true) {
        $score >= 90 => 'A',
        $score >= 75 => 'B',
        $score >= 60 => 'C',
        $score >= 45 => 'D',
        default => 'F',
    };

    return [
        'score' => $score,
        'grade' => $grade,
        'tips' => $tips ?: [ld_seo_tip($lang, 'ok')],
        'title_suggestion' => $bizName . ' — ' . ld_seo_tip($lang, 'city_suffix'),
        'description_suggestion' => $desc !== '' ? $desc : ld_seo_tip($lang, 'desc_example'),
        'keywords_suggestion' => $keywords !== '' ? $keywords : $bizName . ', services, booking',
    ];
}

function ld_seo_tip(string $lang, string $key): string
{
    $tips = [
        'title_short' => ['lt' => 'Title per trumpas — pridėkite miestą ir paslaugą (50–60 simbolių).', 'uk' => 'Title занадто короткий — додайте місто та послугу (50–60 символів).', 'ru' => 'Title слишком короткий — добавьте город и услугу (50–60 символов).', 'en' => 'Title too short — add city and service (50–60 chars).'],
        'title_long' => ['lt' => 'Title per ilgas — sutrumpinkite iki ~60 simbolių.', 'uk' => 'Title занадто довгий — скоротіть до ~60 символів.', 'ru' => 'Title слишком длинный — сократите до ~60 символов.', 'en' => 'Title too long — shorten to ~60 chars.'],
        'desc_short' => ['lt' => 'Description < 120 simbolių — Google rodo iki 155.', 'uk' => 'Description < 120 символів — Google показує до 155.', 'ru' => 'Description < 120 символов — Google показывает до 155.', 'en' => 'Description < 120 chars — Google shows up to 155.'],
        'desc_long' => ['lt' => 'Description per ilgas — sutrumpinkite iki 150–160 simbolių.', 'uk' => 'Description занадто довгий — до 150–160 символів.', 'ru' => 'Description слишком длинный — до 150–160 символов.', 'en' => 'Description too long — aim for 150–160 chars.'],
        'keywords_missing' => ['lt' => 'Pridėkite keywords: verslas, miestas, paslaugos.', 'uk' => 'Додайте keywords: бізнес, місто, послуги.', 'ru' => 'Добавьте keywords: бизнес, город, услуги.', 'en' => 'Add keywords: business, city, services.'],
        'og_missing' => ['lt' => 'Nustatykite OG Image — socialiniai tinklai ir Google.', 'uk' => 'Вкажіть OG Image — соцмережі та Google.', 'ru' => 'Укажите OG Image — соцсети и Google.', 'en' => 'Set OG Image for social and rich snippets.'],
        'name_in_title' => ['lt' => 'Į title įtraukite verslo pavadinimą.', 'uk' => 'Додайте назву бізнесу в title.', 'ru' => 'Добавьте название бизнеса в title.', 'en' => 'Include business name in title.'],
        'ok' => ['lt' => 'SEO atrodo gerai — naudokite AI su API raktu gilesnei analizei.', 'uk' => 'SEO виглядає добре — підключіть API для глибшого аналізу.', 'ru' => 'SEO выглядит хорошо — подключите API для глубокого анализа.', 'en' => 'SEO looks good — connect API key for deeper AI analysis.'],
        'city_suffix' => ['lt' => 'oficialus puslapis', 'uk' => 'офіційний сайт', 'ru' => 'официальный сайт', 'en' => 'official website'],
        'desc_example' => ['lt' => 'Profesionalios paslaugos — užsiregistruokite online.', 'uk' => 'Професійні послуги — запис онлайн.', 'ru' => 'Профессиональные услуги — запись онлайн.', 'en' => 'Professional services — book online.'],
    ];
    $row = $tips[$key] ?? $tips['ok'];
    return ld_pick($row, $lang);
}