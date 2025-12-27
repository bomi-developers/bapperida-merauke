<x-modal id="formModal" title="Tambah Pegawai">
    <div class="max-h-[calc(100vh-200px)] overflow-y-auto p-4 custom-scrollbar">
        <form id="formPegawai" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="pegawai_id" name="id">

            <!-- Preview Foto -->
            <div id="preview-container" class="hidden mb-3 text-center">
                <img id="preview-image" src="" alt="Preview Foto" class="mx-auto w-32 h-32 object-cover rounded-full border border-gray-300">
                <p class="text-xs text-gray-500 mt-1">Foto saat ini/terpilih</p>
            </div>
            
            <x-input label="Foto Pegawai" id="foto" name="foto" type="file" onchange="previewFile()" />

            <x-input label="Nama Lengkap" id="nama" name="nama" type="text" />

            <x-input label="NIP" id="nip" name="nip" type="text" />

            <x-input label="NIK" id="nik" name="nik" type="text" />
            <!-- Jabatan -->
            <div class="mb-3">
                <label for="id_jabatan" class="text-sm dark:text-white">Jabatan</label>
                <select id="id_jabatan" name="id_jabatan"
                    class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-primary outline-none">
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
                 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-primary outline-none">
                    <option value="">Pilih Golongan</option>
                    @foreach ($golongan as $item)
                        <option value="{{ $item->id }}">{{ $item->golongan }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Bidang -->
            <div class="mb-3">
                <label for="id_bidang" class="text-sm dark:text-white">Bidang</label>
                <select id="id_bidang" name="id_bidang"
                    class="w-full px-3 py-2 border rounded dark:border-strokedark 
                 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-primary outline-none">
                    <option value="">Pilih Bidang</option>
                    @foreach ($bidang as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_bidang }}</option>
                    @endforeach
                </select>
            </div>

            <x-textarea label="Alamat" name="alamat" rows="4" placeholder="Alamat Rumah"></x-textarea>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">

                <button type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-modal>
<!-- resources/views/components/modal.blade.php -->
<div id="akunModal"
    class="fixed inset-0 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
    <div
        class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">

        <!-- Header -->
        <h3 class="text-xl font-semibold mb-5 text-gray-900 dark:text-white" id="modalTitle">
            Akun Pegawai
        </h3>
        <button onclick="closeFormAkun()"
            class="absolute top-4 right-4 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
        <!-- Content -->
        <form id="userForm">
            <div class="modal-content">
                <div id="statusAkun" class="mb-2 w-full"></div>
                <div class="mb-2 w-full">
                    <div class="text-gray-500 dark:text-gray-300 dark:bg-gray-800 w-full bg-gray-100 rounded-lg p-3"
                        id="PasswordDefault">
                        Password default adalah <strong class="text-black dark:text-white">PegawaiBAPPERIDA</strong>
                    </div>
                </div>

                <input type="hidden" id="id_pegawai" name="id_pegawai">
                <x-input label="Username" id="username" name="username" type="text" />
                <x-input label="Email address" id="email" name="email" type="emaiil" />
                <!-- role -->
                <div class="mb-3">
                    <label for="role" class="text-sm dark:text-white">Akses Akun</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="bi bi-person-fill-lock text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <select id="role" name="role"
                            class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih Akses</option>
                            <option value="admin">Administrator Bidang</option>
                            <option value="pegawai">Publisher Berita & Galeri</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 ">
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
