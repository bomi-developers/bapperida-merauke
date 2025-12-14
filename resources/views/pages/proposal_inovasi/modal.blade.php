{{-- Modal Detail Proposal --}}
<div id="detailModal" class="hidden fixed inset-0 bg-black/20 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <!-- Header -->
        <div class=" px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-indigo-700 dark:text-white flex items-center gap-2">
                <i class="bi bi-file-earmark-text"></i>
                <span id="detail-title">Detail Proposal</span>
            </h3>
            <button onclick="closeDetailModal()"
                class="text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]" id="detail-content">
            <!-- Content will be loaded dynamically -->
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center ">
            <div class="flex gap-2">
                <button onclick="downloadFile()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="bi bi-download"></i> Download File
                </button>
                <button onclick="openVideoModal()" id="btn-video"
                    class="hidden px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center gap-2">
                    <i class="bi bi-play-circle"></i> Lihat Video
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Modal Video --}}
<div id="videoModal"
    class="hidden fixed inset-0 bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="bi bi-camera-video"></i>
                <span>Video Proposal</span>
            </h3>
            <button onclick="closeVideoModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Video Content -->
        <div class="p-6 bg-black">
            <div id="video-container" class="aspect-video w-full bg-gray-900 rounded-lg overflow-hidden">
                <!-- Video iframe will be loaded here -->
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end">
            <button onclick="closeVideoModal()"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Modal Update Status --}}
<div id="statusModal"
    class="hidden fixed inset-0 bg-black/20 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md">
        <!-- Header -->
        <div class="px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-indigo-700 dark:text-white flex items-center gap-2">
                <i class="bi bi-check-circle"></i>
                <span id="status-modal-title">Update Status</span>
            </h3>
            <button onclick="closeStatusModal()"
                class="text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Content -->
        <form id="formStatus" class="p-6 space-y-4">
            <input type="hidden" id="proposal_id">

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status" required
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                    focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    <option value="pending">Pending</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Catatan / Alasan
                </label>
                <textarea id="catatan" name="catatan" rows="4" placeholder="Tambahkan catatan atau alasan perubahan status..."
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                    bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                    focus:ring-2 focus:ring-indigo-500 outline-none resize-none transition"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">

                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium flex items-center gap-2">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Confirm Delete --}}
<div id="confirmModal"
    class="hidden fixed inset-0 bg-black/20 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all scale-95">
        <div class="p-6">
            <div class="text-center">
                <div
                    class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 dark:text-red-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Hapus Proposal?</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Data yang dihapus tidak dapat dikembalikan. File proposal juga akan dihapus permanen. Apakah Anda
                    yakin ingin melanjutkan?
                </p>
                <div class="flex gap-3 justify-center">
                    <button id="cancelDelete" onclick="closeConfirmModal()"
                        class="px-6 py-2.5 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-medium">
                        Batal
                    </button>
                    <button id="confirmDelete"
                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center gap-2">
                        <i class="bi bi-trash"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
