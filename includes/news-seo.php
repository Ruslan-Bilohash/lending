<?php
declare(strict_types=1);

/** @param array<string, string> $fields title, description, keywords, excerpt, body */
function ld_news_seo_analyze(string $lang, array $fields): array
{
    $title = trim((string) ($fields['seo_title'] ?? $fields['title'] ?? ''));
    $desc = trim((string) ($fields['seo_description'] ?? $fields['excerpt'] ?? ''));
    $keywords = trim((string) ($fields['seo_keywords'] ?? ''));
    $body = trim((string) ($fields['body'] ?? ''));
    $bizName = ld_pick(ld_business()['name'], $lang);

    $ai = ld_ai();
    $apiKey = trim((string) ($ai['api_key'] ?? ''));

    if ($apiKey !== '' && !empty($ai['fill_enabled'])) {
        $prompt = "Analyze SEO for a driving school news article. Language: {$lang}. School: {$bizName}.\n"
            . "SEO Title: {$title}\nMeta description: {$desc}\nKeywords: {$keywords}\n"
            . "Excerpt: " . mb_substr($fields['excerpt'] ?? '', 0, 200) . "\n"
            . "Return JSON only: {\"score\":0-100,\"grade\":\"A-F\",\"tips\":[\"...\"],"
            . "\"seo_title_suggestion\":\"\",\"seo_description_suggestion\":\"\",\"seo_keywords_suggestion\":\"\","
            . "\"title_suggestion\":\"\",\"excerpt_suggestion\":\"\"}";
        $result = ld_ai_call_api($ai, 'You are an SEO expert for local business news. Return valid JSON only.', $prompt, 2000, true);
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

    return ['ok' => true, 'demo' => true, 'data' => ld_news_seo_demo($lang, $title, $desc, $keywords, $body, $bizName)];
}

function ld_news_seo_demo(string $lang, string $title, string $desc, string $keywords, string $body, string $bizName): array
{
    $score = 55;
    $tips = [];

    $tLen = mb_strlen($title);
    if ($tLen < 35) {
        $tips[] = ld_seo_tip($lang, 'title_short');
        $score -= 12;
    } elseif ($tLen > 70) {
        $tips[] = ld_seo_tip($lang, 'title_long');
        $score -= 6;
    } else {
        $score += 12;
    }

    $dLen = mb_strlen($desc);
    if ($dLen < 100) {
        $tips[] = ld_seo_tip($lang, 'desc_short');
        $score -= 12;
    } elseif ($dLen > 165) {
        $tips[] = ld_seo_tip($lang, 'desc_long');
        $score -= 5;
    } else {
        $score += 14;
    }

    if ($keywords === '') {
        $tips[] = ld_seo_tip($lang, 'keywords_missing');
        $score -= 10;
    } else {
        $score += 10;
    }

    if (mb_strlen($body) < 120) {
        $tips[] = ld_news_seo_tip($lang, 'body_short');
        $score -= 8;
    } else {
        $score += 10;
    }

    if ($bizName !== '' && !str_contains(mb_strtolower($title), mb_strtolower($bizName))) {
        $tips[] = ld_seo_tip($lang, 'name_in_title');
        $score -= 4;
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
        'seo_title_suggestion' => $title !== '' ? $title : ($bizName . ' — ' . ld_news_seo_tip($lang, 'news_suffix')),
        'seo_description_suggestion' => $desc !== '' ? $desc : ld_seo_tip($lang, 'desc_example'),
        'seo_keywords_suggestion' => $keywords !== '' ? $keywords : 'vairavimo mokykla, Vilnius, naujienos, B kategorija',
        'title_suggestion' => $title,
        'excerpt_suggestion' => $desc,
    ];
}

function ld_news_seo_tip(string $lang, string $key): string
{
    $map = [
        'body_short' => ['lt' => 'Straipsnio tekstas per trumpas — bent 150 žodžių SEO.', 'uk' => 'Текст статті занадто короткий — мінімум 150 слів для SEO.', 'ru' => 'Текст статьи слишком короткий — минимум 150 слов для SEO.', 'en' => 'Article body too short — aim for 150+ words for SEO.'],
        'news_suffix' => ['lt' => 'naujienos', 'uk' => 'новини', 'ru' => 'новости', 'en' => 'news'],
    ];
    $row = $map[$key] ?? [];
    return (string) ($row[$lang] ?? $row['en'] ?? '');
}

/** @return array{ok:bool,demo:bool,data:array} */
function ld_news_ai_write(string $lang, string $brief): array
{
    $brief = trim($brief);
    if ($brief === '') {
        return ['ok' => false, 'error' => 'brief_required'];
    }

    $biz = ld_pick(ld_business()['name'], $lang);
    $city = ld_pick(ld_business()['city'], $lang);
    $ai = ld_ai();

    if (trim((string) ($ai['api_key'] ?? '')) !== '' && !empty($ai['fill_enabled'])) {
        $prompt = "Write a driving school news article for {$biz} in {$city}. Brief: {$brief}\n"
            . "Return JSON with keys for languages lt, uk, ru, en. Each lang: title, excerpt (max 160 chars), body (2-3 paragraphs HTML-free), "
            . "seo_title (50-60 chars), seo_description (140-155 chars), seo_keywords (comma-separated).\n"
            . "Format: {\"lt\":{\"title\":\"\",...},\"uk\":{...},\"ru\":{...},\"en\":{...}}";
        $result = ld_ai_call_api($ai, 'You write SEO-optimized driving school news in Lithuanian, Ukrainian, Russian and English. JSON only.', $prompt, 4000, true);
        if ($result['ok']) {
            $raw = trim($result['text']);
            if (preg_match('/```(?:json)?\s*([\s\S]*?)```/i', $raw, $m)) {
                $raw = trim($m[1]);
            }
            $parsed = json_decode($raw, true);
            if (is_array($parsed)) {
                return ['ok' => true, 'demo' => false, 'data' => ld_news_ai_normalize($parsed)];
            }
        }
    }

    return ['ok' => true, 'demo' => true, 'data' => ld_news_ai_demo($brief, $biz, $city)];
}

/** @return array<string, array<string, string>> */
function ld_news_ai_demo(string $brief, string $biz, string $city): array
{
    $slug = ld_news_slugify($brief);
    return [
        'lt' => [
            'title' => ucfirst($brief) . ' — ' . $biz,
            'excerpt' => 'Naujiena iš ' . $city . ': ' . $brief . '. Registracija online, konsultacija nemokama.',
            'body' => $biz . ' praneša: ' . $brief . ". Mokyklos komanda pasiruošusi atsakyti į visus klausimus apie B ir BE kategorijas.\n\nUžpildykite formą svetainėje — perskambinsime per 15 minučių.",
            'seo_title' => $biz . ' — ' . $brief . ' | Vilnius',
            'seo_description' => 'Naujienos iš ' . $biz . ' Vilniuje: ' . $brief . '. B ir BE kategorijos, Regitra pasiruošimas. Užsiregistruokite online.',
            'seo_keywords' => 'vairavimo mokykla, Vilnius, B kategorija, naujienos, Regitra',
        ],
        'uk' => [
            'title' => ucfirst($brief) . ' — ' . $biz,
            'excerpt' => 'Новина з ' . $city . ': ' . $brief . '. Запис онлайн, безкоштовна консультація.',
            'body' => $biz . ' повідомляє: ' . $brief . ". Команда школи готова відповісти на питання щодо категорій B та BE.\n\nЗаповніть форму — передзвонимо за 15 хвилин.",
            'seo_title' => $biz . ' — ' . $brief . ' | Вільнюс',
            'seo_description' => 'Новини ' . $biz . ': ' . $brief . '. Категорія B і BE, підготовка до Regitra. Запис онлайн.',
            'seo_keywords' => 'автошкола, Вільнюс, категорія B, новини, Regitra',
        ],
        'ru' => [
            'title' => ucfirst($brief) . ' — ' . $biz,
            'excerpt' => 'Новость из ' . $city . ': ' . $brief . '. Запись онлайн, бесплатная консультация.',
            'body' => $biz . ' сообщает: ' . $brief . ". Команда готова ответить на вопросы по категориям B и BE.\n\nОставьте заявку — перезвоним за 15 минут.",
            'seo_title' => $biz . ' — ' . $brief . ' | Вильнюс',
            'seo_description' => 'Новости ' . $biz . ': ' . $brief . '. Категория B и BE, подготовка к Regitra.',
            'seo_keywords' => 'автошкола, Вильнюс, категория B, новости, Regitra',
        ],
        'en' => [
            'title' => ucfirst($brief) . ' — ' . $biz,
            'excerpt' => 'News from ' . $city . ': ' . $brief . '. Book online — free consultation.',
            'body' => $biz . ' announces: ' . $brief . ". Our team is ready to answer questions about Category B and BE courses.\n\nFill in the form — we call you back within 15 minutes.",
            'seo_title' => $biz . ' — ' . $brief . ' | Vilnius',
            'seo_description' => 'News from ' . $biz . ' in Vilnius: ' . $brief . '. Category B & BE, Regitra exam prep. Enroll online.',
            'seo_keywords' => 'driving school, Vilnius, category B, news, Regitra',
        ],
        'slug' => $slug,
    ];
}

/** @param array<string, mixed> $parsed */
function ld_news_ai_normalize(array $parsed): array
{
    $out = [];
    foreach (ld_langs_codes() as $code) {
        if (!isset($parsed[$code]) || !is_array($parsed[$code])) {
            continue;
        }
        $row = $parsed[$code];
        $out[$code] = [
            'title' => (string) ($row['title'] ?? ''),
            'excerpt' => (string) ($row['excerpt'] ?? ''),
            'body' => (string) ($row['body'] ?? ''),
            'seo_title' => (string) ($row['seo_title'] ?? ''),
            'seo_description' => (string) ($row['seo_description'] ?? ''),
            'seo_keywords' => (string) ($row['seo_keywords'] ?? ''),
        ];
    }
    return $out;
}