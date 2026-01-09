<x-layout>
    <x-header />
    <main class="p-6 flex-1 overflow-y-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manajemen Berita</h2>
            <div class="flex gap-2">
                <button id="open-trash-btn"
                    class="px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                    <i class="bi bi-trash"></i>
                    <span>Recycle Bin</span>
                </button>
                <a href="{{ route('admin.berita.create') }}"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition-colors duration-200 flex items-center gap-2">
                    <i class="bi bi-plus-circle"></i>
                    <span>Tambah Berita</span>
                </a>
            </div>
        </div>


        <div class="flex flex-col sm:flex-row flex-wrap gap-4 w-full items-center mb-6">

            {{-- 1. Search Input --}}
            <div class="relative w-full sm:w-64 group">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-search text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="text" id="search"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl 
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 p-2.5 shadow-sm transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    placeholder="Cari berita...">
            </div>
            <div class="relative w-full sm:w-80">
                <!-- Icon kiri -->
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-calendar-range text-gray-400 dark:text-gray-500"></i>
                </div>

                <!-- Wrapper dua input dalam satu kotak -->
                <div
                    class="flex items-center bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
               focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 
               w-full pl-10 pr-10 p-2.5 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white">

                    <!-- Start Date -->
                    <input type="date" id="startDate"
                        class="w-1/2 bg-transparent outline-none border-none text-sm
                   dark:text-white" />

                    <span class="mx-2 text-gray-400">—</span>

                    <!-- End Date -->
                    <input type="date" id="endDate"
                        class="w-1/2 bg-transparent outline-none border-none text-sm
                   dark:text-white" />

                </div>

                <!-- Icon kanan -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>

            </div>
            {{-- 5. Filter author --}}
            <div class="relative w-full sm:w-56">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-person-check text-gray-400 dark:text-gray-500"></i>
                </div>
                <select id="selectAuthor"
                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="-">Semua Penulis</option>
                    @foreach ($authors as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>
            <div class="relative w-full sm:w-56">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-folder text-gray-400 dark:text-gray-500"></i>
                </div>
                <select id="selectStatus"
                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="-">Semua Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>
            <div class="relative w-full sm:w-56">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-folder text-gray-400 dark:text-gray-500"></i>
                </div>
                <select id="selectJenis"
                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="-">Semua Jenis</option>
                    <option value="berita">Berita Utama</option>
                    <option value="inovasi_riset">Bid.Inovasi - Riset</option>
                    <option value="inovasi_data">Bid.Inovasi - Data</option>
                    <option value="inovasi_kekayaan_intelektual">Bid.Inovasi - Kekayaan Intelektual</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>

        </div>
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <div id="berita-table" class="max-w-full overflow-x-auto p-6"></div>
        </div>


    </main>


    {{-- Recycle Bin Modal --}}
    <div id="trash-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" id="trash-overlay"
                style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            <i class="bi bi-trash me-2"></i> Recycle Bin (Berita)
                        </h3>
                        <div class="flex gap-2">
                            <button type="button" id="force-delete-all-btn"
                                class="text-red-600 hover:text-red-800 font-medium text-sm hidden">
                                <i class="bi bi-radioactive me-1"></i> Kosongkan Sampah
                            </button>
                            <button type="button" class="close-trash-modal text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-2 relative">
                        {{-- Loading State --}}
                        <div id="trash-loading"
                            class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 flex items-center justify-center z-10 hidden">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Berita</th>
                                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="trash-table-body">
                                    {{-- Content loaded via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="close-trash-modal mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-layout>
@include('pages.berita.script')
