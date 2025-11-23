<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ==========================================
        // 1. AJAX FILTERING & PAGINATION
        // ==========================================
        const searchInput = document.getElementById('filter-search');
        const statusSelect = document.getElementById('filter-status');
        const periodSelect = document.getElementById('filter-period');
        const tableContainer = document.getElementById('laporan-table-container');

        // Hanya jalankan jika elemen filter ada (untuk menghindari error di halaman lain)
        if (searchInput && tableContainer) {

            // Fungsi Fetch Data
            function fetchLaporan(url = '/triwulan') {
                const search = searchInput.value;
                const status = statusSelect.value;
                const period = periodSelect.value;

                // Visual Feedback (Loading opacity)
                tableContainer.classList.add('opacity-50', 'pointer-events-none');

                // Build URL Query String
                // Cek apakah URL sudah punya parameter (misal ?page=2)
                const separator = url.includes('?') ? '&' : '?';
                const fullUrl = `${url}${separator}search=${search}&status=${status}&period_filter=${period}`;

                fetch(fullUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Header Wajib agar Controller tau ini AJAX
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Ganti isi tabel
                        tableContainer.innerHTML = html;
                        tableContainer.classList.remove('opacity-50', 'pointer-events-none');

                        // Re-attach listener pagination karena HTML baru saja diganti
                        attachPaginationListeners();
                    })
                    .catch(err => {
                        console.error('Error fetching data:', err);
                        tableContainer.classList.remove('opacity-50', 'pointer-events-none');
                    });
            }

            // Fungsi Listener Pagination (Agar link page juga pakai AJAX)
            function attachPaginationListeners() {
                const links = tableContainer.querySelectorAll('.pagination a, a.page-link');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.getAttribute('href');
                        if (url) fetchLaporan(url);
                    });
                });
            }

            // Event Listeners Filter
            let timeoutId;
            searchInput.addEventListener('keyup', () => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => fetchLaporan(), 500); // Debounce 500ms
            });

            if (statusSelect) statusSelect.addEventListener('change', () => fetchLaporan());
            if (periodSelect) periodSelect.addEventListener('change', () => fetchLaporan());

            // Init Pagination saat load pertama
            attachPaginationListeners();
        }


        // ==========================================
        // 2. TAB SWITCHING (Laporan vs Periode)
        // ==========================================
        const tabLaporan = document.getElementById('laporan-tab');
        const tabPeriode = document.getElementById('periode-tab');
        const contentLaporan = document.getElementById('laporan');
        const contentPeriode = document.getElementById('periode');

        if (tabLaporan && tabPeriode) {
            tabLaporan.addEventListener('click', function() {
                // Style Active Laporan
                tabLaporan.className =
                    "inline-block p-4 border-b-2 rounded-t-lg border-indigo-600 text-indigo-600 dark:text-indigo-500 dark:border-indigo-500 transition-colors duration-200";
                tabPeriode.className =
                    "inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400 transition-colors duration-200";

                contentLaporan.classList.remove('hidden');
                contentPeriode.classList.add('hidden');
            });

            tabPeriode.addEventListener('click', function() {
                // Style Active Periode
                tabPeriode.className =
                    "inline-block p-4 border-b-2 rounded-t-lg border-indigo-600 text-indigo-600 dark:text-indigo-500 dark:border-indigo-500 transition-colors duration-200";
                tabLaporan.className =
                    "inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400 transition-colors duration-200";

                contentPeriode.classList.remove('hidden');
                contentLaporan.classList.add('hidden');
            });
        }

        // ==========================================
        // 3. VALIDASI FILE SIZE (Max 50MB)
        // ==========================================
        const fileInput = document.getElementById('fileInput');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const maxSize = 50 * 1024 * 1024; // 50MB
                if (this.files[0] && this.files[0].size > maxSize) {
                    this.value = ""; // Reset input
                    document.getElementById('fileSizeModal').classList.remove('hidden');
                }
            });
        }

    }); // End DOMContentLoaded


    // ==========================================
    // 4. GLOBAL FUNCTIONS (Diakses via onclick HTML)
    // ==========================================

    // --- MODAL HELPERS ---
    function openUploadModal(mode = 'new', id = null) {
        document.getElementById('uploadModal').classList.remove('hidden');
        // Logic tambahan jika mode revisi bisa ditambahkan disini (misal ganti judul modal)
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }

    function openPeriodModal() {
        // Reset Form
        document.getElementById('period_triwulan').value = '1';
        document.getElementById('period_tahun').value = new Date().getFullYear();
        document.getElementById('period_start').value = '';
        document.getElementById('period_end').value = '';
        document.getElementById('periodModal').classList.remove('hidden');
    }

    function closePeriodModal() {
        document.getElementById('periodModal').classList.add('hidden');
    }

    // Edit Periode (Populate Data)
    function editPeriod(id, triwulan, tahun, start, end) {
        document.getElementById('period_triwulan').value = triwulan;
        document.getElementById('period_tahun').value = tahun;
        document.getElementById('period_start').value = start;
        document.getElementById('period_end').value = end;
        document.getElementById('periodModal').classList.remove('hidden');
    }

    // Verify Modal
    function openVerifyModal(id, name) {
        document.getElementById('verify-text').innerHTML = `Pengirim: <strong>${name}</strong>`;
        document.getElementById('formVerify').action = `/triwulan/${id}/verify`;
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    // --- HISTORY MODAL (AJAX) ---
    function openHistoryModal(id) {
        const modal = document.getElementById('historyModal');
        const container = document.getElementById('history-content');

        container.innerHTML = '<p class="text-center p-4 text-gray-500">Memuat riwayat...</p>';
        modal.classList.remove('hidden');

        fetch(`/triwulan/${id}/history`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-center text-gray-500">Tidak ada riwayat.</p>';
                    return;
                }

                data.forEach(item => {
                    const date = new Date(item.created_at).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const opdNote = item.keterangan_opd ? `"${item.keterangan_opd}"` : '-';
                    const adminNote = item.catatan_admin ? `"${item.catatan_admin}"` : '-';

                    const html = `
                        <li class="mb-6 ml-4">
                            <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                            <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">${date}</time>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Status Akhir: <span class="uppercase">${item.status_snapshot}</span></h3>
                            
                            <div class="grid grid-cols-1 gap-2 text-sm mb-2">
                                <div class="bg-indigo-50 dark:bg-indigo-900/30 p-2 rounded border-l-2 border-indigo-400">
                                    <span class="font-bold text-indigo-700 dark:text-indigo-300 block text-xs">Catatan OPD:</span>
                                    <span class="text-gray-600 dark:text-gray-300 italic">${opdNote}</span>
                                </div>
                                <div class="bg-red-50 dark:bg-red-900/30 p-2 rounded border-l-2 border-red-400">
                                    <span class="font-bold text-red-700 dark:text-red-300 block text-xs">Respon Admin:</span>
                                    <span class="text-gray-600 dark:text-gray-300">${adminNote}</span>
                                </div>
                            </div>

                            <a href="/storage/${item.file_path}" target="_blank" 
                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600">
                                <i class="bi bi-download me-2"></i> File Versi Ini
                            </a>
                        </li>`;
                    container.innerHTML += html;
                });
            })
            .catch(err => {
                container.innerHTML = '<p class="text-center text-red-500">Gagal memuat data.</p>';
            });
    }

    // --- DETAIL & PREVIEW MODAL ---
    function openDetailModal(button) {
        // Ambil Data
        const name = button.getAttribute('data-name');
        const triwulan = button.getAttribute('data-triwulan');
        const tahun = button.getAttribute('data-tahun');
        const start = button.getAttribute('data-start');
        const end = button.getAttribute('data-end');
        const status = button.getAttribute('data-status');
        const fileUrl = button.getAttribute('data-file');
        const opdNote = button.getAttribute('data-opd-note');
        const adminNote = button.getAttribute('data-admin-note');

        // Isi Data ke Modal
        document.getElementById('detail-name').innerText = name;
        document.getElementById('detail-tw').innerText = 'TW ' + triwulan;
        document.getElementById('detail-tahun').innerText = '/ ' + tahun;
        document.getElementById('detail-date').innerText = start + ' s/d ' + end;
        document.getElementById('detail-opd-note').innerText = opdNote;
        document.getElementById('detail-admin-note').innerText = adminNote;

        // Tombol Download
        document.getElementById('detail-download-btn').href = fileUrl;
        document.getElementById('error-download-btn').href = fileUrl;

        // Styling Status
        const statusEl = document.getElementById('detail-status');
        statusEl.innerText = status;
        statusEl.className = "px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide ";
        if (status === 'MENUNGGU') statusEl.classList.add('bg-yellow-100', 'text-yellow-800');
        else if (status === 'REVISI') statusEl.classList.add('bg-red-100', 'text-red-800');
        else statusEl.classList.add('bg-green-100', 'text-green-800');

        // PREVIEW LOGIC
        const iframe = document.getElementById('file-preview');
        const loading = document.getElementById('preview-loading');
        const error = document.getElementById('preview-error');

        iframe.classList.add('hidden');
        error.classList.add('hidden');
        loading.classList.remove('hidden');
        iframe.src = '';

        const extension = fileUrl.split('.').pop().toLowerCase();
        const browserSupported = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        const officeSupported = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

        if (browserSupported.includes(extension)) {
            iframe.src = fileUrl;
            iframe.onload = function() {
                loading.classList.add('hidden');
                iframe.classList.remove('hidden');
            };
            iframe.onerror = function() {
                loading.classList.add('hidden');
                error.classList.remove('hidden');
            };
        } else if (officeSupported.includes(extension)) {
            // Google Docs Viewer
            const googleDocsUrl = `https://docs.google.com/gview?url=${encodeURIComponent(fileUrl)}&embedded=true`;
            iframe.src = googleDocsUrl;
            // Timer fallback karena google docs kadang tidak trigger onload
            setTimeout(() => {
                loading.classList.add('hidden');
                iframe.classList.remove('hidden');
            }, 2500);
        } else {
            loading.classList.add('hidden');
            error.classList.remove('hidden');
        }

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.getElementById('file-preview').src = ''; // Stop loading
    }

    // --- TOGGLE STATUS PERIODE (AJAX) ---
    function toggleStatus(id) {
        const btn = document.getElementById(`btn-status-${id}`);
        const originalText = btn.innerText;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        btn.innerText = '...';
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        fetch(`/triwulan/periode/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.is_open) {
                        btn.className =
                            "px-3 py-1 text-xs font-bold rounded-full transition-all duration-200 border border-opacity-50 bg-green-100 text-green-700 border-green-300 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:border-green-700";
                        btn.innerText = "OPEN (BISA UPLOAD)";
                    } else {
                        btn.className =
                            "px-3 py-1 text-xs font-bold rounded-full transition-all duration-200 border border-opacity-50 bg-red-100 text-red-700 border-red-300 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:border-red-700";
                        btn.innerText = "CLOSED (TERKUNCI)";
                    }
                }
            })
            .catch(error => {
                console.error(error);
                alert('Gagal mengubah status.');
                btn.innerText = originalText;
            })
            .finally(() => {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
    }

    // --- GLOBAL CLICK LISTENER (Tutup Modal saat klik luar) ---
    window.onclick = function(e) {
        if (e.target == document.getElementById('uploadModal')) closeUploadModal();
        if (e.target == document.getElementById('periodModal')) closePeriodModal();
        if (e.target == document.getElementById('verifyModal')) document.getElementById('verifyModal').classList
            .add('hidden');
        if (e.target == document.getElementById('historyModal')) document.getElementById('historyModal').classList
            .add('hidden');
        if (e.target == document.getElementById('detailModal')) closeDetailModal();
        if (e.target == document.getElementById('fileSizeModal')) document.getElementById('fileSizeModal').classList
            .add('hidden');
        if (e.target == document.getElementById('templateModal')) document.getElementById('templateModal').classList
            .add('hidden');
    }
</script>
