<x-layout>
    <x-header></x-header>

    <div class="w-full mx-auto p-6">
        <!-- Login Activity -->
        <div
            class="rounded-sm border border-stroke bg-white shadow-default 
                    dark:border-strokedark dark:bg-boxdark">

            <!-- Header -->
            <div class="border-b border-stroke px-6 py-4 dark:border-strokedark">
                <h3 class="text-lg font-semibold text-black dark:text-white">
                    Activity Tracker
                </h3>
            </div>

            <!-- Table -->
            <div class="p-6 overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-2 text-left dark:bg-meta-4">
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">User</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">Event</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">Model</th>
                            <th class="px-4 py-3 text-sm font-medium text-black dark:text-white">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr
                                class="border-b border-stroke dark:border-strokedark hover:bg-gray-50 dark:hover:bg-meta-3 transition">
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->causer?->name ?? 'System' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->description }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white truncate max-w-[250px]">
                                    {{ $log->log_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black dark:text-white">
                                    {{ $log->created_at }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-sm text-black dark:text-white">
                                    Belum ada aktivitas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
