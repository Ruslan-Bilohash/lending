<?php
/**
 * @var string $api_guide_scope — ai | recaptcha | faktura
 * @var bool $api_guide_inline — compact block inside another card
 * @var array $ta
 */
$scope = $api_guide_scope ?? 'ai';
$inline = !empty($api_guide_inline);
$blocks = [
    'ai' => [
        'intro' => 'api_guide_ai_intro',
        'steps' => ['api_guide_ai_step1', 'api_guide_ai_step2', 'api_guide_ai_step3', 'api_guide_ai_step4', 'api_guide_ai_step5'],
    ],
    'recaptcha' => [
        'intro' => 'api_guide_recaptcha_intro',
        'steps' => ['api_guide_recaptcha_step1', 'api_guide_recaptcha_step2', 'api_guide_recaptcha_step3'],
    ],
    'faktura' => [
        'intro' => 'api_guide_faktura_intro',
        'steps' => ['api_guide_faktura_step1', 'api_guide_faktura_step2', 'api_guide_faktura_step3'],
    ],
];
$block = $blocks[$scope] ?? $blocks['ai'];
?>
<?php if ($inline): ?>
<div class="adm-api-guide-inline adm-instructions">
    <h3 class="adm-subhead"><i class="fas fa-book"></i> <?= ld_h($ta['api_guide_title'] ?? '') ?></h3>
    <p class="adm-field-hint"><?= ld_h($ta[$block['intro']] ?? '') ?></p>
    <ol>
        <?php foreach ($block['steps'] as $stepKey): ?>
        <?php if (!empty($ta[$stepKey])): ?>
        <li><?= ld_h($ta[$stepKey]) ?></li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</div>
<?php else: ?>
<div class="adm-card adm-api-guide">
    <div class="adm-card-head">
        <h2><i class="fas fa-book"></i> <?= ld_h($ta['api_guide_title'] ?? '') ?></h2>
    </div>
    <div class="adm-card-body padded adm-instructions">
        <p class="adm-help"><?= ld_h($ta[$block['intro']] ?? '') ?></p>
        <ol>
            <?php foreach ($block['steps'] as $stepKey): ?>
            <?php if (!empty($ta[$stepKey])): ?>
            <li><?= ld_h($ta[$stepKey]) ?></li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
</div>
<?php endif; ?>