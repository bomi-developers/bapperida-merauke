<!-- Modal Tambah/Edit Dokumen -->
<div id="document-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <form id="document-form" enctype="multipart/form-data">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-white"></h3>
            </div>
            <div class="p-6 space-y-4">
                {{-- Hidden fields --}}
                <input type="hidden" name="_method" id="method-field">
                <input type="hidden" name="document_id" id="document-id-field">

                <div>
                    <label for="judul" class="form-label">Judul Dokumen</label>
                    <input type="text" id="judul" name="judul" class="form-input w-full" required>
                </div>
                <div>
                    <label for="kategori_document_id" class="form-label">Kategori</label>
                    <select id="kategori_document_id" name="kategori_document_id" class="form-input w-full" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="cover" class="form-label">Cover Image (Opsional)</label>
                    <input type="file" id="cover" name="cover" class="form-input w-full" accept="image/*">
                    <div id="cover-preview-container" class="mt-2"></div>
                </div>
                <div>
                    <label for="file" class="form-label">File Dokumen (PDF, Word, Excel, dll)</label>
                    <input type="file" id="file" name="file" class="form-input w-full" required>
                    <div id="file-preview-container" class="mt-2"></div>
                </div>

                <!-- Dynamic JSON Builder -->
                <div>
                    <label class="form-label">Data Tambahan (Opsional)</label>
                    <div class="p-4 border border-dashed border-gray-400 dark:border-gray-500 rounded-lg mt-2">
                        <div id="lainnya-container" class="space-y-4">
                            <!-- Dynamic fields will be injected here by JS -->
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <button type="button" id="add-visi" class="btn-add-item"><i class="bi bi-eye mr-2"></i>
                                Tambah Visi</button>
                            <button type="button" id="add-misi" class="btn-add-item"><i
                                    class="bi bi-list-check mr-2"></i> Tambah Misi</button>
                            <button type="button" id="add-keterangan" class="btn-add-item"><i
                                    class="bi bi-info-circle mr-2"></i> Tambah Keterangan</button>
                        </div>
                    </div>
                    <!-- This hidden input will hold the final JSON string -->
                    <input type="hidden" id="lainnya-json" name="lainnya">
                </div>

            </div>
            <div
                class="p-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                <button type="button" id="close-modal-btn" class="btn-secondary">Batal</button>
                <button type="submit" id="save-btn" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- show modal --}}
<div id="show-modal"
    class="fixed inset-0 z-[60] hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div
        class="bg-slate-800 border border-slate-700 rounded-2xl shadow-xl w-full max-w-4xl max-h-[95vh] flex flex-col text-white">
        <!-- Header -->
        <div class="p-5 border-b border-slate-700 flex justify-between items-center flex-shrink-0">
            <h3 class="text-xl font-semibold flex items-center gap-3">
                <i class="bi bi-file-earmark-text text-blue-400"></i>
                <span>Detail Dokumen</span>
            </h3>
            <button type="button" class="close-show-modal text-slate-400 hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8 overflow-y-auto">
            <!-- Kolom Kiri: Cover & File -->
            <div class="space-y-6">
                <div>
                    <h4 class="font-semibold text-slate-400 mb-2">Cover Dokumen</h4>
                    <div id="show-cover">
                        {{-- JS will inject cover image here --}}
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-400 mb-2">File Dokumen</h4>
                    <div id="show-file">
                        {{-- JS will inject file preview card here --}}
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian -->
            <div class="space-y-5">
                <div>
                    <h2 id="show-judul" class="text-2xl font-bold text-white leading-tight"></h2>
                    <p class="text-sm text-slate-400 mt-1">Kategori: <span id="show-kategori"
                            class="font-medium text-blue-400"></span></p>
                </div>

                <div id="show-lainnya-wrapper" class="hidden pt-5 border-t border-slate-700">
                    <div id="show-lainnya-container" class="space-y-5 text-sm">
                        {{-- JS will inject Visi, Misi, Keterangan here --}}
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
