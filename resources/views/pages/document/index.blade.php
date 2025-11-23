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

        {{-- Tabel Dokumen --}}
        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 min-w-[300px]">#</th>
                            <th scope="col" class="px-6 py-3 min-w-[300px]">Judul</th>
                            <th scope="col" class="px-6 py-3">Kategori</th>
                            <th scope="col" class="px-6 py-3">File</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="document-table-body">
                        @forelse ($documents as $doc)
                            <tr id="doc-row-{{ $doc->id }}"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-2 w-[10px]">{{ $loop->iteration }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $doc->cover ? asset('storage/' . $doc->cover) : 'https://placehold.co/100x60/e2e8f0/e2e8f0?text=No+Cover' }}"
                                            alt="Cover" class="w-24 h-14 object-cover rounded-md flex-shrink-0">
                                        <span class="font-semibold">{{ $doc->judul }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4"><span
                                        class="bg-indigo-200 dark:bg-indigo-600 px-2 py-1 text-indigo-800 dark:text-indigo-200 rounded-xl">{{ $doc->kategori->nama_kategori ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ asset('storage/' . $doc->file) }}" target="_blank"
                                        class="font-bold text-indigo-600 dark:text-white hover:underline">
                                        Lihat File
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-4">
                                        <button
                                            class="show-btn font-medium text-green-600 dark:text-green-500 hover:underline"
                                            title="Detail" data-id="{{ $doc->id }}">
                                            <i class="bi bi-eye-fill text-base"></i>
                                        </button>
                                        <button
                                            class="edit-btn font-medium text-indigo-600 dark:text-indigo-500 hover:underline"
                                            title="Edit" data-id="{{ $doc->id }}">
                                            <i class="bi bi-pencil-square text-base"></i>
                                        </button>
                                        <button
                                            class="delete-btn font-medium text-red-600 dark:text-red-500 hover:underline"
                                            title="Hapus" data-id="{{ $doc->id }}">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-data-row">
                                <td colspan="4" class="text-center py-12">
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada dokumen yang ditambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    {{-- Modal dirender dari file partial --}}
    @include('pages.document._modals')

    {{-- Script dirender dari file partial --}}
    @push('scripts')
        @include('pages.document._scripts')
    @endpush
</x-layout>
