<div id="show-modal" class="fixed inset-0 z-50 hidden bg-black/20 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-md w-full max-w-4xl max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="p-5 border-b border-gray-200 flex justify-between items-center flex-shrink-0">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-3">
                <i class="bi bi-file-earmark-text text-blue-600"></i>
                Detail Dokumen
            </h3>
            <button type="button"
                class="close-show-modal text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-grow p-6 space-y-6 overflow-y-auto"
            style="scrollbar-width: thin; scrollbar-color: #A0AEC0 #E2E8F0;">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Cover & File -->
                <div class="lg:col-span-1 space-y-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Cover Dokumen</h4>
                        {{-- Placeholder for cover image/message --}}
                        <div id="show-cover"
                            class="aspect-video bg-gray-100 rounded-md flex items-center justify-center">
                            {{-- Content will be filled by JS --}}
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">File Dokumen</h4>
                        {{-- Placeholder for file download card --}}
                        <div id="show-file"></div>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Teks -->
                <div class="lg:col-span-2 space-y-4">
                    <h2 id="show-judul" class="text-2xl font-bold text-gray-900"></h2>
                    <p class="text-sm text-gray-500 -mt-2">Kategori: <span id="show-kategori"
                            class="font-medium text-gray-700"></span></p>

                    <div id="show-lainnya-wrapper" class="hidden pt-4 border-t border-gray-200 space-y-5">
                        <div id="show-lainnya-container">
                            {{-- Visi, Misi, Keterangan will be filled by JS --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
