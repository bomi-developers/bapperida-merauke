<!-- Modal Show Detail (Modern UI) -->
<div id="show-modal"
    class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="p-5 border-b border-slate-700 flex justify-between items-center flex-shrink-0">
            <h3 class="text-xl font-semibold text-white flex items-center gap-3">
                <i class="bi bi-file-earmark-text text-blue-400"></i>
                Detail Dokumen
            </h3>
            <button type="button" class="close-show-modal text-slate-400 hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-grow p-6 space-y-6 overflow-y-auto"
            style="scrollbar-width: thin; scrollbar-color: #4A5568 #2D3748;">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kolom Kiri: Cover & File -->
                <div class="lg:col-span-1 space-y-6">
                    <div>
                        <h4 class="font-semibold text-blue-400 mb-2">Cover Dokumen</h4>
                        <div id="show-cover"></div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-blue-400 mb-2">File Dokumen</h4>
                        <div id="show-file"></div>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Teks -->
                <div class="lg:col-span-2 space-y-4">
                    <h2 id="show-judul" class="text-2xl font-bold text-white"></h2>
                    <p class="text-sm text-slate-400 -mt-2">Kategori: <span id="show-kategori"
                            class="font-medium text-slate-300"></span></p>

                    <div id="show-lainnya-wrapper" class="hidden pt-4 border-t border-slate-700 space-y-5">
                        <div id="show-lainnya-container"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 bg-slate-900/50 border-t border-slate-700 flex justify-end flex-shrink-0">
            <button type="button" class="btn-secondary close-show-modal">Tutup</button>
        </div>
    </div>
</div>