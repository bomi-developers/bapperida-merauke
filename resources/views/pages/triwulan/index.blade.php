<x-layout>
    <x-header />
    <main class="p-6 overflow-auto">

        {{-- Header & Judul --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">E-Reporting Evaluasi</h2>
        </div>

        {{-- NAVIGASI TAB --}}
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg border-indigo-600 text-indigo-600 dark:text-indigo-500 dark:border-indigo-500 transition-colors duration-200"
                        id="laporan-tab" data-tabs-target="#laporan" type="button" role="tab"><i
                            class="bi bi-file-text me-2"></i> Data Laporan</button>
                </li>
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400 transition-colors duration-200"
                            id="periode-tab" data-tabs-target="#periode" type="button" role="tab"><i
                                class="bi bi-calendar-week me-2"></i> Manajemen Periode</button>
                    </li>
                @endif
            </ul>
        </div>

        <div id="myTabContent">
            {{-- TAB LAPORAN --}}
            <div class="" id="laporan" role="tabpanel">

                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-5">
                    {{-- FORM FILTER AJAX --}}
                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto items-center">

                        {{-- 1. Search Input --}}
                        <div class="relative w-full sm:w-64 group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <i
                                    class="bi bi-search text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <input type="text" id="filter-search"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full pl-10 p-2.5 shadow-sm transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500/40"
                                placeholder="Cari Nama OPD...">
                        </div>

                        {{-- 2. Status Select (Custom Arrow) --}}
                        <div class="relative w-full sm:w-48">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <i class="bi bi-funnel text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <select id="filter-status"
                                class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="MENUNGGU">Menunggu</option>
                                <option value="REVISI">Revisi</option>
                                <option value="DISETUJUI">Disetujui</option>
                            </select>
                            {{-- Custom Arrow Icon --}}
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                            </div>
                        </div>

                        {{-- 3. Periode Select (Custom Arrow) --}}
                        <div class="relative w-full sm:w-56">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <i class="bi bi-calendar-range text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <select id="filter-period"
                                class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer transition-all duration-200 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="">Semua Periode</option>
                                @foreach ($allPeriods as $p)
                                    <option value="{{ $p->id }}">TW {{ $p->triwulan }} - {{ $p->tahun }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Custom Arrow Icon --}}
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="bi bi-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Action untuk OPD --}}
                    @if (Auth::user()->role == 'opd')
                        <div class="mb-4 flex justify-end gap-3">

                            {{-- 1. Tombol Download Template (Baru) --}}
                            @if ($masterTemplate)
                                <a href="{{ route('triwulan.template') }}"
                                    class="px-5 py-2.5 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 transition-colors duration-200">
                                    <i class="bi bi-download"></i>
                                    <span>Download Format</span>
                                </a>
                            @endif

                            {{-- 2. Tombol Upload Baru --}}
                            @if ($openPeriods->count() > 0)
                                <button onclick="openUploadModal()"
                                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md flex items-center gap-2 transition-colors duration-200">
                                    <i class="bi bi-cloud-upload"></i>
                                    <span>Upload Baru</span>
                                </button>
                            @else
                                <div
                                    class="px-4 py-2 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 rounded-lg text-sm font-medium flex items-center">
                                    <i class="bi bi-lock-fill me-2"></i> Periode Tutup
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- CONTAINER UNTUK AJAX --}}
                <section
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden"
                    id="laporan-table-container">
                    @include('pages.triwulan._table_list')
                </section>
            </div>

            {{-- === TAB 2: MANAJEMEN PERIODE (Admin Only) === --}}
            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                <div class="hidden" id="periode" role="tabpanel" aria-labelledby="periode-tab">

                    <div class="flex justify-between items-center mb-4">

                        {{-- Info Template Aktif --}}
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Template Aktif:
                            @if ($masterTemplate)
                                <span class="font-semibold text-indigo-600 dark:text-indigo-400"><i
                                        class="bi bi-file-earmark-check"></i>
                                    {{ $masterTemplate->judul ?? 'File Tersedia' }}</span>
                            @else
                                <span class="text-red-500 italic">Belum ada template diupload.</span>
                            @endif
                        </div>

                        <div class="flex gap-3">
                            {{-- Tombol Upload Template (Admin) --}}
                            <button onclick="document.getElementById('templateModal').classList.remove('hidden')"
                                class="px-4 py-2 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg flex items-center gap-2 text-sm font-medium shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <i class="bi bi-file-earmark-arrow-up"></i> Upload Template
                            </button>

                            {{-- Tombol Set Periode --}}
                            <button onclick="openPeriodModal()"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 text-white rounded-lg flex items-center gap-2 text-sm font-medium shadow-md transition-colors duration-200">
                                <i class="bi bi-plus-lg"></i> Set Periode
                            </button>
                        </div>
                    </div>

                    <section
                        class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-6 py-3">Tahun</th>
                                        <th class="px-6 py-3">Evaluasi</th>
                                        <th class="px-6 py-3">Durasi (Mulai - Selesai)</th>
                                        <th class="px-6 py-3 text-center">Status (Buka/Tutup)</th>
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($periods as $period)
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">

                                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                                {{ $period->tahun }}
                                            </td>

                                            <td class="px-6 py-4">
                                                <span
                                                    class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                                    Ke-{{ $period->triwulan }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                                {{ date('d M Y', strtotime($period->start_date)) }} s/d
                                                {{ date('d M Y', strtotime($period->end_date)) }}
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                {{-- GANTI FORM LAMA DENGAN BUTTON AJAX INI --}}
                                                <button onclick="toggleStatus({{ $period->id }})"
                                                    id="btn-status-{{ $period->id }}"
                                                    class="px-3 py-1 text-xs font-bold rounded-full transition-all duration-200 border border-opacity-50
                                                    {{ $period->is_open
                                                        ? 'bg-green-100 text-green-700 border-green-300 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:border-green-700'
                                                        : 'bg-red-100 text-red-700 border-red-300 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:border-red-700' }}">
                                                    {{ $period->is_open ? 'OPEN (BISA UPLOAD)' : 'CLOSED (TERKUNCI)' }}
                                                </button>
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                <button
                                                    onclick="editPeriod({{ $period->id }}, '{{ $period->triwulan }}', '{{ $period->tahun }}', '{{ $period->start_date }}', '{{ $period->end_date }}')"
                                                    class="text-blue-600 dark:text-blue-500 hover:underline transition-colors">
                                                    <i class="bi bi-pencil-square text-lg"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="text-center py-8 text-gray-500 dark:text-gray-400">
                                                Belum ada riwayat periode.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- Pagination Periode --}}
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            {{ $periods->appends(['page' => $laporans->currentPage()])->links() }}
                        </div>
                    </section>
                </div>
            @endif

        </div>
    </main>

    @include('pages.triwulan._modals')
    @push('scripts')
        @include('pages.triwulan._scripts')
    @endpush
</x-layout>
