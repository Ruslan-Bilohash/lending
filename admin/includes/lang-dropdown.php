<?php
/** Admin panel UI language — expects $lang, $ta */
?>
<div class="adm-lang-dd" data-adm-lang-dd>
    <button type="button" class="adm-lang-btn" aria-expanded="false" aria-haspopup="listbox"
            aria-label="<?= ld_h($ta['admin_lang_menu'] ?? 'Admin language') ?>">
        <span class="adm-lang-flag" aria-hidden="true"><?= ld_h(ld_langs()[$lang]['flag'] ?? '') ?></span>
        <span class="adm-lang-name"><?= ld_h(ld_langs()[$lang]['name'] ?? $lang) ?></span>
        <i class="fas fa-chevron-down adm-lang-chev" aria-hidden="true"></i>
    </button>
    <ul class="adm-lang-menu" role="listbox" hidden>
        <?php foreach (ld_langs() as $code => $info): ?>
        <li>
            <a href="<?= ld_h(ld_admin_lang_url($code)) ?>" role="option"
               class="<?= $lang === $code ? 'is-active' : '' ?>">
                <span class="adm-lang-flag" aria-hidden="true"><?= $info['flag'] ?></span>
                <?= ld_h($info['name']) ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>