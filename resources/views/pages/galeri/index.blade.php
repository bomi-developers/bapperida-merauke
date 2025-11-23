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
                <button type="submit"
                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-r-lg text-sm flex items-center">
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
                            <th scope="col" class="px-6 py-3">#</th>
                            <th scope="col" class="px-6 py-3">Cover</th>
                            <th scope="col" class="px-6 py-3 min-w-[300px]">Judul Album</th>
                            <th scope="col" class="px-6 py-3">Jumlah Item</th>
                            <th scope="col" class="px-6 py-3">Tanggal Dibuat</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    {{-- PERBAIKAN: Ganti @forelse dengan @include --}}
                    <tbody id="galeri-table-body">
                        {{-- Render baris awal dari data album --}}
                        @forelse ($galeris as $album)
                            <tr id="galeri-row-{{ $album->id }}"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                {{-- PERBAIKAN: Tambahkan <td> untuk Cover --}}
                                <td class="px-2 flex items-center justify-center !flex">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($album->firstItem)
                                        @if ($album->firstItem->tipe_file == 'image')
                                            <img src="{{ asset('storage/' . $album->firstItem->file_path) }}"
                                                alt="Cover" class="w-16 h-12 object-cover rounded-md">
                                        @elseif($album->firstItem->tipe_file == 'video' || $album->firstItem->tipe_file == 'video_url')
                                            <div
                                                class="w-16 h-12 bg-gray-700 rounded-md flex items-center justify-center">
                                                <i class="bi bi-film text-2xl text-gray-400"></i>
                                            </div>
                                        @endif
                                    @else
                                        <div
                                            class="w-16 h-12 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                            <i class="bi bi-image-alt text-2xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    @endif
                                </td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $album->judul }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $album->items_count }} Item
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ $album->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @if ($album->is_highlighted)
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                                            Pilihan
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Normal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <button
                                            class="show-btn font-medium text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-800 rounded-full w-8 h-8"
                                            title="Detail" data-id="{{ $album->id }}"><i
                                                class="bi bi-eye-fill text-lg"></i></button>
                                        <button
                                            class="edit-btn font-medium text-indigo-600 dark:text-indigo-500 hover:bg-indigo-200 dark:hover:bg-indigo-800 rounded-full w-8 h-8"
                                            title="Edit" data-id="{{ $album->id }}">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>
                                        <button
                                            class="delete-btn font-medium text-red-600 dark:text-red-500  hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8"
                                            title="Hapus" data-id="{{ $album->id }}">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-data-row">
                                {{-- PERBAIKAN: Ubah colspan menjadi 5 --}}
                                <td colspan="5" class="text-center py-12">
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada album galeri yang ditambahkan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
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
