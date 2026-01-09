<!-- 1. MODAL TAHAPAN (ADMIN) -->
<div id="tahapanModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-70 flex justify-center items-center backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl p-8 transform transition-all scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="tahapanModalTitle">Atur Tahapan RKPD</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola jadwal dan template.</p>
            </div>
            <button onclick="closeTahapanModal()" class="text-gray-400 hover:text-gray-600 transition-colors"><i
                    class="bi bi-x-lg text-xl"></i></button>
        </div>
        <form action="{{ route('renja.tahapan.update') }}" method="POST" enctype="multipart/form-data"
            id="formTahapan">
            @csrf <input type="hidden" name="id" id="tahapan_id">
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-semibold mb-2">Tahun Anggaran</label><input type="number"
                        name="tahun" id="tahapan_tahun" value="{{ date('Y') + 1 }}"
                        class="block w-full rounded-xl border-gray-300 dark:bg-gray-700 p-3 shadow-sm"></div>
                <div class="relative group">
                    <label class="block text-sm font-semibold mb-2">Jenis Tahapan</label>
                    <div class="relative">
                        <select name="nama_tahapan" id="tahapan_nama"
                            class="appearance-none block w-full rounded-xl border-gray-300 dark:bg-gray-700 p-3 pr-10 shadow-sm cursor-pointer">
                            <option value="RANWAL">Ranwal</option>
                            <option value="RANCANGAN">Rancangan</option>
                            <option value="RANHIR">Ranhir</option>
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="mb-6 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800">
                <p class="text-xs font-bold text-indigo-600 uppercase mb-3">Jadwal Upload</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">Mulai</label><input type="date"
                            name="start_date" id="tahapan_start" required
                            class="block w-full rounded-lg border-gray-300 dark:bg-gray-700 p-2.5"></div>
                    <div><label class="block text-sm font-medium mb-1">Selesai</label><input type="date"
                            name="end_date" id="tahapan_end" required
                            class="block w-full rounded-lg border-gray-300 dark:bg-gray-700 p-2.5"></div>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">File Template RKPD</label>
                <div id="current-file-area"
                    class="hidden mb-3 p-3 bg-green-50 border border-green-200 rounded-lg flex justify-between items-center">
                    <div class="flex items-center gap-2 overflow-hidden"><i
                            class="bi bi-file-earmark-check-fill text-green-600 text-xl"></i><span id="current-filename"
                            class="text-sm truncate max-w-[200px]">filename.docx</span></div>
                    <a id="current-file-link" href="#" target="_blank"
                        class="text-xs bg-white border px-2 py-1 rounded text-green-700 hover:bg-gray-50">Lihat</a>
                </div>
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file"
                        class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 transition-colors relative">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4"><i
                                class="bi bi-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                            <p class="mb-1 text-sm text-gray-500" id="dropzone-text"><span
                                    class="font-semibold text-indigo-600">Klik upload</span> atau drag file</p>
                        </div>
                        <input id="dropzone-file" name="file_template" type="file"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="closeTahapanModal()"
                    class="px-6 py-2.5 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200">Batal</button>
                <button type="submit" id="btnSubmitTahapan"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. MODAL UPLOAD RENJA (OPD) -->
<div id="uploadModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-70 flex justify-center items-center backdrop-blur-sm p-4">
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl p-8 transform transition-all scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Upload Dokumen Renja</h3>
                <p class="text-sm text-gray-500 mt-1">Pastikan format sesuai template.</p>
            </div>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 transition-colors"><i class="bi bi-x-lg text-xl"></i></button>
        </div>
        <form id="uploadForm" action="{{ route('renja.store') }}" method="POST" enctype="multipart/form-data">
            @csrf <input type="hidden" name="tahapan_id" id="upload_tahapan_id">
            <div class="space-y-6 mb-8">
                <div id="container-doc">
                    <div class="flex justify-between items-center mb-2"><label
                            class="block text-sm font-bold flex items-center gap-2"><span
                                class="bg-red-100 text-red-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                            File Naskah (PDF/Word)</label><span id="status-badge-doc"
                            class="hidden text-xs font-bold px-2 py-1 rounded bg-green-100 text-green-700">SUDAH
                            DISETUJUI</span></div>
                    <div id="upload-area-doc" class="flex items-center justify-center w-full">
                        <label for="doc-file"
                            class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 transition-colors relative">
                            <div class="flex flex-col items-center justify-center pt-2 pb-3"><i
                                    class="bi bi-file-earmark-text text-2xl text-gray-400 mb-1"></i>
                                <p class="text-sm text-gray-500" id="doc-text"><span
                                        class="font-semibold text-indigo-600">Klik upload</span></p>
                            </div>
                            <input id="doc-file" name="file_dokumen" type="file"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        </label>
                    </div>
                    <div id="approved-msg-doc"
                        class="hidden p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Dokumen disetujui.
                    </div>
                </div>
                <div id="container-matrix">
                    <div class="flex justify-between items-center mb-2"><label
                            class="block text-sm font-bold flex items-center gap-2"><span
                                class="bg-green-100 text-green-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                            File Matriks (Excel)</label><span id="status-badge-matrix"
                            class="hidden text-xs font-bold px-2 py-1 rounded bg-green-100 text-green-700">SUDAH
                            DISETUJUI</span></div>
                    <div id="upload-area-matrix" class="flex items-center justify-center w-full">
                        <label for="matrix-file"
                            class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 transition-colors relative">
                            <div class="flex flex-col items-center justify-center pt-2 pb-3"><i
                                    class="bi bi-file-earmark-spreadsheet text-2xl text-gray-400 mb-1"></i>
                                <p class="text-sm text-gray-500" id="matrix-text"><span
                                        class="font-semibold text-indigo-600">Klik upload</span></p>
                            </div>
                            <input id="matrix-file" name="file_matriks" type="file"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        </label>
                    </div>
                    <div id="approved-msg-matrix"
                        class="hidden p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Matriks disetujui.
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')"
                    class="px-6 py-2.5 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200">Batal</button>
                <button type="submit"
                    class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition-all"><i
                        class="bi bi-send-fill me-1"></i> Kirim</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. MODAL VERIFIKASI (ADMIN) -->
<div id="verifyModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-70 flex justify-center items-center backdrop-blur-sm p-4">
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-4xl p-8 transform transition-all scale-100 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-start mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Verifikasi Dokumen</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400" id="verify-opd-name">OPD: -</p>
            </div>
            <button onclick="document.getElementById('verifyModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 transition-colors"><i
                    class="bi bi-x-lg text-xl"></i></button>
        </div>

        <form id="formVerify" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                {{-- KIRI: DOKUMEN NASKAH --}}
                <div
                    class="flex flex-col h-full bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                        <h4 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs flex items-center justify-center">1</span>
                            Dokumen Naskah
                        </h4>
                    </div>

                    <div class="p-4 flex-1 space-y-4">
                        {{-- Status Radio --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2 block">Status
                                Dokumen</label>
                            <div class="flex gap-3">
                                <label class="flex-1 relative cursor-pointer group">
                                    <input type="radio" name="status_dokumen" value="DISETUJUI"
                                        class="peer sr-only">
                                    <div
                                        class="p-2 text-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 hover:bg-gray-50 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">
                                        <i class="bi bi-check-circle me-1"></i> Sesuai
                                    </div>
                                </label>
                                <label class="flex-1 relative cursor-pointer group">
                                    <input type="radio" name="status_dokumen" value="REVISI" class="peer sr-only">
                                    <div
                                        class="p-2 text-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 hover:bg-gray-50 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all">
                                        <i class="bi bi-x-circle me-1"></i> Revisi
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1 block">Catatan</label>
                            <textarea name="catatan_dokumen" rows="3"
                                class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 text-sm focus:ring-red-500 focus:border-red-500"
                                placeholder="Keterangan revisi dokumen..."></textarea>
                        </div>

                        {{-- Upload Koreksi Dokumen --}}
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                            <label class="text-xs font-bold text-red-500 uppercase mb-2 block flex items-center gap-1">
                                <i class="bi bi-file-earmark-arrow-up"></i> Upload Koreksi Naskah
                            </label>
                            <input type="file" name="file_feedback_dokumen"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800">
                        </div>
                    </div>
                </div>

                {{-- KANAN: MATRIKS EXCEL --}}
                <div
                    class="flex flex-col h-full bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                        <h4 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-green-100 text-green-600 text-xs flex items-center justify-center">2</span>
                            Matriks Excel
                        </h4>
                    </div>

                    <div class="p-4 flex-1 space-y-4">
                        {{-- Status Radio --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2 block">Status
                                Matriks</label>
                            <div class="flex gap-3">
                                <label class="flex-1 relative cursor-pointer group">
                                    <input type="radio" name="status_matriks" value="DISETUJUI"
                                        class="peer sr-only">
                                    <div
                                        class="p-2 text-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 hover:bg-gray-50 peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">
                                        <i class="bi bi-check-circle me-1"></i> Sesuai
                                    </div>
                                </label>
                                <label class="flex-1 relative cursor-pointer group">
                                    <input type="radio" name="status_matriks" value="REVISI" class="peer sr-only">
                                    <div
                                        class="p-2 text-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 hover:bg-gray-50 peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all">
                                        <i class="bi bi-x-circle me-1"></i> Revisi
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1 block">Catatan</label>
                            <textarea name="catatan_matriks" rows="3"
                                class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-600 text-sm focus:ring-green-500 focus:border-green-500"
                                placeholder="Keterangan revisi matriks..."></textarea>
                        </div>

                        {{-- Upload Koreksi Matriks --}}
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                            <label
                                class="text-xs font-bold text-green-600 uppercase mb-2 block flex items-center gap-1">
                                <i class="bi bi-file-earmark-arrow-up"></i> Upload Koreksi Excel
                            </label>
                            <input type="file" name="file_feedback_matriks"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800">
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                <button type="button" onclick="document.getElementById('verifyModal').classList.add('hidden')"
                    class="px-6 py-2.5 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 font-medium transition-colors">Batal</button>
                <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold shadow-lg transition-all flex items-center gap-2">
                    <i class="bi bi-save-fill"></i> Simpan Hasil Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 4. MODAL DETAIL & PREVIEW -->
<div id="detailModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-80 flex justify-center items-center backdrop-blur-sm p-4 transition-opacity duration-300">
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-6xl h-[90vh] flex flex-col overflow-hidden transform transition-all scale-100">
        <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center flex-shrink-0">
            <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="bi bi-file-earmark-text"></i>
                Preview Dokumen</h3>
            <div class="flex items-center gap-3"><a href="#" id="detail-download-btn" target="_blank"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white rounded text-sm"><i
                        class="bi bi-download"></i> Download</a><button onclick="closeDetailModal()"
                    class="text-indigo-100 hover:text-white"><i class="bi bi-x-lg text-lg"></i></button></div>
        </div>
        <div class="flex-1 flex flex-col md:flex-row overflow-hidden">
            <div class="w-full md:w-1/3 bg-gray-50 dark:bg-gray-900 border-r border-gray-200 p-6 overflow-y-auto">
                <div class="mb-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Pengirim</p>
                    <p class="text-gray-900 dark:text-white font-bold text-lg mt-1" id="detail-name">-</p>
                    <div class="mt-2"><span id="detail-status"
                            class="px-3 py-1 rounded-full text-xs font-bold">-</span></div>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Catatan:</p>
                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-xs text-gray-700 dark:text-gray-300"
                        id="detail-admin-note">-</div>
                </div>
            </div>
            <div
                class="w-full md:w-2/3 bg-gray-200 dark:bg-gray-800 flex flex-col justify-center items-center relative">
                <div id="preview-loading" class="absolute inset-0 flex flex-col justify-center items-center z-10">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-indigo-500 border-t-transparent">
                    </div>
                    <p class="mt-3 text-gray-600 text-sm">Memuat...</p>
                </div>
                <div id="preview-error"
                    class="hidden absolute inset-0 flex flex-col justify-center items-center text-center p-6"><i
                        class="bi bi-file-earmark-x text-6xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Preview tidak tersedia.</p>
                </div>
                <iframe id="file-preview" class="w-full h-full border-none hidden bg-white"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- 5. MODAL HISTORY -->
<div id="historyModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-70 flex justify-center items-center backdrop-blur-sm p-4">
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-3xl p-6 transform transition-all scale-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Revisi</h3><button
                onclick="document.getElementById('historyModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="overflow-y-auto max-h-[60vh]">
            <ol class="relative border-l border-gray-200 dark:border-gray-700" id="history-content"></ol>
        </div>
    </div>
</div>
