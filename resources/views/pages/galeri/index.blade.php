<x-layout>
    <x-header />
    <main class="p-6 flex-1 overflow-y-auto"> {{-- Pastikan scrolling aktif --}}
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manajemen Galeri Album</h2>
            <button id="add-galeri-btn"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Album Baru</span>
            </button>
        </div>

        {{-- =================================== --}}
        {{-- === SEARCH BAR BARU === --}}
        {{-- =================================== --}}
        <form method="GET" action="{{ route('admin.galeri.index') }}" class="mb-5">
            <div class="flex">
                <input type="text" id="searchInput" name="search" placeholder="Ketik judul album..." 
                       value="{{ request('search') }}" {{-- Tampilkan query pencarian sebelumnya --}}
                       class="w-full sm:w-80 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-l-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" />
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-r-lg text-sm flex items-center">
                    <i class="bi bi-search mr-2"></i>
                    Cari
                </button>
            </div>
        </form>
        {{-- =================================== --}}

        {{-- Tabel Galeri (Album) --}}
        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Cover</th>
                            <th scope="col" class="px-6 py-3 min-w-[300px]">Judul Album</th>
                            <th scope="col" class="px-6 py-3">Jumlah Item</th>
                            <th scope="col" class="px-6 py-3">Tanggal Dibuat</th>
                            <th scope="col" class.="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    {{-- PERBAIKAN: Ganti @forelse dengan @include --}}
                    <tbody id="galeri-table-body">
                        @include('pages.galeri.partials._galeri_rows', ['galeris' => $galeris])
                    </tbody>
                </table>
            </div>
            {{-- Paginasi tidak diperlukan untuk index admin, karena kita load semua --}}
        </section>
    </main>

    {{-- Memuat Modal Tambah/Edit dan Modal Detail --}}
    @include('pages.galeri.partials._modal')

    @push('scripts')
        {{-- Memuat Skrip AJAX untuk galeri --}}
        @include('pages.galeri.partials._scripts')
    @endpush
</x-layout>
