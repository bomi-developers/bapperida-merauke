<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Elements ---
        const tableBody = document.getElementById('data-table');
        const paginationContainer = document.getElementById('pagination-container');
        const searchInput = document.getElementById('search-input');
        const periodeFilter = document.getElementById('periode-filter');
        const sortFilter = document.getElementById('sort-filter');
        const showModalEl = document.getElementById('show-modal');
        const closeShowModalBtns = document.querySelectorAll('.close-show-modal');

        let searchTimeout;

        // --- Functions ---
        const openShowModal = () => showModalEl.classList.remove('hidden');
        const closeShowModal = () => showModalEl.classList.add('hidden');

        const fetchDocuments = async () => {
            const searchQuery = searchInput.value;
            const periode = periodeFilter.value;
            const sort = sortFilter.value;
            const kategoriId = '{{ $kategori->id }}';

            // Tambahkan indikator loading
            tableBody.innerHTML =
                `<tr><td colspan="5" class="text-center py-16"><i class="bi bi-arrow-repeat text-4xl text-gray-400 animate-spin"></i><p class="mt-2 text-gray-500">Memuat...</p></td></tr>`;

            const url =
                `{{ route('documents.search_public') }}?kategori_id=${kategoriId}&search=${searchQuery}&periode=${periode}&sort=${sort}`;

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                tableBody.innerHTML = data.table_html;
                paginationContainer.innerHTML = data.pagination_html;
            } catch (error) {
                console.error('Error fetching documents:', error);
                tableBody.innerHTML =
                    `<tr><td colspan="5" class="text-center py-16"><p class="text-red-500">Gagal memuat data. Silakan coba lagi.</p></td></tr>`;
            }
        };

        // --- Event Listeners ---
        searchInput.addEventListener('keyup', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchDocuments, 300); // Debounce
        });
        periodeFilter.addEventListener('change', fetchDocuments);
        sortFilter.addEventListener('change', fetchDocuments);

        if (closeShowModalBtns) {
            closeShowModalBtns.forEach(btn => btn.addEventListener('click', closeShowModal));
        }

        if (tableBody) {
            tableBody.addEventListener('click', async (e) => {
                const target = e.target.closest('button.review-btn');
                if (!target) return;

                const id = target.dataset.id;
                const response = await fetch(`{{ url('admin/documents') }}/${id}`);
                const doc = await response.json();

                document.getElementById('show-judul').textContent = doc.judul;
                document.getElementById('show-kategori').textContent = doc.kategori.nama_kategori;
                document.getElementById('show-cover').innerHTML = doc.cover ?
                    `<img src="{{ asset('storage') }}/${doc.cover}" class="w-full h-48 object-cover rounded-md border border-slate-700 shadow-md">` :
                    '<div class="w-full h-48 bg-slate-700/50 rounded-md flex items-center justify-center text-slate-500">Tidak ada cover</div>';

                const fileName = doc.file.split('/').pop();
                const downloadUrl = `{{ url('admin/documents') }}/${doc.id}/download`;
                document.getElementById('show-file').innerHTML = `
                    <a href="${downloadUrl}" class="relative block w-full h-24 rounded-lg overflow-hidden border border-slate-700 group transition-all duration-300 hover:shadow-lg hover:border-blue-500" title="Unduh ${fileName}">
                        <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center">
                            <i class="bi bi-file-earmark-zip-fill text-4xl text-slate-500"></i>
                        </div>
                        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-blue-600/80 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <i class="bi bi-download text-2xl"></i>
                            <span class="mt-1 font-semibold text-xs">Unduh File</span>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-black/50 text-white text-xs font-medium text-center truncate">
                            ${fileName}
                        </div>
                    </a>
                `;

                const showLainnyaWrapper = document.getElementById('show-lainnya-wrapper');
                const showLainnyaContainer = document.getElementById('show-lainnya-container');
                showLainnyaContainer.innerHTML = '';
                let lainnyaDataToShow = doc.lainnya;
                if (typeof lainnyaDataToShow === 'string' && lainnyaDataToShow.length > 0) {
                    try {
                        lainnyaDataToShow = JSON.parse(lainnyaDataToShow);
                    } catch (e) {
                        console.error('Gagal mem-parsing data JSON untuk detail:', e);
                        lainnyaDataToShow = null;
                    }
                }

                if (lainnyaDataToShow && typeof lainnyaDataToShow === 'object' && Object.keys(
                        lainnyaDataToShow).length > 0) {
                    showLainnyaWrapper.classList.remove('hidden');
                    let html = '';
                    if (lainnyaDataToShow.visi) html +=
                        `<div><h5 class="font-semibold text-blue-400">Visi</h5><p class="mt-1 text-slate-300">${lainnyaDataToShow.visi}</p></div>`;
                    if (lainnyaDataToShow.misi && lainnyaDataToShow.misi.length > 0) {
                        html +=
                            `<div><h5 class="font-semibold text-blue-400">Misi</h5><ul class="list-decimal list-inside mt-2 space-y-1 text-slate-300">`;
                        lainnyaDataToShow.misi.forEach(m => {
                            html += `<li>${m}</li>`;
                        });
                        html += `</ul></div>`;
                    }
                    if (lainnyaDataToShow.keterangan) html +=
                        `<div><h5 class="font-semibold text-blue-400">Keterangan</h5><p class="mt-1 text-slate-300 whitespace-pre-wrap">${lainnyaDataToShow.keterangan}</p></div>`;
                    showLainnyaContainer.innerHTML = html;
                } else {
                    showLainnyaWrapper.classList.add('hidden');
                }

                openShowModal();
            });
        }
    });
</script>
<style>
    /* Mencegah ikon "mencuri" klik dari tombol */
    .review-btn i,
    .download-btn i {
        pointer-events: none;
    }
</style>
