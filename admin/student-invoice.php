<?php
require_once __DIR__ . '/init.php';
ld_admin_require();

$studentId = trim((string) ($_GET['id'] ?? $_POST['id'] ?? ''));
$billingMonth = trim((string) ($_GET['month'] ?? $_POST['month'] ?? date('Y-m')));
if (!preg_match('/^\d{4}-\d{2}$/', $billingMonth)) {
    $billingMonth = date('Y-m');
}

$student = $studentId !== '' ? ld_get_student($studentId) : null;
if (!$student) {
    header('Location: ' . ld_admin_url('students.php'), true, 302);
    exit;
}

$invoice = ld_student_monthly_invoice($studentId, $lang, $billingMonth);
if ($invoice && !empty($invoice['view_url'])) {
    header('Location: ' . $invoice['view_url'], true, 302);
    exit;
}

header('Location: ' . ld_admin_url('students.php', ['error' => 'invoice_failed']), true, 302);
exit;