<?php
declare(strict_types=1);

function ld_version(): string
{
    $path = dirname(__DIR__) . '/VERSION';
    if (!is_readable($path)) {
        return '0.0.0';
    }

    return trim((string) file_get_contents($path)) ?: '0.0.0';
}

/** @return list<array{version:string,date:string,items:list<string>,meta:list<array{label:string,text:string}>}> */
function ld_changelog_entries(): array
{
    $path = dirname(__DIR__) . '/CHANGELOG.md';
    if (!is_readable($path)) {
        return [];
    }

    $entries = [];
    $current = null;

    foreach (explode("\n", (string) file_get_contents($path)) as $line) {
        $line = rtrim($line);
        if (preg_match('/^##\s+(.+?)\s+[—–-]\s+(.+)$/u', $line, $m)) {
            if ($current !== null) {
                $entries[] = $current;
            }
            $current = [
                'version' => trim($m[1]),
                'date' => trim($m[2]),
                'items' => [],
                'meta' => [],
            ];
            continue;
        }
        if ($current === null) {
            continue;
        }
        if ($line === '') {
            continue;
        }
        if (preg_match('/^\*\*(.+?):\*\*\s*(.+)$/', $line, $m)) {
            $current['meta'][] = ['label' => trim($m[1]), 'text' => trim($m[2])];
            continue;
        }
        if (preg_match('/^-\s+(.+)$/', $line, $m)) {
            $current['items'][] = trim($m[1]);
        }
    }

    if ($current !== null) {
        $entries[] = $current;
    }

    return $entries;
}

function ld_changelog_format_line(string $line): string
{
    $safe = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
    $safe = (string) preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $safe);
    $safe = (string) preg_replace(
        '#(https?://[^\s<]+)#',
        '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
        $safe
    );

    return $safe;
}