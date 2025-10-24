<x-modal id="formModal" title="Tambah Jabatan">
    <form id="formPegawai" class="space-y-4">
        @csrf
        <input type="hidden" id="pegawai_id" name="id">

        <x-input label="Nama Lengkap" id="nama" name="nama" type="text" />

        <x-input label="NIP" id="nip" name="nip" type="text" />

        <x-input label="NIK" id="nik" name="nik" type="text" />

        <!-- Jabatan -->
        <div class="mb-3">
            <label for="id_jabatan" class="text-sm dark:text-white">Jabatan</label>
            <select id="id_jabatan" name="id_jabatan"
                class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none">
                <option value="">Pilih Jabatan</option>
                @foreach ($jabatan as $item)
                    <option value="{{ $item->id }}">{{ $item->jabatan }}</option>
                @endforeach
            </select>
        </div>

        <!-- Golongan -->
        <div class="mb-3">
            <label for="id_golongan" class="text-sm dark:text-white">Golongan</label>
            <select id="id_golongan" name="id_golongan"
                class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none">
                <option value="">Pilih Golongan</option>
                @foreach ($golongan as $item)
                    <option value="{{ $item->id }}">{{ $item->golongan }}</option>
                @endforeach
            </select>
        </div>

        <x-textarea label="Alamat" name="alamat" rows="4">Alamat Rumah...</x-textarea>

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
