<?php
declare(strict_types=1);

/** @return array{labels:list<string>,values:list<int>} */
function ld_analytics_leads_weekly(int $weeks = 8): array
{
    $leads = ld_load_leads();
    $labels = [];
    $values = [];
    $now = new DateTimeImmutable('today');

    for ($i = $weeks - 1; $i >= 0; $i--) {
        $start = $now->modify('-' . $i . ' weeks')->modify('monday this week');
        $end = $start->modify('+6 days');
        $labels[] = $start->format('d M');
        $count = 0;
        foreach ($leads as $lead) {
            $at = substr((string) ($lead['created_at'] ?? ''), 0, 10);
            if ($at === '') {
                continue;
            }
            try {
                $d = new DateTimeImmutable($at);
            } catch (Throwable) {
                continue;
            }
            if ($d >= $start && $d <= $end) {
                $count++;
            }
        }
        $values[] = $count;
    }

    return ['labels' => $labels, 'values' => $values];
}

/** @return array{labels:list<string>,values:list<int>} */
function ld_analytics_leads_by_status(): array
{
    $leads = ld_load_leads();
    $buckets = ['new' => 0, 'callback' => 0, 'invoiced' => 0, 'other' => 0];
    foreach ($leads as $lead) {
        $st = (string) ($lead['status'] ?? 'new');
        if (isset($buckets[$st])) {
            $buckets[$st]++;
        } else {
            $buckets['other']++;
        }
    }
    return [
        'labels' => array_keys($buckets),
        'values' => array_values($buckets),
    ];
}

/** @return array{labels:list<string>,values:list<float>} */
function ld_analytics_invoices_monthly(int $months = 6): array
{
    $invoices = ld_all_invoices_list();
    $labels = [];
    $values = [];
    $now = new DateTimeImmutable('first day of this month');

    for ($i = $months - 1; $i >= 0; $i--) {
        $month = $now->modify('-' . $i . ' months');
        $key = $month->format('Y-m');
        $labels[] = $month->format('M Y');
        $sum = 0.0;
        foreach ($invoices as $inv) {
            $at = substr((string) ($inv['created_at'] ?? ''), 0, 7);
            if ($at === $key) {
                $sum += (float) ($inv['amount'] ?? 0);
            }
        }
        $values[] = round($sum, 2);
    }

    return ['labels' => $labels, 'values' => $values];
}

/** @return array<string, mixed> */
function ld_dashboard_stats(): array
{
    $leads = ld_load_leads();
    $news = ld_load_news();
    $invoices = ld_all_invoices_list();

    return [
        'leads_total' => count($leads),
        'leads_new' => count(array_filter($leads, static fn(array $l): bool => ($l['status'] ?? 'new') === 'new')),
        'leads_callback' => count(array_filter($leads, static fn(array $l): bool => ($l['status'] ?? '') === 'callback')),
        'invoices_total' => count($invoices),
        'invoices_revenue' => array_sum(array_map(static fn(array $i): float => (float) ($i['amount'] ?? 0), $invoices)),
        'news_total' => count($news),
        'news_published' => count(array_filter($news, static fn(array $n): bool => ($n['status'] ?? '') === 'published')),
        'news_avg_seo' => ld_news_avg_seo_score(),
    ];
}

function ld_news_avg_seo_score(): int
{
    $news = ld_load_news();
    if ($news === []) {
        return 0;
    }
    $sum = 0;
    $n = 0;
    foreach ($news as $row) {
        if ((int) ($row['seo_score'] ?? 0) > 0) {
            $sum += (int) $row['seo_score'];
            $n++;
        }
    }
    return $n > 0 ? (int) round($sum / $n) : 0;
}