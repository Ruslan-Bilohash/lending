<?php
$current = $LD_LANGS[$lang] ?? $LD_LANGS['lt'];
?>
<div class="ld-lang-dd" id="ldLangDd">
    <button type="button" class="ld-lang-btn" aria-expanded="false" aria-haspopup="listbox">
        <span aria-hidden="true"><?= $current['flag'] ?></span>
        <span class="ld-lang-label"><?= ld_h($current['label']) ?></span>
        <i class="fas fa-chevron-down" aria-hidden="true"></i>
    </button>
    <ul class="ld-lang-menu" role="listbox" hidden>
        <?php foreach (ld_langs() as $code => $info): ?>
        <li role="option">
            <a href="<?= ld_h(ld_lang_url($code)) ?>" class="<?= $lang === $code ? 'active' : '' ?>">
                <span aria-hidden="true"><?= $info['flag'] ?></span>
                <?= ld_h($info['name']) ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>