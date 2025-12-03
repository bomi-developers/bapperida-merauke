<x-modal id="formModal" title="Tambah Pegawai">
    <form id="formPegawai" class="space-y-4">
        @csrf
        <input type="hidden" id="pegawai_id" name="id">

        <x-input label="Nama Lengkap" id="name" name="name" type="text" />

        <x-input label="NIP" id="nip" name="nip" type="text" />
        <x-input label="NO HP" id="no_hp" name="no_hp" type="text" />
        <x-input label="Email" id="email_opd" name="email" type="email" />
        <x-input label="Asal Instansi" id="instansi" name="instansi" type="text" />

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">

            <button type="submit"
                class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                Simpan
            </button>
        </div>
    </form>
</x-modal>
<!-- resources/views/components/modal.blade.php -->
<div id="akunModal"
    class="fixed inset-0 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
    <div
        class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">

        <!-- Header -->
        <h3 class="text-xl font-semibold mb-5 text-gray-900 dark:text-white" id="modalTitle">
            Akun Pegawai Perangkat Daerah
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
                        Password default adalah <strong
                            class="text-black dark:text-white">PerangkatDaerahMerauke</strong>
                    </div>
                </div>

                <input type="hidden" id="id_pegawai" name="id_pegawai">
                <input type="hidden" id="role" name="role" value="opd">
                <x-input label="Username" id="username" name="username" type="text" />
                <x-input label="Email address" id="email" name="email" type="emaiil" />
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
