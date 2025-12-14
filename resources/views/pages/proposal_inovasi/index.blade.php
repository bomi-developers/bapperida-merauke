<x-layout>
    <x-header />
    <div class="w-full mx-auto p-6 transition-colors duration-300 bg-gray-50 dark:bg-gray-900 overflow-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Daftar Proposal Inovasi</h2>
            </div>
            <div class="flex gap-2">
                <button onclick="exportData()"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="search" placeholder="Cari nama, email, judul..."
                    class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 
                    bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
                    focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200" />
            </div>

            <!-- Status Filter -->
            <div>
                <select id="status-filter"
                    class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 
                    bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
                    focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <input type="date" id="date-filter"
                    class="w-full px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 
                    bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
                    focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200" />
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Proposal</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="total-count">0</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <i class="bi bi-file-earmark-text text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="pending-count">0</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <i class="bi bi-clock-history text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Disetujui</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="approved-count">0</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400" id="rejected-count">0</p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                        <i class="bi bi-x-circle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <div id="proposal-table" class="max-w-full overflow-x-auto p-6"></div>
        </div>
    </div>

    @include('pages.proposal_inovasi.modal')
</x-layout>

@include('pages.proposal_inovasi.script')
