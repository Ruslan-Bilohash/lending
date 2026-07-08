<?php
declare(strict_types=1);

function ld_news_file(): string
{
    return ld_data_path('news.json');
}

function ld_load_news(): array
{
    return ld_load_json(ld_news_file(), []);
}

function ld_save_news(array $items): bool
{
    return ld_save_json(ld_news_file(), $items);
}

function ld_get_news(string $id): ?array
{
    foreach (ld_load_news() as $row) {
        if (($row['id'] ?? '') === $id) {
            return $row;
        }
    }
    return null;
}

function ld_news_published(string $lang, int $limit = 0): array
{
    $items = array_values(array_filter(ld_load_news(), static fn(array $n): bool => ($n['status'] ?? '') === 'published'));
    usort($items, static fn(array $a, array $b): int => strcmp((string) ($b['published_at'] ?? ''), (string) ($a['published_at'] ?? '')));
    if ($limit > 0) {
        $items = array_slice($items, 0, $limit);
    }
    return $items;
}

function ld_news_localize(array $row, string $lang): array
{
    foreach (['title', 'excerpt', 'body', 'seo_title', 'seo_description', 'seo_keywords'] as $key) {
        if (isset($row[$key]) && is_array($row[$key])) {
            $row[$key] = ld_pick($row[$key], $lang);
        }
    }
    return $row;
}

/**
 * @return list<string>
 */
function ld_news_normalize_images(array $input, ?array $existing = null): array
{
    $urls = [];
    if (isset($input['images_json'])) {
        $decoded = json_decode((string) $input['images_json'], true);
        if (is_array($decoded)) {
            foreach ($decoded as $u) {
                $u = trim((string) $u);
                if ($u !== '' && filter_var($u, FILTER_VALIDATE_URL)) {
                    $urls[] = $u;
                }
            }
        }
    }
    if ($urls === [] && isset($input['images']) && is_array($input['images'])) {
        foreach ($input['images'] as $u) {
            $u = trim((string) $u);
            if ($u !== '' && filter_var($u, FILTER_VALIDATE_URL)) {
                $urls[] = $u;
            }
        }
    }
    if ($urls === []) {
        $single = trim((string) ($input['image'] ?? ''));
        if ($single !== '' && filter_var($single, FILTER_VALIDATE_URL)) {
            $urls[] = $single;
        }
    }
    if ($urls === [] && is_array($existing)) {
        if (!empty($existing['images']) && is_array($existing['images'])) {
            foreach ($existing['images'] as $u) {
                $u = trim((string) $u);
                if ($u !== '') {
                    $urls[] = $u;
                }
            }
        }
        if ($urls === []) {
            $legacy = trim((string) ($existing['image'] ?? ''));
            if ($legacy !== '') {
                $urls[] = $legacy;
            }
        }
    }

    return array_values(array_unique($urls));
}

function ld_news_slugify(string $text): string
{
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/u', '', $text) ?? '';
    $text = preg_replace('/[\s-]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'news';
}

function ld_news_upsert(array $input): array
{
    $items = ld_load_news();
    $id = trim((string) ($input['id'] ?? ''));
    $isNew = $id === '';
    if ($isNew) {
        $id = 'news-' . date('Ymd') . '-' . substr(bin2hex(random_bytes(3)), 0, 6);
    }

    $titleLt = trim((string) ($input['title_lt'] ?? ''));
    $slug = trim((string) ($input['slug'] ?? ''));
    if ($slug === '' && $titleLt !== '') {
        $slug = ld_news_slugify($titleLt);
    }

    $existing = null;
    if (!$isNew) {
        foreach ($items as $existingRow) {
            if (($existingRow['id'] ?? '') === $id) {
                $existing = $existingRow;
                break;
            }
        }
    }
    $images = ld_news_normalize_images($input, $existing);

    $row = [
        'id' => $id,
        'slug' => $slug,
        'status' => in_array(($input['status'] ?? 'draft'), ['draft', 'published'], true) ? $input['status'] : 'draft',
        'published_at' => trim((string) ($input['published_at'] ?? date('Y-m-d'))),
        'images' => $images,
        'image' => $images[0] ?? '',
        'title' => ld_news_i18n_from_post($input, 'title'),
        'excerpt' => ld_news_i18n_from_post($input, 'excerpt'),
        'body' => ld_news_i18n_from_post($input, 'body'),
        'seo_title' => ld_news_i18n_from_post($input, 'seo_title'),
        'seo_description' => ld_news_i18n_from_post($input, 'seo_description'),
        'seo_keywords' => ld_news_i18n_from_post($input, 'seo_keywords'),
        'seo_score' => (int) ($input['seo_score'] ?? 0),
        'updated_at' => date('c'),
    ];

    if ($isNew) {
        $row['created_at'] = date('c');
        array_unshift($items, $row);
    } else {
        $found = false;
        foreach ($items as $i => $existing) {
            if (($existing['id'] ?? '') === $id) {
                $row['created_at'] = $existing['created_at'] ?? date('c');
                $items[$i] = $row;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $row['created_at'] = date('c');
            array_unshift($items, $row);
        }
    }

    ld_save_news($items);
    return ['ok' => true, 'id' => $id];
}

/** @return array<string, string> */
function ld_news_i18n_from_post(array $input, string $prefix): array
{
    $out = [];
    foreach (ld_langs_codes() as $code) {
        $key = $prefix . '_' . $code;
        if (isset($input[$key])) {
            $out[$code] = trim((string) $input[$key]);
        }
    }
    return $out;
}

function ld_news_delete(string $id): bool
{
    $items = ld_load_news();
    $before = count($items);
    $items = array_values(array_filter($items, static fn(array $n): bool => ($n['id'] ?? '') !== $id));
    return $before !== count($items) && ld_save_news($items);
}

function ld_ensure_news(): void
{
    if (!is_file(ld_news_file())) {
        ld_save_json(ld_news_file(), []);
    }
}