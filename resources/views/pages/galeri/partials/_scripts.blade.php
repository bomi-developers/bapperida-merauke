<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Elements ---
        const modal = document.getElementById('galeri-modal');
        const showModalEl = document.getElementById('show-modal');
        const addBtn = document.getElementById('add-galeri-btn');
        const closeModalBtns = document.querySelectorAll('.close-modal-btn');
        const closeShowModalBtns = document.querySelectorAll('.close-show-modal');
        const form = document.getElementById('galeri-form');

        // GANTI TARGET LISTENER KE CONTAINER UTAMA
        const galeriTableContainer = document.getElementById('galeri-table');

        const saveBtn = document.getElementById('save-btn');

        // --- Elements for Add/Edit Modal ---
        const modalTitle = document.getElementById('modal-title');
        const methodField = document.getElementById('method-field');
        const galeriIdField = document.getElementById('galeri-id-field');
        const judulInput = document.getElementById('judul');
        const keteranganInput = document.getElementById('keterangan');
        const isHighlightedCheckbox = document.getElementById('is_highlighted');
        const existingItemsContainer = document.getElementById('existing-items-container');
        const newItemsContainer = document.getElementById('new-items-container');
        const deletedItemsContainer = document.getElementById('deleted-items-input-container');

        const addImageBtn = document.getElementById('add-image-item');
        const addVideoBtn = document.getElementById('add-video-item');
        const addVideoUrlBtn = document.getElementById('add-video-url-item');

        // Elements for Show Modal
        const showJudul = document.getElementById('show-judul');
        const showKeterangan = document.getElementById('show-keterangan');
        const showItemsGrid = document.getElementById('show-items-grid');
        const noItemsMessage = document.getElementById('no-items-message');
        const loadingModal = document.getElementById('loading-modal');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let newItemCounter = 0;

        // --- Helper: Cek Dark Mode ---
        const isDarkMode = () => document.documentElement.classList.contains('dark');
        const swalColors = () => {
            return isDarkMode() ?
                {
                    background: '#1f2937',
                    color: '#f3f4f6'
                } // bg-gray-800, text-gray-100
                :
                {
                    background: '#ffffff',
                    color: '#1f2937'
                }; // bg-white, text-gray-800
        };

        // --- Functions ---
        const openModal = () => modal.classList.remove('hidden');
        const closeModal = () => modal.classList.add('hidden');
        const openShowModal = () => showModalEl.classList.remove('hidden');
        const closeShowModal = () => showModalEl.classList.add('hidden');
        const openLoadingModal = () => {
            if (loadingModal) loadingModal.classList.remove('hidden');
        };
        const closeLoadingModal = () => {
            if (loadingModal) loadingModal.classList.add('hidden');
        };

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                // Apply dark mode to toast if needed
                if (isDarkMode()) {
                    toast.style.background = '#1f2937';
                    toast.style.color = '#f3f4f6';
                }
            }
        });

        // --- Reset Form ---
        const resetForm = () => {
            form.reset();
            methodField.value = '';
            galeriIdField.value = '';
            existingItemsContainer.innerHTML = '';
            existingItemsContainer.classList.add('hidden');
            newItemsContainer.innerHTML = '';
            deletedItemsContainer.innerHTML = '';
            newItemCounter = 0;
            if (isHighlightedCheckbox) isHighlightedCheckbox.checked = false;
        };

        // --- Functions for Dynamic Items ---
        const renderExistingItem = (item) => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'existing-item-preview';
            itemDiv.dataset.itemId = item.id;
            let previewElement = '',
                fileName = '';

            if (item.tipe_file === 'image') {
                previewElement = `<img src="{{ asset('storage') }}/${item.file_path}" alt="Preview">`;
                fileName = item.file_path.split('/').pop();
            } else if (item.tipe_file === 'video') {
                previewElement =
                    `<div class="video-placeholder"><i class="bi bi-film text-xl text-gray-400 dark:text-gray-500"></i></div>`;
                fileName = item.file_path.split('/').pop();
            } else if (item.tipe_file === 'video_url') {
                previewElement =
                    `<div class="video-placeholder"><i class="bi bi-youtube text-xl text-red-500"></i></div>`;
                fileName = item.file_path;
            }

            itemDiv.innerHTML = `
                ${previewElement}
                <div class="file-info">
                    <span class="file-name" title="${fileName}">${fileName}</span>
                    <span>${item.tipe_file.replace('_', ' ').toUpperCase()}</span>
                    <input type="text" name="existing_captions[${item.id}]" value="${item.caption || ''}" class="form-input w-full mt-1 text-xs" placeholder="Caption (opsional)">
                </div>
                <button type="button" class="remove-existing-item" title="Hapus item ini"><i class="bi bi-trash-fill text-base"></i></button>
            `;
            existingItemsContainer.appendChild(itemDiv);
            existingItemsContainer.classList.remove('hidden');
        };

        const addNewItemInput = (type) => {
            const uniqueId = `new_${newItemCounter++}`;
            const itemDiv = document.createElement('div');
            itemDiv.className = 'galeri-item-input-wrapper';
            itemDiv.dataset.itemId = uniqueId;
            let inputHtml = '',
                previewPlaceholder = '';

            if (type === 'image' || type === 'video') {
                const acceptType = type === 'image' ? 'image/*' : 'video/*';
                const iconClass = type === 'image' ? 'bi-image' : 'bi-film';
                previewPlaceholder =
                    `<div class="placeholder"><i class="bi ${iconClass} text-2xl"></i></div>`;
                inputHtml =
                    `<input type="file" name="items[${uniqueId}][file]" class="form-input w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="${acceptType}" required>`;
            } else if (type === 'video_url') {
                previewPlaceholder =
                    `<div class="placeholder"><i class="bi bi-link-45deg text-2xl"></i></div>`;
                inputHtml =
                    `<input type="url" name="items[${uniqueId}][url]" class="form-input w-full text-sm" placeholder="https://youtube.com/..." required>`;
            }

            itemDiv.innerHTML = `
                <div class="item-preview">${previewPlaceholder}</div>
                <div class="input-area space-y-1">
                     ${inputHtml}
                     <input type="text" name="items[${uniqueId}][caption]" class="form-input w-full text-xs" placeholder="Caption (opsional)">
                     <input type="hidden" name="items[${uniqueId}][type]" value="${type}">
                </div>
                <button type="button" class="remove-new-item" title="Hapus item baru ini"><i class="bi bi-x-circle-fill text-base"></i></button>
            `;
            newItemsContainer.appendChild(itemDiv);

            if (type === 'image' || type === 'video') {
                const fileInput = itemDiv.querySelector('input[type="file"]');
                const previewContainer = itemDiv.querySelector('.item-preview');
                const iconClass = type === 'image' ? 'bi-image' : 'bi-film';

                fileInput.addEventListener('change', (event) => {
                    const file = event.target.files[0];
                    const maxSize = 50 * 1024 * 1024;
                    if (file) {
                        if (file.size > maxSize) {
                            const colors = swalColors();
                            Swal.fire({
                                icon: 'error',
                                title: 'File Terlalu Besar',
                                text: `Ukuran file melebihi batas 50MB.`,
                                background: colors.background,
                                color: colors.color
                            });
                            event.target.value = null;
                            previewContainer.innerHTML =
                                `<div class="placeholder"><i class="bi ${iconClass} text-2xl"></i></div>`;
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            if (type === 'image') previewContainer.innerHTML =
                                `<img src="${e.target.result}" alt="Preview">`;
                            else previewContainer.innerHTML =
                                `<div class="video-placeholder"><i class="bi bi-film text-xl text-gray-400 dark:text-gray-500"></i></div>`;
                        }
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.innerHTML =
                            `<div class="placeholder"><i class="bi ${iconClass} text-2xl"></i></div>`;
                    }
                });
            }
        };

        const addDeletedItemInput = (itemId) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleted_items[]';
            input.value = itemId;
            deletedItemsContainer.appendChild(input);
        };

        // --- Event Listeners ---
        if (addBtn) {
            addBtn.addEventListener('click', () => {
                resetForm();
                modalTitle.textContent = 'Tambah Album Galeri Baru';
                form.action = '{{ route('admin.galeri.store') }}';
                methodField.value = 'POST';
                openModal();
            });
        }

        if (closeModalBtns) closeModalBtns.forEach(btn => btn.addEventListener('click', closeModal));
        if (closeShowModalBtns) closeShowModalBtns.forEach(btn => btn.addEventListener('click',
        closeShowModal));

        if (addImageBtn) addImageBtn.addEventListener('click', () => addNewItemInput('image'));
        if (addVideoBtn) addVideoBtn.addEventListener('click', () => addNewItemInput('video'));
        if (addVideoUrlBtn) addVideoUrlBtn.addEventListener('click', () => addNewItemInput('video_url'));

        existingItemsContainer.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.remove-existing-item');
            if (removeBtn) {
                const itemPreviewDiv = removeBtn.closest('.existing-item-preview');
                addDeletedItemInput(itemPreviewDiv.dataset.itemId);
                itemPreviewDiv.remove();
            }
        });

        newItemsContainer.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.remove-new-item');
            if (removeBtn) removeBtn.closest('.galeri-item-input-wrapper').remove();
        });

        // ============================================================
        // PERBAIKAN UTAMA: Event Delegation pada Container Tabel (#galeri-table)
        // ============================================================
        if (galeriTableContainer) {
            galeriTableContainer.addEventListener('click', async (e) => {
                // Cari tombol terdekat yang diklik (karena di dalam tombol ada icon)
                const target = e.target.closest('button');

                // Jika yang diklik bukan tombol, abaikan
                if (!target) return;

                const id = target.dataset.id;

                // 1. LOGIK TOMBOL EDIT
                if (target.classList.contains('edit-btn')) {
                    resetForm();
                    modalTitle.textContent = 'Edit Album Galeri';
                    methodField.value = 'PUT';
                    galeriIdField.value = id;
                    form.action = `{{ url('admin/galeri') }}/${id}`;

                    try {
                        // Tampilkan loading di tombol atau modal
                        const response = await fetch(`{{ url('admin/galeri') }}/${id}`);
                        if (!response.ok) throw new Error('Gagal mengambil data');
                        const galeri = await response.json();

                        judulInput.value = galeri.judul;
                        keteranganInput.value = galeri.keterangan || '';
                        isHighlightedCheckbox.checked = galeri.is_highlighted;

                        existingItemsContainer.innerHTML = '';
                        if (galeri.items && galeri.items.length > 0) {
                            existingItemsContainer.classList.remove('hidden');
                            galeri.items.forEach(item => renderExistingItem(item));
                        } else {
                            existingItemsContainer.classList.add('hidden');
                        }
                        openModal();
                    } catch (error) {
                        console.error("Error:", error);
                        const colors = swalColors();
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal memuat data untuk diedit.',
                            icon: 'error',
                            background: colors.background,
                            color: colors.color
                        });
                    }
                }

                // 2. LOGIK TOMBOL DELETE (DENGAN DARK MODE)
                if (target.classList.contains('delete-btn')) {
                    const colors = swalColors(); // Ambil warna sesuai mode

                    Swal.fire({
                        title: 'Anda yakin?',
                        text: "Album ini beserta semua isinya akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        background: colors.background,
                        color: colors.color
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch(
                                    `{{ url('admin/galeri') }}/${id}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Accept': 'application/json'
                                        }
                                    });
                                const res = await response.json();
                                if (!response.ok || !res.success) throw new Error(res
                                    .message || 'Gagal menghapus');

                                // Reload tabel agar data sinkron
                                loadData();

                                Toast.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            } catch (error) {
                                Swal.fire({
                                    title: 'Error',
                                    text: error.message,
                                    icon: 'error',
                                    background: colors.background,
                                    color: colors.color
                                });
                            }
                        }
                    });
                }

                // 3. LOGIK TOMBOL SHOW (DETAIL)
                if (target.classList.contains('show-btn')) {
                    try {
                        const response = await fetch(`{{ url('admin/galeri') }}/${id}`);
                        if (!response.ok) throw new Error('Gagal mengambil data');
                        const galeri = await response.json();

                        showJudul.textContent = galeri.judul;
                        showKeterangan.textContent = galeri.keterangan || '';
                        showItemsGrid.innerHTML = '';

                        if (galeri.items && galeri.items.length > 0) {
                            noItemsMessage.classList.add('hidden');
                            galeri.items.forEach(item => {
                                const itemElement = document.createElement('div');
                                itemElement.className =
                                    'aspect-square rounded-lg overflow-hidden relative group border border-gray-200 dark:border-slate-700 bg-black';
                                let mediaElement = '';

                                if (item.tipe_file === 'image') {
                                    mediaElement =
                                        `<img src="{{ asset('storage') }}/${item.file_path}" alt="${item.caption || galeri.judul}" class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105">`;
                                } else if (item.tipe_file === 'video') {
                                    mediaElement =
                                        `<div class="w-full h-full flex items-center justify-center cursor-pointer video-placeholder-show"><i class="bi bi-play-circle-fill text-5xl text-white/70 group-hover:text-white transition-colors"></i></div><video controls class="w-full h-full object-contain absolute inset-0 hidden"><source src="{{ asset('storage') }}/${item.file_path}" type="video/mp4">Browser Anda tidak mendukung tag video.</video>`;
                                } else if (item.tipe_file === 'video_url') {
                                    // Logic Youtube ID
                                    const getYouTubeId = (url) => {
                                        if (!url) return null;
                                        const regExp =
                                            /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                                        const match = url.match(regExp);
                                        return (match && match[2].length === 11) ?
                                            match[2] : null;
                                    };
                                    const videoId = getYouTubeId(item.file_path);
                                    if (videoId) mediaElement =
                                        `<iframe class="w-full h-full" src="https://www.youtube.com/embed/${videoId}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
                                    else mediaElement =
                                        `<div class="w-full h-full flex items-center justify-center text-slate-500 text-xs text-center p-2">URL Video tidak valid</div>`;
                                }

                                itemElement.innerHTML = mediaElement;
                                if (item.caption) itemElement.innerHTML +=
                                    `<div class="absolute bottom-0 left-0 right-0 p-2 bg-black/60 text-white text-xs text-center truncate pointer-events-none">${item.caption}</div>`;
                                showItemsGrid.appendChild(itemElement);
                            });
                        } else {
                            noItemsMessage.classList.remove('hidden');
                        }
                        openShowModal();
                    } catch (error) {
                        const colors = swalColors();
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal memuat detail album.',
                            icon: 'error',
                            background: colors.background,
                            color: colors.color
                        });
                    }
                }
            });
        }

        // --- SUBMIT FORM ---
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const originalButtonText = saveBtn.innerHTML;
                saveBtn.innerHTML =
                    `<i class="bi bi-arrow-repeat animate-spin mr-2"></i> Memproses...`;
                saveBtn.disabled = true;
                openLoadingModal();

                const formData = new FormData(form);

                // Tambahkan checkbox manual karena jika uncheck tidak terkirim
                if (!isHighlightedCheckbox.checked) {
                    // Jika uncheck, kita tidak perlu append apa-apa atau append '0'
                    // Laravel biasanya handle checkbox boolean dgn validation atau default false
                    // Tapi agar aman, FormData tidak mengirim field unchecked.
                } else {
                    formData.set('is_highlighted', '1');
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST', // Method POST (nanti _method PUT di handle Laravel jika edit)
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    const res = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && res.errors) {
                            let errorHtml =
                            '<ul class="text-left list-disc list-inside space-y-1">';
                            for (const key in res.errors) {
                                res.errors[key].forEach(message => {
                                    errorHtml += `<li>${message}</li>`;
                                });
                            }
                            errorHtml += '</ul>';
                            const colors = swalColors();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Validasi',
                                html: errorHtml,
                                background: colors.background,
                                color: colors.color
                            });
                        } else {
                            throw new Error(res.message ||
                            `HTTP error! status: ${response.status}`);
                        }
                    } else {
                        // SUKSES
                        closeModal();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                        loadData(); // Reload data tabel via AJAX
                    }
                } catch (error) {
                    const colors = swalColors();
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'Terjadi kesalahan saat menyimpan data.',
                        icon: 'error',
                        background: colors.background,
                        color: colors.color
                    });
                } finally {
                    saveBtn.innerHTML = originalButtonText;
                    saveBtn.disabled = false;
                    closeLoadingModal();
                }
            });
        }

        // --- Show Items Logic (Play Video) ---
        showItemsGrid.addEventListener('click', (e) => {
            const placeholder = e.target.closest('.video-placeholder-show');
            if (placeholder) {
                const videoElement = placeholder.nextElementSibling;
                if (videoElement && videoElement.tagName === 'VIDEO') {
                    placeholder.classList.add('hidden');
                    videoElement.classList.remove('hidden');
                    videoElement.play();
                }
            }
        });
    });

    // --- Script Load Data (AJAX Search & Pagination) ---
    let pageUrl = "{{ route('admin.galeri.data') }}";

    function loadData(url = pageUrl) {
        const search = encodeURIComponent(document.getElementById('search').value.trim());
        let finalUrl = new URL(url, window.location.origin);
        if (search !== "") finalUrl.searchParams.set("search", search);

        // Render Skeleton/Loading State
        document.getElementById('galeri-table').innerHTML = `
            <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                        <tr><th class="px-6 py-3">#</th><th class="px-6 py-3">Cover</th><th class="px-6 py-3">Judul Album</th><th class="px-6 py-3">Jumlah Item</th><th class="px-6 py-3">Tanggal Dibuat</th><th class="px-6 py-3">Status</th><th class="px-6 py-3 text-center">Aksi</th></tr>
                    </thead>
                    <tbody><tr><td colspan="7" class="text-center py-6"><div class="w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mx-auto"></div></td></tr></tbody>
                </table>
            </div>`;

        fetch(finalUrl)
            .then(res => res.json())
            .then(res => {
                // ... (Kode Render HTML Tabel Anda sebelumnya yang di _script lama) ...
                // SAYA SALIN ULANG RENDERNYA DI BAWAH AGAR LENGKAP
                let html = `
                    <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                                <tr>
                                    <th scope="col" class="px-6 py-3">#</th>
                                    <th scope="col" class="px-6 py-3">Cover</th>
                                    <th scope="col" class="px-6 py-3 min-w-[200px]">Judul Album</th>
                                    <th scope="col" class="px-6 py-3">Jumlah Item</th>
                                    <th scope="col" class="px-6 py-3">Tanggal Dibuat</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>`;

                if (res.data.length > 0) {
                    res.data.forEach((g, i) => {
                        const statusBadge = g.is_highlighted ?
                            `<span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">Pilihan</span>` :
                            `<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Normal</span>`;

                        let coverHtml =
                            `<div class="w-16 h-12 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center"><i class="bi bi-image-alt text-2xl text-gray-400"></i></div>`;
                        if (g.first_item) {
                            if (g.first_item.tipe_file === 'image') coverHtml =
                                `<img src="{{ asset('storage') }}/${g.first_item.file_path}" alt="Cover" class="w-16 h-12 object-cover rounded-md">`;
                            else if (g.first_item.tipe_file === 'video' || g.first_item.tipe_file ===
                                'video_url') coverHtml =
                                `<div class="w-16 h-12 bg-gray-700 rounded-md flex items-center justify-center"><i class="bi bi-film text-2xl text-gray-400"></i></div>`;
                        }

                        html += `
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">${res.from + i}</td>
                                <td class="px-6 py-4">${coverHtml}</td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">${g.judul}</th>
                                <td class="px-6 py-4">${g.items_count || 0} Item</td>
                                <td class="px-6 py-4">${new Date(g.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</td>
                                <td class="px-6 py-4">${statusBadge}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="show-btn text-green-600 hover:bg-green-100 rounded-full w-8 h-8 flex items-center justify-center" title="Detail" data-id="${g.id}"><i class="bi bi-eye-fill"></i></button>
                                        <button class="edit-btn text-indigo-600 hover:bg-indigo-100 rounded-full w-8 h-8 flex items-center justify-center" title="Edit" data-id="${g.id}"><i class="bi bi-pencil-square"></i></button>
                                        <button class="delete-btn text-red-600 hover:bg-red-100 rounded-full w-8 h-8 flex items-center justify-center" title="Hapus" data-id="${g.id}"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>`;
                    });
                } else {
                    html +=
                        `<tr><td colspan="7" class="text-center py-6 text-gray-500">Belum ada album galeri.</td></tr>`;
                }

                html += `</tbody></table></div><div class="mt-4 flex flex-wrap gap-2">`;

                // Pagination Links
                res.links.forEach(link => {
                    if (link.url) {
                        const activeClass = link.active ? 'bg-indigo-600 text-white' :
                            'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300';
                        html +=
                            `<button onclick="loadData('${link.url}')" class="px-3 py-1 text-sm rounded ${activeClass}">${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}</button>`;
                    }
                });
                html += `</div>`;

                document.getElementById('galeri-table').innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                document.getElementById('galeri-table').innerHTML =
                    '<p class="text-red-500 text-center p-4">Gagal memuat data.</p>';
            });
    }

    // Init Load
    document.getElementById('search').addEventListener('input', () => loadData());
    loadData();
</script>
