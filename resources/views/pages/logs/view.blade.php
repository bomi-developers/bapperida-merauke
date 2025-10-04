<x-layout>
    <x-header></x-header>

    <div class="w-full mx-auto p-6">
        <!-- Cards Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">

            <!-- Guest Card -->
            <div class="rounded-lg border border-stroke bg-white shadow-md dark:border-strokedark dark:bg-boxdark">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-black dark:text-white mb-4">PENGUNJUNG</h4>

                    <div class="flex justify-between items-center border-t border-stroke dark:border-strokedark py-3">
                        <span class="text-sm font-medium text-black dark:text-white">Hari ini</span>
                        <span class="text-lg font-bold text-primary">{{ $todayGuest }}</span>
                    </div>

                    <div class="flex justify-between items-center border-t border-stroke dark:border-strokedark py-3">
                        <span class="text-sm font-medium text-black dark:text-white">Total</span>
                        <span class="text-lg font-bold text-primary">{{ $totalGuest }}</span>
                    </div>
                </div>
            </div>

            <!-- User Card -->
            <div
                class="rounded-lg border border-stroke bg-white shadow-md 
            dark:border-strokedark dark:bg-boxdark transition-colors duration-300">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">USER</h4>

                    <div class="flex justify-between items-center border-t border-stroke dark:border-strokedark py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Hari ini</span>
                        <span class="text-lg font-bold text-primary">{{ $todayUser }}</span>
                    </div>

                    <div class="flex justify-between items-center border-t border-stroke dark:border-strokedark py-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total</span>
                        <span class="text-lg font-bold text-primary">{{ $totalUser }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- View Tracker -->
        <div
            class="rounded-sm border border-stroke bg-white shadow-default 
                    dark:border-strokedark dark:bg-boxdark">

            <!-- Header -->
            <div
                class="border-b border-stroke px-6 py-4 dark:border-strokedark flex flex-col md:flex-row md:justify-between md:items-center gap-3">
                <h3 class="text-lg font-semibold text-black dark:text-white">
                    View Tracker
                </h3>

                <div class="flex flex-col md:flex-row md:items-center md:gap-3 w-full ">
                    <!-- Search -->
                    <form method="GET" action="{{ route('admin.view-logs') }}"
                        class="flex w-full md:w-auto md:flex-grow gap-2 mb-3">
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Cari URL, IP, User..."
                            class="w-full md:w-auto flex-grow px-3 py-1 text-sm rounded border border-gray-300 dark:border-strokedark 
                       bg-white dark:bg-boxdark text-black dark:text-white 
                       focus:ring-2 focus:ring-primary outline-none transition-colors duration-200" />

                        <button type="submit"
                            class="px-3 py-1 text-sm rounded bg-primary text-white hover:bg-primary/80 transition">
                            Cari
                        </button>
                    </form>

                    <!-- Filter -->
                    <div class="flex gap-2 ml-auto">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'user']) }}"
                            class="px-3 py-1 text-sm rounded font-medium 
               {{ request('filter') === 'user' ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-black dark:text-white' }}">
                            User
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'guest']) }}"
                            class="px-3 py-1 text-sm rounded font-medium 
               {{ request('filter') === 'guest' ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-black dark:text-white' }}">
                            Guest
                        </a>

                        <a href="{{ route('admin.view-logs') }}"
                            class="px-3 py-1 text-sm rounded font-medium 
               {{ !request('filter') ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-black dark:text-white' }}">
                            All
                        </a>
                    </div>
                </div>
            </div>
            <!-- Table -->
            <div class="p-6 overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-2 text-left dark:bg-meta-4">
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">URL</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">IP</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">User</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">User Agent</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">Viewed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr
                                class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-meta-3 transition">
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->url }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white truncate max-w-[250px]">
                                    {{ $log->user?->name ?? 'Guest' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ \Illuminate\Support\Str::limit($log->user_agent, 50) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->viewed_at ? $log->viewed_at : '' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-sm text-black dark:text-white">
                                    Belum ada View
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>
