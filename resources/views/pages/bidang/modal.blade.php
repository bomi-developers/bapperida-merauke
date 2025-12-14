<x-modal id="formModal" title="Tambah Bidang">
    <form id="formBidang" class="space-y-4">
        @csrf
        <input type="hidden" id="bidang_id" name="id">

        <x-input label="Nama Bidang" id="nama_bidang" name="nama_bidang" type="text" />

        <x-textarea label="Deskripsi" name="deskripsi" rows="4">Tulis sesuatu...</x-textarea>
        <!-- Jabatan -->
        <div class="mb-3">
            <label for="tampilkan" class="text-sm dark:text-white">Tampilkan di home</label>
            <select id="tampilkan" name="tampilkan"
                class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-primary outline-none">
                <option value="1">Tampilkan</option>
                <option value="0">Sembunyikan</option>
            </select>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">

            <button type="submit"
                class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                Simpan
            </button>
        </div>
    </form>
</x-modal>
