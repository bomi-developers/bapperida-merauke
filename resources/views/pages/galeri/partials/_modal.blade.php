<!-- Modal Tambah/Edit Album Galeri -->
<div id="galeri-modal"
    class="fixed inset-0 z-50 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg w-full max-w-3xl max-h-[90vh] flex flex-col border border-gray-200 dark:border-gray-700">
        <form id="galeri-form" enctype="multipart/form-data" class="flex flex-col flex-grow min-h-0">
            <!-- Header Modal -->
            <div class="pt-5 px-5 dark:border-gray-700 flex justify-between items-center flex-shrink-0">
                <h3 id="modal-title" class="text-xl font-semibold mb-5 text-gray-900 dark:text-white"></h3>
                <button type="button" {{-- Tombol ini sudah benar --}}
                    class="close-modal-btn text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Konten Modal (Scrollable) -->
            <div class="flex-grow overflow-y-auto px-5 space-y-4"
                style="scrollbar-width: thin; scrollbar-color: #A0AEC0 #E2E8F0;">
                {{-- Hidden fields --}}
                <input type="hidden" name="_method" id="method-field">
                <input type="hidden" name="galeri_id" id="galeri-id-field">

                {{-- Input Judul Album --}}
                <div>
                    <label for="judul" class="form-label">Judul Album</label>
                    <input type="text" id="judul" name="judul" class="form-input w-full" required>
                </div>

                {{-- Input Keterangan Album --}}
                <div>
                    <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" class="form-input w-full" rows="3"></textarea>
                </div>

                <div class="mb-4">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        <input type="checkbox" name="is_highlighted" id="is_highlighted"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                        <span>Jadikan Album Pilihan (Highlight Album)</span>
                    </label>
                </div>

                {{-- Container untuk Item Media (Dinamis) --}}
                <div>
                    <label class="form-label">Item Media</label>
                    <div class="p-4 border border-dashed border-gray-400 dark:border-gray-500 rounded-lg mt-2">
                        {{-- Daftar Item yang Sudah Ada (Mode Edit) --}}
                        <div id="existing-items-container" class="space-y-3 mb-4 border-b dark:border-gray-600 pb-4">
                            {{-- Item akan di-render oleh JS --}}
                        </div>
                        {{-- Daftar Item Baru yang Ditambahkan --}}
                        <div id="new-items-container" class="space-y-3">
                            {{-- Item baru akan ditambahkan di sini oleh JS --}}
                        </div>
                        {{-- Tombol untuk Menambah Item Baru --}}
                        <div class="flex flex-wrap gap-2 mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <button type="button" id="add-image-item" class="btn-add-item"><i
                                    class="bi bi-image mr-2"></i> Tambah Foto</button>
                            <button type="button" id="add-video-item" class="btn-add-item"><i
                                    class="bi bi-film mr-2"></i> Tambah Video</button>
                            <button type="button" id="add-video-url-item" class="btn-add-item"><i
                                    class="bi bi-link-45deg mr-2"></i> Tambah Link Video</button>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                            Catatan: Ukuran file maksimum untuk foto atau video adalah 50MB.
                        </p>
                    </div>
                </div>

                {{-- Hidden input untuk item yang dihapus --}}
                <div id="deleted-items-input-container"></div>

            </div>

            <!-- Footer Modal -->
            <div class="p-4  flex justify-end gap-2 flex-shrink-0">

                <button type="submit" id="save-btn"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">Simpan</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Show Detail Album Galeri -->
<div id="show-modal"
    class="fixed inset-0 z-50 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center p-4">
    <div
        class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-md w-full max-w-4xl max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="p-5 flex justify-between items-center flex-shrink-0">
            <h3 class="text-xl font-semibold text-black dark:text-white flex items-center gap-3">
                <i class="bi bi-images dark:text-indigo-400 text-indigo-700"></i>
                Detail Album Galeri
            </h3>
            <button type="button"
                class="close-show-modal text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">
                &times;
            </button>
        </div>

        <!-- Content -->
        <div class="flex-grow p-6 space-y-4 overflow-y-auto"
            style="scrollbar-width: thin; scrollbar-color: #4A5568 #2D3748;">
            <h2 id="show-judul" class="text-2xl font-bold text-black dark:text-white mb-1"></h2>
            <p id="show-keterangan" class="dark:text-slate-200 text-slate-700 text-sm mb-6"></p>

            <div id="show-items-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                {{-- Item galeri akan di-render oleh JS di sini --}}
            </div>
            <p id="no-items-message" class="text-slate-500 text-center py-8 hidden">Album ini belum memiliki item media.
            </p>
        </div>


    </div>
</div>

{{-- ====================================== --}}
{{-- === MODAL LOADING (PENGGANTI PROGRESS) === --}}
{{-- ====================================== --}}
<div id="loading-modal" class="fixed inset-0 z-[60] hidden bg-black bg-opacity-75 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-xs p-6 text-center">
        <div class="flex justify-center items-center mb-4">
            <i class="bi bi-arrow-repeat text-4xl text-blue-600 animate-spin"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sedang memproses...</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Harap tunggu sebentar.</p>
    </div>
</div>
{{-- ====================================== --}}


<style>
    /* Style tambahan untuk item baru dan yang sudah ada */
    .galeri-item-input-wrapper,
    .existing-item-preview {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background-color: #f9fafb;
    }

    .dark .galeri-item-input-wrapper,
    .dark .existing-item-preview {
        border-color: #4b5563;
        background-color: #374151;
    }

    .galeri-item-input-wrapper .item-preview img,
    .existing-item-preview img,
    .galeri-item-input-wrapper .item-preview video,
    .existing-item-preview video {
        width: 4rem;
        height: 3rem;
        object-fit: cover;
        border-radius: 0.375rem;
        flex-shrink: 0;
    }

    .galeri-item-input-wrapper .item-preview .placeholder,
    .existing-item-preview .video-placeholder {
        width: 4rem;
        height: 3rem;
        background-color: #e5e7eb;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #9ca3af;
    }

    .dark .galeri-item-input-wrapper .item-preview .placeholder,
    .dark .existing-item-preview .video-placeholder {
        background-color: #4b5563;
        color: #6b7280;
    }

    .galeri-item-input-wrapper .input-area,
    .existing-item-preview .file-info {
        flex-grow: 1;
        overflow: hidden;
        font-size: 0.75rem;
        color: #6b7280;
    }

    .dark .galeri-item-input-wrapper .input-area,
    .dark .existing-item-preview .file-info {
        color: #9ca3af;
    }

    .existing-item-preview .file-name {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 500;
        color: #374151;
    }

    .dark .existing-item-preview .file-name {
        color: #d1d5db;
    }

    .galeri-item-input-wrapper .remove-new-item,
    .existing-item-preview .remove-existing-item {
        padding: 0.25rem;
        color: #ef4444;
        border-radius: 9999px;
        transition: background-color 0.2s;
        flex-shrink: 0;
    }

    .galeri-item-input-wrapper .remove-new-item:hover,
    .existing-item-preview .remove-existing-item:hover {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .dark .galeri-item-input-wrapper .remove-new-item:hover,
    .dark .existing-item-preview .remove-existing-item:hover {
        background-color: #991b1b;
        color: #fecaca;
    }
</style>
