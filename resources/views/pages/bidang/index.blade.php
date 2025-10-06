<x-layout>
    <x-header />

    <main class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Daftar Bidang
            </h2>
            <button onclick="openCreateForm()"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow transition-colors duration-200">
                + Tambah Bidang
            </button>
        </div>

        <!-- Search Input -->
        <div class="mb-5">
            <input type="text" id="search" placeholder="Cari bidang..."
                class="w-full sm:w-80 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg
               bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
               focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" />
        </div>

        <!-- Table Container -->
        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
            <div class="max-w-full overflow-x-auto" id="bidang-table"></div>
        </section>
    </main>

    @include('pages.bidang.modal')
</x-layout>
@include('pages.bidang.script')
