<?php
require_once __DIR__ . '/init.php';
ld_admin_logout();
header('Location: ' . ld_admin_url('login.php'), true, 302);
exit;