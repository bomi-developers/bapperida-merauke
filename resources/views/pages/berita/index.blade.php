<x-layout>
    <x-header />
    <main class="p-6 flex-1 overflow-y-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manajemen Berita</h2>
            <a href="{{ route('admin.berita.create') }}"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Berita</span>
            </a>
        </div>

        @if (session('success'))
            <div id="success-alert"
                class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- PERBAIKAN: Menggunakan form HTML standar untuk pencarian (lebih andal) --}}
        <form method="GET" action="{{ route('admin.berita.index') }}" class="mb-5">
            <div class="flex">
                <input type="text" id="searchInput" name="search" placeholder="Ketik judul berita..."
                    value="{{ request('search') }}" {{-- Tampilkan query pencarian sebelumnya --}}
                    class="w-full sm:w-80 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-l-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" />
                <button type="submit"
                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-r-lg text-sm">
                    Cari
                </button>
            </div>
        </form>


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
                    {{-- PERBAIKAN: Tambahkan appends agar paginasi tetap membawa query pencarian --}}
                    {{ $beritas->appends(request()->query())->links() }}
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            // Script untuk menghilangkan alert
            setTimeout(() => {
                const alertBox = document.getElementById('success-alert');
                if (alertBox) {
                    alertBox.style.transition = 'opacity 0.5s ease';
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500);
                }
            }, 3000); // Hilang setelah 3 detik
        </script>
    @endpush
</x-layout>
