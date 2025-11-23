<x-layout>
    <x-header />

    <div class="w-full mx-auto p-6 transition-colors duration-300 bg-gray-50 dark:bg-gray-900 overflow-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Daftar Bidang</h2>
            <button onclick="openCreateForm()"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                + Tambah Bidang
            </button>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <div class="relative w-full md:w-1/3">
                <!-- Icon -->
                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>

                <!-- Input -->
                <input type="text" id="search" placeholder="Cari golongan..."
                    class="w-full pl-10 pr-4 py-3 text-sm rounded-xl border border-gray-300 dark:border-gray-700 
             bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
             focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200" />
            </div>
        </div>

        <!-- Table -->
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <div id="bidang-table" class="max-w-full overflow-x-auto p-6"></div>
        </div>
    </div>

    @include('pages.bidang.modal')
</x-layout>
@include('pages.bidang.script')
