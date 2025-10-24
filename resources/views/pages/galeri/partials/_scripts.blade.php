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
        const tableBody = document.getElementById('galeri-table-body');
        const saveBtn = document.getElementById('save-btn');

        // --- Elements for Add/Edit Modal (New Structure) ---
        const modalTitle = document.getElementById('modal-title');
        const methodField = document.getElementById('method-field');
        const galeriIdField = document.getElementById('galeri-id-field');
        const judulInput = document.getElementById('judul');
        const keteranganInput = document.getElementById('keterangan');
        // Containers for items
        const existingItemsContainer = document.getElementById('existing-items-container');
        const newItemsContainer = document.getElementById('new-items-container');
        const deletedItemsContainer = document.getElementById('deleted-items-input-container');
        // Add item buttons
        const addImageBtn = document.getElementById('add-image-item');
        const addVideoBtn = document.getElementById('add-video-item');
        const addVideoUrlBtn = document.getElementById('add-video-url-item');

        // Elements for Show Modal
        const showJudul = document.getElementById('show-judul');
        const showKeterangan = document.getElementById('show-keterangan');
        const showItemsGrid = document.getElementById('show-items-grid');
        const noItemsMessage = document.getElementById('no-items-message');

        // --- Elements for Loading Modal ---
        const loadingModal = document.getElementById('loading-modal'); // Modal loading sederhana

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let newItemCounter = 0;
        let currentXhr = null;

        // --- Functions ---
        const openModal = () => modal.classList.remove('hidden');
        const closeModal = () => modal.classList.add('hidden');
        const openShowModal = () => showModalEl.classList.remove('hidden');
        const closeShowModal = () => showModalEl.classList.add('hidden');
        // --- Fungsi untuk modal loading sederhana ---
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
        });

        // --- Reset Form (Updated) ---
        const resetForm = () => {
            form.reset();
            methodField.value = '';
            galeriIdField.value = '';
            existingItemsContainer.innerHTML = '';
            existingItemsContainer.classList.add('hidden');
            newItemsContainer.innerHTML = '';
            deletedItemsContainer.innerHTML = '';
            newItemCounter = 0;
        };

        // --- Render Table Row (Updated) ---
        const renderRow = (galeri) => {
            const itemCount = galeri.items_count !== undefined ? galeri.items_count : 0;
            let coverHtml = '';
            if (galeri.first_item) {
                if (galeri.first_item.tipe_file === 'image') {
                    coverHtml =
                        `<img src="{{ asset('storage') }}/${galeri.first_item.file_path}" alt="Cover" class="w-16 h-12 object-cover rounded-md">`;
                } else if (galeri.first_item.tipe_file === 'video' || galeri.first_item.tipe_file ===
                    'video_url') {
                    coverHtml =
                        `<div class="w-16 h-12 bg-gray-700 rounded-md flex items-center justify-center"><i class="bi bi-film text-2xl text-gray-400"></i></div>`;
                }
            } else {
                coverHtml =
                    `<div class="w-16 h-12 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center"><i class="bi bi-image-alt text-2xl text-gray-400 dark:text-gray-500"></i></div>`;
            }

            return `
                <tr id="galeri-row-${galeri.id}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">${coverHtml}</td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">${galeri.judul}</th>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">${itemCount} Item</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">${new Date(galeri.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-4">
                            <button class="show-btn font-medium text-green-600 dark:text-green-500 hover:underline" title="Detail" data-id="${galeri.id}"><i class="bi bi-eye-fill text-base"></i></button>
                            <button class="edit-btn font-medium text-indigo-600 dark:text-indigo-500 hover:underline" title="Edit" data-id="${galeri.id}"><i class="bi bi-pencil-square text-base"></i></button>
                            <button class="delete-btn font-medium text-red-600 dark:text-red-500 hover:underline" title="Hapus" data-id="${galeri.id}"><i class="bi bi-trash text-base"></i></button>
                        </div>
                    </td>
                </tr>
            `;
        };

        // --- Functions for Dynamic Items in Add/Edit Modal ---
        const renderExistingItem = (item) => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'existing-item-preview';
            itemDiv.dataset.itemId = item.id;

            let previewElement = '';
            let fileName = '';
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
                <button type="button" class="remove-existing-item" title="Hapus item ini">
                    <i class="bi bi-trash-fill text-base"></i>
                </button>
            `;
            existingItemsContainer.appendChild(itemDiv);
            existingItemsContainer.classList.remove('hidden');
        };

        const addNewItemInput = (type) => {
            const uniqueId = `new_${newItemCounter++}`;
            const itemDiv = document.createElement('div');
            itemDiv.className = 'galeri-item-input-wrapper';
            itemDiv.dataset.itemId = uniqueId;

            let inputHtml = '';
            let previewPlaceholder = '';

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
                <div class="item-preview">
                    ${previewPlaceholder}
                </div>
                <div class="input-area space-y-1">
                     ${inputHtml}
                     <input type="text" name="items[${uniqueId}][caption]" class="form-input w-full text-xs" placeholder="Caption (opsional)">
                     <input type="hidden" name="items[${uniqueId}][type]" value="${type}">
                </div>
                <button type="button" class="remove-new-item" title="Hapus item baru ini">
                    <i class="bi bi-x-circle-fill text-base"></i>
                </button>
            `;
            newItemsContainer.appendChild(itemDiv);

            if (type === 'image' || type === 'video') {
                const fileInput = itemDiv.querySelector('input[type="file"]');
                const previewContainer = itemDiv.querySelector('.item-preview');
                const iconClass = type === 'image' ? 'bi-image' : 'bi-film';
                fileInput.addEventListener('change', (event) => {
                    /* Preview logic */ });
            }
        };

        const addDeletedItemInput = (itemId) => {
            console.log('Marking item for deletion:', itemId);
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
                const itemId = itemPreviewDiv.dataset.itemId;
                addDeletedItemInput(itemId);
                itemPreviewDiv.remove();
                if (existingItemsContainer.children.length === 0) {
                    // Biarkan container, jangan sembunyikan
                }
            }
        });

        newItemsContainer.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.remove-new-item');
            if (removeBtn) {
                removeBtn.closest('.galeri-item-input-wrapper').remove();
            }
        });


        if (tableBody) {
            tableBody.addEventListener('click', async (e) => {
                const target = e.target.closest('button');
                if (!target) return;
                const id = target.dataset.id;

                if (target.classList.contains('edit-btn')) {
                    resetForm();
                    modalTitle.textContent = 'Edit Album Galeri';
                    methodField.value = 'PUT';
                    galeriIdField.value = id;
                    form.action = `{{ url('admin/galeri') }}/${id}`;

                    try {
                        const response = await fetch(`{{ url('admin/galeri') }}/${id}`);
                        if (!response.ok) throw new Error('Gagal mengambil data');
                        const galeri = await response.json();
                        judulInput.value = galeri.judul;
                        keteranganInput.value = galeri.keterangan || '';
                        existingItemsContainer.innerHTML = '';
                        if (galeri.items && galeri.items.length > 0) {
                            existingItemsContainer.classList.remove('hidden');
                            galeri.items.forEach(item => renderExistingItem(item));
                        } else {
                            existingItemsContainer.classList.add('hidden');
                        }
                        openModal();
                    } catch (error) {
                        console.error("Error fetching galeri details:", error);
                        Swal.fire('Error', 'Gagal memuat data untuk diedit.', 'error');
                    }
                }

                if (target.classList.contains('delete-btn')) {
                    Swal.fire({
                        title: 'Anda yakin?',
                        text: "Album ini beserta semua isinya akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            let responseText = ''; // Simpan respons teks
                            try {
                                const response = await fetch(
                                    `{{ url('admin/galeri') }}/${id}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Accept': 'application/json'
                                        }
                                    });

                                responseText = await response
                            .text(); // Selalu baca sebagai teks dulu
                                const res = JSON.parse(responseText); // Coba parse

                                if (!response.ok || !res.success) throw new Error(res
                                    .message || 'Gagal menghapus');

                                document.getElementById(`galeri-row-${id}`).remove();
                                Toast.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                                if (tableBody.children.length === 0 || (tableBody
                                        .children.length === 1 && tableBody
                                        .firstElementChild.id === 'no-data-row')) {
                                    tableBody.innerHTML =
                                        `<tr id="no-data-row"><td colspan="5" class="text-center py-12"><p class="text-gray-500 dark:text-gray-400">Belum ada album galeri yang ditambahkan.</p></td></tr>`;
                                }
                            } catch (error) {
                                // Tangani jika parsing JSON gagal (respons bukan JSON)
                                console.error("Error deleting galeri:", error,
                                    "Response Text:", responseText);
                                let errorMsg = error.message;
                                if (error instanceof SyntaxError) { // JSON.parse gagal
                                    errorMsg =
                                        'Gagal menghapus. Respons server tidak valid.';
                                }
                                Swal.fire('Error', errorMsg + (responseText ?
                                        '<br><small>Respons Server: ' + responseText
                                        .substring(0, 100) + '...</small>' : ''),
                                    'error');
                            }
                        }
                    });
                }


                // =======================================================
                // === PERBAIKAN: MENAMBAHKAN KEMBALI LOGIKA SHOW MODAL ===
                // =======================================================
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
                                    'aspect-square rounded-lg overflow-hidden relative group border border-slate-700 bg-black';

                                let mediaElement = '';
                                if (item.tipe_file === 'image') {
                                    mediaElement =
                                        `<img src="{{ asset('storage') }}/${item.file_path}" alt="${item.caption || galeri.judul}" class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105">`;
                                } else if (item.tipe_file === 'video') { // Video Upload
                                    mediaElement = `
                                        <div class="w-full h-full flex items-center justify-center cursor-pointer video-placeholder-show">
                                            <i class="bi bi-play-circle-fill text-5xl text-white/70 group-hover:text-white transition-colors"></i>
                                        </div>
                                        <video controls class="w-full h-full object-contain absolute inset-0 hidden">
                                             <source src="{{ asset('storage') }}/${item.file_path}" type="video/mp4">
                                             Browser Anda tidak mendukung tag video.
                                        </video>
                                    `;
                                } else if (item.tipe_file === 'video_url') { // Video URL
                                    const getYouTubeId = (url) => {
                                        if (!url) return null;
                                        const regExp =
                                            /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                                        const match = url.match(regExp);
                                        return (match && match[2].length === 11) ?
                                            match[2] : null;
                                    };
                                    const videoId = getYouTubeId(item.file_path);
                                    if (videoId) {
                                        mediaElement =
                                            `<iframe class="w-full h-full" src="https://www.youtube.com/embed/${videoId}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
                                    } else {
                                        mediaElement =
                                            `<div class="w-full h-full flex items-center justify-center text-slate-500 text-xs text-center p-2">URL Video tidak valid atau tidak didukung: ${item.file_path}</div>`;
                                    }
                                }


                                itemElement.innerHTML = mediaElement;
                                if (item.caption) {
                                    itemElement.innerHTML +=
                                        `<div class="absolute bottom-0 left-0 right-0 p-2 bg-black/60 text-white text-xs text-center truncate pointer-events-none">${item.caption}</div>`;
                                }
                                showItemsGrid.appendChild(itemElement);
                            });
                        } else {
                            noItemsMessage.classList.remove('hidden');
                        }
                        openShowModal();
                    } catch (error) {
                        console.error("Error fetching galeri details for show:", error);
                        Swal.fire('Error', 'Gagal memuat detail album.', 'error');
                    }
                }
            });
        }

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

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const originalButtonText = saveBtn.innerHTML;
                saveBtn.innerHTML =
                    `<i class="bi bi-arrow-repeat animate-spin mr-2"></i> Memproses...`;
                saveBtn.disabled = true;

                const formData = new FormData();
                const isUpdate = methodField.value === 'PUT';

                // Kumpulkan data teks
                formData.append('judul', judulInput.value);
                formData.append('keterangan', keteranganInput.value);

                if (isUpdate) {
                    formData.append('_method', 'PUT');
                    deletedItemsContainer.querySelectorAll('input[name="deleted_items[]"]').forEach(
                        input => {
                            formData.append('deleted_items[]', input.value);
                        });
                    existingItemsContainer.querySelectorAll('.existing-item-preview').forEach(
                        itemDiv => {
                            const itemId = itemDiv.dataset.itemId;
                            const captionInput = itemDiv.querySelector(
                                `input[name="existing_captions[${itemId}]"]`);
                            if (captionInput) {
                                formData.append(`existing_captions[${itemId}]`, captionInput
                                    .value);
                            }
                        });
                }

                let hasNewFiles = false; // Flag hanya untuk file upload
                const newItemInputs = newItemsContainer.querySelectorAll(
                    '.galeri-item-input-wrapper');
                let hasNewItems = false; // Flag untuk item baru (file atau URL)

                newItemInputs.forEach((itemDiv) => {
                    const uniqueId = itemDiv.dataset.itemId;
                    const fileInput = itemDiv.querySelector('input[type="file"]');
                    const urlInput = itemDiv.querySelector('input[type="url"]');
                    const captionInput = itemDiv.querySelector(
                        'input[type="text"][name$="[caption]"]');
                    const typeInput = itemDiv.querySelector(
                        'input[type="hidden"][name$="[type]"]');

                    if (typeInput) {
                        formData.append(`items[${uniqueId}][type]`, typeInput.value);
                        if (captionInput) formData.append(`items[${uniqueId}][caption]`,
                            captionInput.value);

                        if (fileInput && fileInput.files.length > 0) {
                            formData.append(`items[${uniqueId}][file]`, fileInput.files[0]);
                            hasNewFiles = true; // Set flag JIKA ada file
                        } else if (urlInput && urlInput.value.trim()) {
                            formData.append(`items[${uniqueId}][url]`, urlInput.value);
                            hasNewItems = true; // URL juga item baru
                        }
                    }
                });

                // --- VALIDASI KLIEN DIPERBARUI ---
                const existingItemCount = existingItemsContainer.querySelectorAll(
                    '.existing-item-preview').length;
                const deletedItemCount = deletedItemsContainer.querySelectorAll(
                    'input[name="deleted_items[]"]').length;

                // Hitung item baru yang valid (punya file ATAU url)
                const validNewItemCount = Array.from(newItemInputs).filter(itemDiv => {
                    const fileInput = itemDiv.querySelector('input[type="file"]');
                    const urlInput = itemDiv.querySelector('input[type="url"]');
                    return (fileInput && fileInput.files.length > 0) || (urlInput &&
                        urlInput.value.trim() !== '');
                }).length;

                // Hitung jumlah item yang tersisa setelah dihapus
                const remainingExistingItems = existingItemCount - deletedItemCount;
                // Hitung total item final
                const totalFinalItemCount = remainingExistingItems + validNewItemCount;

                // PERBAIKAN: Hapus validasi ini seperti yang Anda minta
                // if (totalFinalItemCount === 0) {
                //     Swal.fire('Error', 'Album harus memiliki setidaknya satu file media.', 'error');
                //     saveBtn.innerHTML = originalButtonText;
                //     saveBtn.disabled = false;
                //     return;
                // }
                // --- AKHIR VALIDASI DIPERBARUI ---

                console.log('Submitting FormData:');
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) console.log(`${key}: File(${value.name})`);
                    else console.log(`${key}: ${value}`);
                }


                // --- MENGGUNAKAN FETCH DAN MODAL LOADING SEDERHANA ---
                if (hasNewFiles) { // Tampilkan modal loading HANYA jika ada file upload
                    openLoadingModal();
                } else {
                    // Tombol save sudah di-set ke "Memproses..."
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const responseText = await response.text();
                    let res;
                    try {
                        res = JSON.parse(responseText);
                    } catch (e) {
                        console.error("Failed to parse JSON response:", responseText);
                        Swal.fire('Server Error',
                            `Terjadi kesalahan di server. Respons:\n\n${responseText.substring(0, 500)}...`,
                            'error');
                        throw new Error(
                            `Invalid JSON response from server. Status: ${response.status}`);
                    }

                    if (!response.ok) {
                        if (response.status === 422 && res.errors) {
                            let errorHtml =
                            '<ul class="text-left list-disc list-inside space-y-1">';
                            for (const key in res.errors) {
                                res.errors[key].forEach(message => {
                                    let fieldName = key.replace(/^items\.new_\d+\./,
                                            'Item Baru - ').replace(/^items\./, 'Item - ')
                                        .replace(/\./g, ' ').replace(/_/g, ' ');
                                    fieldName = fieldName.charAt(0).toUpperCase() +
                                        fieldName.slice(1);
                                    if (key === 'files') fieldName = 'File Media';
                                    if (key.includes('url')) fieldName = fieldName.replace(
                                        'Url', 'URL');
                                    errorHtml +=
                                        `<li><strong>${fieldName}:</strong> ${message}</li>`;
                                });
                            }
                            errorHtml += '</ul>';
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Validasi',
                                html: errorHtml
                            });
                        } else {
                            throw new Error(res.message ||
                            `HTTP error! status: ${response.status}`);
                        }

                    } else {
                        if (!res.success) {
                            throw new Error(res.message || 'Operasi gagal.');
                        }
                        const newRowHtml = renderRow(res.data);
                        const existingRow = document.getElementById(`galeri-row-${res.data.id}`);

                        if (existingRow) {
                            existingRow.outerHTML = newRowHtml;
                        } else {
                            document.getElementById('no-data-row')?.remove();
                            tableBody.insertAdjacentHTML('afterbegin', newRowHtml);
                        }

                        closeModal();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                    }

                } catch (error) {
                    console.error("Error submitting form:", error);
                    if (!String(error.message).includes('Gagal Validasi') && !String(error.message)
                        .includes('Invalid JSON')) {
                        Swal.fire('Error', error.message ||
                            'Terjadi kesalahan saat menyimpan data.', 'error');
                    }
                } finally {
                    saveBtn.innerHTML = originalButtonText;
                    saveBtn.disabled = false;
                    closeLoadingModal(); // Selalu tutup modal loading
                }
            }); // Akhir event listener submit
        } // Akhir if (form)

    }); // Akhir DOMContentLoaded
</script>
<style>
    /* PERBAIKAN SCROLL: Pastikan area konten bisa scroll */
    #galeri-modal>div {
        display: flex;
        flex-direction: column;
    }

    #galeri-modal form {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        min-height: 0;
    }

    #galeri-modal form>div.overflow-y-auto {
        flex-grow: 1;
        min-height: 0;
    }

    /* Styling dasar */
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
    }

    .dark .form-label {
        color: #d1d5db;
    }

    .form-input {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        width: 100%;
        background-color: #f9fafb;
        color: #111827;
    }

    .dark .form-input {
        border-color: #4b5563;
        background-color: #374151;
        color: #f9fafb;
    }

    .btn-primary {
        background-color: #4f46e5;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: background-color 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Tambah display flex */
    .btn-primary:hover {
        background-color: #4338ca;
    }

    .btn-primary:disabled {
        background-color: #a5b4fc;
        cursor: not-allowed;
    }

    /* Style disabled */
    .btn-secondary {
        background-color: #e5e7eb;
        color: #1f2937;
        padding: 0.6rem 1.2rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .dark .btn-secondary {
        background-color: #4b5563;
        color: #f9fafb;
    }

    .btn-secondary:hover {
        background-color: #d1d5db;
    }

    .dark .btn-secondary:hover {
        background-color: #6b7280;
    }

    .btn-add-item {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
    }

    .dark .btn-add-item {
        border-color: #4b5563;
    }

    .btn-add-item:hover:not(:disabled) {
        background-color: #f3f4f6;
    }

    .dark .btn-add-item:hover:not(:disabled) {
        background-color: #374151;
    }

    .btn-add-item:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Mencegah ikon mencuri klik */
    .show-btn i,
    .edit-btn i,
    .delete-btn i,
    .close-modal-btn i,
    .close-show-modal i,
    .remove-existing-item i,
    .remove-new-item i {
        pointer-events: none;
    }
</style>
