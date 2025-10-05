<!-- Modal Pegawai -->
<div id="formModal" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center z-50 backdrop-blur">
    <div class="bg-white dark:bg-boxdark p-6 rounded shadow w-11/12 md:w-1/2 relative">
        <h3 id="modalTitle" class="text-lg font-bold mb-4 dark:text-white">Tambah Pegawai</h3>
        <form id="formPegawai">
            @csrf
            <input type="hidden" id="pegawai_id" name="id">

            <!-- Nama -->
            <div class="mb-3">
                <label for="nama" class="text-sm dark:text-white">Nama Lengkap</label>
                <input type="text" id="nama" name="nama"
                    class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>

            <!-- NIP -->
            <div class="mb-3">
                <label for="nip" class="text-sm dark:text-white">NIP</label>
                <input type="text" id="nip" name="nip"
                    class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>

            <!-- NIK -->
            <div class="mb-3">
                <label for="nik" class="text-sm dark:text-white">NIK</label>
                <input type="text" id="nik" name="nik"
                    class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none">
            </div>

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

            <!-- Alamat -->
            <div class="mb-3">
                <label for="alamat" class="text-sm dark:text-white">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2"
                    class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none"></textarea>
            </div>



            <!-- Tombol -->
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeForm()" class="px-3 py-1 bg-danger text-white rounded">
                    Batal
                </button>
                <button type="submit" class="px-3 py-1 bg-primary text-white rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
