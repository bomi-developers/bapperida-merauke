<x-layout>
    <x-header />

    <div class="w-full mx-auto p-6 transition-colors duration-300  overflow-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Data Pegawai</h2>
            <button onclick="openCreateForm()"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                + Tambah Pegawai
            </button>
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
                    placeholder="Cari pegawai...">
            </div>

            {{-- 2. Filter Golongan --}}
            <div class="relative w-full sm:w-48">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-grid text-gray-400 dark:text-gray-500"></i>
                </div>
                <select id="selectGolongan"
                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="-">Semua Golongan</option>
                    @foreach ($golongan as $item)
                        <option value="{{ $item->id }}">{{ $item->golongan }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>

            {{-- 3. Filter Jabatan --}}
            <div class="relative w-full sm:w-56">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-briefcase text-gray-400 dark:text-gray-500"></i>
                </div>
                <select id="selectJabatan"
                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="-">Semua Jabatan</option>
                    @foreach ($jabatan as $item)
                        <option value="{{ $item->id }}">{{ $item->jabatan }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>

            {{-- 4. Filter Bidang --}}
            <div class="relative w-full sm:w-56">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-layers text-gray-400 dark:text-gray-500"></i>
                </div>
                <select id="selectBidang"
                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="-">Semua Bidang</option>
                    @foreach ($bidang as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_bidang }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>

            {{-- 5. Filter Akun --}}
            <div class="relative w-full sm:w-56">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-person-check text-gray-400 dark:text-gray-500"></i>
                </div>
                <select id="selectAkun"
                    class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl
                   focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 
                   block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="-">Semua Akun</option>
                    <option value="1">Sudah ada akun</option>
                    <option value="0">Belum ada akun</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>

        </div>
        <!-- Table -->
        <div
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
            <div id="pegawai-table" class="max-w-full overflow-x-auto p-6"></div>
        </div>
    </div>

    @include('pages.pegawai.modal')
</x-layout>

@include('pages.pegawai.script')
