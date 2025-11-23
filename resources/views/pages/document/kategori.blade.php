<x-layout>
    <x-header />
    <main class="p-6">
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manajemen Kategori Dokumen</h2>
            <button id="add-kategori-btn"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Kategori</span>
            </button>
        </div>

        {{-- Tabel Kategori --}}
        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Nama Kategori</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kategori-table-body">
                        @forelse ($kategori as $kat)
                            <tr id="kat-row-{{ $kat->id }}"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $kat->nama_kategori }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-4">
                                        <button
                                            class="edit-btn font-medium text-indigo-600 dark:text-indigo-500 hover:underline"
                                            title="Edit" data-id="{{ $kat->id }}">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>
                                        <button
                                            class="delete-btn font-medium text-red-600 dark:text-red-500 hover:underline"
                                            title="Hapus" data-id="{{ $kat->id }}">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-data-row">
                                <td colspan="2" class="text-center py-12">
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada kategori yang ditambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    {{-- MODAL AREA --}}

    <!-- Modal Tambah/Edit Kategori -->
    <div id="kategori-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg">
            <form id="kategori-form">
                <div class=" flex justify-between items-center p-6 ">
                    <h3 id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-white"></h3>
                    <button id="close-modal-btn"
                        class="text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
                </div>
                <div class="p-6">
                    <input type="hidden" name="_method" id="method-field">
                    <input type="hidden" name="kategori_id" id="kategori-id-field">
                    <div>
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" id="nama_kategori" name="nama_kategori" class="form-input w-full"
                            required>
                    </div>
                </div>
                <div class="p-6  flex justify-end gap-2">
                    {{-- <button type="button" id="close-modal-btn" class="btn-secondary">Batal</button> --}}
                    <button type="submit" id="save-btn" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- Elements ---
                const modal = document.getElementById('kategori-modal');
                const addBtn = document.getElementById('add-kategori-btn');
                const closeModalBtn = document.getElementById('close-modal-btn');
                const form = document.getElementById('kategori-form');
                const tableBody = document.getElementById('kategori-table-body');

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // --- Functions ---
                const openModal = () => modal.classList.remove('hidden');
                const closeModal = () => modal.classList.add('hidden');

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
                    document.getElementById('kategori-id-field').value = '';
                };

                const renderRow = (kat) => {
                    return `
                    <tr id="kat-row-${kat.id}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">${kat.nama_kategori}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">${kat.nama_kategori}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-4">
                                <button class="edit-btn font-medium text-indigo-600 dark:text-indigo-500 hover:underline" title="Edit" data-id="${kat.id}"><i class="bi bi-pencil-square text-base"></i></button>
                                <button class="delete-btn font-medium text-red-600 dark:text-red-500 hover:underline" title="Hapus" data-id="${kat.id}"><i class="bi bi-trash text-base"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
                };

                // --- Event Listeners ---
                if (addBtn) {
                    addBtn.addEventListener('click', () => {
                        resetForm();
                        document.getElementById('modal-title').textContent = 'Tambah Kategori Baru';
                        form.action = '{{ route('admin.doctkategori.store') }}';
                        openModal();
                    });
                }

                if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);

                if (tableBody) {
                    tableBody.addEventListener('click', async (e) => {
                        const target = e.target.closest('button');
                        if (!target) return;
                        const id = target.dataset.id;

                        if (target.classList.contains('edit-btn')) {
                            resetForm();
                            document.getElementById('modal-title').textContent = 'Edit Kategori';
                            document.getElementById('method-field').value = 'PUT';
                            document.getElementById('kategori-id-field').value = id;
                            form.action = `{{ url('admin/document-kategori') }}/${id}`;

                            const response = await fetch(`{{ url('admin/document-kategori') }}/${id}`);
                            const kat = await response.json();

                            form.querySelector('#nama_kategori').value = kat.nama_kategori;
                            openModal();
                        }

                        if (target.classList.contains('delete-btn')) {
                            Swal.fire({
                                title: 'Anda yakin?',
                                text: "Menghapus kategori juga akan menghapus semua dokumen di dalamnya!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Ya, hapus!',
                                cancelButtonText: 'Batal'
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    const response = await fetch(
                                        `{{ url('admin/document-kategori') }}/${id}`, {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken,
                                                'Accept': 'application/json'
                                            }
                                        });
                                    const res = await response.json();
                                    if (res.success) {
                                        document.getElementById(`kat-row-${id}`).remove();
                                        Toast.fire({
                                            icon: 'success',
                                            title: res.message
                                        });
                                    }
                                }
                            });
                        }
                    });
                }

                if (form) {
                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const isUpdating = formData.get('_method') === 'PUT';
                        const data = {
                            nama_kategori: formData.get('nama_kategori')
                        };

                        const response = await fetch(form.action, {
                            method: isUpdating ? 'POST' :
                            'POST', // Tetap POST, method asli di-handle oleh _method
                            body: JSON.stringify(isUpdating ? {
                                ...data,
                                _method: 'PUT'
                            } : data),
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
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
                        const existingRow = document.getElementById(`kat-row-${res.data.id}`);

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
            .delete-btn i {
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
        </style>
    @endpush
</x-layout>
