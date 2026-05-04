<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Data slot aktif dari server (untuk peringatan replace)
    const activeSlots = @json(isset($masterTemplates) ? $masterTemplates->keys()->toArray() : []);

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
                // 3. VALIDASI FILE SIZE (Max 100MB) - 4 File Inputs
                // ==========================================
                const maxSize = 100 * 1024 * 1024; // 100MB
                for (let i = 1; i <= 4; i++) {
                    const fi = document.getElementById('fileInput' + i);
                    if (fi) {
                        fi.addEventListener('change', function() {
                            if (this.files[0] && this.files[0].size > maxSize) {
                                this.value = ""; // Reset input
                                document.getElementById('fileSizeModal').classList.remove('hidden');
                            }
                        });
                    }
                }

                // ==========================================
                // 5. BACKGROUND UPLOAD LISTENER
                // ==========================================
                const uploadForm = document.getElementById('uploadForm');
                if (uploadForm) {
                    uploadForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        closeUploadModal();

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
                            title: 'Upload berjalan di latar belakang...'
                        });

                        const formData = new FormData(this);

                        window.uploadProgressManager.upload(this.action, formData, {
                            name: 'Laporan Triwulan',
                            onSuccess: function(response) {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message || 'Upload berhasil!'
                                });
                                // Refresh Data Table
                                if (typeof fetchLaporan === 'function') {
                                    fetchLaporan();
                                } else {
                                    const searchInput = document.getElementById('filter-search');
                                    if (searchInput) searchInput.dispatchEvent(new Event('keyup'));
                                }
                            },
                            onError: function(error) {
                                console.error(error);
                                let errorMessage = 'Gagal upload laporan.';
                                if (error.responseJSON && error.responseJSON.message) {
                                    errorMessage = error.responseJSON.message;
                                }
                                Swal.fire('Gagal', errorMessage, 'error');
                            }
                        });
                    });
                }


                // ==========================================
                // 4. GLOBAL FUNCTIONS (Diakses via onclick HTML)
                // ==========================================

                // --- MODAL HELPERS ---
                function openUploadModal(mode = 'new', id = null) {
                    document.getElementById('uploadModal').classList.remove('hidden');
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

                // ==========================================
                // TEMPLATE MODAL FUNCTIONS (3 Slot)
                // ==========================================
                function openTemplateModal(slot = 1) {
                    selectSlot(slot);
                    document.getElementById('templateModal').classList.remove('hidden');
                }

                function closeTemplateModal() {
                    document.getElementById('templateModal').classList.add('hidden');
                }

                function selectSlot(slot) {
                    // Update hidden input
                    const slotInput = document.getElementById('template_slot');
                    if (slotInput) slotInput.value = slot;

                    const slotNames = {1: 'Indikator', 2: 'Realisasi', 3: 'OPD', 4: 'Distrik'};

                    // Update button styles
                    document.querySelectorAll('.slot-btn').forEach(btn => {
                        const btnSlot = parseInt(btn.getAttribute('data-slot'));
                        if (btnSlot === slot) {
                            btn.className = 'slot-btn relative px-3 py-3 rounded-lg border-2 text-center transition-all duration-200 focus:outline-none border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 ring-2 ring-indigo-200 dark:ring-indigo-800';
                        } else {
                            btn.className = 'slot-btn relative px-3 py-3 rounded-lg border-2 text-center transition-all duration-200 focus:outline-none border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-400';
                        }
                    });

                    // Show/hide replace warning
                    const warning = document.getElementById('slot-replace-warning');
                    const warningText = document.getElementById('slot-replace-text');
                    if (warning && warningText) {
                        if (activeSlots.includes(slot)) {
                            warning.classList.remove('hidden');
                            warningText.textContent = `Template ${slotNames[slot]} sudah ada dan akan digantikan dengan file baru.`;
                        } else {
                            warning.classList.add('hidden');
                        }
                    }
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

                            <div class="flex flex-wrap gap-2">
                                ${item.file_path ? `<a href="/storage/${item.file_path}" target="_blank" 
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600">
                                    <i class="bi bi-download me-2"></i> Indikator
                                </a>` : ''}
                                ${item.file_path_2 ? `<a href="/storage/${item.file_path_2}" target="_blank" 
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600">
                                    <i class="bi bi-download me-2"></i> Realisasi
                                </a>` : ''}
                                ${item.file_path_3 ? `<a href="/storage/${item.file_path_3}" target="_blank" 
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600">
                                    <i class="bi bi-download me-2"></i> OPD
                                </a>` : ''}
                                ${item.file_path_4 ? `<a href="/storage/${item.file_path_4}" target="_blank" 
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600">
                                    <i class="bi bi-download me-2"></i> Distrik
                                </a>` : ''}
                            </div>
                        </li>`;
                                container.innerHTML += html;
                            });
                        })
                        .catch(err => {
                            container.innerHTML = '<p class="text-center text-red-500">Gagal memuat data.</p>';
                        });
                }

                // --- DETAIL & PREVIEW MODAL (3 Files Stacked) ---
                function buildPreviewSection(fileUrl, label, index) {
                    const section = document.createElement('div');
                    section.className = 'bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm';

                    const extension = fileUrl.split('.').pop().toLowerCase();
                    const browserSupported = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                    const officeSupported = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

                    // Header
                    const header = document.createElement('div');
                    header.className = 'flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700';
                    header.innerHTML = `
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-md flex items-center justify-center">
                                <span class="text-white text-xs font-bold">${index}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">${label}</span>
                            <span class="text-xs text-gray-400 uppercase">.${extension}</span>
                        </div>
                        <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                            <i class="bi bi-download"></i> Download
                        </a>
                    `;
                    section.appendChild(header);

                    // Preview Body
                    const body = document.createElement('div');
                    body.className = 'relative';

                    if (browserSupported.includes(extension)) {
                        // PDF / Images → Direct iframe
                        const iframe = document.createElement('iframe');
                        iframe.src = fileUrl;
                        iframe.className = 'w-full border-none bg-white';
                        iframe.style.height = '500px';

                        const loader = document.createElement('div');
                        loader.className = 'absolute inset-0 flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-800 z-10';
                        loader.innerHTML = `
                            <div class="animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent"></div>
                            <p class="mt-2 text-gray-500 text-xs">Memuat ${label}...</p>
                        `;
                        body.appendChild(loader);
                        body.appendChild(iframe);

                        iframe.onload = () => loader.remove();
                        iframe.onerror = () => {
                            loader.innerHTML = `
                                <i class="bi bi-file-earmark-x text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500 text-sm">Gagal memuat preview</p>
                            `;
                        };
                    } else if (officeSupported.includes(extension)) {
                        // Office files → Google Docs Viewer
                        // Buat absolute URL jika perlu
                        let absoluteUrl = fileUrl;
                        if (!fileUrl.startsWith('http')) {
                            absoluteUrl = window.location.origin + fileUrl;
                        }

                        const googleDocsUrl = `https://docs.google.com/gview?url=${encodeURIComponent(absoluteUrl)}&embedded=true`;

                        const iframe = document.createElement('iframe');
                        iframe.src = googleDocsUrl;
                        iframe.className = 'w-full border-none bg-white';
                        iframe.style.height = '500px';
                        iframe.setAttribute('sandbox', 'allow-scripts allow-same-origin allow-popups');

                        const loader = document.createElement('div');
                        loader.className = 'absolute inset-0 flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-800 z-10';
                        loader.innerHTML = `
                            <div class="animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent"></div>
                            <p class="mt-2 text-gray-500 text-xs">Memuat ${label} via Google Docs Viewer...</p>
                        `;
                        body.appendChild(loader);
                        body.appendChild(iframe);

                        // Fallback: hapus loader setelah 4 detik
                        const fallbackTimeout = setTimeout(() => {
                            if (loader.parentNode) {
                                loader.remove();
                            }
                        }, 4000);

                        iframe.onload = () => {
                            clearTimeout(fallbackTimeout);
                            if (loader.parentNode) loader.remove();
                        };

                        iframe.onerror = () => {
                            clearTimeout(fallbackTimeout);
                            loader.innerHTML = `
                                <div class="text-center">
                                    <i class="bi bi-file-earmark-richtext text-4xl text-indigo-400 mb-3"></i>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm font-medium mb-1">Preview tidak tersedia di localhost</p>
                                    <p class="text-gray-400 text-xs mb-3">Google Docs viewer memerlukan URL publik.</p>
                                    <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                                        <i class="bi bi-download"></i> Download untuk melihat
                                    </a>
                                </div>
                            `;
                        };
                    } else {
                        // Unsupported format
                        body.className = 'p-8 text-center';
                        body.innerHTML = `
                            <i class="bi bi-file-earmark-x text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Preview tidak tersedia untuk format .${extension}</p>
                            <p class="text-xs text-gray-400 mt-1">Silakan download file untuk melihat isinya.</p>
                        `;
                    }

                    section.appendChild(body);
                    return section;
                }

                function openDetailModal(button) {
                    // Ambil Data
                    const name = button.getAttribute('data-name');
                    const triwulan = button.getAttribute('data-triwulan');
                    const tahun = button.getAttribute('data-tahun');
                    const start = button.getAttribute('data-start');
                    const end = button.getAttribute('data-end');
                    const status = button.getAttribute('data-status');
                    const fileUrl1 = button.getAttribute('data-file') || '';
                    const fileUrl2 = button.getAttribute('data-file-2') || '';
                    const fileUrl3 = button.getAttribute('data-file-3') || '';
                    const fileUrl4 = button.getAttribute('data-file-4') || '';
                    const opdNote = button.getAttribute('data-opd-note');
                    const adminNote = button.getAttribute('data-admin-note');

                    // Isi Data ke Modal
                    document.getElementById('detail-name').innerText = name;
                    document.getElementById('detail-tw').innerText = 'TW ' + triwulan;
                    document.getElementById('detail-tahun').innerText = '/ ' + tahun;
                    document.getElementById('detail-date').innerText = start + ' s/d ' + end;
                    document.getElementById('detail-opd-note').innerText = opdNote;
                    document.getElementById('detail-admin-note').innerText = adminNote;

                    // Styling Status
                    const statusEl = document.getElementById('detail-status');
                    statusEl.innerText = status;
                    statusEl.className = "px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide ";
                    if (status === 'MENUNGGU') statusEl.classList.add('bg-yellow-100', 'text-yellow-800');
                    else if (status === 'REVISI') statusEl.classList.add('bg-red-100', 'text-red-800');
                    else statusEl.classList.add('bg-green-100', 'text-green-800');

                    // Build file download list (left panel)
                    const filesList = document.getElementById('detail-files-list');
                    filesList.innerHTML = '';
                    const files = [
                        { url: fileUrl1, label: 'Indikator' },
                        { url: fileUrl2, label: 'Realisasi' },
                        { url: fileUrl3, label: 'OPD' },
                        { url: fileUrl4, label: 'Distrik' },
                    ];

                    let hasFiles = false;
                    files.forEach((f) => {
                        if (f.url) {
                            hasFiles = true;
                            const div = document.createElement('div');
                            div.innerHTML = `
                                <a href="${f.url}" target="_blank"
                                    class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:border-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200">
                                    <i class="bi bi-download text-indigo-500"></i> ${f.label}
                                </a>
                            `;
                            filesList.appendChild(div);
                        }
                    });

                    if (!hasFiles) {
                        filesList.innerHTML = '<p class="text-xs text-gray-400">Tidak ada file</p>';
                    }

                    // Build stacked preview (right panel)
                    const previewContainer = document.getElementById('preview-all-files');
                    previewContainer.innerHTML = '';

                    if (!hasFiles) {
                        previewContainer.innerHTML = `
                            <div class="flex flex-col items-center justify-center py-20 text-center">
                                <i class="bi bi-file-earmark text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                <p class="text-gray-400 dark:text-gray-500 font-medium">Tidak ada file untuk ditampilkan.</p>
                            </div>
                        `;
                    } else {
                        files.forEach((f, idx) => {
                            if (f.url) {
                                const section = buildPreviewSection(f.url, f.label, idx + 1);
                                previewContainer.appendChild(section);
                            }
                        });
                    }

                    // Scroll preview to top
                    document.getElementById('preview-container').scrollTop = 0;

                    document.getElementById('detailModal').classList.remove('hidden');
                }

                function closeDetailModal() {
                    document.getElementById('detailModal').classList.add('hidden');
                    // Clear all iframes to stop loading
                    const container = document.getElementById('preview-all-files');
                    if (container) {
                        container.querySelectorAll('iframe').forEach(iframe => iframe.src = '');
                        container.innerHTML = '';
                    }
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
                    if (e.target == document.getElementById('verifyModal')) document.getElementById('verifyModal')
                        .classList
                        .add('hidden');
                    if (e.target == document.getElementById('historyModal')) document.getElementById('historyModal')
                        .classList
                        .add('hidden');
                    if (e.target == document.getElementById('detailModal')) closeDetailModal();
                    if (e.target == document.getElementById('fileSizeModal')) document.getElementById(
                            'fileSizeModal').classList
                        .add('hidden');
                    if (e.target == document.getElementById('templateModal')) closeTemplateModal();
                }

                // --- DELETE LAPORAN (SweetAlert2 Confirmation) ---
                function deleteLaporan(id) {
                    deleteConfirm({
                        title: 'Hapus Laporan?',
                        text: 'Laporan ini akan dihapus permanen beserta file dan riwayatnya!',
                        url: `/triwulan/${id}`,
                        onSuccess: function() {
                            // Refresh tabel via AJAX
                            const searchInput = document.getElementById('filter-search');
                            if (searchInput) {
                                searchInput.dispatchEvent(new Event('keyup'));
                            } else {
                                location.reload();
                            }
                        }
                    });
                }

                // --- EXPOSE FUNCTIONS TO GLOBAL SCOPE (agar bisa dipanggil dari onclick HTML) ---
                window.openUploadModal = openUploadModal;
                window.closeUploadModal = closeUploadModal;
                window.openPeriodModal = openPeriodModal;
                window.closePeriodModal = closePeriodModal;
                window.editPeriod = editPeriod;
                window.openVerifyModal = openVerifyModal;
                window.openHistoryModal = openHistoryModal;
                window.openDetailModal = openDetailModal;
                window.closeDetailModal = closeDetailModal;
                window.toggleStatus = toggleStatus;
                window.openTemplateModal = openTemplateModal;
                window.closeTemplateModal = closeTemplateModal;
                window.selectSlot = selectSlot;
                window.deleteLaporan = deleteLaporan;

    }); // <-- Tutup DOMContentLoaded
</script>
