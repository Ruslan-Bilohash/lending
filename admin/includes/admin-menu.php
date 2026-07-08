<?php
/**
 * Categorized admin sidebar menu (dropdown groups).
 */

/** @return list<array<string, mixed>> */
function ld_admin_sidebar_menu(): array
{
    return [
        [
            'id'        => 'dashboard',
            'type'      => 'link',
            'label_key' => 'dashboard',
            'icon'      => 'chart-pie',
            'url'       => 'index.php',
            'page'      => 'dashboard',
        ],
        [
            'id'        => 'content',
            'type'      => 'group',
            'label_key' => 'nav_group_content',
            'icon'      => 'pen-to-square',
            'items'     => [
                ['label_key' => 'settings', 'icon' => 'briefcase', 'url' => 'settings.php', 'page' => 'settings'],
                ['label_key' => 'content', 'icon' => 'file-lines', 'url' => 'content.php', 'page' => 'content'],
                ['label_key' => 'news', 'icon' => 'newspaper', 'url' => 'news.php', 'page' => 'news'],
                ['label_key' => 'pages_nav', 'icon' => 'file-contract', 'url' => 'pages.php', 'page' => 'pages'],
            ],
        ],
        [
            'id'        => 'crm',
            'type'      => 'group',
            'label_key' => 'nav_group_crm',
            'icon'      => 'users',
            'items'     => [
                ['label_key' => 'leads', 'icon' => 'inbox', 'url' => 'leads.php', 'page' => 'leads', 'badge' => 'leads'],
                ['label_key' => 'students', 'icon' => 'user-graduate', 'url' => 'students.php', 'page' => 'students'],
                ['label_key' => 'invoices', 'icon' => 'file-invoice-dollar', 'url' => 'invoices.php', 'page' => 'invoices'],
            ],
        ],
        [
            'id'        => 'design',
            'type'      => 'group',
            'label_key' => 'nav_group_design',
            'icon'      => 'palette',
            'items'     => [
                ['label_key' => 'design', 'icon' => 'brush', 'url' => 'design.php', 'page' => 'design'],
                ['label_key' => 'blocks', 'icon' => 'cubes', 'url' => 'blocks.php', 'page' => 'blocks'],
                ['label_key' => 'templates', 'icon' => 'layer-group', 'url' => 'templates.php', 'page' => 'templates'],
            ],
        ],
        [
            'id'        => 'marketing',
            'type'      => 'group',
            'label_key' => 'nav_group_marketing',
            'icon'      => 'chart-line',
            'items'     => [
                ['label_key' => 'seo', 'icon' => 'search', 'url' => 'seo.php', 'page' => 'seo'],
                ['label_key' => 'integrations', 'icon' => 'plug', 'url' => 'integrations.php', 'page' => 'integrations'],
                ['label_key' => 'ai', 'icon' => 'robot', 'url' => 'ai.php', 'page' => 'ai'],
            ],
        ],
        [
            'id'        => 'help',
            'type'      => 'group',
            'label_key' => 'nav_group_help',
            'icon'      => 'life-ring',
            'items'     => [
                ['label_key' => 'help_nav', 'icon' => 'book', 'url' => 'help.php', 'page' => 'help'],
                ['label_key' => 'support_owner', 'icon' => 'headset', 'url' => 'support.php', 'page' => 'support'],
                ['label_key' => 'changelog', 'icon' => 'clock-rotate-left', 'url' => 'changelog.php', 'page' => 'changelog'],
            ],
        ],
        [
            'id'        => 'view_site',
            'type'      => 'link',
            'label_key' => 'view_site',
            'icon'      => 'external-link-alt',
            'href'      => ld_url('index.php'),
            'external'  => true,
        ],
    ];
}

function ld_admin_menu_item_active(array $item, string $adminPage): bool
{
    $page = $item['page'] ?? '';
    if ($page !== '') {
        return $adminPage === $page;
    }

    return false;
}

function ld_admin_menu_group_has_active(array $group, string $adminPage): bool
{
    foreach ($group['items'] ?? [] as $item) {
        if (ld_admin_menu_item_active($item, $adminPage)) {
            return true;
        }
    }

    return false;
}

function ld_admin_menu_item_label(array $item, array $ta): string
{
    $key = $item['label_key'] ?? '';
    if ($key === '') {
        return '';
    }

    return (string) ($ta[$key] ?? $key);
}

function ld_admin_menu_item_href(array $item): string
{
    if (!empty($item['href'])) {
        return (string) $item['href'];
    }

    return ld_admin_url($item['url'] ?? 'index.php');
}

function ld_admin_menu_leads_badge(): int
{
    static $count = null;
    if ($count !== null) {
        return $count;
    }

    $path = dirname(__DIR__, 2) . '/includes/admin-analytics.php';
    if (!is_file($path)) {
        $count = 0;
        return 0;
    }

    require_once $path;
    $stats = ld_dashboard_stats();
    $count = (int) ($stats['leads_new'] ?? 0);

    return $count;
}

function ld_render_admin_sidebar_nav(array $ta, string $adminPage): void
{
    foreach (ld_admin_sidebar_menu() as $entry) {
        if (($entry['type'] ?? '') === 'link') {
            $active = ld_admin_menu_item_active($entry, $adminPage);
            $href = ld_admin_menu_item_href($entry);
            $ext = !empty($entry['external']);
            ?>
            <a href="<?= ld_h($href) ?>"
               class="<?= $active ? 'active' : '' ?>"
               <?= $ext ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                <i class="fas fa-<?= ld_h($entry['icon'] ?? 'circle') ?>"></i>
                <?= ld_h(ld_admin_menu_item_label($entry, $ta)) ?>
            </a>
            <?php
            continue;
        }

        if (($entry['type'] ?? '') !== 'group') {
            continue;
        }

        $open = ld_admin_menu_group_has_active($entry, $adminPage);
        ?>
        <details class="adm-nav-group<?= $open ? ' is-active' : '' ?>"<?= $open ? ' open' : '' ?>>
            <summary class="adm-nav-group-toggle">
                <span class="adm-nav-group-label">
                    <i class="fas fa-<?= ld_h($entry['icon'] ?? 'folder') ?>"></i>
                    <?= ld_h(ld_admin_menu_item_label(['label_key' => $entry['label_key']], $ta)) ?>
                </span>
                <i class="fas fa-chevron-down adm-nav-group-chevron" aria-hidden="true"></i>
            </summary>
            <div class="adm-nav-sub">
                <?php foreach ($entry['items'] as $item):
                    $active = ld_admin_menu_item_active($item, $adminPage);
                    $href = ld_admin_menu_item_href($item);
                    $badge = 0;
                    if (($item['badge'] ?? '') === 'leads') {
                        $badge = ld_admin_menu_leads_badge();
                    }
                    ?>
                <a href="<?= ld_h($href) ?>" class="<?= $active ? 'active' : '' ?>">
                    <i class="fas fa-<?= ld_h($item['icon'] ?? 'circle') ?>"></i>
                    <span><?= ld_h(ld_admin_menu_item_label($item, $ta)) ?></span>
                    <?php if ($badge > 0): ?>
                    <span class="adm-nav-badge"><?= (int) $badge ?></span>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </details>
        <?php
    }
}