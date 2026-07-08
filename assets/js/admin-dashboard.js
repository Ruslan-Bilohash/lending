(function () {
    var root = document.getElementById('admDashboardCharts');
    if (!root || typeof Chart === 'undefined') return;

    var i18n = window.LD_ADMIN_I18N || {};
    var weekly = {};
    var status = {};
    var revenue = {};
    try {
        weekly = JSON.parse(root.getAttribute('data-weekly') || '{}');
        status = JSON.parse(root.getAttribute('data-status') || '{}');
        revenue = JSON.parse(root.getAttribute('data-revenue') || '{}');
    } catch (e) { return; }

    var accent = '#10b981';
    var muted = '#94a3b8';
    var grid = 'rgba(148, 163, 184, 0.2)';
    var font = { family: "'Segoe UI', system-ui, sans-serif", size: 12 };
    var leadsLabel = root.getAttribute('data-label-leads') || i18n.chart_leads || 'Leads';
    var eurLabel = root.getAttribute('data-label-eur') || i18n.chart_eur || 'EUR';

    function makeChart(id, type, labels, values, opts) {
        var canvas = document.getElementById(id);
        if (!canvas) return;
        new Chart(canvas, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: opts.label || '',
                    data: values,
                    backgroundColor: opts.colors || accent,
                    borderColor: opts.border || accent,
                    borderWidth: type === 'line' ? 2 : 1,
                    fill: type === 'line',
                    tension: 0.35,
                    borderRadius: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: opts.legend !== false, labels: { font: font, color: muted } },
                },
                scales: type === 'doughnut' || type === 'pie' ? {} : {
                    x: { ticks: { font: font, color: muted }, grid: { color: grid } },
                    y: { beginAtZero: true, ticks: { font: font, color: muted }, grid: { color: grid } },
                },
            },
        });
    }

    makeChart('chartLeadsWeekly', 'line', weekly.labels || [], weekly.values || [], {
        label: leadsLabel,
        border: accent,
        colors: 'rgba(16, 185, 129, 0.15)',
        legend: false,
    });

    var statusColors = ['#10b981', '#3b82f6', '#f59e0b', '#94a3b8'];
    makeChart('chartLeadsStatus', 'doughnut', status.labels || [], status.values || [], {
        colors: statusColors,
    });

    makeChart('chartRevenue', 'bar', revenue.labels || [], revenue.values || [], {
        label: eurLabel,
        colors: 'rgba(59, 130, 246, 0.7)',
        border: '#3b82f6',
        legend: false,
    });
})();