<?php
declare(strict_types=1);

function ld_ai(): array
{
    return ld_settings()['ai'] ?? ld_default_settings()['ai'];
}

function ld_ai_enabled(): bool
{
    return !empty(ld_ai()['enabled']);
}

function ld_ai_fill_enabled(): bool
{
    $ai = ld_ai();
    return !empty($ai['fill_enabled']) || !empty($ai['enabled']);
}

function ld_ai_welcome(string $lang): string
{
    $ai = ld_ai();
    return ld_pick($ai['welcome'] ?? [], $lang) ?: 'Hi!';
}

function ld_ai_build_context(string $lang): string
{
    $business = ld_business();
    $services = ld_services($lang);
    $lines = [];
    $lines[] = 'Business: ' . ld_pick($business['name'], $lang);
    $lines[] = 'Tagline: ' . ld_pick($business['tagline'], $lang);
    $lines[] = 'Phone: ' . ($business['phone'] ?? '');
    $lines[] = 'Email: ' . ($business['email'] ?? '');
    $lines[] = 'Address: ' . ld_pick($business['address'], $lang);
    $lines[] = 'Hours: ' . ld_pick($business['hours'], $lang);
    foreach ($services as $s) {
        $price = ($s['price'] ?? '') !== '' ? ' — from ' . $s['price'] . ' ' . ld_currency() : '';
        $lines[] = 'Service: ' . ($s['name'] ?? '') . $price;
    }
    return implode("\n", $lines);
}

function ld_ai_chat(string $message, string $lang): array
{
    $ai = ld_ai();
    $message = trim($message);
    if ($message === '') {
        return ['ok' => false, 'text' => '', 'demo' => true, 'error' => 'empty'];
    }

    $business = ld_business();
    $system = str_replace(
        ['{business_name}', '{city}', '{lang}'],
        [ld_pick($business['name'], $lang), ld_pick($business['city'], $lang), $lang],
        (string) ($ai['system_prompt'] ?? 'You are a helpful business assistant.')
    );
    $system .= "\n\nBusiness data:\n" . ld_ai_build_context($lang);

    if (empty($ai['enabled']) || trim((string) ($ai['api_key'] ?? '')) === '') {
        return [
            'ok' => true,
            'text' => ld_ai_fallback_reply($message, $lang),
            'demo' => true,
            'error' => '',
        ];
    }

    $result = ld_ai_call_api($ai, $system, $message, 500, false);
    if (!$result['ok']) {
        return ['ok' => true, 'text' => ld_ai_fallback_reply($message, $lang), 'demo' => true, 'error' => $result['error']];
    }

    return ['ok' => true, 'text' => $result['text'], 'demo' => false, 'error' => ''];
}

function ld_ai_fallback_reply(string $message, string $lang): string
{
    $phone = ld_business()['phone'] ?? '';
    $replies = [
        'lt' => "Ačiū už klausimą! Skambinkite {$phone} arba užpildykite formą — konsultantas atsakys netrukus.",
        'uk' => "Дякуємо за запит! Телефонуйте {$phone} або заповніть форму — ми зв'яжемося з вами.",
        'en' => "Thanks for your question! Call {$phone} or use the contact form — we will reply soon.",
    ];
    return $replies[$lang] ?? $replies['en'];
}