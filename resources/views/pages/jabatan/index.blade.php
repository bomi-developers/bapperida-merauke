<x-layout>
    <x-header />

    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-black dark:text-white">Daftar Jabatan</h2>
            <button onclick="openCreateForm()" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/80">
                + Tambah Jabatan
            </button>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <input type="text" id="search" placeholder="Cari bidang..."
                class="px-3 py-3 text-sm rounded border border-gray-300 dark:border-strokedark 
                       bg-white dark:bg-boxdark text-black dark:text-white 
                       focus:ring-2 focus:ring-primary outline-none transition-colors duration-200">
        </div>

        <!-- Table -->
        <div
            class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default 
                   dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
            <div class="max-w-full overflow-x-auto" id="jabatan-table"></div>
        </div>
    </div>
    @include('pages.jabatan.modal')
    </div>
</x-layout>
@include('pages.jabatan.script')
