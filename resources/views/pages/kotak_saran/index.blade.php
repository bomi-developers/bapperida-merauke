<x-layout>
    <x-header />

    <div class="w-full mx-auto p-6 transition-colors duration-300 bg-gray-50 dark:bg-gray-900 overflow-auto">
        <!-- Activity Tracker -->
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">

            <!-- Header -->
            <div
                class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Kotak Saran
                </h3>

                <!-- Search -->
                <form method="GET" action="{{ route('admin.kotak-saran.index') }}"
                    class="flex w-full md:w-auto md:flex-grow gap-2">

                    <div class="relative w-full">
                        <!-- Icon Search -->
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="bi bi-search"></i>
                        </span>

                        <!-- Input -->
                        <input type="text" name="search" value="{{ request('search') ?? '' }}"
                            placeholder="Cari Pengirim, isi Saran atau ip address.."
                            class="w-full pl-9 pr-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 
                            bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                            focus:ring-2 focus:ring-indigo-500 outline-none transition-colors duration-200" />
                    </div>

                    <button type="submit"
                        class="px-3 py-1 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="p-6 mb-4">
                <!-- Bungkus tabel dalam div overflow -->
                <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-3 ">No</th>
                                <th class="px-4 py-3 whitespace-nowrap">Pengirim</th>
                                <th class="px-4 py-3 whitespace-nowrap">Isi</th>
                                <th class="px-4 py-3 whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kotakSaran as $item)
                                <tr
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                    <td class="px-4 py-3 ">{{ $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-lg mb-1">{{ $item->pengirim }}</div>
                                        <span
                                            class="px-2 py-1 text-xs rounded-xl bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 border border-green-700 dark:border-green-400">
                                            {{ $item->ip_address . ' | ' . $item->created_at->format('d F Y') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap italic">" {{ $item->isi }} " </td>
                                    <td class="px-4 py-3 truncate max-w-[250px]"></td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                                        Belum ada Saran
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $kotakSaran->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>
