<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'dashboard';
$page_title = $ta['dashboard'] ?? 'Dashboard';

$stats = ld_dashboard_stats();
$active = ld_active_template();
$names = ld_template_names($lang);
$presetId = ld_business_preset_id();
$preset = ld_business_preset($presetId);
$weekly = ld_analytics_leads_weekly(8);
$byStatus = ld_analytics_leads_by_status();
$statusLabels = [
    'new' => $ta['lead_status_new'] ?? 'new',
    'callback' => $ta['lead_status_callback'] ?? 'callback',
    'invoiced' => $ta['lead_status_invoiced'] ?? 'invoiced',
    'other' => $ta['lead_status_other'] ?? 'other',
];
$byStatus['labels'] = array_map(static fn(string $k): string => $statusLabels[$k] ?? $k, $byStatus['labels']);
$revenue = ld_analytics_invoices_monthly(6);

require __DIR__ . '/includes/layout.php';
?>

<div class="adm-alert adm-alert-info">
    <i class="fas fa-flask"></i> <?= ld_h($ta['demo_note'] ?? '') ?>
</div>

<?php require __DIR__ . '/includes/master-checklist-panel.php'; ?>
<?php require __DIR__ . '/includes/seo-health-panel.php'; ?>

<div class="adm-dashboard-guides">
<?php require __DIR__ . '/includes/seo-tips-panel.php'; ?>
</div>

<div class="adm-stats">
    <div class="adm-stat">
        <div class="adm-stat-icon indigo"><i class="fas fa-inbox"></i></div>
        <div>
            <div class="adm-stat-val"><?= (int) $stats['leads_total'] ?></div>
            <div class="adm-stat-label"><?= ld_h($ta['stats_leads'] ?? '') ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon orange"><i class="fas fa-bell"></i></div>
        <div>
            <div class="adm-stat-val"><?= (int) $stats['leads_new'] ?></div>
            <div class="adm-stat-label"><?= ld_h($ta['stats_new'] ?? '') ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon blue"><i class="fas fa-file-invoice-dollar"></i></div>
        <div>
            <div class="adm-stat-val"><?= (int) $stats['invoices_total'] ?></div>
            <div class="adm-stat-label"><?= ld_h($ta['stats_invoices'] ?? 'Invoices') ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon green"><i class="fas fa-euro-sign"></i></div>
        <div>
            <div class="adm-stat-val"><?= ld_h(number_format((float) $stats['invoices_revenue'], 0, '.', ' ')) ?></div>
            <div class="adm-stat-label"><?= ld_h($ta['stats_revenue'] ?? 'Revenue (EUR)') ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon indigo"><i class="fas fa-newspaper"></i></div>
        <div>
            <div class="adm-stat-val"><?= (int) $stats['news_published'] ?></div>
            <div class="adm-stat-label"><?= ld_h($ta['stats_news'] ?? 'News') ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon gold"><i class="fas fa-chart-line"></i></div>
        <div>
            <div class="adm-stat-val"><?= (int) $stats['news_avg_seo'] ?: '—' ?></div>
            <div class="adm-stat-label"><?= ld_h($ta['stats_seo_avg'] ?? 'Avg SEO') ?></div>
        </div>
    </div>
</div>

<div class="adm-charts-grid" id="admDashboardCharts"
    data-weekly="<?= ld_h(json_encode($weekly, JSON_UNESCAPED_UNICODE)) ?>"
    data-status="<?= ld_h(json_encode($byStatus, JSON_UNESCAPED_UNICODE)) ?>"
    data-revenue="<?= ld_h(json_encode($revenue, JSON_UNESCAPED_UNICODE)) ?>"
    data-label-leads="<?= ld_h(ld_admin_t('chart_leads')) ?>"
    data-label-eur="<?= ld_h(ld_admin_t('chart_eur')) ?>"
    data-aria-weekly="<?= ld_h(ld_admin_t('chart_aria_weekly')) ?>"
    data-aria-status="<?= ld_h(ld_admin_t('chart_aria_status')) ?>"
    data-aria-revenue="<?= ld_h(ld_admin_t('chart_aria_revenue')) ?>">
    <div class="adm-card adm-chart-card">
        <div class="adm-card-head"><h2><i class="fas fa-chart-line"></i> <?= ld_h($ta['chart_leads_weekly'] ?? 'Leads per week') ?></h2></div>
        <div class="adm-card-body adm-chart-body"><canvas id="chartLeadsWeekly" aria-label="<?= ld_h(ld_admin_t('chart_aria_weekly')) ?>"></canvas></div>
    </div>
    <div class="adm-card adm-chart-card">
        <div class="adm-card-head"><h2><i class="fas fa-chart-pie"></i> <?= ld_h($ta['chart_leads_status'] ?? 'Leads by status') ?></h2></div>
        <div class="adm-card-body adm-chart-body"><canvas id="chartLeadsStatus" aria-label="<?= ld_h(ld_admin_t('chart_aria_status')) ?>"></canvas></div>
    </div>
    <div class="adm-card adm-chart-card adm-chart-card--wide">
        <div class="adm-card-head"><h2><i class="fas fa-coins"></i> <?= ld_h($ta['chart_revenue_monthly'] ?? 'Invoice revenue') ?></h2></div>
        <div class="adm-card-body adm-chart-body adm-chart-body--tall"><canvas id="chartRevenue" aria-label="<?= ld_h(ld_admin_t('chart_aria_revenue')) ?>"></canvas></div>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head"><h2><?= ld_h($names[$active] ?? ('#' . $active)) ?></h2></div>
    <div class="adm-card-body padded">
        <p style="margin:0 0 8px;color:var(--adm-muted)"><?= ld_h(ld_pick(ld_business()['name'], $lang)) ?> — <?= ld_h(ld_pick(ld_business()['city'], $lang)) ?></p>
        <?php if ($preset): ?>
        <p style="margin:0 0 12px;font-size:13px"><i class="fas <?= ld_h($preset['icon'] ?? 'fa-store') ?>"></i> <?= ld_h(ld_pick($preset['label'], $lang)) ?></p>
        <?php endif; ?>
        <a href="<?= ld_h(ld_admin_url('news.php')) ?>" class="adm-btn adm-btn-outline" style="margin-right:8px">
            <i class="fas fa-newspaper"></i> <?= ld_h($ta['news'] ?? 'News') ?>
        </a>
        <a href="<?= ld_h(ld_admin_url('invoices.php')) ?>" class="adm-btn adm-btn-outline" style="margin-right:8px">
            <i class="fas fa-file-invoice-dollar"></i> <?= ld_h($ta['invoices'] ?? '') ?>
        </a>
        <a href="<?= ld_h(ld_url('template.php', ['t' => $active])) ?>" class="adm-btn adm-btn-primary" target="_blank">
            <i class="fas fa-external-link-alt"></i> <?= ld_h($ta['view_site'] ?? '') ?>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
<script src="<?= ld_h(ld_asset('js/admin-dashboard.js')) ?>?v=2" defer></script>
<?php require __DIR__ . '/includes/layout-end.php'; ?>