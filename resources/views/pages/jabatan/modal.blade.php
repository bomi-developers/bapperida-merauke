<x-modal id="formModal" title="Tambah Jabatan">
    <form id="formJabatan" class="space-y-4">
        @csrf
        <input type="hidden" id="jabatan_id" name="id">
        <x-input label="Nama Jabatan" id="jabatan" name="jabatan" type="text" />
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
</x-modal>
