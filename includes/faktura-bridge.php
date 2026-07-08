<?php
declare(strict_types=1);

function ld_faktura_root(): string
{
    return dirname(__DIR__, 2) . '/faktura';
}

function ld_faktura_available(): bool
{
    return is_file(ld_faktura_root() . '/includes/storage.php');
}

function ld_faktura_bootstrap(): bool
{
    static $ready = false;
    if ($ready) {
        return true;
    }
    $root = ld_faktura_root();
    if (!is_file($root . '/includes/storage.php')) {
        return false;
    }
    require_once $root . '/includes/storage.php';
    require_once $root . '/includes/helpers.php';
    require_once $root . '/includes/print-designs.php';
    $ready = true;
    return true;
}

function ld_faktura_integration(): array
{
    return ld_settings()['integrations']['faktura'] ?? ld_default_settings()['integrations']['faktura'];
}

function ld_service_price_raw(string $serviceName, string $lang): ?float
{
    foreach (ld_settings()['services'] ?? [] as $row) {
        $name = is_array($row['name'] ?? null) ? ld_pick($row['name'], $lang) : (string) ($row['name'] ?? '');
        if ($name === $serviceName) {
            $price = preg_replace('/[^\d.,]/', '', (string) ($row['price'] ?? ''));
            $price = str_replace([' ', ','], ['', '.'], $price);
            return $price !== '' ? (float) $price : null;
        }
    }
    return null;
}

function ld_faktura_base_url(): string
{
    return 'https://bilohash.com/faktura';
}

function ld_faktura_support_url(): string
{
    global $lang;
    $url = ld_faktura_base_url() . '/admin/support.php';
    if (($lang ?? 'lt') !== 'en') {
        $url .= '?lang=' . rawurlencode($lang);
    }

    return $url;
}

function ld_faktura_invoice_url(string $id): string
{
    return ld_faktura_base_url() . '/invoice.php?id=' . rawurlencode($id);
}

function ld_faktura_lending_invoices(): array
{
    if (!ld_faktura_bootstrap()) {
        return [];
    }
    $list = fk_load_invoices();
    return array_values(array_filter($list, static fn(array $row): bool => ($row['source'] ?? '') === 'lending'));
}

/**
 * @param array{buyer_name?:string,buyer_phone?:string,buyer_email?:string,service?:string,price?:float|int|string,lead_id?:string,notes?:string} $opts
 */
function ld_create_faktura_invoice(array $opts, string $lang = 'lt'): ?array
{
    if (!ld_faktura_bootstrap()) {
        return null;
    }

    $integration = ld_faktura_integration();
    if (empty($integration['enabled'])) {
        return null;
    }

    $settings = fk_load_settings();
    $countryId = (string) ($integration['country_id'] ?? 'lt');
    $country = fk_country_by_id($settings, $countryId);
    if ($country === null) {
        return null;
    }

    $business = ld_business();
    $serviceName = trim((string) ($opts['service'] ?? ''));
    $priceRaw = $opts['price'] ?? null;
    if ($priceRaw === null || $priceRaw === '') {
        $price = ld_service_price_raw($serviceName, $lang) ?? 0.0;
    } else {
        $price = (float) str_replace([',', ' '], ['.', ''], (string) $priceRaw);
    }

    $prefix = (string) ($settings['invoice_prefix'] ?? 'INV-');
    $nextNo = (int) ($settings['next_number'] ?? 1001);
    $invoiceNo = $prefix . $nextNo;

    $company = fk_company_for_country($settings, $countryId);
    $seller = [
        'name' => (string) ($company['name'] ?? ld_pick($business['name'], $lang)),
        'org_nr' => (string) ($company['org_nr'] ?? ''),
        'vat_nr' => (string) ($company['vat_nr'] ?? ''),
        'address' => (string) ($company['address'] ?? ld_pick($business['address'], $lang)),
        'city' => (string) ($company['city'] ?? ld_pick($business['city'], $lang)),
        'email' => (string) ($company['email'] ?? $business['email'] ?? ''),
        'phone' => (string) ($company['phone'] ?? $business['phone'] ?? ''),
    ];

    $qty = max(1, (int) ($opts['qty'] ?? 1));
    $unit = trim((string) ($opts['unit'] ?? '')) ?: 'pcs';
    $lines = [[
        'desc' => $serviceName !== '' ? $serviceName : 'Service',
        'qty' => $qty,
        'unit' => $unit,
        'price' => $price,
        'pay_url' => '',
    ]];

    $totals = fk_calculate_totals($lines, $country, $lang);
    $today = date('Y-m-d');
    $due = date('Y-m-d', strtotime('+14 days'));
    $leadId = trim((string) ($opts['lead_id'] ?? ''));
    $studentId = trim((string) ($opts['student_id'] ?? ''));
    $notes = trim((string) ($opts['notes'] ?? ''));
    if ($notes === '' && $leadId !== '') {
        $notes = 'Lending CMS lead #' . $leadId;
    } elseif ($notes === '' && $studentId !== '') {
        $notes = 'Lending CMS student #' . $studentId;
    }

    $record = [
        'invoice_no' => $invoiceNo,
        'invoice_date' => $today,
        'due_date' => $due,
        'country_id' => $countryId,
        'currency' => $country['currency'] ?? 'EUR',
        'seller' => $seller,
        'buyer' => [
            'name' => trim((string) ($opts['buyer_name'] ?? 'Customer')) ?: 'Customer',
            'email' => trim((string) ($opts['buyer_email'] ?? '')),
            'phone' => trim((string) ($opts['buyer_phone'] ?? '')),
            'address' => trim((string) ($opts['buyer_address'] ?? '')),
        ],
        'lines' => $lines,
        'totals' => $totals,
        'notes' => $notes,
        'payment_purpose' => $invoiceNo,
        'lang' => $lang,
        'print_design' => fk_normalize_print_design((string) ($integration['print_design'] ?? 'classic-blue')),
        'print_format' => fk_normalize_print_format((string) ($integration['print_format'] ?? 'a4')),
        'source' => 'lending',
        'lead_id' => $leadId,
        'student_id' => $studentId,
    ];

    $saved = fk_add_invoice($record);
    $settings['next_number'] = $nextNo + 1;
    fk_save_settings($settings);

    $id = (string) ($saved['id'] ?? '');

    return [
        'id' => $id,
        'invoice_no' => $invoiceNo,
        'view_url' => ld_faktura_invoice_url($id),
        'total' => $totals['total'] ?? 0,
        'currency' => $country['currency'] ?? 'EUR',
    ];
}

function ld_lead_to_faktura_invoice(array $lead, string $lang = 'lt', ?float $priceOverride = null): ?array
{
    $serviceName = (string) ($lead['service'] ?? $lead['course'] ?? '');
    $opts = [
        'buyer_name' => (string) ($lead['name'] ?? 'Customer'),
        'buyer_phone' => (string) ($lead['phone'] ?? ''),
        'buyer_email' => (string) ($lead['email'] ?? ''),
        'service' => $serviceName,
        'lead_id' => (string) ($lead['id'] ?? ''),
    ];
    if ($priceOverride !== null) {
        $opts['price'] = $priceOverride;
    }

    return ld_create_faktura_invoice($opts, $lang);
}