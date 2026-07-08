<?php
declare(strict_types=1);

function ld_ai_providers(): array
{
    return [
        'openai' => [
            'label' => 'OpenAI',
            'api_base' => 'https://api.openai.com/v1',
            'models' => ['gpt-4o-mini', 'gpt-4o', 'gpt-4.1-mini', 'gpt-4.1'],
            'admin_models' => ['gpt-4o', 'gpt-4.1', 'gpt-4o-mini'],
        ],
        'grok' => [
            'label' => 'xAI Grok',
            'api_base' => 'https://api.x.ai/v1',
            'models' => ['grok-3-mini', 'grok-3', 'grok-2-latest'],
            'admin_models' => ['grok-3', 'grok-3-mini'],
        ],
        'compatible' => [
            'label' => 'OpenAI-compatible',
            'api_base' => 'https://api.openai.com/v1',
            'models' => [],
            'admin_models' => [],
        ],
    ];
}

function ld_ai_resolve_config(array $ai, bool $admin = false): array
{
    $providers = ld_ai_providers();
    $provider = (string) ($ai['provider'] ?? 'openai');
    if (!isset($providers[$provider])) {
        $provider = 'openai';
    }
    $preset = $providers[$provider];
    $apiBase = rtrim(trim((string) ($ai['api_base'] ?? '')), '/');
    if ($apiBase === '') {
        $apiBase = rtrim($preset['api_base'], '/');
    }
    $modelKey = $admin ? 'admin_model' : 'model';
    $model = trim((string) ($ai[$modelKey] ?? ''));
    if ($model === '') {
        $list = $admin ? ($preset['admin_models'] ?? $preset['models']) : $preset['models'];
        $model = $list[0] ?? 'gpt-4o-mini';
    }
    return ['provider' => $provider, 'api_base' => $apiBase, 'model' => $model];
}

function ld_ai_call_api(array $ai, string $system, string $user, int $maxTokens = 4000, bool $admin = false): array
{
    $apiKey = trim((string) ($ai['api_key'] ?? ''));
    if ($apiKey === '') {
        return ['ok' => false, 'text' => '', 'error' => 'no_api_key'];
    }

    $resolved = ld_ai_resolve_config($ai, $admin);
    $payload = json_encode([
        'model' => $resolved['model'],
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $user],
        ],
        'temperature' => $admin ? 0.35 : 0.5,
        'max_tokens' => max(256, min(8000, $maxTokens)),
        'response_format' => $admin ? ['type' => 'json_object'] : null,
    ], JSON_UNESCAPED_UNICODE);

    if ($payload === false) {
        return ['ok' => false, 'text' => '', 'error' => 'json_encode'];
    }

    $payload = str_replace('"response_format":null,', '', $payload);
    $endpoint = $resolved['api_base'] . '/chat/completions';

    if (!function_exists('curl_init')) {
        return ['ok' => false, 'text' => '', 'error' => 'no_curl'];
    }

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 90,
    ]);
    $raw = curl_exec($ch);
    $http = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($raw === false || $http < 200 || $http >= 300) {
        return ['ok' => false, 'text' => '', 'error' => 'api_http_' . $http];
    }

    $data = json_decode((string) $raw, true);
    $text = trim((string) ($data['choices'][0]['message']['content'] ?? ''));
    if ($text === '') {
        return ['ok' => false, 'text' => '', 'error' => 'empty_response'];
    }

    return ['ok' => true, 'text' => $text, 'error' => ''];
}