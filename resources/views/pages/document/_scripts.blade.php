<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Elements ---
        const modal = document.getElementById('document-modal');
        const showModalEl = document.getElementById('show-modal');
        const addBtn = document.getElementById('add-document-btn');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const closeShowModalBtns = document.querySelectorAll('.close-show-modal');
        const form = document.getElementById('document-form');
        const tableBody = document.getElementById('document-table-body');

        const lainnyaContainer = document.getElementById('lainnya-container');
        const addVisiBtn = document.getElementById('add-visi');
        const addMisiBtn = document.getElementById('add-misi');
        const addKeteranganBtn = document.getElementById('add-keterangan');
        const lainnyaJsonInput = document.getElementById('lainnya-json');

        const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenEl) {
            console.error('CSRF token meta tag not found!');
            Swal.fire('Error', 'Konfigurasi halaman tidak lengkap. CSRF token tidak ditemukan.', 'error');
            return;
        }
        const csrfToken = csrfTokenEl.getAttribute('content');


        // --- Functions ---
        const openModal = () => modal.classList.remove('hidden');
        const closeModal = () => modal.classList.add('hidden');
        const openShowModal = () => showModalEl.classList.remove('hidden');
        const closeShowModal = () => showModalEl.classList.add('hidden');

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        const resetForm = () => {
            form.reset();
            document.getElementById('method-field').value = '';
            document.getElementById('document-id-field').value = '';
            document.getElementById('cover-preview-container').innerHTML = '';
            document.getElementById('file-preview-container').innerHTML = '';
            lainnyaContainer.innerHTML = '';
            lainnyaJsonInput.value = '';
            if (addVisiBtn) addVisiBtn.disabled = false;
            if (addKeteranganBtn) addKeteranganBtn.disabled = false;
            form.querySelector('#file').setAttribute('required', 'required');
        };

        const renderRow = (doc, iteration) => {
            const storagePath = "{{ asset('storage') }}";
            const coverUrl = doc.cover ? `${storagePath}/${doc.cover}` :
                'https://placehold.co/100x60/e2e8f0/e2e8f0?text=No+Cover';
            const fileUrl = `${storagePath}/${doc.file}`;
            const createdDate = new Date(doc.created_at).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

            const kategoriNama = (doc.kategori && doc.kategori.nama_kategori) ? doc.kategori.nama_kategori :
                'Tidak Berkategori';

            const downloadText = doc.download > 0 ? `${doc.download} Kali` : 'Belum Pernah';

            return `
                        <tr id="doc-row-${doc.id}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">${iteration}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center gap-4">
                                    <img src="${coverUrl}" alt="Cover" class="w-24 h-14 object-cover rounded-md flex-shrink-0">
                                    <div>
                                        <span class="font-bold text-lg">${doc.judul}</span><br>
                                        <small>${createdDate}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-indigo-200 dark:bg-indigo-600 px-2 py-1 text-indigo-800 dark:text-indigo-200 rounded-xl">
                                    ${kategoriNama}
                                </span>
                            </td>
                            
                            {{-- ... (Sisa kolom Download, File, Aksi tetap sama) ... --}}
                            
                            <td class="px-6 py-4">
                                <span class="px-3 py-2 text-md rounded-full transition-all duration-200 border border-opacity-50 bg-yellow-100 text-yellow-700 border-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:border-yellow-700">
                                    ${downloadText}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="${fileUrl}" target="_blank"
                                    class="inline-flex items-center gap-1 px-3 py-2 text-md rounded-full transition-all duration-200 border border-opacity-50 bg-green-100 text-green-700 border-green-300 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:border-green-700">
                                    <i class="bi bi-folder2-open text-lg"></i> Lihat File
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-4">
                                    <button class="show-btn p-2 rounded-lg text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900 transition" title="Detail" data-id="${doc.id}">
                                        <i class="bi bi-eye-fill text-base"></i>
                                    </button>
                                    <button class="edit-btn p-2 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition" title="Edit" data-id="${doc.id}">
                                        <i class="bi bi-pencil-square text-base"></i>
                                    </button>
                                    <button class="delete-btn p-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900 transition" title="Hapus" data-id="${doc.id}">
                                        <i class="bi bi-trash text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
        };


        const updateMisiLabels = () => {
            const misiLabels = lainnyaContainer.querySelectorAll('[data-type="misi"] label');
            misiLabels.forEach((label, index) => {
                label.textContent = `Misi ${index + 1}`;
            });
        };

        const buildVisiInput = (value = '') => {
            const div = document.createElement('div');
            div.innerHTML = `
                <div class="json-item flex items-start gap-2" data-type="visi">
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visi</label>
                        <textarea class="form-input w-full" rows="3" placeholder="Masukkan teks visi...">${value}</textarea>
                    </div>
                    <button type="button" class="remove-json-item text-red-500 hover:text-red-700 mt-7" title="Hapus Visi">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            `;
            lainnyaContainer.appendChild(div);
            if (addVisiBtn) addVisiBtn.disabled = true;
        };

        const buildMisiInput = (value = '') => {
            const div = document.createElement('div');
            div.innerHTML = `
                <div class="json-item flex items-start gap-2" data-type="misi">
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Misi</label>
                        <textarea class="form-input w-full" rows="2" placeholder="Masukkan satu poin misi...">${value}</textarea>
                    </div>
                    <button type="button" class="remove-json-item text-red-500 hover:text-red-700 mt-7" title="Hapus Misi">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            `;
            lainnyaContainer.appendChild(div);
            updateMisiLabels();
        };

        const buildKeteranganInput = (value = '') => {
            const div = document.createElement('div');
            div.innerHTML = `
                <div class="json-item flex items-start gap-2" data-type="keterangan">
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan Tambahan</label>
                        <textarea class="form-input w-full" rows="4" placeholder="Masukkan keterangan...">${value}</textarea>
                    </div>
                    <button type="button" class="remove-json-item text-red-500 hover:text-red-700 mt-7" title="Hapus Keterangan">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            `;
            lainnyaContainer.appendChild(div);
            if (addKeteranganBtn) addKeteranganBtn.disabled = true;
        };

        const buildJsonData = () => {
            const data = {};
            const visiItem = lainnyaContainer.querySelector('[data-type="visi"] textarea');
            if (visiItem && visiItem.value.trim() !== '') {
                data.visi = visiItem.value;
            }

            const misiItems = lainnyaContainer.querySelectorAll('[data-type="misi"] textarea');
            if (misiItems.length > 0) {
                const misiValues = Array.from(misiItems).map(textarea => textarea.value).filter(value =>
                    value.trim() !== '');
                if (misiValues.length > 0) data.misi = misiValues;
            }

            const keteranganItem = lainnyaContainer.querySelector('[data-type="keterangan"] textarea');
            if (keteranganItem && keteranganItem.value.trim() !== '') {
                data.keterangan = keteranganItem.value;
            }

            lainnyaJsonInput.value = Object.keys(data).length > 0 ? JSON.stringify(data) : '';
        };

        // --- Event Listeners ---
        if (addBtn) addBtn.addEventListener('click', () => {
            resetForm();
            document.getElementById('modal-title').textContent = 'Tambah Dokumen Baru';
            form.action = '{{ route('admin.documents.store') }}';
            openModal();
        });

        if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
        if (closeShowModalBtns) {
            closeShowModalBtns.forEach(btn => btn.addEventListener('click', closeShowModal));
        }

        if (addVisiBtn) addVisiBtn.addEventListener('click', () => buildVisiInput());
        if (addMisiBtn) addMisiBtn.addEventListener('click', () => buildMisiInput());
        if (addKeteranganBtn) addKeteranganBtn.addEventListener('click', () => buildKeteranganInput());

        if (lainnyaContainer) lainnyaContainer.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.remove-json-item');
            if (removeBtn) {
                const itemToRemove = removeBtn.closest('.json-item');
                if (itemToRemove) {
                    const type = itemToRemove.getAttribute('data-type');
                    itemToRemove.parentElement.remove();

                    if (type === 'visi' && addVisiBtn) addVisiBtn.disabled = false;
                    else if (type === 'keterangan' && addKeteranganBtn) addKeteranganBtn.disabled =
                        false;
                    else if (type === 'misi') updateMisiLabels();
                }
            }
        });

        if (tableBody) {
            tableBody.addEventListener('click', async (e) => {
                const target = e.target.closest('button');
                if (!target) return;
                const id = target.dataset.id;

                if (target.classList.contains('edit-btn')) {
                    resetForm();
                    document.getElementById('modal-title').textContent = 'Edit Dokumen';
                    document.getElementById('method-field').value = 'PUT';
                    document.getElementById('document-id-field').value = id;
                    form.action = `{{ url('admin/documents') }}/${id}`;
                    form.querySelector('#file').removeAttribute('required');

                    const response = await fetch(`{{ url('admin/documents') }}/${id}`);
                    const doc = await response.json();

                    form.querySelector('#judul').value = doc.judul;
                    form.querySelector('#kategori_document_id').value = doc.kategori_document_id;

                    let lainnyaData = doc.lainnya;
                    if (typeof lainnyaData === 'string' && lainnyaData.length > 0) {
                        try {
                            lainnyaData = JSON.parse(lainnyaData);
                        } catch (e) {
                            console.error('Gagal mem-parsing data JSON:', e);
                            lainnyaData = null;
                        }
                    }

                    if (lainnyaData && typeof lainnyaData === 'object') {
                        if (lainnyaData.visi) buildVisiInput(lainnyaData.visi);
                        if (lainnyaData.misi && Array.isArray(lainnyaData.misi)) {
                            lainnyaData.misi.forEach(misiText => buildMisiInput(misiText));
                        }
                        if (lainnyaData.keterangan) buildKeteranganInput(lainnyaData.keterangan);
                    }

                    if (doc.cover) document.getElementById('cover-preview-container').innerHTML =
                        `<img src="{{ asset('storage') }}/${doc.cover}" class="w-32 h-auto rounded-md border">`;
                    if (doc.file) {
                        const downloadUrl = `{{ url('admin/documents') }}/${doc.id}/download`;
                        document.getElementById('file-preview-container').innerHTML =
                            `<a href="${downloadUrl}" class="text-sm text-indigo-600 hover:underline">Unduh file saat ini</a>`;
                    }

                    openModal();
                }

                if (target.classList.contains('delete-btn')) {
                    deleteConfirm({
                        title: 'Hapus Dokumen?',
                        text: 'Dokumen ini akan dihapus permanen!',
                        url: `{{ url('admin/documents') }}/${id}`,
                        onSuccess: function() {
                            document.getElementById(`doc-row-${id}`).remove();
                        }
                    });
                }

                if (target.classList.contains('show-btn')) {
                    const response = await fetch(`{{ url('admin/documents') }}/${id}`);
                    const doc = await response.json();

                    document.getElementById('show-judul').textContent = doc.judul;
                    document.getElementById('show-kategori').textContent = doc.kategori
                        .nama_kategori;
                    document.getElementById('show-cover').innerHTML = doc.cover ?
                        `<img src="{{ asset('storage') }}/${doc.cover}" class="w-full h-100 object-cover rounded-xl  ">` :
                        '<div class="w-full h-64 bg-slate-700/50 rounded-md flex items-center justify-center text-slate-500">Tidak ada cover</div>';

                    const fileName = doc.file.split('/').pop();
                    const downloadUrl = `{{ url('admin/documents') }}/${doc.id}/download`;
                    document.getElementById('show-file').innerHTML = `
                        <a href="${downloadUrl}" class="relative block w-full h-24 rounded-xl overflow-hidden border border-slate-700 group transition-all duration-300  hover:border-indigo-500" title="Unduh ${fileName}">
                            <div class="absolute inset-0 bg-gray-400/20 dark:bg-slate-900/20 flex items-center justify-center">
                                <i class="bi bi-file-earmark-zip-fill text-4xl text-slate-500"></i>
                            </div>
                            <div class="absolute inset-0  backdrop-blur-sm"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-indigo-600/80 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <i class="bi bi-download text-2xl"></i>
                                <span class="mt-1 font-semibold text-xs">Unduh File</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-2 bg-indigo-800/90 text-white text-xs font-medium text-center truncate mt-3">
                                ${fileName}
                            </div>
                        </a>
                    `;

                    const showLainnyaWrapper = document.getElementById('show-lainnya-wrapper');
                    const showLainnyaContainer = document.getElementById('show-lainnya-container');
                    showLainnyaContainer.innerHTML = '';
                    let lainnyaDataToShow = doc.lainnya;
                    if (typeof lainnyaDataToShow === 'string' && lainnyaDataToShow.length > 0) {
                        try {
                            lainnyaDataToShow = JSON.parse(lainnyaDataToShow);
                        } catch (e) {
                            console.error('Gagal mem-parsing data JSON untuk detail:', e);
                            lainnyaDataToShow = null;
                        }
                    }

                    if (lainnyaDataToShow && typeof lainnyaDataToShow === 'object' && Object.keys(
                            lainnyaDataToShow).length > 0) {
                        showLainnyaWrapper.classList.remove('hidden');
                        let html = '';
                        if (lainnyaDataToShow.visi) html +=
                            `<div><h5 class="font-semibold text-indigo-700 dark:text-indigo-400">Visi</h5><p class="mt-1 text-slate-300">${lainnyaDataToShow.visi}</p></div>`;
                        if (lainnyaDataToShow.misi && lainnyaDataToShow.misi.length > 0) {
                            html +=
                                `<div><h5 class="font-semibold text-indigo-700 dark:text-indigo-400">Misi</h5><ul class="list-decimal list-inside mt-2 space-y-1 text-slate-300">`;
                            lainnyaDataToShow.misi.forEach(m => {
                                html += `<li>${m}</li>`;
                            });
                            html += `</ul></div>`;
                        }
                        if (lainnyaDataToShow.keterangan) html +=
                            `<div><h5 class="font-semibold text-indigo-700 dark:text-indigo-400">Keterangan</h5><p class="mt-1 text-gray-600 dark:text-slate-300 whitespace-pre-wrap">${lainnyaDataToShow.keterangan}</p></div>`;
                        showLainnyaContainer.innerHTML = html;
                    } else {
                        showLainnyaWrapper.classList.add('hidden');
                    }

                    openShowModal();
                }
            });
        }

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                buildJsonData();
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();
                if (!response.ok) {
                    let errorMessages = Object.values(res.errors).map(e => `<li>${e}</li>`).join(
                        '');
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Validasi',
                        html: `<ul class="text-left list-disc list-inside">${errorMessages}</ul>`
                    });
                    return;
                }
                const newRowHtml = renderRow(res.data);
                const existingRow = document.getElementById(`doc-row-${res.data.id}`);
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
            });
        }
    });
</script>
<style>
    .show-btn i,
    .edit-btn i,
    .delete-btn i,
    .remove-json-item i {
        pointer-events: none;
    }

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
    }

    .btn-secondary {
        background-color: #e5e7eb;
        color: #1f2937;
        padding: 0.6rem 1.2rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }

    .dark .btn-secondary {
        background-color: #4b5563;
        color: #f9fafb;
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
</style>
