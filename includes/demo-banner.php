<?php
/** Demo disclaimer — no real services. */
$demoText = $t['landing']['demo_banner'] ?? ($t['home']['demo_disclaimer'] ?? '');
if ($demoText === '') {
    return;
}
?>
<div class="ld-demo-banner" role="note">
    <i class="fas fa-circle-info" aria-hidden="true"></i>
    <span><?= ld_h($demoText) ?></span>
</div>