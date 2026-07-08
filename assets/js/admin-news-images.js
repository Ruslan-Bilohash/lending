(function () {
    var root = document.getElementById('ldNewsImages');
    var form = document.getElementById('admNewsForm');
    if (!root || !form) return;

    var uploadUrl = root.getAttribute('data-upload-url') || '';
    var listEl = root.querySelector('.adm-img-gallery-list');
    var dropzone = root.querySelector('.adm-img-dropzone');
    var fileInput = root.querySelector('.adm-img-file-input');
    var jsonInput = document.getElementById('ldNewsImagesJson');
    var coverInput = document.getElementById('ldNewsImageCover');
    var urlInput = document.getElementById('ldNewsImageUrlInput');
    var urlAddBtn = document.getElementById('ldNewsImageUrlAdd');
    var statusEl = root.querySelector('.adm-img-status');
    var dragSrc = null;

    function images() {
        var items = listEl ? listEl.querySelectorAll('.adm-img-gallery-item') : [];
        return Array.prototype.map.call(items, function (el) {
            return (el.getAttribute('data-url') || '').trim();
        }).filter(Boolean);
    }

    function syncHidden() {
        var urls = images();
        if (jsonInput) {
            jsonInput.value = JSON.stringify(urls);
        }
        if (coverInput) {
            coverInput.value = urls[0] || '';
        }
    }

    function setStatus(msg, type) {
        if (!statusEl) return;
        statusEl.textContent = msg || '';
        statusEl.className = 'adm-img-status' + (type ? ' adm-img-status--' + type : '');
        statusEl.hidden = !msg;
    }

    function isValidUrl(url) {
        try {
            var u = new URL(url);
            return u.protocol === 'http:' || u.protocol === 'https:';
        } catch (e) {
            return false;
        }
    }

    function createItem(url) {
        var li = document.createElement('li');
        li.className = 'adm-img-gallery-item';
        li.setAttribute('data-url', url);
        li.setAttribute('draggable', 'true');
        li.innerHTML =
            '<div class="adm-img-gallery-thumb">' +
            '<img src="' + url.replace(/"/g, '&quot;') + '" alt="" loading="lazy">' +
            '<span class="adm-img-gallery-drag" title="' + (root.getAttribute('data-drag') || '') + '"><i class="fas fa-grip-vertical"></i></span>' +
            '</div>' +
            '<button type="button" class="adm-img-gallery-remove" aria-label="' + (root.getAttribute('data-remove') || 'Remove') + '"><i class="fas fa-trash"></i></button>';
        bindItem(li);
        return li;
    }

    function addImage(url) {
        if (!url || !listEl) return false;
        if (images().indexOf(url) !== -1) return false;
        listEl.appendChild(createItem(url));
        syncHidden();
        return true;
    }

    function addUrlsFromText(text) {
        var added = 0;
        String(text || '').split(/[\n,;]+/).forEach(function (part) {
            var url = part.trim();
            if (url && isValidUrl(url) && addImage(url)) {
                added++;
            }
        });
        return added;
    }

    function removeItem(li) {
        var url = li.getAttribute('data-url') || '';
        if (url.indexOf('/uploads/') !== -1 && uploadUrl) {
            var fd = new FormData();
            fd.append('action', 'delete');
            fd.append('url', url);
            fetch(uploadUrl, { method: 'POST', body: fd, credentials: 'same-origin' }).catch(function () {});
        }
        li.remove();
        syncHidden();
    }

    function bindItem(li) {
        var removeBtn = li.querySelector('.adm-img-gallery-remove');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                removeItem(li);
            });
        }
        li.addEventListener('dragstart', function (e) {
            dragSrc = li;
            li.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
        });
        li.addEventListener('dragend', function () {
            li.classList.remove('is-dragging');
            dragSrc = null;
            syncHidden();
        });
        li.addEventListener('dragover', function (e) {
            e.preventDefault();
            if (!dragSrc || dragSrc === li) return;
            var rect = li.getBoundingClientRect();
            var after = e.clientX > rect.left + rect.width / 2;
            listEl.insertBefore(dragSrc, after ? li.nextSibling : li);
        });
    }

    if (listEl) {
        listEl.querySelectorAll('.adm-img-gallery-item').forEach(bindItem);
        syncHidden();
    }

    function uploadFiles(files) {
        if (!files || !files.length || !uploadUrl) return;
        var queue = Array.prototype.filter.call(files, function (file) {
            return file.type && file.type.indexOf('image/') === 0;
        });
        if (!queue.length) return;

        var pending = queue.length;
        setStatus(root.getAttribute('data-uploading') || 'Uploading…', 'loading');

        queue.forEach(function (file) {
            var fd = new FormData();
            fd.append('image', file);
            fetch(uploadUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.ok && res.url) {
                        addImage(res.url);
                    } else {
                        throw new Error(res.error || 'upload_failed');
                    }
                })
                .catch(function (err) {
                    setStatus(err.message || root.getAttribute('data-upload-error') || 'Upload failed', 'error');
                })
                .finally(function () {
                    pending--;
                    if (pending <= 0) {
                        setStatus(root.getAttribute('data-upload-ok') || 'Images added.', 'success');
                        window.setTimeout(function () { setStatus('', ''); }, 2500);
                    }
                });
        });
    }

    if (dropzone) {
        dropzone.addEventListener('click', function () {
            if (fileInput) fileInput.click();
        });
        dropzone.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                if (fileInput) fileInput.click();
            }
        });
        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropzone.classList.add('is-dragover');
        });
        dropzone.addEventListener('dragleave', function () {
            dropzone.classList.remove('is-dragover');
        });
        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.classList.remove('is-dragover');
            if (e.dataTransfer.files && e.dataTransfer.files.length) {
                uploadFiles(e.dataTransfer.files);
                return;
            }
            var text = e.dataTransfer.getData('text/plain') || e.dataTransfer.getData('text/uri-list') || '';
            if (text && addUrlsFromText(text)) {
                setStatus(root.getAttribute('data-upload-ok') || 'Images added.', 'success');
            }
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            uploadFiles(fileInput.files);
            fileInput.value = '';
        });
    }

    if (urlAddBtn && urlInput) {
        urlAddBtn.addEventListener('click', function () {
            var raw = urlInput.value.trim();
            if (!raw) return;
            var added = addUrlsFromText(raw);
            if (!added) {
                setStatus(root.getAttribute('data-invalid-url') || 'Invalid URL', 'error');
                return;
            }
            urlInput.value = '';
            setStatus(root.getAttribute('data-upload-ok') || 'Images added.', 'success');
            window.setTimeout(function () { setStatus('', ''); }, 2000);
        });
        urlInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                urlAddBtn.click();
            }
        });
    }

    form.addEventListener('submit', syncHidden);
})();