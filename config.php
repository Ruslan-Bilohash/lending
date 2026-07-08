<?php
declare(strict_types=1);

define('LD_BASE_PATH', '/lending');
define('LD_DOMAIN', 'bilohash.com');
define('LD_SITE_NAME', 'Lending CMS');
define('LD_DEMO_MODE', true);

$detected = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_path = (strpos($host, LD_DOMAIN) !== false) ? LD_BASE_PATH : ($detected ?: LD_BASE_PATH);

$protocol = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
) ? 'https' : 'http';

$site_url   = rtrim($protocol . '://' . $host . $base_path, '/');
$cms_name   = LD_SITE_NAME;