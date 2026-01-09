<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let pageUrl = "{{ route('admin.berita.data') }}";
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });

    function formatDate(dateString) {
        const date = new Date(dateString);

        const options = {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        };

        return date.toLocaleString('id-ID', options);
    }

    function loadData(url = pageUrl) {
        const search = encodeURIComponent(document.getElementById('search').value.trim());
        const selectAuthor = encodeURIComponent(document.getElementById('selectAuthor').value.trim());
        const selectStatus = encodeURIComponent(document.getElementById('selectStatus').value.trim());
        const selectJenis = encodeURIComponent(document.getElementById('selectJenis').value.trim());

        // pastikan URL sudah benar formatnya
        let finalUrl = new URL(url, window.location.origin);
        if (search !== "") {
            finalUrl.searchParams.set("search", search);
        }
        if (selectAuthor !== "") {
            finalUrl.searchParams.set("author", selectAuthor);
        }

        if (selectStatus !== "") {
            finalUrl.searchParams.set("status", selectStatus);
        }
        if (selectJenis !== "") {
            finalUrl.searchParams.set("jenis", selectJenis);
        }

        const editUrlBase = "{{ route('admin.berita.edit', ':id') }}";
        const AUTH_ID = {{ auth()->id() }};
        const AUTH_ROLE = "{{ auth()->user()->role }}";

        // loading table
        document.getElementById('berita-table').innerHTML = `
                <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Status</th>
                                <th>Penayangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center py-6 my-4">
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
                <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-6 py-3 min-w-[10px]">#</th>
                                <th scope="col" class="px-6 py-3 min-w-[300px]">Judul</th>
                                <th scope="col" class="px-6 py-3">Penulis</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Penayangan</th>
                                <th scope="col" class="px-6 py-3 text-center min-w-[150px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>`;

                if (res.data.length > 0) {
                    res.data.forEach((g, i) => {
                        function canEditDelete(g) {
                            if (AUTH_ROLE === 'admin' || AUTH_ROLE === 'super_admin') {
                                return true;
                            }
                            return g.author_id === AUTH_ID;
                        }

                        html += `
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            <td class="px-6 py-3 font-medium text-gray-900 dark:text-gray-100">${i + 1}</td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center gap-4">
                                    <img src="/storage/${g.cover_image}"
                                        alt="Cover" class="w-20 h-14 object-cover rounded-md flex-shrink-0">
                                    
                                    <div class="">
                                        <span class="font-semibold text-md whitespace-normal break-words">${g.title}</span><br>
                                        <a target="_blank" href="/berita/${g.slug}" class="mt-2 text-sm text-blue-700 hover:underline whitespace-normal break-words">${window.location.origin}/berita/${g.slug}</a>
                                    </div>
                                </div>
                            </td>
                             <td class="px-6 py-4">
                                <span class="text-indigo-700 font-bold text-lg dark:text-indigo-400">${g.author?.name ?? 'N/A' }</span>
                                <br><small>Pada :  ${formatDate(g.created_at)}</small>
                            </td>
                             <td class="px-6 py-4">
                                <span
                                    class="px-3 py-2 text-md font-medium rounded-full ${g.status === 'published' 
                                          ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 border border-green-700 dark:border-green-400' 
                                          : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 border border-yellow-700 dark:border-yellow-400'
                                      }">
                                    ${g.status}
                                </span>
                            </td>
                             <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300 }}">
                                      ${g.views_count}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-3">
                                  ${canEditDelete(g) ? `
                                    <a href="${editUrlBase.replace(':id', g.id)}"
                                        class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline" title="Edit">
                                        <i class="bi bi-pencil-square text-base"></i>
                                    </a>
                                    <button onclick="deleteBerita(${g.id})"
                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                    ` : '<span class="px-2 py-1 border border-red-700 bg-red-200 rounded-full text-red-700 ">Not the Author</span>'}
                                </div>
                            </td>
                        </tr>`;
                    });
                } else {
                    html += `
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">
                            Belum ada berita
                        </td>
                    </tr>`;
                }

                html += `
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">`;

                // FIX PAGINATION: tetap kirim search saat pindah halaman
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
                document.getElementById('berita-table').innerHTML = html;
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

    // --- Recycle Bin Logic ---

    // Open Modal
    document.getElementById('open-trash-btn').addEventListener('click', () => {
        document.getElementById('trash-modal').classList.remove('hidden');
        loadTrash();
    });

    // Close Modal
    document.querySelectorAll('.close-trash-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('trash-modal').classList.add('hidden');
        });
    });

    // Load Trash Data
    function loadTrash() {
        const loading = document.getElementById('trash-loading');
        const tbody = document.getElementById('trash-table-body');
        const forceDeleteAllBtn = document.getElementById('force-delete-all-btn');

        loading.classList.remove('hidden');
        forceDeleteAllBtn.classList.add('hidden');

        fetch("{{ route('admin.berita.trash') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(res => {
                tbody.innerHTML = res.html;
                if (res.count > 0) {
                    forceDeleteAllBtn.classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML =
                    '<tr><td colspan="2" class="text-center py-4 text-red-500">Gagal memuat data sampah.</td></tr>';
            })
            .finally(() => {
                loading.classList.add('hidden');
            });
    }

    // Event Delegation for Restore & Force Delete buttons inside the modal
    document.getElementById('trash-table-body').addEventListener('click', function(e) {
        const restoreBtn = e.target.closest('.btn-restore');
        const deleteBtn = e.target.closest('.btn-force-delete');

        if (restoreBtn) {
            const id = restoreBtn.dataset.id;
            // Use deleteConfirm with success type for Restore
            deleteConfirm({
                title: 'Pulihkan Berita?',
                text: 'Berita akan dikembalikan ke daftar aktif.',
                confirmText: 'Ya, Pulihkan!',
                cancelText: 'Batal',
                type: 'success',
                iconClass: 'bi-arrow-counterclockwise',
                url: `/admin/berita/${id}/restore`,
                method: 'POST',
                onSuccess: function(response) {
                    loadTrash();
                    loadData();
                }
            });
        } else if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            // Use deleteConfirm with danger type for Force Delete
            deleteConfirm({
                title: 'Hapus Permanen?',
                text: 'Berita ini tidak dapat dikembalikan lagi!',
                confirmText: 'Ya, Hapus Permanen!',
                cancelText: 'Batal',
                type: 'danger',
                url: `/admin/berita/${id}/force-delete`,
                method: 'DELETE',
                onSuccess: function(response) {
                    loadTrash();
                    // loadData(); // No need to reload main table
                }
            });
        }
    });

    // Force Delete All
    document.getElementById('force-delete-all-btn').addEventListener('click', () => {
        deleteConfirm({
            title: 'Kosongkan Sampah?',
            text: 'Semua berita di sampah akan dihapus permanen!',
            confirmText: 'Ya, Kosongkan!',
            cancelText: 'Batal',
            type: 'danger',
            url: `/admin/berita/force-delete-all`,
            method: 'DELETE',
            onSuccess: function(response) {
                loadTrash();
            }
        });
    });

    function deleteBerita(id) {
        deleteConfirm({
            title: 'Pindahkan ke Sampah?',
            text: 'Berita ini akan dipindahkan ke Recycle Bin.',
            confirmText: 'Ya, Hapus', // Changed from "Ya, hapus!"
            cancelText: 'Batal',
            url: `/admin/berita/${id}`,
            onSuccess: function(response) {
                loadData();
                // Opsional: loadTrash() jika modal terbuka, tapi biasanya delete dari main table
            },
            onError: function(error) {
                console.error('Delete error:', error);
            }
        });
    }

    document.getElementById('search').addEventListener('input', () => loadData());
    document.getElementById('selectAuthor').addEventListener('input', () => loadData());
    document.getElementById('selectStatus').addEventListener('input', () => loadData());
    document.getElementById('selectJenis').addEventListener('input', () => loadData());
    loadData();
</script>
