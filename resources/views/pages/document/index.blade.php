<x-layout>
    <x-header />
    <main class="p-6 overflow-auto">
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manajemen Dokumen</h2>
            <button id="add-document-btn"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Dokumen</span>
            </button>
        </div>

        {{-- Filter & Search --}}
        <div class="flex flex-col sm:flex-row flex-wrap gap-4 w-full items-center mb-6">
            {{-- 1. Search Input --}}
            <div class="relative w-full sm:w-64 group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-search text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="text" id="search"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full pl-10 p-2.5 shadow-sm transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    placeholder="Cari dokumen...">
            </div>

            {{-- 2. Date Range --}}
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-calendar-range text-gray-400 dark:text-gray-500"></i>
                </div>
                <div
                    class="flex items-center bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 w-full pl-10 pr-10 p-2.5 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <input type="date" id="startDate"
                        class="w-1/2 bg-transparent outline-none border-none text-sm dark:text-white" />
                    <span class="mx-2 text-gray-400">—</span>
                    <input type="date" id="endDate"
                        class="w-1/2 bg-transparent outline-none border-none text-sm dark:text-white" />
                </div>
            </div>

            {{-- 3. Filter Kategori --}}
            <div class="relative w-full sm:w-56">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-folder text-gray-400 dark:text-gray-500"></i>
                </div>
                <select id="selectKategori"
                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>
        </div>

        {{-- Tabel Dokumen --}}
        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-[10px]">#</th>
                            <th scope="col" class="px-6 py-3 min-w-[300px]">Nama Dokumen</th>
                            <th scope="col" class="px-6 py-3">Kategori</th>
                            <th scope="col" class="px-6 py-3">Download</th>
                            <th scope="col" class="px-6 py-3">File</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="document-table-body">
                        @forelse ($documents as $doc)
                            <tr id="doc-row-{{ $doc->id }}"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $doc->cover ? asset('storage/' . $doc->cover) : 'https://placehold.co/100x60/e2e8f0/e2e8f0?text=No+Cover' }}"
                                            alt="Cover" class="w-24 h-14 object-cover rounded-md flex-shrink-0">
                                        <div>
                                            <span class="font-bold text-lg">{{ $doc->judul }}</span><br>
                                            <small>{{ $doc->created_at->format('d F Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="bg-indigo-200 dark:bg-indigo-600 px-2 py-1 text-indigo-800 dark:text-indigo-200 rounded-xl">
                                        {{ $doc->kategori->nama_kategori ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-2 text-md rounded-full transition-all duration-200 border border-opacity-50 bg-yellow-100 text-yellow-700 border-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:border-yellow-700">
                                        {{ $doc->download > 0 ? $doc->download . ' Kali' : 'Belum Pernah' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ asset('storage/' . $doc->file) }}" target="_blank"
                                        class="inline-flex items-center gap-1 px-3 py-2 text-md rounded-full transition-all duration-200 border border-opacity-50 bg-green-100 text-green-700 border-green-300 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:border-green-700">
                                        <i class="bi bi-folder2-open text-lg"></i> Lihat File
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-4">
                                        <button
                                            class="show-btn p-2 rounded-lg text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900 transition"
                                            title="Detail" data-id="{{ $doc->id }}">
                                            <i class="bi bi-eye-fill text-base"></i>
                                        </button>
                                        <button
                                            class="edit-btn p-2 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition"
                                            title="Edit" data-id="{{ $doc->id }}">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>
                                        <button
                                            class="delete-btn p-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900 transition"
                                            title="Hapus" data-id="{{ $doc->id }}">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-data-row">
                                <td colspan="6" class="text-center py-12">
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada dokumen yang ditambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-200 dark:border-gray-700" id="pagination-container">
                {{ $documents->links() }}
            </div>
        </section>
    </main>

    {{-- MODAL --}}
    @include('pages.document._modals')

    {{-- SCRIPT AJAX --}}
    @push('scripts')
        {{-- Kita masukkan script langsung di sini agar variabel PHP blade bisa diakses --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search');
                const startDateInput = document.getElementById('startDate');
                const endDateInput = document.getElementById('endDate');
                const kategoriSelect = document.getElementById('selectKategori');
                const tableBody = document.getElementById('document-table-body');
                const paginationContainer = document.getElementById('pagination-container');
                const baseUrl = "{{ route('admin.documents.index') }}"; // Route Index Utama

                // --- 1. Fungsi Fetch Data ---
                function loadData(url = baseUrl) {
                    const search = searchInput.value;
                    const startDate = startDateInput.value;
                    const endDate = endDateInput.value;
                    const kategori = kategoriSelect.value;

                    // Buat URL dengan query params
                    let targetUrl = new URL(url);
                    if (search) targetUrl.searchParams.set('search', search);
                    if (startDate) targetUrl.searchParams.set('start_date', startDate);
                    if (endDate) targetUrl.searchParams.set('end_date', endDate);
                    if (kategori) targetUrl.searchParams.set('kategori_id', kategori);

                    // Tampilkan Loading State
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">Memuat data...</p>
                            </td>
                        </tr>
                    `;

                    fetch(targetUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Render Tabel
                            let html = '';
                            if (data.data.length > 0) {
                                data.data.forEach((doc, index) => {
                                    // Hitung nomor urut (from + index)
                                    const iteration = (data.from || 1) + index;
                                    html += renderRow(doc, iteration);
                                });
                            } else {
                                html = `
                                <tr id="no-data-row">
                                    <td colspan="6" class="text-center py-12">
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada dokumen ditemukan.</p>
                                    </td>
                                </tr>`;
                            }
                            tableBody.innerHTML = html;

                            // Render Pagination Links (Manual Construct)
                            renderPagination(data);
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            tableBody.innerHTML =
                                `<tr><td colspan="6" class="text-center text-red-500 py-6">Gagal memuat data.</td></tr>`;
                        });
                }

                // --- 2. Fungsi Render Baris Tabel (Sesuai Struktur Awal) ---
                const renderRow = (doc, iteration) => {
                    const storagePath = "{{ asset('storage') }}";
                    const coverUrl = doc.cover ? `${storagePath}/${doc.cover}` :
                        'https://placehold.co/100x60/e2e8f0/e2e8f0?text=No+Cover';
                    const fileUrl = `${storagePath}/${doc.file}`;
                    const createdDate = new Date(doc.created_at).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    const kategoriNama = doc.kategori ? doc.kategori.nama_kategori : 'N/A';
                    const downloadText = doc.download > 0 ? `${doc.download} Kali` : 'Belum Pernah';

                    return `
                        <tr id="doc-row-${doc.id}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">${iteration}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center gap-4">
                                    <img src="${coverUrl}" alt="Cover" class="w-24 h-14 object-cover rounded-md flex-shrink-0">
                                    <div>
                                        <span class="font-bold text-lg">${doc.judul}</span><br>
                                        <small>${createdDate}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-indigo-200 dark:bg-indigo-600 px-2 py-1 text-indigo-800 dark:text-indigo-200 rounded-xl">
                                    ${kategoriNama}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-2 text-md rounded-full transition-all duration-200 border border-opacity-50 bg-yellow-100 text-yellow-700 border-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:border-yellow-700">
                                    ${downloadText}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="${fileUrl}" target="_blank"
                                    class="inline-flex items-center gap-1 px-3 py-2 text-md rounded-full transition-all duration-200 border border-opacity-50 bg-green-100 text-green-700 border-green-300 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:border-green-700">
                                    <i class="bi bi-folder2-open text-lg"></i> Lihat File
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-4">
                                    <button class="show-btn p-2 rounded-lg text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900 transition" title="Detail" data-id="${doc.id}">
                                        <i class="bi bi-eye-fill text-base"></i>
                                    </button>
                                    <button class="edit-btn p-2 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition" title="Edit" data-id="${doc.id}">
                                        <i class="bi bi-pencil-square text-base"></i>
                                    </button>
                                    <button class="delete-btn p-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900 transition" title="Hapus" data-id="${doc.id}">
                                        <i class="bi bi-trash text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                };

                // --- 3. Fungsi Render Pagination ---
                const renderPagination = (data) => {
                    let linksHtml = '<div class="flex flex-wrap gap-2">';
                    data.links.forEach(link => {
                        let activeClass = link.active ? 'bg-indigo-600 text-white' :
                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700';
                        let disabledClass = !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer';

                        // Clean up label (remove &laquo; etc)
                        let label = link.label.replace('&laquo; Previous', 'Previous').replace(
                            'Next &raquo;', 'Next');

                        if (link.url) {
                            linksHtml +=
                                `<button onclick="loadData('${link.url}')" class="relative inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md ${activeClass} ${disabledClass}">${label}</button>`;
                        } else {
                            linksHtml +=
                                `<span class="relative inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md ${activeClass} ${disabledClass}">${label}</span>`;
                        }
                    });
                    linksHtml += '</div>';
                    paginationContainer.innerHTML = linksHtml;
                };

                // --- 4. Event Listeners ---
                // Debounce untuk search agar tidak request setiap ketikan
                let timeoutId;
                searchInput.addEventListener('input', () => {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => loadData(), 500);
                });

                startDateInput.addEventListener('change', () => loadData());
                endDateInput.addEventListener('change', () => loadData());
                kategoriSelect.addEventListener('change', () => loadData());

                // Expose loadData ke global window agar bisa dipanggil oleh onclick pagination
                window.loadData = loadData;
            });
        </script>

        {{-- Include Script Form (Modal Logic, Create/Edit/Delete) --}}
        @include('pages.document._scripts')
    @endpush
</x-layout>
