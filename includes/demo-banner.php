<?php
/** Demo disclaimer — no real services. */
$landingT = $t['landing'] ?? [];
if (function_exists('ld_template_preview_active') && ld_template_preview_active()) {
    $demoText = $landingT['demo_banner_generic'] ?? ($landingT['demo_banner_short'] ?? ($t['home']['demo_disclaimer'] ?? ''));
} else {
    $demoText = $landingT['demo_banner'] ?? ($t['home']['demo_disclaimer'] ?? '');
}
if ($demoText === '') {
    return;
}
?>
<div class="ld-demo-banner" role="note">
    <i class="fas fa-circle-info" aria-hidden="true"></i>
    <span><?= ld_h($demoText) ?></span>
</div>