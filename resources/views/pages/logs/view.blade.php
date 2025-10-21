<x-layout>
    <x-header />

    <div class="w-full mx-auto p-6 transition-colors duration-300 bg-gray-50 dark:bg-gray-900  overflow-auto">
        <!-- Header: Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Pengunjung -->
            <div
                class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-md transition-colors duration-300">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">PENGUNJUNG</h4>
                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Hari ini</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $todayGuest }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $totalGuest }}</span>
                    </div>
                </div>
            </div>

            <!-- User -->
            <div
                class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-md transition-colors duration-300">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">USER</h4>
                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Hari ini</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $todayUser }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total</span>
                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $totalUser }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Tracker -->
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <!-- Header -->
            <div
                class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">View Tracker</h3>
                    <!-- Toggle Dark Mode -->
                    <button id="theme-toggle"
                        class="p-2 rounded-md bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-gray-700 dark:text-gray-100"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293a8 8 0 01-11.586-11.586A8.001 8.001 0 0017.293 13.293z" />
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-gray-700 dark:text-gray-100"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 15a1 1 0 011 1v2a1 1 0 11-2 0v-2a1 1 0 011-1zm5-5a1 1 0 110-2h2a1 1 0 110 2h-2zm-5 5a1 1 0 100-2 1 1 0 000 2zm-5-5a1 1 0 110-2H3a1 1 0 110 2h2zm9.071 4.071a1 1 0 011.415 1.415l-1.415-1.415zM6.343 6.343a1 1 0 010-1.415L4.929 4.929a1 1 0 011.414 1.414zm7.071-1.415a1 1 0 010 1.415l1.415-1.415a1 1 0 01-1.415 0zM4.929 15.071a1 1 0 011.414 0L4.929 15.071z" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:gap-3 w-full">
                    <!-- Search -->
                    <form method="GET" action="{{ route('admin.view-logs') }}"
                        class="flex w-full md:w-auto md:flex-grow gap-2">
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Cari URL, IP, User..."
                            class="w-full px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 
                            bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                            focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200" />
                        <button type="submit"
                            class="px-3 py-1 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700 transition">Cari</button>
                    </form>

                    <!-- Filter -->
                    <div class="flex gap-2 ml-auto mt-2 md:mt-0">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'user']) }}"
                            class="px-3 py-1 text-sm rounded font-medium {{ request('filter') === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">User</a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'guest']) }}"
                            class="px-3 py-1 text-sm rounded font-medium {{ request('filter') === 'guest' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">Guest</a>
                        <a href="{{ route('admin.view-logs') }}"
                            class="px-3 py-1 text-sm rounded font-medium {{ !request('filter') ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">All</a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="p-6 mb-4">
                <div
                    class="overflow-x-auto max-h-[80vh] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead
                            class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3">URL</th>
                                <th class="px-4 py-3">IP</th>
                                <th class="px-4 py-3">User</th>
                                <th class="px-4 py-3">User Agent</th>
                                <th class="px-4 py-3">Viewed At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                    <td class="px-4 py-3">{{ $log->url }}</td>
                                    <td class="px-4 py-3">{{ $log->ip_address }}</td>
                                    <td class="px-4 py-3">{{ $log->user?->name ?? 'Guest' }}</td>
                                    <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($log->user_agent, 50) }}
                                    </td>
                                    <td class="px-4 py-3">{{ $log->viewed_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                                        Belum ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

</x-layout>
