<?php
declare(strict_types=1);

function ld_design(): array
{
    return ld_settings()['design'] ?? ld_default_design();
}

function ld_default_design(): array
{
    return [
        'accent' => '',
        'button_style' => 'rounded',
        'font_scale' => '100',
        'hero_style' => 'default',
        'sections' => [
            'stats' => true,
            'services' => true,
            'features' => true,
            'gallery' => true,
            'video' => false,
            'partners' => false,
            'promo' => true,
            'team' => true,
            'reviews' => true,
            'map' => true,
            'faq' => true,
            'contact' => true,
        ],
    ];
}

function ld_section_enabled(string $key): bool
{
    $sections = ld_design()['sections'] ?? [];
    return !isset($sections[$key]) || !empty($sections[$key]);
}

function ld_design_demo_presets(): array
{
    return [
        'clean-medical' => [
            'label' => ['lt' => 'Švarus medicinos', 'uk' => 'Чистий медичний', 'ru' => 'Чистый медицинский', 'en' => 'Clean medical'],
            'hint' => ['lt' => 'Stomatologija, klinikos — šviesus minimalus', 'uk' => 'Стоматологія, клініки — світлий мінімал', 'ru' => 'Стоматология, клиники — светлый минимал', 'en' => 'Dentistry, clinics — light minimal'],
            'template' => 5,
            'accent' => '#0d9488',
            'hero_style' => 'default',
            'button_style' => 'pill',
        ],
        'bold-auto' => [
            'label' => ['lt' => 'Ryškus auto', 'uk' => 'Яскравий авто', 'ru' => 'Яркий авто', 'en' => 'Bold automotive'],
            'hint' => ['lt' => 'Autoservisas, autoškola — raudonas', 'uk' => 'СТО, автошкола — червоний', 'ru' => 'СТО, автошкола — красный', 'en' => 'Auto — energetic red'],
            'template' => 6,
            'accent' => '#dc2626',
            'hero_style' => 'default',
            'button_style' => 'rounded',
        ],
        'premium-beauty' => [
            'label' => ['lt' => 'Premium grožis', 'uk' => 'Преміум краса', 'ru' => 'Премиум красота', 'en' => 'Premium beauty'],
            'hint' => ['lt' => 'Salonas, SPA — violetinis', 'uk' => 'Салон, SPA — фіолетовий', 'ru' => 'Салон, SPA — фиолетовый', 'en' => 'Salon, SPA — purple'],
            'template' => 7,
            'accent' => '#7c3aed',
            'hero_style' => 'default',
            'button_style' => 'pill',
        ],
        'corporate-trust' => [
            'label' => ['lt' => 'Korporatyvinis', 'uk' => 'Корпоративний', 'ru' => 'Корпоративный', 'en' => 'Corporate trust'],
            'hint' => ['lt' => 'Teisė, finansai — pilkas', 'uk' => 'Право, фінанси — сірий', 'ru' => 'Право, финансы — серый', 'en' => 'Law, finance — slate'],
            'template' => 10,
            'accent' => '#334155',
            'hero_style' => 'minimal',
            'button_style' => 'square',
        ],
        'sunset-hospitality' => [
            'label' => ['lt' => 'Svetingumas', 'uk' => 'Гостинність', 'ru' => 'Гостеприимство', 'en' => 'Hospitality'],
            'hint' => ['lt' => 'Restoranas — šiltas saulėlydis', 'uk' => 'Ресторан — теплий захід', 'ru' => 'Ресторан — тёплый закат', 'en' => 'Restaurant — warm sunset'],
            'template' => 2,
            'accent' => '#ea580c',
            'hero_style' => 'default',
            'button_style' => 'rounded',
        ],
        'urban-fitness' => [
            'label' => ['lt' => 'Miesto fitnesas', 'uk' => 'Міський фітнес', 'ru' => 'Городской фитнес', 'en' => 'Urban fitness'],
            'hint' => ['lt' => 'Sporto klubas — žalias', 'uk' => 'Фітнес — зелений акцент', 'ru' => 'Фитнес — зелёный акцент', 'en' => 'Gym — green accent'],
            'template' => 3,
            'accent' => '#16a34a',
            'hero_style' => 'default',
            'button_style' => 'rounded',
        ],
    ];
}

function ld_apply_design_preset(string $id): array
{
    $presets = ld_design_demo_presets();
    if (!isset($presets[$id])) {
        return ['ok' => false];
    }
    $p = $presets[$id];
    $settings = ld_settings();
    $settings['active_template'] = (int) ($p['template'] ?? 1);
    $settings['design'] = array_replace_recursive(ld_default_design(), $settings['design'] ?? [], [
        'accent' => $p['accent'] ?? '',
        'hero_style' => $p['hero_style'] ?? 'default',
        'button_style' => $p['button_style'] ?? 'rounded',
    ]);
    $ok = ld_save_settings($settings);
    return ['ok' => $ok, 'template' => $settings['active_template']];
}

function ld_design_inline_css(): string
{
    $d = ld_design();
    $css = '';
    $accent = trim((string) ($d['accent'] ?? ''));
    if ($accent !== '' && preg_match('/^#[0-9a-fA-F]{3,8}$/', $accent)) {
        $css .= ':root{--ld-primary:' . $accent . ';--ld-primary-hover:' . $accent . ';}';
    }
    $scale = max(90, min(115, (int) ($d['font_scale'] ?? 100)));
    if ($scale !== 100) {
        $css .= 'body.ld-landing{font-size:' . ($scale / 100 * 15) . 'px;}';
    }
    $btn = $d['button_style'] ?? 'rounded';
    if ($btn === 'pill') {
        $css .= '.ld-btn{border-radius:999px;}';
    } elseif ($btn === 'square') {
        $css .= '.ld-btn{border-radius:4px;}';
    }
    return $css;
}