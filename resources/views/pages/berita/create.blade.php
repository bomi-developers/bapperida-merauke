<x-layout>
    <x-header />
    <main class="p-6" id="main-container">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Tambah Berita Baru</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                role="alert">
                <span class="font-medium">Validasi Gagal!</span>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Kolom Kiri: Detail Utama --}}
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                    <div class="mb-4">
                        <label for="title" class="form-label">Judul Berita</label>
                        <input type="text" id="title" name="title" class="form-input block w-full"
                            value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="excerpt" class="form-label">Kutipan (Excerpt)</label>
                        <textarea id="excerpt" name="excerpt" class="form-input block w-full" rows="3" required>{{ old('excerpt') }}</textarea>
                    </div>

                    {{-- Content Blocks --}}
                    <div>
                        <label class="form-label">Konten Berita</label>
                        <div class="p-4 border border-dashed border-gray-400 dark:border-gray-500 rounded-lg mt-2">
                            <div id="items-container">
                                {{-- Item dinamis akan ditambahkan di sini oleh JS --}}
                            </div>
                            <div class="flex flex-wrap gap-2 mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <button type="button" id="add-text" class="btn-add-item"><i
                                        class="bi bi-textarea-t mr-2"></i> Tambah Teks</button>
                                {{-- <button type="button" id="add-quote" class="btn-add-item"><i class="bi bi-chat-left-quote-fill mr-2"></i> Tambah Kutipan</button> --}}
                                <button type="button" id="add-image" class="btn-add-item"><i
                                        class="bi bi-image mr-2"></i> Tambah Gambar</button>
                                <button type="button" id="add-video" class="btn-add-item"><i
                                        class="bi bi-youtube mr-2"></i> Tambah Video</button>
                                <button type="button" id="add-embed" class="btn-add-item"><i
                                        class="bi bi-code-slash mr-2"></i> Tambah Embed</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Metadata --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                        <div class="mb-4">
                            <label class="form-label">Status</label>
                            <div class="relative
                            w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="bi bi-check-circle-fill text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <select id="status" name="status"
                                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                            focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                            block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                            dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    <option value="draft" @selected(old('status') == 'draft')>Draft</option>
                                    <option value="published" @selected(old('status') == 'published')>Published</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Halaman</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="bi bi-folder text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <select id="page" name="page"
                                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                                            focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                                            block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                                            dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    <option value="berita">Berita Utama</option>
                                    <option value="inovasi_riset">Bid.Inovasi - Riset</option>
                                    <option value="inovasi_data">Bid.Inovasi - Data</option>
                                    <option value="inovasi_kekayaan_intelektual">Bid.Inovasi - Kekayaan Intelektual
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="cover_image" class="form-label">Cover Berita</label>
                            <input type="file" id="cover_image" name="cover_image" class="form-input block w-full"
                                accept="image/*" required>
                        </div>
                        <div class="mt-6 flex gap-2">
                            <button type="submit"
                                class="px-4 py-4 text-sm text-lg rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white transition w-full">Simpan
                                Berita</button>
                            <a href="{{ route('admin.berita.index') }}"
                                class="w-full text-center px-4 py-4 text-sm text-lg rounded-2xl border border-red-300 dark:border-red-600
                       text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-800 transition">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    @push('scripts')
        @include('pages.berita._form_scripts', ['itemIndex' => 0])
    @endpush
</x-layout>

{{-- Tambahkan style helper ini di layout utama Anda atau di sini --}}
<style>
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
    }

    .dark .form-label {
        color: #d1d5db;
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

    .btn-add-item:hover {
        background-color: #f3f4f6;
    }

    .dark .btn-add-item:hover {
        background-color: #374151;
    }

    .btn-primary {
        background-color: #4f46e5;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-primary:hover {
        background-color: #4338ca;
    }

    .btn-secondary {
        background-color: #e5e7eb;
        color: #1f2937;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .dark .btn-secondary {
        background-color: #4b5563;
        color: #f9fafb;
    }

    .btn-secondary:hover {
        background-color: #d1d5db;
    }

    .dark .btn-secondary:hover {
        background-color: #6b7280;
    }

    /* Scroll for item container */
    #main-container {
        max-height: 100vh;
        /* Atur ketinggian maksimum sesuai kebutuhan */
        overflow-y: auto;
        padding-right: 0.5rem;
        /* Memberi ruang untuk scrollbar */
    }

    /* Custom scrollbar for webkit browsers */
    #main-container::-webkit-scrollbar {
        width: 8px;
    }

    #main-container::-webkit-scrollbar-track {
        background: transparent;
    }

    #main-container::-webkit-scrollbar-thumb {
        background: #d1d5db;
        /* gray-300 */
        border-radius: 4px;
    }

    .dark #main-container::-webkit-scrollbar-thumb {
        background: #4b5563;
        /* gray-600 */
    }

    #main-container::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
        /* gray-400 */
    }

    .dark #main-container::-webkit-scrollbar-thumb:hover {
        background: #6b7280;
        /* gray-500 */
    }
</style>
