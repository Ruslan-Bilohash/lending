(function () {
    function reindexRows(container, prefix) {
        var rows = container.querySelectorAll('.adm-repeat-row');
        rows.forEach(function (row, index) {
            row.querySelectorAll('[name]').forEach(function (input) {
                input.name = input.name.replace(/\[\d+\]/, '[' + index + ']');
            });
            var head = row.querySelector('.adm-repeat-head strong');
            if (head) {
                head.textContent = '#' + (index + 1);
            }
        });
    }

    function bindRemove(btn) {
        btn.addEventListener('click', function () {
            var row = btn.closest('.adm-repeat-row');
            var list = row && row.parentElement;
            if (!row || !list) return;
            if (list.querySelectorAll('.adm-repeat-row').length <= 1) {
                row.querySelectorAll('input, textarea').forEach(function (el) {
                    el.value = '';
                });
                return;
            }
            row.remove();
            reindexRows(list, list.dataset.repeatList || '');
        });
    }

    document.querySelectorAll('.adm-repeat-remove').forEach(bindRemove);

    document.querySelectorAll('[data-repeat-add]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var type = btn.getAttribute('data-repeat-add');
            var list = document.querySelector('[data-repeat-list="' + type + '"]');
            var template = document.getElementById('tpl-' + type);
            if (!list || !template) return;
            var index = list.querySelectorAll('.adm-repeat-row').length;
            var html = template.innerHTML.replace(/__INDEX__/g, String(index));
            var wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            var row = wrap.firstElementChild;
            list.appendChild(row);
            var removeBtn = row.querySelector('.adm-repeat-remove');
            if (removeBtn) bindRemove(removeBtn);
        });
    });
})();