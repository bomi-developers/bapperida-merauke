<script>
    let pageUrl = "{{ route('admin.jabatan.data') }}";

    function loadData(url = pageUrl) {
        fetch(url + "?search=" + document.getElementById('search').value)
            .then(res => res.json())
            .then(res => {
                let html = `
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                  
                                    <th class="min-w-[200px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                                        Nama jabatan
                                    </th>
                            
                                    <th class="px-4 py-4 font-medium text-black dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>`;

                if (res.data.length > 0) {
                    res.data.forEach(b => {
                        html += `
                                <tr>
                                    <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                                        <h5 class="font-medium text-black dark:text-white">${b.jabatan}</h5>
                                    </td>
                                  
                                    <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                        <div class="flex items-center space-x-3.5">
                                            <button onclick="editJabatan(${b.id}, '${b.jabatan}')" 
                                                class="hover:text-primary" title="Edit">
                                                   <i class="bi bi-pencil"></i>
                                            </button>
                                            <button onclick="deleteJabaran(${b.id})" 
                                                class="hover:text-primary" title="Hapus">
                                                 <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                    });
                } else {
                    html += `<tr><td colspan="3" class="text-center py-5 dark:text-white">Tidak ada data</td></tr>`;
                }

                html += `</tbody></table>
                             <div class="mt-3 flex gap-2">`;

                res.links.forEach(link => {
                    if (link.url) {
                        html +=
                            `<button onclick="loadData('${link.url}')" 
                                    class="px-3 py-1 rounded 
                                           ${link.active ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-black dark:text-white'}">
                                    ${link.label}
                                </button>`;
                    }
                });
                html += `</div>`;
                document.getElementById('jabatan-table').innerHTML = html;
            });
    }

    function openCreateForm() {
        document.getElementById('modalTitle').innerText = "Tambah jabatan";
        document.getElementById('jabatan_id').value = "";
        document.getElementById('jabatan').value = "";
        document.getElementById('formModal').classList.remove('hidden');
    }

    function editJabatan(id, nama, desk) {
        document.getElementById('modalTitle').innerText = "Edit jabatan";
        document.getElementById('jabatan_id').value = id;
        document.getElementById('jabatan').value = nama;
        document.getElementById('formModal').classList.remove('hidden');
    }

    function closeForm() {
        document.getElementById('formModal').classList.add('hidden');
    }

    document.getElementById('formJabatan').addEventListener('submit', function(e) {
        e.preventDefault();

        let id = document.getElementById('jabatan_id').value;
        let method = id ? 'PUT' : 'POST';
        let url = id ? `/admin/jabatan/${id}` : `/admin/jabatan`;

        let data = {
            jabatan: document.getElementById('jabatan').value,
        };

        fetch(url, {
                method: method,
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

    function deleteJabaran(id) {
        if (confirm("Yakin hapus jabatan ini?")) {
            fetch(`/admin/jabatan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                    loadData();
                });
        }
    }

    document.getElementById('search').addEventListener('input', () => loadData());

    loadData();
</script>
