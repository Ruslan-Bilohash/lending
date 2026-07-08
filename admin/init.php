<?php
require_once dirname(__DIR__) . '/init.php';
require_once dirname(__DIR__) . '/includes/admin-auth.php';
$t = ld_admin_reload_i18n();
$ta = $t['admin'] ?? [];