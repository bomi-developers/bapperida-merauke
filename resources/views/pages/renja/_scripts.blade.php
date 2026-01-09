<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- 1. AJAX FILTERING & PAGINATION ---
        const searchInput = document.getElementById('filter-search');
        const statusSelect = document.getElementById('filter-status');
        const tahapanSelect = document.getElementById('filter-tahapan');
        const tableContainer = document.getElementById('renja-table-container');

        if (searchInput && tableContainer) {
            function fetchRenja(url = '/renja') {
                const search = searchInput.value;
                const status = statusSelect.value;
                const tahapan = tahapanSelect.value;

                tableContainer.classList.add('opacity-50', 'pointer-events-none');

                const separator = url.includes('?') ? '&' : '?';
                const fullUrl = `${url}${separator}search=${search}&status=${status}&tahapan_filter=${tahapan}`;

                fetch(fullUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        tableContainer.classList.remove('opacity-50', 'pointer-events-none');
                        attachPaginationListeners();
                    })
                    .catch(err => {
                        console.error(err);
                        tableContainer.classList.remove('opacity-50', 'pointer-events-none');
                    });
            }

            function attachPaginationListeners() {
                const links = tableContainer.querySelectorAll('.pagination a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.getAttribute('href');
                        if (url) fetchRenja(url);
                    });
                });
            }

            let timeoutId;
            searchInput.addEventListener('keyup', () => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => fetchRenja(), 500);
            });

            if (statusSelect) statusSelect.addEventListener('change', () => fetchRenja());
            if (tahapanSelect) tahapanSelect.addEventListener('change', () => fetchRenja());

            attachPaginationListeners();
        }

        // --- 2. FILE UPLOAD LISTENER ---
        function setupFileListener(inputId, textId) {
            const input = document.getElementById(inputId);
            const text = document.getElementById(textId);
            if (input && text) {
                input.addEventListener('change', function(e) {
                    if (this.files.length) text.innerHTML =
                        `<span class="font-bold text-indigo-600">${this.files[0].name}</span>`;
                });
            }
        }
        setupFileListener('dropzone-file', 'dropzone-text');
        setupFileListener('doc-file', 'doc-text');
        setupFileListener('matrix-file', 'matrix-text');

        // ==========================================
        // 3. BACKGROUND UPLOAD LISTENER (RENJA)
        // ==========================================
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Close modal
                document.getElementById('uploadModal').classList.add('hidden');

                // Toast Info
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: 'info',
                    title: 'Upload Renja berjalan di latar belakang...'
                });

                const formData = new FormData(this);

                window.uploadProgressManager.upload(this.action, formData, {
                    name: 'Dokumen Renja',
                    onSuccess: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message || 'Upload berhasil!'
                        });
                        // Refresh Data Table via AJAX Search
                        if (typeof fetchRenja === 'function') {
                            fetchRenja();
                        } else {
                            // Fallback
                            const searchInput = document.getElementById('filter-search');
                            if (searchInput) searchInput.dispatchEvent(new Event('keyup'));
                        }
                    },
                    onError: function(error) {
                        console.error(error);
                        let errorMessage = 'Gagal upload dokumen.';
                        if (error.responseJSON && error.responseJSON.message) {
                            errorMessage = error.responseJSON.message;
                        }
                        Swal.fire('Gagal', errorMessage, 'error');
                    }
                });
            });
        }
    });

    // --- MODAL FUNCTIONS ---
    function openTahapanModal() {
        document.getElementById('formTahapan').reset();
        document.getElementById('tahapan_id').value = '';
        document.getElementById('tahapanModalTitle').innerText = 'Tambah Tahapan Baru';
        document.getElementById('btnSubmitTahapan').innerText = 'Simpan & Aktifkan';
        document.getElementById('current-file-area').classList.add('hidden');
        document.getElementById('dropzone-text').innerHTML =
            '<span class="font-semibold text-indigo-600">Klik upload</span> atau drag file';
        document.getElementById('tahapan_tahun').value = new Date().getFullYear() + 1;
        document.getElementById('tahapanModal').classList.remove('hidden');
    }

    function editTahapan(button) {
        const id = button.getAttribute('data-id');
        const tahun = button.getAttribute('data-tahun');
        const nama = button.getAttribute('data-nama');
        const start = button.getAttribute('data-start');
        const end = button.getAttribute('data-end');
        const fileUrl = button.getAttribute('data-file');
        const fileName = button.getAttribute('data-filename');

        document.getElementById('tahapan_id').value = id;
        document.getElementById('tahapan_tahun').value = tahun;
        document.getElementById('tahapan_nama').value = nama;
        document.getElementById('tahapan_start').value = start;
        document.getElementById('tahapan_end').value = end;

        const fileArea = document.getElementById('current-file-area');
        if (fileUrl && fileUrl !== "") {
            fileArea.classList.remove('hidden');
            document.getElementById('current-filename').innerText = fileName;
            document.getElementById('current-file-link').href = fileUrl;
        } else {
            fileArea.classList.add('hidden');
        }

        document.getElementById('tahapanModalTitle').innerText = 'Edit Tahapan RKPD';
        document.getElementById('btnSubmitTahapan').innerText = 'Simpan Perubahan';
        document.getElementById('tahapanModal').classList.remove('hidden');
    }

    function closeTahapanModal() {
        document.getElementById('tahapanModal').classList.add('hidden');
    }

    function toggleTahapan(id) {
        const btn = document.getElementById(`btn-tahapan-${id}`);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        btn.innerText = '...';
        btn.disabled = true;
        btn.classList.add('opacity-50');
        fetch(`/renja/tahapan/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) setTimeout(() => window.location.reload(), 300);
                else alert(data.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.classList.remove('opacity-50');
            });
    }

    // --- UPLOAD MODAL LOGIC ---
    function openUploadModal(tahapanId, statusDoc = 'MENUNGGU', statusMatrix = 'MENUNGGU') {
        document.getElementById('upload_tahapan_id').value = tahapanId;
        const modalTitle = document.querySelector('#uploadModalTitle');
        const modalDesc = document.querySelector('#uploadModal p.text-sm');

        if (modalTitle && modalDesc) {
            if (statusDoc === 'MENUNGGU' && statusMatrix === 'MENUNGGU') {
                modalTitle.innerText = "Upload Dokumen Renja";
                modalDesc.innerText = "Pastikan format sesuai template.";
            } else {
                modalTitle.innerText = "Revisi Dokumen Renja";
                modalDesc.innerText = "Upload file yang perlu direvisi saja.";
            }
        }

        const toggleDisplay = (id, show) => {
            const el = document.getElementById(id);
            if (el) {
                if (show) el.classList.remove('hidden');
                else el.classList.add('hidden');
            }
        };
        const setRequired = (id, required) => {
            const el = document.getElementById(id);
            if (el) {
                if (required) el.setAttribute('required', 'required');
                else el.removeAttribute('required');
            }
        };

        if (statusDoc === 'DISETUJUI') {
            toggleDisplay('upload-area-doc', false);
            toggleDisplay('approved-msg-doc', true);
            toggleDisplay('status-badge-doc', true);
            setRequired('doc-file', false);
        } else {
            toggleDisplay('upload-area-doc', true);
            toggleDisplay('approved-msg-doc', false);
            toggleDisplay('status-badge-doc', false);
            if (statusDoc === 'MENUNGGU' && statusMatrix === 'MENUNGGU') setRequired('doc-file', true);
            else setRequired('doc-file', false);
        }

        if (statusMatrix === 'DISETUJUI') {
            toggleDisplay('upload-area-matrix', false);
            toggleDisplay('approved-msg-matrix', true);
            toggleDisplay('status-badge-matrix', true);
            setRequired('matrix-file', false);
        } else {
            toggleDisplay('upload-area-matrix', true);
            toggleDisplay('approved-msg-matrix', false);
            toggleDisplay('status-badge-matrix', false);
            if (statusDoc === 'MENUNGGU' && statusMatrix === 'MENUNGGU') setRequired('matrix-file', true);
            else setRequired('matrix-file', false);
        }

        const docText = document.getElementById('doc-text');
        if (docText) docText.innerHTML =
            '<span class="font-semibold text-indigo-600">Klik upload</span> atau drag file';
        const matrixText = document.getElementById('matrix-text');
        if (matrixText) matrixText.innerHTML =
            '<span class="font-semibold text-indigo-600">Klik upload</span> atau drag file';

        document.getElementById('uploadModal').classList.remove('hidden');
    }

    function openVerifyModal(id, opdName) {
        document.getElementById('verify-opd-name').innerText = `OPD: ${opdName}`;
        document.getElementById('formVerify').action = `/renja/${id}/verify`;
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function openDetailModal(button) {
        document.getElementById('detail-name').innerText = button.getAttribute('data-name');
        document.getElementById('detail-admin-note').innerText = button.getAttribute('data-admin-note') || '-';
        const fileUrl = button.getAttribute('data-file');
        document.getElementById('detail-download-btn').href = fileUrl;
        const status = button.getAttribute('data-status');
        const statusEl = document.getElementById('detail-status');
        statusEl.innerText = status;
        statusEl.className = "px-3 py-1 rounded-full text-xs font-bold uppercase " + (status === 'MENUNGGU' ?
            'bg-yellow-100 text-yellow-800' : (status === 'REVISI' ? 'bg-red-100 text-red-800' :
                'bg-green-100 text-green-800'));

        const iframe = document.getElementById('file-preview');
        const loading = document.getElementById('preview-loading');
        const error = document.getElementById('preview-error');
        iframe.classList.add('hidden');
        error.classList.add('hidden');
        loading.classList.remove('hidden');
        iframe.src = '';

        const ext = fileUrl.split('.').pop().toLowerCase();
        if (['pdf', 'jpg', 'png'].includes(ext)) {
            iframe.src = fileUrl;
            iframe.onload = () => {
                loading.classList.add('hidden');
                iframe.classList.remove('hidden');
            };
        } else if (['doc', 'docx', 'xls', 'xlsx'].includes(ext)) {
            iframe.src = `https://docs.google.com/gview?url=${encodeURIComponent(fileUrl)}&embedded=true`;
            setTimeout(() => {
                loading.classList.add('hidden');
                iframe.classList.remove('hidden');
            }, 2000);
        } else {
            loading.classList.add('hidden');
            error.classList.remove('hidden');
        }

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('file-preview').src = '';
    }

    function openHistoryModal(id) {
        const modal = document.getElementById('historyModal');
        const container = document.getElementById('history-content');
        container.innerHTML =
            '<div class="flex flex-col items-center justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div><p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Memuat...</p></div>';
        modal.classList.remove('hidden');
        fetch(`/renja/${id}/history`).then(res => {
                if (!res.ok) throw new Error('Failed');
                return res.json();
            })
            .then(data => {
                container.innerHTML = '';
                if (!data.length) {
                    container.innerHTML =
                        '<div class="text-center py-8 text-gray-500 dark:text-gray-400"><p>Belum ada riwayat.</p></div>';
                    return;
                }
                data.forEach(item => {
                    const date = new Date(item.created_at).toLocaleDateString('id-ID');
                    const feedback = item.file_matriks_verifikasi ?
                        `<a href="/storage/${item.file_matriks_verifikasi}" target="_blank" class="text-red-600 dark:text-red-400 hover:underline text-xs block mt-1"><i class="bi bi-download"></i> Koreksi Excel</a>` :
                        '';
                    const feedbackDoc = item.file_dokumen_verifikasi ?
                        `<a href="/storage/${item.file_dokumen_verifikasi}" target="_blank" class="text-red-600 dark:text-red-400 hover:underline text-xs block mt-1"><i class="bi bi-download"></i> Koreksi Naskah</a>` :
                        '';
                    const html =
                        `<li class="mb-8 ml-6"><span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-full -left-3 ring-8 ring-white dark:ring-gray-800"><i class="bi bi-clock text-blue-800 dark:text-blue-300 text-xs"></i></span><h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">${item.status_snapshot} <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 px-2 py-0.5 rounded ml-2">${date}</span></h3><div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600"><span class="font-bold text-gray-700 dark:text-gray-300 block mb-1">Catatan:</span><p class="text-sm text-gray-600 dark:text-gray-400">"${item.catatan_verifikasi || '-'}"</p>${feedback}${feedbackDoc}</div><div class="flex gap-2"><a href="/storage/${item.file_dokumen_renja}" target="_blank" class="px-3 py-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"><i class="bi bi-file-pdf text-red-500"></i> Dokumen Lama</a><a href="/storage/${item.file_matriks_renja}" target="_blank" class="px-3 py-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"><i class="bi bi-file-earmark-excel text-green-600"></i> Matriks Lama</a></div></li>`;
                    container.innerHTML += html;
                });
            }).catch(err => {
                container.innerHTML = '<div class="text-center text-red-500 py-4">Gagal memuat history.</div>';
            });
    }

    window.onclick = function(e) {
        const ids = ['tahapanModal', 'uploadModal', 'verifyModal', 'detailModal', 'historyModal'];
        ids.forEach(id => {
            if (e.target == document.getElementById(id)) document.getElementById(id).classList.add(
                'hidden');
        });
    }
</script>
