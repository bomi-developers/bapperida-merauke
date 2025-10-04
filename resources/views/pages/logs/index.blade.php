<x-layout>
    <x-header></x-header>

    <div class="max-w-6xl mx-auto p-6">
        <!-- Login Activity -->
        <div
            class="rounded-sm border border-stroke bg-white shadow-default 
                    dark:border-strokedark dark:bg-boxdark">

            <!-- Header -->
            <div class="border-b border-stroke px-6 py-4 dark:border-strokedark">
                <h3 class="text-lg font-semibold text-black dark:text-white">
                    Login Activity
                </h3>
            </div>

            <!-- Table -->
            <div class="p-6 overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-2 text-left dark:bg-meta-4">
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">User</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">IP Address</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">Device</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">Login At</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">Logout At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr
                                class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-meta-3 transition">
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->user->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white truncate max-w-[250px]">
                                    {{ $log->user_agent }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->logged_in_at }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->logged_out_at ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-sm text-black dark:text-white">
                                    Belum ada aktivitas login
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
