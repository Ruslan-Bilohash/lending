<?php
declare(strict_types=1);

function ld_admin_seo_grade(int $score): string
{
    return match (true) {
        $score >= 90 => 'A',
        $score >= 75 => 'B',
        $score >= 60 => 'C',
        $score >= 45 => 'D',
        default => 'F',
    };
}

function ld_admin_seo_pill_class(int $score): string
{
    return match (true) {
        $score >= 75 => 'adm-seo-pill--good',
        $score >= 50 => 'adm-seo-pill--mid',
        default => 'adm-seo-pill--low',
    };
}

/**
 * @return array{
 *   avg: int,
 *   grade: string,
 *   langs: array<string, array{label: string, flag: string, score: int, grade: string, pill: string}>,
 *   tips: list<string>
 * }
 */
function ld_admin_seo_health_summary(): array
{
    $seo = ld_seo();
    $business = ld_business();
    $og = trim((string) ($seo['og_image'] ?? ''));
    $langs = [];
    $scores = [];
    $tipsByPriority = [];

    foreach (ld_langs_codes() as $code) {
        $meta = ld_langs()[$code] ?? [];
        $title = ld_pick($seo['title'] ?? [], $code);
        $desc = ld_pick($seo['description'] ?? [], $code);
        $keywords = ld_pick($seo['keywords'] ?? [], $code);
        $bizName = ld_pick($business['name'], $code);
        $data = ld_ai_seo_demo_analysis($code, $title, $desc, $keywords, $og, $bizName);
        $score = (int) ($data['score'] ?? 0);
        $scores[] = $score;
        $langs[$code] = [
            'label' => (string) ($meta['name'] ?? strtoupper($code)),
            'flag' => (string) ($meta['flag'] ?? ''),
            'score' => $score,
            'grade' => (string) ($data['grade'] ?? ld_admin_seo_grade($score)),
            'pill' => ld_admin_seo_pill_class($score),
        ];
        foreach ((array) ($data['tips'] ?? []) as $tip) {
            $tip = trim((string) $tip);
            if ($tip === '') {
                continue;
            }
            if (!isset($tipsByPriority[$tip])) {
                $tipsByPriority[$tip] = $score;
            } else {
                $tipsByPriority[$tip] = min($tipsByPriority[$tip], $score);
            }
        }
    }

    asort($tipsByPriority);
    $tips = array_keys($tipsByPriority);
    $okTip = ld_seo_tip($GLOBALS['lang'] ?? 'lt', 'ok');
    $tips = array_values(array_filter($tips, static fn(string $t): bool => $t !== $okTip));
    if (count($tips) > 5) {
        $tips = array_slice($tips, 0, 5);
    }

    $avg = $scores !== [] ? (int) round(array_sum($scores) / count($scores)) : 0;

    return [
        'avg' => $avg,
        'grade' => ld_admin_seo_grade($avg),
        'pill' => ld_admin_seo_pill_class($avg),
        'langs' => $langs,
        'tips' => $tips,
    ];
}