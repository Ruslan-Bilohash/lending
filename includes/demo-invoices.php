<?php
declare(strict_types=1);

function ld_demo_invoices_file(): string
{
    return ld_data_path('invoices.json');
}

function ld_load_demo_invoices(): array
{
    return ld_load_json(ld_demo_invoices_file(), []);
}

function ld_save_demo_invoices(array $rows): bool
{
    return ld_save_json(ld_demo_invoices_file(), $rows);
}

function ld_all_invoices_list(): array
{
    $demo = ld_load_demo_invoices();
    $faktura = ld_faktura_lending_invoices();
    $leads = ld_load_leads();
    $out = [];

    foreach ($demo as $row) {
        $src = (string) ($row['source'] ?? 'demo');
        $pdf = (string) ($row['pdf_url'] ?? '');
        if ($pdf === '' && $src === 'student') {
            $pdf = ld_admin_url('invoice-print.php', ['id' => (string) ($row['id'] ?? '')]);
        }
        $out[] = array_merge($row, [
            'source' => $src,
            'pdf_url' => $pdf !== '' ? $pdf : '#',
        ]);
    }

    foreach ($faktura as $inv) {
        $id = (string) ($inv['id'] ?? '');
        if ($id === '') {
            continue;
        }
        $out[] = [
            'id' => $id,
            'invoice_no' => (string) ($inv['invoice_no'] ?? ''),
            'buyer_name' => (string) ($inv['buyer']['name'] ?? '—'),
            'service' => (string) ($inv['items'][0]['name'] ?? ''),
            'amount' => (float) ($inv['totals']['total'] ?? 0),
            'currency' => (string) ($inv['currency'] ?? 'EUR'),
            'status' => (string) ($inv['status'] ?? 'issued'),
            'created_at' => substr((string) ($inv['created_at'] ?? $inv['invoice_date'] ?? ''), 0, 10),
            'pdf_url' => ld_faktura_invoice_url($id),
            'source' => 'faktura',
        ];
    }

    foreach ($leads as $lead) {
        if (empty($lead['invoice_url'])) {
            continue;
        }
        $invId = (string) ($lead['invoice_id'] ?? '');
        $out[] = [
            'id' => $invId !== '' ? $invId : ('lead-' . ($lead['id'] ?? '')),
            'invoice_no' => (string) ($lead['invoice_no'] ?? '—'),
            'buyer_name' => (string) ($lead['name'] ?? ''),
            'service' => (string) ($lead['service'] ?? $lead['course'] ?? ''),
            'amount' => 0,
            'currency' => 'EUR',
            'status' => 'issued',
            'created_at' => substr((string) ($lead['created_at'] ?? ''), 0, 10),
            'pdf_url' => (string) ($lead['invoice_url'] ?? ''),
            'source' => 'lead',
        ];
    }

    usort($out, static fn(array $a, array $b): int => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? '')));
    return $out;
}

function ld_ensure_demo_invoices(): void
{
    if (!is_file(ld_demo_invoices_file())) {
        ld_save_json(ld_demo_invoices_file(), []);
    }
}