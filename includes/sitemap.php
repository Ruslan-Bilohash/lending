<?php
declare(strict_types=1);

/** @return list<array{loc:string,lastmod:string,priority:string,changefreq:string,alternates:array<string,string>}> */
function ld_sitemap_entries(): array
{
    $langs = ld_langs_codes();
    $lastmod = date('Y-m-d');
    $groups = [];

    $staticRoutes = [
        ['path' => 'index.php', 'priority' => '0.7', 'changefreq' => 'weekly', 'qs' => []],
        ['path' => 'template.php', 'priority' => '1.0', 'changefreq' => 'weekly', 'qs' => ['t' => (string) ld_active_template()]],
    ];

    foreach ($staticRoutes as $route) {
        $key = $route['path'] . '?' . http_build_query($route['qs']);
        $alternates = [];
        foreach ($langs as $code) {
            $qs = $route['qs'];
            if ($code !== 'no') {
                $qs['lang'] = $code;
            } else {
                unset($qs['lang']);
            }
            $alternates[$code] = ld_absolute_url($route['path'], $qs);
        }
        $groups[$key] = [
            'loc' => $alternates['no'] ?? reset($alternates),
            'lastmod' => $lastmod,
            'priority' => $route['priority'],
            'changefreq' => $route['changefreq'],
            'alternates' => $alternates,
        ];
    }

    foreach (ld_pages_published() as $page) {
        $slug = (string) ($page['slug'] ?? '');
        if ($slug === '') {
            continue;
        }
        $key = 'page:' . $slug;
        $alternates = [];
        foreach ($langs as $code) {
            $alternates[$code] = ld_absolute_url('page.php', array_filter([
                'slug' => $slug,
                'lang' => $code !== 'no' ? $code : null,
            ]));
        }
        $groups[$key] = [
            'loc' => $alternates['no'] ?? reset($alternates),
            'lastmod' => substr((string) ($page['updated_at'] ?? $lastmod), 0, 10) ?: $lastmod,
            'priority' => ($slug === 'privacy') ? '0.5' : '0.6',
            'changefreq' => 'monthly',
            'alternates' => $alternates,
        ];
    }

    return array_values($groups);
}

function ld_render_sitemap_xml(): string
{
    $entries = ld_sitemap_entries();
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
    $xml .= '        xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

    foreach ($entries as $entry) {
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1) . "</loc>\n";
        $xml .= '    <lastmod>' . htmlspecialchars($entry['lastmod'], ENT_XML1) . "</lastmod>\n";
        $xml .= '    <changefreq>' . htmlspecialchars($entry['changefreq'], ENT_XML1) . "</changefreq>\n";
        $xml .= '    <priority>' . htmlspecialchars($entry['priority'], ENT_XML1) . "</priority>\n";
        foreach ($entry['alternates'] as $code => $href) {
            $xml .= '    <xhtml:link rel="alternate" hreflang="' . htmlspecialchars($code, ENT_XML1) . '" href="' . htmlspecialchars($href, ENT_XML1) . "\" />\n";
        }
        $xml .= "  </url>\n";
    }

    $xml .= "</urlset>\n";
    return $xml;
}