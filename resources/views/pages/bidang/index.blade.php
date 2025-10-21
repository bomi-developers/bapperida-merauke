<x-layout>
    <x-header />


    <main class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Manajemen Bidang
            </h2>
            <button onclick="openCreateForm()"
                class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Bidang</span>
            </button>
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div id="success-alert"
                class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Input -->
        <div class="mb-5">
            <input type="text" id="searchInput" placeholder="Ketik untuk mencari bidang..."
                class="w-full sm:w-80 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200" />
        </div>

        <!-- Table Container -->
        {{-- <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Bidang</th>
                            <th scope="col" class="px-6 py-3">Keterangan</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bidangTableBody">
        @include('pages.bidang._bidang_rows', ['bidangs' => $bidangs])
        </tbody>
        </table>

        <div id="paginationLinks" class="p-4">
            {{ $bidangs->links() }}
        </div>
        </div>
        </section> --}}
        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
            <div class="max-w-full overflow-x-auto" id="bidang-table"></div>
        </section>
    </main>

    @include('pages.bidang.modal')
</x-layout>
@include('pages.bidang.script')
