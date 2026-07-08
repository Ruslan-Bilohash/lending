(function () {
    document.querySelectorAll('[data-adm-image-picker]').forEach(function (root) {
        var uploadUrl = root.getAttribute('data-upload-url') || '';
        var subdir = root.getAttribute('data-subdir') || 'blocks';
        var hiddenInput = root.querySelector('[data-picker-value]');
        var previewWrap = root.querySelector('[data-picker-preview]');
        var previewImg = root.querySelector('[data-picker-preview] img');
        var dropzone = root.querySelector('.adm-img-dropzone');
        var fileInput = root.querySelector('.adm-img-file-input');
        var urlInput = root.querySelector('[data-picker-url]');
        var urlAddBtn = root.querySelector('[data-picker-url-add]');
        var removeBtn = root.querySelector('[data-picker-remove]');
        var statusEl = root.querySelector('.adm-img-status');

        function currentUrl() {
            return hiddenInput ? hiddenInput.value.trim() : '';
        }

        function setUrl(url) {
            if (!hiddenInput) return;
            hiddenInput.value = url || '';
            if (previewWrap) {
                previewWrap.hidden = !url;
            }
            if (previewImg && url) {
                previewImg.src = url;
            }
            if (dropzone) {
                dropzone.classList.toggle('has-image', !!url);
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

        function uploadFile(file) {
            if (!file || !uploadUrl || file.type.indexOf('image/') !== 0) return;
            setStatus(root.getAttribute('data-uploading') || 'Uploading…', 'loading');
            var fd = new FormData();
            fd.append('image', file);
            fd.append('subdir', subdir);
            fetch(uploadUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.ok && res.url) {
                        setUrl(res.url);
                        setStatus(root.getAttribute('data-upload-ok') || 'Image added.', 'success');
                        window.setTimeout(function () { setStatus('', ''); }, 2000);
                    } else {
                        throw new Error(res.error || 'upload_failed');
                    }
                })
                .catch(function (err) {
                    setStatus(err.message || root.getAttribute('data-upload-error') || 'Upload failed', 'error');
                });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                var url = currentUrl();
                if (url.indexOf('/uploads/') !== -1 && uploadUrl) {
                    var fd = new FormData();
                    fd.append('action', 'delete');
                    fd.append('url', url);
                    fetch(uploadUrl, { method: 'POST', body: fd, credentials: 'same-origin' }).catch(function () {});
                }
                setUrl('');
                if (urlInput) urlInput.value = '';
            });
        }

        if (dropzone) {
            dropzone.addEventListener('click', function (e) {
                if (e.target.closest('[data-picker-remove]')) return;
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
                    uploadFile(e.dataTransfer.files[0]);
                    return;
                }
                var text = (e.dataTransfer.getData('text/plain') || e.dataTransfer.getData('text/uri-list') || '').trim();
                if (text && isValidUrl(text)) {
                    setUrl(text);
                    setStatus(root.getAttribute('data-upload-ok') || 'Image added.', 'success');
                }
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                if (fileInput.files && fileInput.files[0]) {
                    uploadFile(fileInput.files[0]);
                }
                fileInput.value = '';
            });
        }

        if (urlAddBtn && urlInput) {
            urlAddBtn.addEventListener('click', function () {
                var url = urlInput.value.trim();
                if (!isValidUrl(url)) {
                    setStatus(root.getAttribute('data-invalid-url') || 'Invalid URL', 'error');
                    return;
                }
                setUrl(url);
                urlInput.value = '';
                setStatus(root.getAttribute('data-upload-ok') || 'Image added.', 'success');
                window.setTimeout(function () { setStatus('', ''); }, 2000);
            });
            urlInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    urlAddBtn.click();
                }
            });
        }

        setUrl(currentUrl());
    });
})();