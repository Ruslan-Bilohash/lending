<?php
declare(strict_types=1);

require_once __DIR__ . '/admin-seo-checklist.php';

/**
 * @return list<array{key: string, done: bool, label: string, missing: list<string>, link: string, anchor: string, target: string, group: string}>
 */
function ld_admin_setup_checklist_items(): array
{
    global $ta;
    $settings = ld_settings();
    $ai = $settings['ai'] ?? [];
    $rc = $settings['recaptcha'] ?? [];
    $notify = $settings['notifications'] ?? [];
    $blocks = $settings['blocks'] ?? [];

    $t = static fn(string $k, string $fb = ''): string => (string) ($ta[$k] ?? $fb);
    $aiUrl = ld_admin_url('ai.php');
    $integrationsUrl = ld_admin_url('integrations.php');
    $blocksUrl = ld_admin_url('blocks.php');

    $apiKey = trim((string) ($ai['api_key'] ?? ''));
    $apiOk = $apiKey !== '';
    $apiMissing = $apiOk ? [] : [$t('tasks_missing_ai_api')];

    $chatOk = !empty($ai['enabled']);
    $chatMissing = $chatOk ? [] : [$t('tasks_missing_ai_chat')];

    $rcEnabled = !empty($rc['enabled']);
    $siteKey = trim((string) ($rc['site_key'] ?? ''));
    $secretKey = trim((string) ($rc['secret_key'] ?? ''));
    $rcOk = $rcEnabled && $siteKey !== '' && $secretKey !== '';
    $rcMissing = [];
    if (!$rcEnabled) {
        $rcMissing[] = $t('tasks_missing_recaptcha_enable');
    } else {
        if ($siteKey === '') {
            $rcMissing[] = $t('tasks_missing_recaptcha_site');
        }
        if ($secretKey === '') {
            $rcMissing[] = $t('tasks_missing_recaptcha_secret');
        }
    }

    $leadsEmail = trim((string) ($notify['leads_email'] ?? ''));
    $bizEmail = trim((string) (($settings['business'] ?? [])['email'] ?? ''));
    $leadsOk = $leadsEmail !== '' || $bizEmail !== '';
    $leadsMissing = $leadsOk ? [] : [$t('tasks_missing_leads_email')];

    $heroImage = trim((string) ($blocks['hero_image'] ?? ''));
    $ogImage = trim((string) (($settings['seo'] ?? [])['og_image'] ?? ''));
    $heroOk = $heroImage !== '' || $ogImage !== '';
    $heroMissing = [];
    if ($heroImage === '') {
        $heroMissing[] = $t('tasks_missing_hero_image');
    }
    if ($ogImage === '') {
        $heroMissing[] = $t('tasks_missing_og_image');
    }

    return [
        [
            'key' => 'ai_api',
            'done' => $apiOk,
            'label' => $t('tasks_item_ai_api'),
            'missing' => $apiMissing,
            'link' => $aiUrl,
            'anchor' => '',
            'target' => 'ai',
            'group' => 'setup',
        ],
        [
            'key' => 'ai_chat',
            'done' => $chatOk,
            'label' => $t('tasks_item_ai_chat'),
            'missing' => $chatMissing,
            'link' => $aiUrl,
            'anchor' => '',
            'target' => 'ai',
            'group' => 'setup',
        ],
        [
            'key' => 'recaptcha',
            'done' => $rcOk,
            'label' => $t('tasks_item_recaptcha'),
            'missing' => $rcMissing,
            'link' => $integrationsUrl,
            'anchor' => '',
            'target' => 'integrations',
            'group' => 'setup',
        ],
        [
            'key' => 'leads_email',
            'done' => $leadsOk,
            'label' => $t('tasks_item_leads_email'),
            'missing' => $leadsMissing,
            'link' => $integrationsUrl,
            'anchor' => '',
            'target' => 'integrations',
            'group' => 'setup',
        ],
        [
            'key' => 'hero_image',
            'done' => $heroOk,
            'label' => $t('tasks_item_visual'),
            'missing' => $heroMissing,
            'link' => $heroImage === '' ? $blocksUrl : ld_admin_url('seo.php'),
            'anchor' => $heroImage === '' ? '#adm-hero-image' : '',
            'target' => $heroImage === '' ? 'blocks' : 'seo',
            'group' => 'setup',
        ],
    ];
}

/**
 * @return list<array{key: string, done: bool, label: string, missing: list<string>, link: string, anchor: string, target: string, group: string}>
 */
function ld_admin_master_checklist_items(): array
{
    $items = [];
    foreach (ld_admin_content_checklist_items() as $item) {
        $item['group'] = 'content';
        $item['target'] = ($item['key'] ?? '') === 'seo' ? 'seo' : 'content';
        $items[] = $item;
    }
    foreach (ld_admin_seo_checklist_items() as $item) {
        $item['group'] = 'seo';
        $items[] = $item;
    }
    foreach (ld_admin_setup_checklist_items() as $item) {
        $items[] = $item;
    }

    return $items;
}

/**
 * @return array{content: list<array>, seo: list<array>, setup: list<array>}
 */
function ld_admin_master_checklist_pending_by_group(array $items): array
{
    $pending = ld_admin_checklist_pending($items);
    $groups = ['content' => [], 'seo' => [], 'setup' => []];
    foreach ($pending as $item) {
        $g = (string) ($item['group'] ?? 'content');
        if (!isset($groups[$g])) {
            $groups[$g] = [];
        }
        $groups[$g][] = $item;
    }

    return $groups;
}