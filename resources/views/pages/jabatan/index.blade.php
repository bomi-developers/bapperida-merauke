<x-layout>
    <x-header />

    <div class="w-full mx-auto p-6 transition-colors duration-300 bg-gray-50 dark:bg-gray-900 overflow-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Daftar Jabatan</h2>
            <button onclick="openCreateForm()"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                + Tambah Jabatan
            </button>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <input type="text" id="search" placeholder="Cari bidang..."
                class="w-full md:w-1/3 px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
                       focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200" />
        </div>

        <!-- Table -->
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <div id="jabatan-table" class="max-w-full overflow-x-auto p-6"></div>
        </div>
    </div>

    @include('pages.jabatan.modal')
</x-layout>

@include('pages.jabatan.script')
