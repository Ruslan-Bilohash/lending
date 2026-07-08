<?php
require_once __DIR__ . '/init.php';
ld_admin_require();
$admin_page = 'students';
$page_title = $ta['students'] ?? 'Students';

$services = ld_services($lang);
$saved = false;
$deleted = false;
$error = '';
$flashInvoice = isset($_GET['invoiced']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_student'])) {
        $sid = trim((string) ($_POST['student_id'] ?? ''));
        if ($sid !== '' && ld_student_delete($sid)) {
            header('Location: ' . ld_admin_url('students.php', ['deleted' => '1']));
            exit;
        }
        $error = 'delete_failed';
    } else {
        $result = ld_student_upsert($_POST);
        if (!empty($result['ok'])) {
            header('Location: ' . ld_admin_url('students.php', ['saved' => '1']));
            exit;
        }
        $error = 'save_failed';
    }
}

if (isset($_GET['saved'])) {
    $saved = true;
}
if (isset($_GET['deleted'])) {
    $deleted = true;
}

$students = ld_load_students();
$billingMonth = date('Y-m');
$fakturaOn = ld_faktura_available() && !empty(ld_faktura_integration()['enabled']);

require __DIR__ . '/includes/layout.php';
?>

<?php if ($saved): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['students_saved'] ?? 'Saved.') ?></div>
<?php endif; ?>
<?php if ($deleted): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check"></i> <?= ld_h($ta['students_deleted'] ?? 'Deleted.') ?></div>
<?php endif; ?>
<?php if ($flashInvoice): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-file-pdf"></i> <?= ld_h($ta['students_invoice_ok'] ?? 'Invoice created.') ?></div>
<?php endif; ?>
<?php if ($error === 'delete_failed'): ?>
<div class="adm-alert adm-alert-warning"><?= ld_h($ta['students_delete_error'] ?? 'Could not delete.') ?></div>
<?php endif; ?>

<p class="adm-help"><?= ld_h($ta['students_help'] ?? '') ?></p>

<div class="adm-card">
    <div class="adm-card-head"><h2><i class="fas fa-user-plus"></i> <?= ld_h($ta['students_add'] ?? 'Add student') ?></h2></div>
    <div class="adm-card-body padded">
        <form method="post" class="adm-form-grid">
            <div class="adm-field">
                <label><?= ld_h($ta['student_name'] ?? 'Name') ?> *</label>
                <input type="text" name="name" required>
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['student_phone'] ?? 'Phone') ?></label>
                <input type="text" name="phone">
            </div>
            <div class="adm-field adm-field-full">
                <label><?= ld_h($ta['email'] ?? 'Email') ?></label>
                <input type="email" name="email">
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['student_course'] ?? 'Course') ?></label>
                <select name="course" id="stuCourse">
                    <option value="">—</option>
                    <?php foreach ($services as $svc): ?>
                    <option value="<?= ld_h($svc['name'] ?? '') ?>" data-price="<?= ld_h((string) ($svc['price'] ?? '')) ?>">
                        <?= ld_h($svc['name'] ?? '') ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="course" id="stuCourseText" placeholder="<?= ld_h($ta['invoice_service_custom'] ?? '') ?>" style="margin-top:6px">
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['student_monthly_price'] ?? 'Price per month') ?> (<?= ld_h(ld_currency()) ?>)</label>
                <input type="text" name="monthly_price" id="stuMonthlyPrice" inputmode="decimal" placeholder="120" required>
                <p class="adm-field-hint"><?= ld_h($ta['student_monthly_hint'] ?? '') ?></p>
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['student_started'] ?? 'Start date') ?></label>
                <input type="date" name="started_at" value="<?= ld_h(date('Y-m-d')) ?>">
            </div>
            <div class="adm-field">
                <label><?= ld_h($ta['student_status'] ?? 'Status') ?></label>
                <select name="status">
                    <option value="active"><?= ld_h($ta['student_status_active'] ?? 'Active') ?></option>
                    <option value="paused"><?= ld_h($ta['student_status_paused'] ?? 'Paused') ?></option>
                    <option value="completed"><?= ld_h($ta['student_status_completed'] ?? 'Completed') ?></option>
                </select>
            </div>
            <div class="adm-field adm-field-full adm-form-actions">
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= ld_h($ta['save'] ?? 'Save') ?></button>
            </div>
        </form>
    </div>
</div>

<?php if ($students !== []): ?>
<div class="adm-card adm-students-list">
    <div class="adm-card-head">
        <h2><i class="fas fa-user-graduate"></i> <?= ld_h($ta['students_list'] ?? 'Students') ?></h2>
        <span class="adm-muted"><?= ld_h(ld_student_billing_label($lang, $billingMonth)) ?></span>
    </div>
    <div class="adm-card-body" style="overflow-x:auto">
        <table class="adm-table">
            <thead>
                <tr>
                    <th><?= ld_h($ta['student_name'] ?? 'Name') ?></th>
                    <th><?= ld_h($ta['student_course'] ?? 'Course') ?></th>
                    <th><?= ld_h($ta['student_monthly_price'] ?? 'Monthly') ?></th>
                    <th><?= ld_h($ta['student_status'] ?? 'Status') ?></th>
                    <th><?= ld_h($ta['student_last_invoice'] ?? 'Last invoice') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $stu):
                    $sid = (string) ($stu['id'] ?? '');
                    $monthly = (float) ($stu['monthly_price'] ?? 0);
                    $st = (string) ($stu['status'] ?? 'active');
                    $lastNo = (string) ($stu['last_invoice_no'] ?? '');
                    $lastUrl = (string) ($stu['last_invoice_url'] ?? '');
                    $lastMonth = (string) ($stu['last_billing_month'] ?? '');
                    $alreadyThisMonth = $lastMonth === $billingMonth;
                ?>
                <tr>
                    <td>
                        <strong><?= ld_h($stu['name'] ?? '') ?></strong><br>
                        <small class="adm-muted"><?= ld_h($stu['phone'] ?? '') ?></small>
                    </td>
                    <td><?= ld_h($stu['course'] ?? '') ?></td>
                    <td><strong><?= ld_h(number_format($monthly, 2, '.', ' ')) ?> <?= ld_h(ld_currency()) ?></strong><span class="adm-muted"> / <?= ld_h(ld_invoice_unit_month($lang)) ?></span></td>
                    <td><span class="adm-badge <?= $st === 'active' ? 'adm-badge-active' : '' ?>"><?= ld_h($ta['student_status_' . $st] ?? $st) ?></span></td>
                    <td>
                        <?php if ($lastNo !== ''): ?>
                        <code><?= ld_h($lastNo) ?></code>
                        <?php if ($lastMonth !== ''): ?><br><small class="adm-muted"><?= ld_h($lastMonth) ?></small><?php endif; ?>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td class="adm-table-actions">
                        <a href="<?= ld_h(ld_admin_url('student-invoice.php', ['id' => $sid, 'month' => $billingMonth])) ?>"
                           class="adm-btn adm-btn-sm adm-btn-primary"
                           title="<?= ld_h($ta['students_print_invoice'] ?? 'Print invoice') ?>">
                            <i class="fas fa-print"></i> <?= ld_h($ta['students_invoice_btn'] ?? 'Invoice') ?>
                        </a>
                        <?php if ($lastUrl !== '' && $lastUrl !== '#'): ?>
                        <a href="<?= ld_h($lastUrl) ?>" class="adm-btn adm-btn-sm adm-btn-outline" target="_blank" rel="noopener"><i class="fas fa-file-pdf"></i></a>
                        <?php endif; ?>
                        <form method="post" style="display:inline" onsubmit="return confirm('<?= ld_h($ta['students_delete_confirm'] ?? 'Delete?') ?>')">
                            <input type="hidden" name="student_id" value="<?= ld_h($sid) ?>">
                            <button type="submit" name="delete_student" value="1" class="adm-btn adm-btn-sm adm-btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="adm-card"><div class="adm-card-body padded"><p class="adm-muted"><?= ld_h($ta['students_empty'] ?? 'No students yet.') ?></p></div></div>
<?php endif; ?>

<?php if (!$fakturaOn): ?>
<div class="adm-alert adm-alert-info" style="margin-top:12px">
    <i class="fas fa-info-circle"></i> <?= ld_h($ta['students_invoice_demo_hint'] ?? 'Faktura off — demo printable invoice.') ?>
</div>
<?php endif; ?>

<script>
(function () {
    var sel = document.getElementById('stuCourse');
    var txt = document.getElementById('stuCourseText');
    var price = document.getElementById('stuMonthlyPrice');
    var form = sel ? sel.closest('form') : null;
    if (sel) {
        sel.addEventListener('change', function () {
            var opt = sel.options[sel.selectedIndex];
            if (txt) txt.value = opt ? opt.value : '';
            var p = opt ? opt.getAttribute('data-price') : '';
            if (price && p) price.value = p;
        });
    }
    if (form) {
        form.addEventListener('submit', function () {
            if (txt && txt.value.trim() !== '') {
                if (sel) sel.removeAttribute('name');
                txt.setAttribute('name', 'course');
            } else if (sel) {
                sel.setAttribute('name', 'course');
                if (txt) txt.removeAttribute('name');
            }
        });
    }
})();
</script>

<?php require __DIR__ . '/includes/layout-end.php'; ?>