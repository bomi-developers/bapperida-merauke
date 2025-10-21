<script>
    let pageUrl = "{{ route('admin.pegawai.data') }}";

    function loadData(url = pageUrl) {
        const search = document.getElementById('search').value;

        fetch(`${url}?search=${encodeURIComponent(search)}`)
            .then(res => res.json())
            .then(res => {
                let html = `
                    <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">NIP</th>
                                    <th class="px-4 py-3">NIK</th>
                                    <th class="px-4 py-3">Golongan</th>
                                    <th class="px-4 py-3">Jabatan</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>`;

                if (res.data.length > 0) {
                    res.data.forEach(b => {
                        html += `
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">${b.nama}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">${b.nip ?? '-'}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">${b.nik ?? '-'}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">${b.golongan?.golongan ?? '-'}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">${b.jabatan?.jabatan ?? '-'}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-3">
                                        <button onclick='editPegawai(${JSON.stringify(b)})'
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition"
                                            title="Edit">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>
                                        <button onclick="deletePegawai(${b.id})"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
                                            title="Hapus">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                    });
                } else {
                    html += `
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">
                                Tidak ada data
                            </td>
                        </tr>`;
                }

                html += `
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">`;

                // Pagination
                res.links.forEach(link => {
                    if (link.url) {
                        const label = link.label.replace('&laquo;', '«').replace('&raquo;', '»');
                        html += `
                            <button onclick="loadData('${link.url}')"
                                class="px-3 py-1 text-sm rounded font-medium transition-colors duration-200
                                ${link.active
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600'}">
                                ${label}
                            </button>`;
                    }
                });

                html += `</div>`;
                document.getElementById('pegawai-table').innerHTML = html;
            })
            .catch(err => console.error(err));
    }

    // === FORM HANDLING ===
    function openCreateForm() {
        document.getElementById('modalTitle').innerText = "Tambah Pegawai";
        document.getElementById('pegawai_id').value = "";
        document.getElementById('formPegawai').reset();
        document.getElementById('formModal').classList.remove('hidden');
    }

    function editPegawai(data) {
        document.getElementById('modalTitle').innerText = "Edit Pegawai";
        document.getElementById('pegawai_id').value = data.id ?? '';
        document.getElementById('nama').value = data.nama ?? '';
        document.getElementById('nip').value = data.nip ?? '';
        document.getElementById('nik').value = data.nik ?? '';
        document.getElementById('alamat').value = data.alamat ?? '';
        document.getElementById('id_jabatan').value = data.id_jabatan ?? '';
        document.getElementById('id_golongan').value = data.id_golongan ?? '';
        document.getElementById('formModal').classList.remove('hidden');
    }

    function closeForm() {
        document.getElementById('formModal').classList.add('hidden');
    }

    document.getElementById('formPegawai').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('pegawai_id').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `/admin/pegawai/${id}` : `/admin/pegawai`;

        const data = {
            nama: document.getElementById('nama').value,
            nip: document.getElementById('nip').value,
            nik: document.getElementById('nik').value,
            alamat: document.getElementById('alamat').value,
            id_golongan: document.getElementById('id_golongan').value,
            id_jabatan: document.getElementById('id_jabatan').value,
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
                alert(res.message);
                closeForm();
                loadData();
            })
            .catch(err => console.error(err));
    });

    function deletePegawai(id) {
        if (confirm("Yakin hapus pegawai ini?")) {
            fetch(`/admin/pegawai/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                    loadData();
                })
                .catch(err => console.error(err));
        }
    }

    document.getElementById('search').addEventListener('input', () => loadData());

    // Initial load
    loadData();
</script>
