<?php
declare(strict_types=1);

function ld_recaptcha(): array
{
    return ld_settings()['recaptcha'] ?? ld_default_settings()['recaptcha'];
}

function ld_recaptcha_enabled(): bool
{
    $rc = ld_recaptcha();
    if (empty($rc['enabled'])) {
        return false;
    }
    $site = trim((string) ($rc['site_key'] ?? ''));
    $secret = trim((string) ($rc['secret_key'] ?? ''));
    if ($site !== '' && $secret !== '') {
        return true;
    }
    $cmsPath = dirname(__DIR__, 2) . '/includes/cms-contact.php';
    if (is_file($cmsPath)) {
        require_once $cmsPath;
        return cms_recaptcha_site_key() !== '';
    }
    return false;
}

function ld_recaptcha_site_key(): string
{
    $rc = ld_recaptcha();
    $key = trim((string) ($rc['site_key'] ?? ''));
    if ($key !== '') {
        return $key;
    }
    $cmsPath = dirname(__DIR__, 2) . '/includes/cms-contact.php';
    if (is_file($cmsPath)) {
        require_once $cmsPath;
        return cms_recaptcha_site_key();
    }
    return '';
}

function ld_recaptcha_secret_key(): string
{
    $rc = ld_recaptcha();
    $key = trim((string) ($rc['secret_key'] ?? ''));
    if ($key !== '') {
        return $key;
    }
    $cmsPath = dirname(__DIR__, 2) . '/includes/cms-contact.php';
    if (is_file($cmsPath)) {
        require_once $cmsPath;
        return cms_recaptcha_secret_key();
    }
    return '';
}

function ld_verify_recaptcha(?string $response): bool
{
    if (!ld_recaptcha_enabled()) {
        return true;
    }
    $rc = ld_recaptcha();
    if (trim((string) ($rc['site_key'] ?? '')) === '' || trim((string) ($rc['secret_key'] ?? '')) === '') {
        $cmsPath = dirname(__DIR__, 2) . '/includes/cms-contact.php';
        if (is_file($cmsPath)) {
            require_once $cmsPath;
            return cms_verify_recaptcha($response);
        }
    }

    $response = trim((string) $response);
    if ($response === '') {
        return false;
    }

    $payload = http_build_query([
        'secret' => ld_recaptcha_secret_key(),
        'response' => $response,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $ctx = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 12,
        ],
    ]);

    $raw = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $ctx);
    if ($raw === false) {
        return false;
    }
    $data = json_decode($raw, true);
    return !empty($data['success']);
}

function ld_recaptcha_widget(): void
{
    if (!ld_recaptcha_enabled()) {
        return;
    }
    $siteKey = ld_recaptcha_site_key();
    if ($siteKey === '') {
        return;
    }
    ?>
    <div class="ld-recaptcha g-recaptcha" data-sitekey="<?= ld_h($siteKey) ?>"></div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php
}