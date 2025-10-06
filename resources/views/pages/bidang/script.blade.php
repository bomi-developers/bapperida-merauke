<script>
    const pageUrl = "{{ route('admin.bidang.data') }}";
    let deleteId = null;



    // 🔹 Load Data
    function loadData(url = pageUrl) {
        const search = document.getElementById('search').value;
        fetch(`${url}?search=${search}`)
            .then(res => res.json())
            .then(res => {
                let html = `
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-800 text-left text-sm font-semibold">
                                <th class="px-4 py-3 text-gray-700 dark:text-gray-200 w-16">No</th>
                                <th class="px-4 py-3 text-gray-700 dark:text-gray-200">Nama Bidang</th>
                                <th class="px-4 py-3 text-gray-700 dark:text-gray-200">Deskripsi</th>
                                <th class="px-4 py-3 text-gray-700 dark:text-gray-200 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-800 dark:text-gray-100">
                `;

                if (res.data.length > 0) {
                    res.data.forEach((b, i) => {
                        html += `
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                <td class="px-4 py-3">${i + 1}</td>
                                <td class="px-4 py-3 font-medium">${b.nama_bidang}</td>
                                <td class="px-4 py-3">${b.deskripsi ?? ''}</td>
                                <td class="px-4 py-3 flex items-center justify-center gap-3">
                                    <button onclick="editBidang(${b.id}, '${b.nama_bidang}', '${b.deskripsi ?? ''}')"
                                        class="p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400 transition"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button onclick="deleteBidang(${b.id})"
                                        class="p-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900 text-red-600 dark:text-red-400 transition"
                                        title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    html += `
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Tidak ada data
                            </td>
                        </tr>`;
                }

                html += `
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4 flex flex-wrap gap-2 justify-center">
                `;

                res.links.forEach(link => {
                    if (link.url) {
                        html += `
                            <button onclick="loadData('${link.url}')"
                                class="px-3 py-1 rounded-md text-sm font-medium transition
                                       ${link.active 
                                            ? 'bg-blue-600 text-white' 
                                            : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600'}">
                                ${link.label}
                            </button>`;
                    }
                });

                html += `</div>`;
                document.getElementById('bidang-table').innerHTML = html;
            })
            .catch(err => {
                console.error('Error loading data:', err);
                showToast('Gagal memuat data', false);
            });
    }

    // 🔹 Open Form
    function openCreateForm() {
        document.getElementById('modalTitle').innerText = "Tambah Bidang";
        document.getElementById('bidang_id').value = "";
        document.getElementById('nama_bidang').value = "";
        document.getElementById('deskripsi').value = "";
        document.getElementById('formModal').classList.remove('hidden');
    }

    // 🔹 Edit Form
    function editBidang(id, nama, desk) {
        document.getElementById('modalTitle').innerText = "Edit Bidang";
        document.getElementById('bidang_id').value = id;
        document.getElementById('nama_bidang').value = nama;
        document.getElementById('deskripsi').value = desk;
        document.getElementById('formModal').classList.remove('hidden');
    }

    // 🔹 Close Form
    function closeForm() {
        document.getElementById('formModal').classList.add('hidden');
    }

    // 🔹 Submit Form
    document.getElementById('formBidang').addEventListener('submit', e => {
        e.preventDefault();

        const id = document.getElementById('bidang_id').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `/admin/bidang/${id}` : `/admin/bidang`;

        const data = {
            nama_bidang: document.getElementById('nama_bidang').value,
            deskripsi: document.getElementById('deskripsi').value
        };

        fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                closeForm();
                showToast(res.message, true);
                loadData();
            })
            .catch(err => {
                console.error('Error saving data:', err);
                showToast('Terjadi kesalahan saat menyimpan', false);
            });
    });

    // 🔹 Delete with confirmation modal
    function deleteBidang(id) {
        deleteId = id;
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    document.getElementById('cancelDelete').addEventListener('click', () => {
        document.getElementById('confirmModal').classList.add('hidden');
    });

    document.getElementById('confirmDelete').addEventListener('click', () => {
        document.getElementById('confirmModal').classList.add('hidden');
        if (deleteId) {
            fetch(`/admin/bidang/${deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    showToast(res.message, true);
                    loadData();
                })
                .catch(err => {
                    console.error('Error deleting data:', err);
                    showToast('Gagal menghapus data', false);
                });
        }
    });

    // 🔹 Search realtime
    document.getElementById('search').addEventListener('input', () => loadData());

    // 🔹 Initial Load
    loadData();
</script>
