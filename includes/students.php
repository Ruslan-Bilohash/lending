<?php
declare(strict_types=1);

function ld_students_file(): string
{
    return ld_data_path('students.json');
}

function ld_load_students(): array
{
    return ld_load_json(ld_students_file(), []);
}

function ld_save_students(array $rows): bool
{
    return ld_save_json(ld_students_file(), $rows);
}

function ld_ensure_students(): void
{
    if (!is_file(ld_students_file())) {
        ld_save_json(ld_students_file(), []);
    }
}

function ld_get_student(string $id): ?array
{
    foreach (ld_load_students() as $row) {
        if (($row['id'] ?? '') === $id) {
            return $row;
        }
    }
    return null;
}

function ld_student_upsert(array $input): array
{
    $items = ld_load_students();
    $id = trim((string) ($input['id'] ?? ''));
    $isNew = $id === '';
    if ($isNew) {
        $id = 'stu-' . date('Ymd') . '-' . substr(bin2hex(random_bytes(3)), 0, 6);
    }

    $row = [
        'id' => $id,
        'name' => trim((string) ($input['name'] ?? '')),
        'phone' => trim((string) ($input['phone'] ?? '')),
        'email' => trim((string) ($input['email'] ?? '')),
        'course' => trim((string) ($input['course'] ?? '')),
        'monthly_price' => round((float) str_replace([',', ' '], ['.', ''], (string) ($input['monthly_price'] ?? '0')), 2),
        'status' => in_array(($input['status'] ?? 'active'), ['active', 'paused', 'completed'], true) ? $input['status'] : 'active',
        'started_at' => trim((string) ($input['started_at'] ?? date('Y-m-d'))),
        'updated_at' => date('c'),
    ];

    if ($isNew) {
        $row['created_at'] = date('c');
        $row['last_invoice_no'] = '';
        $row['last_invoice_url'] = '';
        $row['last_invoiced_at'] = '';
        $row['last_billing_month'] = '';
        array_unshift($items, $row);
    } else {
        $found = false;
        foreach ($items as $i => $existing) {
            if (($existing['id'] ?? '') === $id) {
                foreach (['last_invoice_no', 'last_invoice_url', 'last_invoiced_at', 'last_billing_month', 'created_at'] as $k) {
                    if (isset($existing[$k])) {
                        $row[$k] = $existing[$k];
                    }
                }
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

    ld_save_students($items);
    return ['ok' => true, 'id' => $id];
}

function ld_student_delete(string $id): bool
{
    $items = ld_load_students();
    $before = count($items);
    $items = array_values(array_filter($items, static fn(array $s): bool => ($s['id'] ?? '') !== $id));
    return $before !== count($items) && ld_save_students($items);
}

function ld_update_student(string $id, array $patch): bool
{
    $items = ld_load_students();
    $found = false;
    foreach ($items as $i => $row) {
        if (($row['id'] ?? '') === $id) {
            $items[$i] = array_merge($row, $patch);
            $found = true;
            break;
        }
    }
    return $found && ld_save_students($items);
}

function ld_invoice_unit_month(string $lang): string
{
    return match ($lang) {
        'uk' => 'міс.',
        'ru' => 'мес.',
        'en' => 'mo',
        default => 'mėn.',
    };
}

function ld_student_billing_label(string $lang, string $ym): string
{
    try {
        $d = new DateTimeImmutable($ym . '-01');
    } catch (Throwable) {
        $d = new DateTimeImmutable('first day of this month');
    }
    $months = [
        'lt' => ['sausis', 'vasaris', 'kovas', 'balandis', 'gegužė', 'birželis', 'liepa', 'rugpjūtis', 'rugsėjis', 'spalis', 'lapkritis', 'gruodis'],
        'uk' => ['січень', 'лютий', 'березень', 'квітень', 'травень', 'червень', 'липень', 'серпень', 'вересень', 'жовтень', 'листопад', 'грудень'],
        'ru' => ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'],
        'en' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    ];
    $m = (int) $d->format('n') - 1;
    $name = $months[$lang][$m] ?? $months['en'][$m];
    $prefix = match ($lang) {
        'uk' => 'Щомісячний платіж',
        'ru' => 'Ежемесячный платёж',
        'en' => 'Monthly fee',
        default => 'Mėnesio mokestis',
    };
    return $prefix . ' · ' . $name . ' ' . $d->format('Y');
}

/** @return array{id:string,invoice_no:string,view_url:string,total:float,currency:string,demo:bool}|null */
function ld_student_monthly_invoice(string $studentId, string $lang, ?string $billingMonth = null): ?array
{
    $student = ld_get_student($studentId);
    if (!$student || trim((string) ($student['name'] ?? '')) === '') {
        return null;
    }

    $billingMonth = $billingMonth ?? date('Y-m');
    $label = ld_student_billing_label($lang, $billingMonth);
    $course = trim((string) ($student['course'] ?? ''));
    $service = ($course !== '' ? $course . ' — ' : '') . $label;
    $price = (float) ($student['monthly_price'] ?? 0);

    $integration = ld_faktura_integration();
    $fakturaOn = ld_faktura_available() && !empty($integration['enabled']);

    if ($fakturaOn) {
        $inv = ld_create_faktura_invoice([
            'buyer_name' => (string) $student['name'],
            'buyer_phone' => (string) ($student['phone'] ?? ''),
            'buyer_email' => (string) ($student['email'] ?? ''),
            'service' => $service,
            'price' => $price,
            'unit' => ld_invoice_unit_month($lang),
            'qty' => 1,
            'student_id' => $studentId,
            'notes' => 'Lending CMS student #' . $studentId . ' · ' . $billingMonth,
        ], $lang);
        if ($inv) {
            $inv['demo'] = false;
        }
    } else {
        $inv = ld_create_demo_student_invoice($student, $service, $price, $billingMonth, $lang);
    }

    if ($inv) {
        ld_update_student($studentId, [
            'last_invoice_no' => (string) ($inv['invoice_no'] ?? ''),
            'last_invoice_url' => (string) ($inv['view_url'] ?? ''),
            'last_invoiced_at' => date('c'),
            'last_billing_month' => $billingMonth,
        ]);
    }

    return $inv;
}

/** @return array{id:string,invoice_no:string,view_url:string,total:float,currency:string,demo:bool} */
function ld_create_demo_student_invoice(array $student, string $service, float $price, string $billingMonth, string $lang): array
{
    $demo = ld_load_demo_invoices();
    $seq = count($demo) + 1;
    $id = 'inv-stu-' . date('Ym') . '-' . str_pad((string) $seq, 3, '0', STR_PAD_LEFT);
    $invoiceNo = 'LD-STU-' . date('Ym') . '-' . str_pad((string) $seq, 3, '0', STR_PAD_LEFT);
    $row = [
        'id' => $id,
        'invoice_no' => $invoiceNo,
        'buyer_name' => (string) ($student['name'] ?? ''),
        'service' => $service,
        'amount' => $price,
        'currency' => 'EUR',
        'status' => 'issued',
        'created_at' => date('Y-m-d'),
        'student_id' => (string) ($student['id'] ?? ''),
        'billing_month' => $billingMonth,
        'source' => 'student',
    ];
    array_unshift($demo, $row);
    ld_save_demo_invoices($demo);

    return [
        'id' => $id,
        'invoice_no' => $invoiceNo,
        'view_url' => ld_admin_url('invoice-print.php', ['id' => $id]),
        'total' => $price,
        'currency' => 'EUR',
        'demo' => true,
    ];
}

function ld_demo_invoice_by_id(string $id): ?array
{
    foreach (ld_load_demo_invoices() as $row) {
        if (($row['id'] ?? '') === $id) {
            return $row;
        }
    }
    return null;
}