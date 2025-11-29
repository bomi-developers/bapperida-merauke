<x-layout>
    <x-header />

    <div class="w-full mx-auto p-6 transition-colors duration-300 bg-gray-50 dark:bg-gray-900 overflow-auto">
        <!-- Login Activity -->
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">

            <!-- Header -->
            <div
                class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Login Activity
                    </h3>
                </div>

                <!-- Search -->
                <form method="GET" action="{{ route('admin.login-logs') }}"
                    class="flex w-full md:w-auto md:flex-grow gap-2">

                    <div class="relative w-full">
                        <!-- Icon Search -->
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="bi bi-search"></i>
                        </span>

                        <!-- Input -->
                        <input type="text" name="search" value="{{ request('search') ?? '' }}"
                            placeholder="Cari user, IP, device..."
                            class="w-full pl-9 pr-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                   focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200" />
                    </div>

                    <!-- Tombol Cari -->
                    <button type="submit"
                        class="px-3 py-1 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="p-6 mb-4">
                <!-- Bungkus tabel dengan overflow-x-auto agar bisa di-scroll di HP -->
                <div
                    class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 scroll-smooth">
                    <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead
                            class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3 whitespace-nowrap">User</th>
                                <th class="px-4 py-3 whitespace-nowrap">IP Address</th>
                                <th class="px-4 py-3 whitespace-nowrap">Device</th>
                                <th class="px-4 py-3 whitespace-nowrap">Login At</th>
                                <th class="px-4 py-3 whitespace-nowrap">Logout At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $log->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $log->ip_address }}</td>
                                    <td class="px-4 py-3 truncate max-w-[250px]">{{ $log->user_agent }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $log->logged_in_at }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {!! $log->logged_out_at ??
                                            '<span class="px-2 py-1 border border-red-700 bg-red-200 rounded-full text-red-700"> Auto </span>' !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                                        Belum ada aktivitas login
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
