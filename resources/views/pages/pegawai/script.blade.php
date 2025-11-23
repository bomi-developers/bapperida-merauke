<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let pageUrl = "{{ route('admin.pegawai.data') }}";
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });

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
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3"></th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Bidang</th>
                                    <th class="px-4 py-3">Golongan</th>
                                    <th class="px-4 py-3">Akun</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>`;
                if (res.data.length > 0) {
                    res.data.forEach((b, i) => {
                        html += `
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">${i +1}</td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100"><i class="bi bi-person-bounding-box text-2xl text-indigo-600 dark:text-indigo-400"></i></td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100"><b>${b.nama}</b><br><small class="bg-indigo-200 dark:bg-indigo-600 px-2 py-1 text-indigo-800 dark:text-indigo-200 rounded-xl">${b.nip ?? '-'}</small></td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100"><b>${b.bidang?.nama_bidang ?? '-'}</b><br><small class="bg-indigo-200 dark:bg-indigo-600 px-2 py-1 text-indigo-800 dark:text-indigo-200 rounded-xl">${b.jabatan?.jabatan ?? '-'}</small></td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">${b.golongan?.golongan ?? '-'}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    ${b.user 
                                        ? `<b>${b.user.role}<b><br><small class="bg-green-200 dark:bg-green-600 px-2 py-1 text-green-800 dark:text-green-200 rounded-xl">${b.user.email}</small>` 
                                        : `<small class="bg-red-200 dark:bg-red-600 px-2 py-1 text-red-800 dark:text-red-200 rounded-xl">Belum ada akun</small>`
                                    }
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-3">
                                        <button onclick='userPegawai(${JSON.stringify(b)})'
                                            class="p-2 rounded-lg text-yellow-600 dark:text-yellow-400 hover:bg-yellow-100 dark:hover:bg-yellow-900 hover:text-yellow-800 dark:hover:text-yellow-300 transition"
                                            title="Edit">
                                            <i class="bi bi-person-fill text-lg"></i>
                                        </button>
                                        <button onclick='editPegawai(${JSON.stringify(b)})'
                                            class="p-2 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900 hover:text-indigo-800 dark:hover:text-indigo-300 transition"
                                            title="Edit">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>
                                        <button onclick="deletePegawai(${b.id})"
                                            class="p-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900 hover:text-red-800 dark:hover:text-red-300 transition"
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

    function openCreateForm() {
        document.getElementById('modalTitle').innerText = "Tambah Pegawai";
        document.getElementById('pegawai_id').value = "";
        document.getElementById('formPegawai').reset();
        document.getElementById('formModal').classList.remove('hidden');
    }
    document.getElementById('userForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        let btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        let formData = new FormData();
        formData.append('id_pegawai', document.getElementById('id_pegawai').value);
        formData.append('username', document.getElementById('username').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('role', document.getElementById('role').value);

        try {
            let res = await fetch("{{ route('admin.akun.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            let data = await res.json();

            if (res.status === 422) {
                // validasi gagal
                let msg = Object.values(data.errors)
                    .map(err =>
                        `<div class="text-red-500 dark:text-red-300 dark:bg-red-800 w-full bg-red-100 rounded-lg p-3">${err}</div>`
                    )
                    .join("");
                document.getElementById('statusAkun').innerHTML = msg;

            } else if (data.status === true) {

                document.getElementById('statusAkun').innerHTML =
                    `<div class="text-green-500 dark:text-green-300 dark:bg-green-800 w-full bg-green-100 rounded-lg p-3">${data.message}</div>`;

                // document.getElementById('userForm').reset();
                loadData();
            } else {
                document.getElementById('statusAkun').innerHTML =
                    `<div class="text-red-500 dark:text-red-300 dark:bg-red-800 w-full bg-red-100 rounded-lg p-3">Terjadi kesalahan.</div>`;
            }

        } catch (err) {
            console.log(err);
            document.getElementById('statusAkun').innerHTML =
                `<div class="text-red-500 dark:text-red-300 dark:bg-red-800 w-full bg-red-100 rounded-lg p-3">Gagal menyimpan ke server.</div>`;
        }

        btn.disabled = false;
        btn.innerText = 'Simpan';
    });

    function userPegawai(pegawai) {
        const id = pegawai.id;

        document.getElementById('modalTitle').innerText = "Akun Pegawai";

        document.getElementById('akunModal').classList.remove('hidden');

        document.getElementById('id_pegawai').value = id;
        document.getElementById('username').value = pegawai.nama;
        document.getElementById('email').value = "";
        document.getElementById('role').value = "";
        document.getElementById('statusAkun').innerHTML = "Memeriksa akun...";

        fetch(`/admin/akun/${id}`)
            .then(response => response.json())
            .then(result => {
                if (!result.success) {
                    // Akun belum ada
                    document.getElementById('statusAkun').innerHTML =
                        `<div class="text-red-500 dark:text-red-300 dark:bg-red-800 w-full bg-red-100 rounded-lg p-3">${result.message}</div>`;
                    document.getElementById('PasswordDefault').classList.remove('hidden');
                } else {
                    document.getElementById('statusAkun').innerHTML = "";
                    document.getElementById('PasswordDefault').classList.add('hidden');
                    // Isi data user
                    document.getElementById('username').value = result.data.name ?? '';
                    document.getElementById('email').value = result.data.email ?? '';
                    document.getElementById('role').value = result.data.role ?? '';

                }
            })
            .catch(err => {
                // console.error(err);
                document.getElementById('statusAkun').innerHTML =
                    `<span class="text-red-600">Terjadi kesalahan</span>`;
            });
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
        document.getElementById('id_bidang').value = data.id_bidang ?? '';
        document.getElementById('formModal').classList.remove('hidden');
    }

    function closeForm() {
        document.getElementById('formModal').classList.add('hidden');
    }

    function closeFormAkun() {
        document.getElementById('akunModal').classList.add('hidden');
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
            id_bidang: document.getElementById('id_bidang').value,
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
                // alert(res.message);
                Toast.fire({
                    icon: 'success',
                    title: res.message
                });
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
                    // alert(res.message);
                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    });
                    loadData();
                })
                .catch(err => console.error(err));
        }
    }

    document.getElementById('search').addEventListener('input', () => loadData());

    // Initial load
    loadData();
</script>
