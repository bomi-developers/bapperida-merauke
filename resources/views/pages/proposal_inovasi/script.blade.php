<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let pageUrl = "{{ route('admin.proposal.data') }}";
    let currentProposal = null;
    let deleteId = null;

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });

    // Load Data
    function loadData(url = pageUrl) {
        const search = document.getElementById('search').value;
        const status = document.getElementById('status-filter').value;
        const date = document.getElementById('date-filter').value;

        let finalUrl = new URL(url, window.location.origin);
        if (search) finalUrl.searchParams.set("search", search);
        if (status) finalUrl.searchParams.set("status", status);
        if (date) finalUrl.searchParams.set("date", date);

        showLoadingTable();

        fetch(finalUrl)
            .then(res => res.json())
            .then(res => {
                updateStats(res.stats);
                renderTable(res);
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Data',
                    text: 'Terjadi kesalahan saat memuat data'
                });
            });
    }

    function showLoadingTable() {
        document.getElementById('proposal-table').innerHTML = `
            <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 w-14">#</th>
                            <th class="px-4 py-3">Nama & Email</th>
                            <th class="px-4 py-3">Judul Inovasi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center py-6">
                                <div class="w-10 h-10 border-4 border-gray-200 border-t-indigo-600 rounded-full animate-spin mx-auto"></div>
                                <p class="mt-2 text-gray-500">Loading...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }

    function renderTable(res) {
        let html = `
            <div class="overflow-x-auto max-h-[75vh] overflow-y-auto rounded-lg">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 w-14">#</th>
                            <th class="px-4 py-3">Nama & Email</th>
                            <th class="px-4 py-3">Judul Inovasi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (res.data.length > 0) {
            res.data.forEach((item, i) => {
                const statusBadge = getStatusBadge(item.status);
                const hasVideo = item.link_video ? true : false;

                html += `
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                        <td class="px-4 py-3 font-medium">${i + 1}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900 dark:text-gray-100">${item.nama}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">${item.email}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">${item.no_hp}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="max-w-xs">
                                <p class="font-medium text-gray-900 dark:text-gray-100 line-clamp-2">${item.judul}</p>
                                ${hasVideo ? '<span class="text-xs text-purple-600 dark:text-purple-400 flex items-center gap-1 mt-1"><i class="bi bi-camera-video"></i> Ada Video</span>' : ''}
                            </div>
                        </td>
                        <td class="px-4 py-3">${statusBadge}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                            ${formatDate(item.created_at)}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <button onclick="viewDetail(${item.id})" 
                                    class="p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400 transition" 
                                    title="Lihat Detail">
                                    <i class="bi bi-eye text-lg"></i>
                                </button>
                                <button onclick="downloadFile(${item.id})" 
                                    class="p-2 rounded-lg hover:bg-green-100 dark:hover:bg-green-900 text-green-600 dark:text-green-400 transition" 
                                    title="Download File">
                                    <i class="bi bi-download text-lg"></i>
                                </button>
                                <button onclick="openStatusModal(${item.id}, '${item.status}')" 
                                    class="p-2 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900 text-purple-600 dark:text-purple-400 transition" 
                                    title="Update Status">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </button>
                                <button onclick="deleteProposal(${item.id})" 
                                    class="p-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900 text-red-600 dark:text-red-400 transition" 
                                    title="Hapus">
                                    <i class="bi bi-trash text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += `
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <i class="bi bi-inbox text-5xl text-gray-300 dark:text-gray-600"></i>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Tidak ada data proposal</p>
                    </td>
                </tr>
            `;
        }

        html += `
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
        `;

        // Pagination
        res.links.forEach(link => {
            if (link.url) {
                const label = link.label.replace('&laquo;', '«').replace('&raquo;', '»');
                html += `
                    <button onclick="loadData('${link.url}')"
                        class="px-3 py-1 text-sm rounded font-medium transition-colors duration-200
                        ${link.active ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600'}">
                        ${label}
                    </button>
                `;
            }
        });

        html += `</div>`;
        document.getElementById('proposal-table').innerHTML = html;
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full text-xs font-semibold">Pending</span>',
            'approved': '<span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-semibold">Disetujui</span>',
            'rejected': '<span class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-full text-xs font-semibold">Ditolak</span>'
        };
        return badges[status] || badges['pending'];
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function updateStats(stats) {
        document.getElementById('total-count').textContent = stats.total || 0;
        document.getElementById('pending-count').textContent = stats.pending || 0;
        document.getElementById('approved-count').textContent = stats.approved || 0;
        document.getElementById('rejected-count').textContent = stats.rejected || 0;
    }

    // View Detail
    function viewDetail(id) {
        fetch(`/admin/proposal/${id}`)
            .then(res => res.json())
            .then(data => {
                currentProposal = data;
                renderDetailContent(data);
                document.getElementById('detailModal').classList.remove('hidden');
            })
            .catch(err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal memuat detail'
                });
            });
    }

    function renderDetailContent(data) {
        const content = `
            <div class="space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center justify-between">
                    ${getStatusBadge(data.status)}
                    <span class="text-sm text-gray-500">${formatDate(data.created_at)}</span>
                </div>

                <!-- Identitas Pengirim -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                        <i class="bi bi-person-circle"></i> Identitas Pengirim
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Nama:</span>
                            <p class="font-medium text-gray-900 dark:text-gray-100">${data.nama}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">No HP:</span>
                            <p class="font-medium text-gray-900 dark:text-gray-100">${data.no_hp}</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-gray-500 dark:text-gray-400">Email:</span>
                            <p class="font-medium text-gray-900 dark:text-gray-100">${data.email}</p>
                        </div>
                    </div>
                </div>

                <!-- Judul -->
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Judul Inovasi</h4>
                    <p class="text-gray-700 dark:text-gray-300">${data.judul}</p>
                </div>

                <!-- Latar Belakang -->
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Latar Belakang</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-justify">${data.latar_belakang}</p>
                </div>

                <!-- Ide Inovasi -->
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Ide Inovasi</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-justify">${data.ide_inovasi || '-'}</p>
                </div>

                <!-- Grid 2 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Tujuan Inovasi</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">${data.tujuan_inovasi || '-'}</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-green-900 dark:text-green-100 mb-2">Target Perubahan</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">${data.target_perubahan || '-'}</p>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-purple-900 dark:text-purple-100 mb-2">Stakeholder</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">${data.stakeholder || '-'}</p>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-2">Sumber Daya (SDM)</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">${data.sdm || '-'}</p>
                    </div>
                    <div class="bg-pink-50 dark:bg-pink-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-pink-900 dark:text-pink-100 mb-2">Penerima Manfaat</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">${data.penerima_manfaat || '-'}</p>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4">
                        <h4 class="font-semibold text-indigo-900 dark:text-indigo-100 mb-2">Kebaruan</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">${data.kebaruan || '-'}</p>
                    </div>
                </div>

                <!-- Deskripsi Ide -->
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Deskripsi Singkat Ide</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-justify">${data.deskripsi_ide || '-'}</p>
                </div>

                <!-- Keterangan -->
                ${data.keterangan ? `
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Keterangan Tambahan</h4>
                    <p class="text-gray-700 dark:text-gray-300">${data.keterangan}</p>
                </div>
                ` : ''}
            </div>
        `;

        document.getElementById('detail-content').innerHTML = content;
        document.getElementById('detail-title').textContent = data.judul;

        // Show/hide video button
        if (data.link_video) {
            document.getElementById('btn-video').classList.remove('hidden');
        } else {
            document.getElementById('btn-video').classList.add('hidden');
        }
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        currentProposal = null;
    }

    // Video Modal
    function openVideoModal() {
        if (!currentProposal || !currentProposal.link_video) return;

        const videoUrl = getEmbedUrl(currentProposal.link_video);
        document.getElementById('video-container').innerHTML = `
            <iframe src="${videoUrl}" 
                class="w-full h-full" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        `;
        document.getElementById('videoModal').classList.remove('hidden');
    }

    function getEmbedUrl(url) {
        // YouTube
        if (url.includes('youtube.com') || url.includes('youtu.be')) {
            const videoId = url.includes('youtu.be') ?
                url.split('/').pop() :
                new URL(url).searchParams.get('v');
            return `https://www.youtube.com/embed/${videoId}`;
        }
        // Google Drive
        if (url.includes('drive.google.com')) {
            const fileId = url.match(/[-\w]{25,}/);
            return `https://drive.google.com/file/d/${fileId}/preview`;
        }
        return url;
    }

    function closeVideoModal() {
        document.getElementById('videoModal').classList.add('hidden');
        document.getElementById('video-container').innerHTML = '';
    }

    // Download File
    function downloadFile(id = null) {
        const proposalId = id || (currentProposal ? currentProposal.id : null);
        if (!proposalId) return;

        window.open(`/admin/proposal/${proposalId}/download`, '_blank');
    }

    // Status Modal
    function openStatusModal(id, currentStatus) {
        document.getElementById('proposal_id').value = id;
        document.getElementById('status').value = currentStatus;
        document.getElementById('catatan').value = '';
        document.getElementById('statusModal').classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }

    document.getElementById('formStatus').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('proposal_id').value;
        const data = {
            status: document.getElementById('status').value,
            catatan: document.getElementById('catatan').value
        };

        fetch(`/admin/proposal/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                Toast.fire({
                    icon: 'success',
                    title: res.message
                });
                closeStatusModal();
                loadData();
            })
            .catch(err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal mengupdate status'
                });
            });
    });

    // Delete
    function deleteProposal(id) {
        deleteConfirm({
            title: 'Hapus Proposal?',
            text: 'Proposal inovasi ini akan dihapus permanen!',
            url: `/admin/proposal/${id}`,
            onSuccess: function() {
                loadData();
            }
        });
    }

    // Export
    function exportData() {
        window.location.href = '/admin/proposal/export';
    }

    // Event Listeners
    document.getElementById('search').addEventListener('input', () => loadData());
    document.getElementById('status-filter').addEventListener('change', () => loadData());
    document.getElementById('date-filter').addEventListener('change', () => loadData());

    // Initial Load
    loadData();
</script>
