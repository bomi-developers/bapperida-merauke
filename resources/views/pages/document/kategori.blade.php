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
        <div class="mb-6">
            <div class="relative w-full md:w-1/3">
                <!-- Icon -->
                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>

                <!-- Input -->
                <input type="text" id="search" placeholder="Cari kategori dokumen..."
                    class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border border-gray-300 dark:border-gray-700 
             bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
             focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200" />
            </div>
        </div>

        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <div id="kategori-table" class="max-w-full overflow-x-auto p-6"></div>
        </div>
    </main>

    {{-- MODAL AREA --}}

    <!-- Modal Tambah/Edit Kategori -->
    <div id="kategori-modal"
        class="fixed inset-0 z-50 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-lg">
            <div class=" flex justify-between items-center p-6 ">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-white"></h3>
                <button id="close-modal-btn"
                    class="text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
            </div>
            <form id="kategori-form" novalidate>
                <div class="modal-content px-6">
                    <input type="hidden" name="_method" id="method-field">
                    <input type="hidden" name="kategori_id" id="kategori-id-field">
                    <div>
                        <label for="nama_kategori"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                            Kategori</label>
                        <input type="text" id="nama_kategori" name="nama_kategori"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200"
                            required>
                    </div>
                </div>
                <div class="p-6  flex justify-end gap-2">
                    <button type="submit" id="save-btn"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Elements
                const modal = document.getElementById('kategori-modal');
                const addBtn = document.getElementById('add-kategori-btn');
                const closeModalBtn = document.getElementById('close-modal-btn');
                const form = document.getElementById('kategori-form');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                // Helpers
                const openModal = () => modal.classList.remove('hidden');
                const closeModal = () => modal.classList.add('hidden');
                const resetForm = () => {
                    form.reset();
                    form.action = "";
                    document.getElementById('method-field').value = "";
                    document.getElementById('kategori-id-field').value = "";
                };

                // Create
                addBtn.addEventListener('click', () => {
                    resetForm();
                    document.getElementById('modal-title').textContent = "Tambah Kategori Baru";
                    form.action = "{{ route('admin.doctkategori.store') }}";
                    openModal();
                });

                // Close modal
                closeModalBtn.addEventListener('click', closeModal);

                // Submit
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const method = document.getElementById('method-field').value || "POST";
                    const formData = {
                        nama_kategori: document.getElementById('nama_kategori').value,
                        _method: method
                    };

                    const response = await fetch(form.action, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify(formData)
                    });

                    const res = await response.json();

                    if (!response.ok) {
                        let errors = Object.values(res.errors).map(v => `<li>${v}</li>`).join('');
                        Swal.fire({
                            icon: "error",
                            title: "Validasi Gagal",
                            html: `<ul class='text-left list-disc pl-4'>${errors}</ul>`
                        });
                        return;
                    }

                    closeModal();
                    Toast.fire({
                        icon: "success",
                        title: res.message
                    });
                    loadData();
                    document.getElementById('kategori-table').innerHTML = html;
                    // ========== EVENT HANDLER EDIT ==========
                    document.querySelectorAll('.edit-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.dataset.id;

                            fetch(`/admin/doctkategori/${id}`)
                                .then(res => res.json())
                                .then(data => {
                                    // isi form
                                    document.getElementById('modal-title').textContent =
                                        "Edit Kategori";
                                    document.getElementById('nama_kategori').value =
                                        data.nama_kategori;

                                    document.getElementById('method-field').value =
                                        "PUT";
                                    document.getElementById('kategori-id-field').value =
                                        id;

                                    form.action = `/admin/doctkategori/${id}`;

                                    // buka modal
                                    document.getElementById('kategori-modal').classList
                                        .remove('hidden');
                                });
                        });
                    });

                    // ========== EVENT HANDLER HAPUS ==========
                    document.querySelectorAll('.delete-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.dataset.id;

                            Swal.fire({
                                title: 'Hapus kategori?',
                                text: "Data tidak dapat dikembalikan!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, hapus',
                            }).then(result => {
                                if (result.isConfirmed) {
                                    fetch(`/admin/doctkategori/${id}`, {
                                            method: "DELETE",
                                            headers: {
                                                "X-CSRF-TOKEN": document
                                                    .querySelector(
                                                        'meta[name="csrf-token"]'
                                                    ).content
                                            }
                                        })
                                        .then(res => res.json())
                                        .then(res => {
                                            Toast.fire({
                                                icon: "success",
                                                title: res.message
                                            });
                                            loadData(); // reload data
                                        });
                                }
                            });
                        });
                    });
                });
            });
        </script>
        <script>
            const pageUrl = "{{ route('admin.doctkategori.data') }}";

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
            });

            let deleteId = null;

            // 🔹 Load Data
            function loadData(url = pageUrl) {
                const search = document.getElementById('search').value;

                let finalUrl = new URL(url, window.location.origin);
                if (search !== "") {
                    finalUrl.searchParams.set("search", search);
                }
                // loading table
                document.getElementById('kategori-table').innerHTML = `
                <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-sm uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                            <tr>
                                    <th class="px-4 py-3 w-14">#</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Dokumen</th>
                                    <th class="px-4 py-3 text-center w-28">Aksi</th>
                                </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center py-6 my-4">
                                    <div class="w-10 h-10 border-4 border-gray-200 border-t-indigo-600 rounded-full animate-spin mx-auto"></div> Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;

                fetch(finalUrl)
                    .then(res => res.json())
                    .then(res => {
                        let html = `
                    <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg border border-gray-300 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
                            <thead class="sticky top-0 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 uppercase text-xs font-semibold">
                                <tr>
                                    <th class="px-4 py-3 w-14">#</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Dokumen</th>
                                    <th class="px-4 py-3 text-center w-28">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                `;

                        // 🔹 Jika Ada Data
                        if (res.data.length > 0) {
                            res.data.forEach((b, i) => {
                                html += `
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                <td class="px-4 py-3">${i + 1}</td>
                                <td class="px-4 py-3 font-medium">${b.nama_kategori}</td>
                                <td class="px-4 py-3 font-medium"><span class="px-3 py-2 text-md rounded-full transition-all duration-200 border border-opacity-50 bg-green-100 text-green-700 border-green-300 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:border-green-700">
                                    <strong>${b.documents_count}</strong> Dokumen </span></td>

                                <td class="px-4 py-3 flex justify-center gap-2">
                                   <button
                                            class="edit-btn p-2 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900 hover:text-indigo-800 dark:hover:text-indigo-300 transition"
                                            title="Edit" data-id="${b.id}">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>
                                        <button
                                            class="delete-btn p-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900 hover:text-red-800 dark:hover:text-red-300 transition"
                                            title="Hapus" data-id="${b.id}">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                </td>
                            </tr>
                        `;
                            });
                        } else {
                            html += `
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data
                            </td>
                        </tr>`;
                        }

                        html += `
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2 justify-start">
                `;

                        // 🔹 Pagination kiri
                        res.links.forEach(link => {
                            let buttonUrl = link.url;

                            if (buttonUrl) {
                                let pagUrl = new URL(buttonUrl, window.location.origin);

                                if (search !== "") {
                                    pagUrl.searchParams.set("search", search);
                                }

                                const label = link.label.replace('&laquo;', '«').replace('&raquo;', '»');

                                html += `
                        <button onclick="loadData('${pagUrl}')"
                            class="px-3 py-1 text-sm rounded font-medium transition-colors duration-200
                                ${link.active
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600'}">
                            ${label}
                        </button>`;
                            }
                        });


                        html += `</div>`;

                        document.getElementById('kategori-table').innerHTML = html;
                    })

                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            toast: false,
                            position: 'center',
                            showConfirmButton: true
                        });
                    });
            }

            document.getElementById('search').addEventListener('input', () => loadData());
            loadData();
        </script>
    @endpush
</x-layout>
