<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const pageUrl = "{{ route('admin.bidang.data') }}";

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
        document.getElementById('bidang-table').innerHTML = `
                <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                           <tr>
                                    <th class="px-4 py-3 w-14">#</th>
                                    <th class="px-4 py-3">Nama Bidang</th>
                                    <th class="px-4 py-3">Deskripsi</th>
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
                                    <th class="px-4 py-3">Nama Bidang</th>
                                    <th class="px-4 py-3">Deskripsi</th>
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
                                <td class="px-4 py-3 font-medium">${b.nama_bidang}</td>
                                <td class="px-4 py-3">${b.deskripsi ?? ''}</td>

                                <td class="px-4 py-3 flex justify-center gap-2">
                                    <button onclick="editBidang(${b.id}, '${b.nama_bidang}', '${b.deskripsi ?? ''}')"
                                        class="p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400 transition"
                                        title="Edit">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>

                                    <button onclick="deleteBidang(${b.id})"
                                        class="p-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900 text-red-600 dark:text-red-400 transition"
                                        title="Hapus">
                                        <i class="bi bi-trash text-lg"></i>
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

                document.getElementById('bidang-table').innerHTML = html;
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

    // 🔹 Submit
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
                Toast.fire({
                    icon: 'success',
                    title: res.message
                });
                loadData();
            })
            .catch(err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal menyimpan data'
                });
            });
    });

    // 🔹 Hapus
    function deleteBidang(id) {
        deleteId = id;
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    document.getElementById('cancelDelete').addEventListener('click', () => {
        document.getElementById('confirmModal').classList.add('hidden');
    });

    document.getElementById('confirmDelete').addEventListener('click', () => {
        document.getElementById('confirmModal').classList.add('hidden');

        if (!deleteId) return;

        fetch(`/admin/bidang/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(res => {
                Toast.fire({
                    icon: 'success',
                    title: res.message
                });
                loadData();
            })
            .catch(err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal menghapus data'
                });
            });
    });

    document.getElementById('search').addEventListener('input', () => loadData());

    loadData();
</script>
