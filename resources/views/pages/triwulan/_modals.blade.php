<div id="uploadModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80 flex justify-center items-center backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md p-6 transform transition-all scale-100">

        {{-- Header Modal --}}
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Upload Laporan</h3>
            <button onclick="closeUploadModal()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <form action="{{ route('triwulan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Select Periode --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Periode</label>
                <select name="period_id" required
                    class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                    @foreach ($openPeriods as $p)
                        <option value="{{ $p->id }}">Triwulan {{ $p->triwulan }} - Tahun {{ $p->tahun }}
                        </option>
                    @endforeach
                </select>
                @if ($openPeriods->isEmpty())
                    <p class="text-xs text-red-500 dark:text-red-400 mt-2 flex items-center">
                        <i class="bi bi-info-circle me-1"></i> Tidak ada periode yang sedang dibuka.
                    </p>
                @endif
            </div>

            {{-- Input File --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Laporan
                    (PDF/Docs/Excel)</label>
                <input type="file" name="file_laporan" id="fileInput" required
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maksimal ukuran file 5MB.</p>
            </div>

            {{-- Input Keterangan --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan
                    (Opsional)</label>
                <textarea name="keterangan_opd" rows="3"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white dark:placeholder-gray-400"
                    placeholder="Tambahkan catatan jika perlu..."></textarea>
            </div>

            {{-- Footer Actions --}}
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeUploadModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition-colors">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="periodModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80 flex justify-center items-center backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md p-6">

        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Form Periode Triwulan</h3>
            <button onclick="closePeriodModal()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <form action="{{ route('triwulan.period.update') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun & Triwulan</label>
                <div class="grid grid-cols-2 gap-3">
                    <select name="triwulan" id="period_triwulan"
                        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                        <option value="1">Triwulan 1</option>
                        <option value="2">Triwulan 2</option>
                        <option value="3">Triwulan 3</option>
                        <option value="4">Triwulan 4</option>
                    </select>
                    <input type="number" name="tahun" id="period_tahun" value="{{ date('Y') }}"
                        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mulai</label>
                    <input type="date" name="start_date" id="period_start" required
                        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selesai</label>
                    <input type="date" name="end_date" id="period_end" required
                        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closePeriodModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition-colors">
                    Simpan Periode
                </button>
            </div>
        </form>
    </div>
</div>

<div id="verifyModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80 flex justify-center items-center backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md p-6">

        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Verifikasi Laporan</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5 pb-3 border-b border-gray-100 dark:border-gray-700"
            id="verify-text">
            OPD: -
        </p>

        <form id="formVerify" method="POST">
            @csrf @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan
                    Verifikasi</label>
                <textarea name="catatan_admin" required rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 dark:text-white dark:placeholder-gray-400"
                    placeholder="Tulis alasan revisi atau catatan persetujuan..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('verifyModal').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Batal
                </button>
                <button type="submit" name="action" value="revisi"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 transition-colors">
                    Minta Revisi
                </button>
                <button type="submit" name="action" value="acc"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900 transition-colors">
                    Setujui Laporan
                </button>
            </div>
        </form>
    </div>
</div>
<div id="historyModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80 flex justify-center items-center backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Revisi</h3>
            <button onclick="document.getElementById('historyModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="overflow-y-auto max-h-[60vh]">
            <ol class="relative border-l border-gray-200 dark:border-gray-700" id="history-content">
                {{-- Data akan diisi via Javascript --}}
            </ol>
        </div>
    </div>
</div>
<div id="detailModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-70 dark:bg-opacity-90 flex justify-center items-center backdrop-blur-sm p-4 transition-opacity duration-300">

    {{-- Ubah max-w-2xl jadi max-w-5xl agar lebar --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-5xl h-[90vh] flex flex-col overflow-hidden transform transition-all scale-100">

        {{-- Header --}}
        <div class="bg-indigo-600 dark:bg-indigo-900 px-6 py-4 flex justify-between items-center flex-shrink-0">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="bi bi-file-earmark-text"></i> Preview Laporan
            </h3>
            <div class="flex items-center gap-3">
                {{-- Tombol Download Asli --}}
                <a href="#" id="detail-download-btn" target="_blank"
                    class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white rounded text-sm transition-colors flex items-center gap-2">
                    <i class="bi bi-download"></i> Download
                </a>
                <button onclick="closeDetailModal()" class="text-indigo-100 hover:text-white transition-colors">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
        </div>

        {{-- Body (Split 2 Kolom: Kiri Info, Kanan Preview) --}}
        <div class="flex-1 flex flex-col md:flex-row overflow-hidden">

            {{-- KOLOM KIRI: INFORMASI (Scrollable) --}}
            <div
                class="w-full md:w-1/3 bg-gray-50 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 p-6 overflow-y-auto">

                {{-- Info Utama --}}
                <div class="mb-6">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">Pengirim
                    </p>
                    <p class="text-gray-900 dark:text-white font-bold text-lg leading-tight mt-1" id="detail-name">-
                    </p>
                    <div class="mt-2">
                        <span id="detail-status"
                            class="px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-800">
                            -
                        </span>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-6">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-2">
                        Periode</p>
                    <div class="flex items-end gap-2">
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400" id="detail-tw">TW
                            -</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 mb-1" id="detail-tahun">/ ----</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="detail-date">- s/d -</div>
                </div>

                {{-- Catatan --}}
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                            <i class="bi bi-chat-quote text-indigo-500"></i> Catatan OPD
                        </p>
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-3 rounded-lg text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap"
                            id="detail-opd-note">-</div>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                            <i class="bi bi-person-check text-red-500"></i> Catatan Admin
                        </p>
                        <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap"
                            id="detail-admin-note">-</div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PREVIEW FILE (Iframe) --}}
            <div class="w-full md:w-2/3 bg-gray-200 dark:bg-gray-800 flex flex-col justify-center items-center relative"
                id="preview-container">

                {{-- Spinner Loading --}}
                <div id="preview-loading" class="absolute inset-0 flex flex-col justify-center items-center z-10">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-indigo-500 border-t-transparent">
                    </div>
                    <p class="mt-3 text-gray-600 dark:text-gray-300 text-sm font-medium">Memuat Preview...</p>
                </div>

                {{-- Pesan Error / Unsupported --}}
                <div id="preview-error"
                    class="hidden absolute inset-0 flex flex-col justify-center items-center text-center p-6">
                    <i class="bi bi-file-earmark-x text-6xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-300 font-medium">Preview tidak tersedia untuk tipe file ini.
                    </p>
                    <p class="text-sm text-gray-500 mb-4">Silakan download file untuk melihat isinya.</p>
                    <a href="#" id="error-download-btn"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Download File</a>
                </div>

                {{-- Iframe Preview --}}
                <iframe id="file-preview" class="w-full h-full border-none hidden bg-white"></iframe>
            </div>

        </div>
    </div>
</div>
<div id="fileSizeModal"
    class="fixed inset-0 z-[60] hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80 flex justify-center items-center backdrop-blur-sm">
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm p-6 text-center transform transition-all scale-100">

        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
            <i class="bi bi-exclamation-triangle-fill text-2xl text-red-600 dark:text-red-300"></i>
        </div>

        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">File Terlalu Besar!</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Ukuran file yang Anda pilih melebihi batas maksimum <strong>50MB</strong>. Silakan kompres file Anda atau
            pilih file lain.
        </p>

        <button onclick="document.getElementById('fileSizeModal').classList.add('hidden')"
            class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900 transition-colors">
            Mengerti
        </button>
    </div>
</div>
<div id="templateModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80 flex justify-center items-center backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md p-6">

        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Upload Master Template</h3>
            <button onclick="document.getElementById('templateModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <form action="{{ route('triwulan.template.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 mb-4">
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    File ini akan menjadi acuan format laporan untuk seluruh OPD. Mengupload file baru akan menggantikan
                    template lama.
                </p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Template
                    (Word/Excel/PDF)</label>
                <input type="file" name="file_template" required
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('templateModal').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors">
                    Upload Template
                </button>
            </div>
        </form>
    </div>
</div>
