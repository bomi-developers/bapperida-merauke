<x-layout>
    <x-header />
    <main class="p-6 flex-1 overflow-y-auto">
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manajemen Galeri Album</h2>
            <button id="add-galeri-btn"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Album Baru</span>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row flex-wrap gap-4 w-full items-center mb-6">

            {{-- 1. Search Input --}}
            <div class="relative w-full sm:w-64 group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-search text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="text" id="search"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl 
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 p-2.5 shadow-sm transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    placeholder="Cari berita...">
            </div>
            <div class="relative w-full sm:w-80">

                <!-- Icon kiri -->
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-calendar-range text-gray-400 dark:text-gray-500"></i>
                </div>

                <!-- Wrapper dua input dalam satu kotak -->
                <div
                    class="flex items-center bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
               focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 
               w-full pl-10 pr-10 p-2.5 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">

                    <!-- Start Date -->
                    <input type="date" id="startDate"
                        class="w-1/2 bg-transparent outline-none border-none text-sm
                   dark:text-white" />
                    <span class="mx-2 text-gray-400">—</span>
                    <!-- End Date -->
                    <input type="date" id="endDate"
                        class="w-1/2 bg-transparent outline-none border-none text-sm
                   dark:text-white" />
                </div>
                <!-- Icon kanan -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>
        </div>

        {{-- Tabel Galeri (Album) --}}
        {{-- <section
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
                    <tbody id="galeri-table-body">
                        @forelse ($galeris as $album)
                            <tr id="galeri-row-{{ $album->id }}"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

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
                                <td colspan="5" class="text-center py-12">
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada album galeri yang
                                        ditambahkan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section> --}}
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <div id="galeri-table" class="max-w-full overflow-x-auto p-6"></div>
        </div>
    </main>

    @include('pages.galeri.partials._modal')
    @push('styles')
        <style>
            #galeri-modal>div {
                display: flex;
                flex-direction: column;
            }

            #galeri-modal form {
                display: flex;
                flex-direction: column;
                flex-grow: 1;
                min-height: 0;
            }

            #galeri-modal form>div.overflow-y-auto {
                flex-grow: 1;
                min-height: 0;
            }

            /* Styling dasar */
            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: #374151;
            }

            .dark .form-label {
                color: #d1d5db;
            }

            .form-input {
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                padding: 0.5rem 0.75rem;
                width: 100%;
                background-color: #f9fafb;
                color: #111827;
            }

            .dark .form-input {
                border-color: #4b5563;
                background-color: #374151;
                color: #f9fafb;
            }

            .btn-add-item {
                padding: 0.5rem 1rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                transition: all 0.2s;
                display: inline-flex;
                align-items: center;
            }

            .dark .btn-add-item {
                border-color: #4b5563;
            }

            .btn-add-item:hover:not(:disabled) {
                background-color: #f3f4f6;
            }

            .dark .btn-add-item:hover:not(:disabled) {
                background-color: #374151;
            }

            .btn-add-item:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            /* Mencegah ikon mencuri klik */
            /* .show-btn i,
                                                            .edit-btn i, */
            .delete-btn i,
            .close-modal-btn i,
            .close-show-modal i,
            .remove-existing-item i,
            .remove-new-item i {
                pointer-events: none;
            }
        </style>
    @endpush

    @push('scripts')
        @include('pages.galeri.partials._scripts')
        {{-- tambahan nanti di pindah --}}
    @endpush
</x-layout>
