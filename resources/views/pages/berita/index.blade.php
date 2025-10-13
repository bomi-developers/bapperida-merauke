<x-layout>
    <x-header />
    <main class="p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manajemen Berita</h2>
            <a href="{{ route('admin.berita.create') }}"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Berita</span>
            </a>
        </div>

        @if (session('success'))
            <div id="success-alert" class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Input pencarian sekarang tanpa form --}}
        <div class="mb-5">
            <input type="text" id="searchInput" placeholder="Ketik untuk mencari judul berita..." value=""
                class="w-full sm:w-80 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" />
        </div>

        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 min-w-[300px]">Judul</th>
                            <th scope="col" class="px-6 py-3">Penulis</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="beritaTableBody">
                        {{-- Muat data awal menggunakan partial view --}}
                        @include('pages.berita._berita_rows', ['beritas' => $beritas])
                    </tbody>
                </table>
                {{-- Paginasi untuk tampilan awal --}}
                <div id="paginationLinks" class="p-4">
                    {{ $beritas->links() }}
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            setTimeout(() => {
                const alertBox = document.getElementById('success-alert');
                if (alertBox) {
                    alertBox.style.opacity = '100'; // buat efek memudar
                    setTimeout(() => alertBox.remove(), 500); // hapus elemen setelah animasi selesai
                }
            }, 3000);
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const tableBody = document.getElementById('beritaTableBody');
                const paginationLinks = document.getElementById('paginationLinks');
                let debounceTimer;

                // Simpan konten awal tabel dan paginasi untuk pemulihan cepat
                const initialTableContent = tableBody.innerHTML;
                const initialPaginationContent = paginationLinks.innerHTML;

                // Gunakan 'input' untuk deteksi perubahan yang lebih baik (termasuk paste, dll)
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const query = this.value.trim();

                    debounceTimer = setTimeout(() => {
                        // Jika query kosong, kembalikan ke state semula tanpa reload halaman
                        if (query.length === 0) {
                            tableBody.innerHTML = initialTableContent;
                            paginationLinks.innerHTML = initialPaginationContent;
                            paginationLinks.style.display = 'block';
                            return;
                        }

                        // Jika ada query, lakukan pencarian via AJAX
                        const searchUrl =
                            `{{ route('admin.berita.search') }}?search=${encodeURIComponent(query)}`;

                        fetch(searchUrl, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                paginationLinks.style.display =
                                    'none'; // Sembunyikan paginasi saat mencari
                                tableBody.innerHTML = data.table_rows;
                            })
                            .catch(error => {
                                console.error('Error fetching search results:', error);
                                // Tampilkan pesan error yang lebih informatif
                                tableBody.innerHTML =
                                    '<tr><td colspan="4" class="text-center py-12 text-red-500">Gagal memuat hasil pencarian. Periksa konsol browser untuk detail.</td></tr>';
                            });
                    }, 300); // Tunggu 300ms setelah user berhenti mengetik
                });
            });
        </script>
    @endpush
</x-layout>
