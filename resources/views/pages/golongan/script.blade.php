<script>
    let pageUrl = "{{ route('admin.golongan.data') }}";

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
                                    <th class="px-4 py-3">Nama Golongan</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>`;

                if (res.data.length > 0) {
                    res.data.forEach(g => {
                        html += `
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">${g.golongan}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-3">
                                        <button onclick="editGolongan(${g.id}, '${g.golongan}')"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition"
                                            title="Edit">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>
                                        <button onclick="deleteGolongan(${g.id})"
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
                            <td colspan="2" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">
                                Tidak ada data
                            </td>
                        </tr>`;
                }

                html += `
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">`;

                // Pagination buttons
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
                document.getElementById('golongan-table').innerHTML = html;
            })
            .catch(err => console.error(err));
    }

    function openCreateForm() {
        document.getElementById('modalTitle').innerText = "Tambah Golongan";
        document.getElementById('golongan_id').value = "";
        document.getElementById('golongan').value = "";
        document.getElementById('formModal').classList.remove('hidden');
    }

    function editGolongan(id, nama) {
        document.getElementById('modalTitle').innerText = "Edit Golongan";
        document.getElementById('golongan_id').value = id;
        document.getElementById('golongan').value = nama;
        document.getElementById('formModal').classList.remove('hidden');
    }

    function closeForm() {
        document.getElementById('formModal').classList.add('hidden');
    }

    document.getElementById('formGolongan').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('golongan_id').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `/admin/golongan/${id}` : `/admin/golongan`;

        const data = {
            golongan: document.getElementById('golongan').value,
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

    function deleteGolongan(id) {
        if (confirm("Yakin hapus golongan ini?")) {
            fetch(`/admin/golongan/${id}`, {
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
    loadData();
</script>
