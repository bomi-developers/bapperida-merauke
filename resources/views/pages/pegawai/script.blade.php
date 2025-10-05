<script>
    let pageUrl = "{{ route('admin.pegawai.data') }}";

    function loadData(url = pageUrl) {
        fetch(url + "?search=" + document.getElementById('search').value)
            .then(res => res.json())
            .then(res => {
                let html = `
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                    <th class="min-w-[200px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                                        Nama
                                    </th>
                                    <th class="min-w-[200px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                                        NIP
                                    </th>
                                    <th class="min-w-[200px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                                        NIK
                                    </th>
                                    <th class="min-w-[200px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                                        Golongan
                                    </th>
                                    <th class="min-w-[200px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11">
                                        Jabatan
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
                                        <h5 class="font-medium text-black dark:text-white">${b.nama}</h5>
                                    </td>
                                    <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                                        <h5 class="font-medium text-black dark:text-white">${b.nip}</h5>
                                    </td>
                                    <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                                        <h5 class="font-medium text-black dark:text-white">${b.nik}</h5>
                                    </td>
                                    <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                                        <h5 class="font-medium text-black dark:text-white">${b.golongan?.golongan}</h5>
                                    </td>
                                    <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                                        <h5 class="font-medium text-black dark:text-white">${b.jabatan?.jabatan}</h5>
                                    </td>
                                    
                                    <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                        <div class="flex items-center space-x-3.5">
                                            <button onclick='editPegawai(${JSON.stringify(b)})' 
                                                class="hover:text-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button onclick="deletePegawai(${b.id})" 
                                                class="hover:text-primary" title="Hapus">
                                                 <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                    });
                } else {
                    html += `<tr><td colspan="6" class="text-center py-5 dark:text-white">Tidak ada data</td></tr>`;
                }

                html += `</tbody></table>
                             <div class="mt-3 flex gap-2">`;

                res.links.forEach(link => {
                    if (link.url) {
                        html += `
                            <button onclick="loadData('${link.url}')"
                                class="px-3 py-1 rounded 
                                    ${link.active ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-black dark:text-white'}">
                                ${link.label}
                            </button>`;
                    }
                });
                html += `</div>`;
                document.getElementById('pegawai-table').innerHTML = html;
            });
    }

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
        document.getElementById('id_jabatan').value = data.id_jabatan ?? '';
        document.getElementById('id_golongan').value = data.id_golongan ?? '';
        document.getElementById('alamat').value = data.alamat ?? '';
        document.getElementById('formModal').classList.remove('hidden');
    }

    function closeForm() {
        document.getElementById('formModal').classList.add('hidden');
    }

    document.getElementById('formPegawai').addEventListener('submit', function(e) {
        e.preventDefault();

        let id = document.getElementById('pegawai_id').value;
        let method = id ? 'PUT' : 'POST';
        let url = id ? `/admin/pegawai/${id}` : `/admin/pegawai`;

        let data = {
            nama: document.getElementById('nama').value,
            nip: document.getElementById('nip').value,
            nik: document.getElementById('nik').value,
            alamat: document.getElementById('alamat').value,
            id_golongan: document.getElementById('id_golongan').value,
            id_jabatan: document.getElementById('id_jabatan').value,
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
                });
        }
    }

    document.getElementById('search').addEventListener('input', () => loadData());

    loadData();
</script>
