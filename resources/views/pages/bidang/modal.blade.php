<!-- Modal -->
<div id="formModal"
    class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
    <div
        class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
        <!-- Header -->
        <h3 id="modalTitle" class="text-xl font-semibold mb-5 text-gray-900 dark:text-white">
            Tambah Bidang
        </h3>

        <!-- Form -->
        <form id="formBidang" class="space-y-4">
            @csrf
            <input type="hidden" id="bidang_id" name="id">

            <!-- Nama Bidang -->
            <div>
                <label for="nama_bidang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Bidang
                </label>
                <input type="text" id="nama_bidang" name="nama_bidang"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg
                 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" />
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Deskripsi
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg
                 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 resize-none"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="closeForm()"
                    class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600
                 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
