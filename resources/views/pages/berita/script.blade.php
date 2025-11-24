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

        const editUrlBase = "{{ route('admin.berita.edit', ':id') }}";
        const AUTH_ID = {{ auth()->id() }};
        const AUTH_ROLE = "{{ auth()->user()->role }}";



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
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
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
                                    <span class="font-semibold text-md whitespace-normal break-words">${g.title}</span>
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
                                    ` : ''}
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

    function deleteBerita(id) {
        if (confirm("Yakin hapus berita ini?")) {
            fetch(`/admin/berita/${id}`, {
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
                });
        }
    }

    document.getElementById('search').addEventListener('input', () => loadData());
    document.getElementById('selectAuthor').addEventListener('input', () => loadData());
    document.getElementById('selectStatus').addEventListener('input', () => loadData());
    loadData();
</script>
