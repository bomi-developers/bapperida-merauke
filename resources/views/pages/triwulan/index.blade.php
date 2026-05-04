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
                        <div class="flex items-center gap-3 flex-shrink-0">
                            {{-- Upload Button --}}
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

                {{-- Template Download Section untuk OPD --}}
                @if (Auth::user()->role == 'opd' && $masterTemplates->count() > 0)
                    <div class="mb-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="bi bi-file-earmark-spreadsheet text-indigo-500"></i>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Download Template Format</h4>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            @php $slotNames = [1 => 'Indikator', 2 => 'Realisasi', 3 => 'OPD', 4 => 'Distrik']; @endphp
                            @for ($slot = 1; $slot <= 4; $slot++)
                                @php $tmpl = $masterTemplates->get($slot); @endphp
                                @if ($tmpl)
                                    <a href="{{ route('triwulan.template', $slot) }}"
                                        class="group flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-300 dark:hover:border-indigo-700 transition-all duration-200">
                                        <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="bi bi-file-earmark-excel text-white text-sm"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $tmpl->judul }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $slotNames[$slot] }}</p>
                                        </div>
                                        <i class="bi bi-download text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
                                    </a>
                                @endif
                            @endfor
                        </div>
                    </div>
                @endif

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

                    {{-- === SECTION: Template Aktif (3 Kartu) === --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <i class="bi bi-file-earmark-spreadsheet text-indigo-500"></i>
                                Template Aktif
                            </h3>
                            <button onclick="openPeriodModal()"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 text-white rounded-lg flex items-center gap-2 text-sm font-medium shadow-md transition-colors duration-200">
                                <i class="bi bi-plus-lg"></i> Set Periode
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @php $slotNames = [1 => 'Indikator', 2 => 'Realisasi', 3 => 'OPD', 4 => 'Distrik']; @endphp
                            @for ($slot = 1; $slot <= 4; $slot++)
                                @php $tmpl = $masterTemplates->get($slot); @endphp
                                <div class="relative rounded-xl border transition-all duration-300 flex flex-col
                                    {{ $tmpl
                                        ? 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700'
                                        : 'border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800/50 hover:border-gray-400 dark:hover:border-gray-500' }}">

                                    {{-- Card Header --}}
                                    <div class="flex items-center justify-between px-4 pt-4 pb-2">
                                        <span class="text-xs font-bold uppercase tracking-wider
                                            {{ $tmpl ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500' }}">
                                            {{ $slotNames[$slot] }}
                                        </span>
                                        @if ($tmpl)
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Aktif"></span>
                                        @endif
                                    </div>

                                    {{-- Card Body --}}
                                    <div class="px-4 pb-4 flex-1 flex flex-col">
                                        @if ($tmpl)
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <i class="bi bi-file-earmark-excel text-white text-lg"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate" title="{{ $tmpl->judul }}">
                                                        {{ $tmpl->judul }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $tmpl->created_at->format('d M Y, H:i') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex gap-2 mt-auto">
                                                <a href="{{ route('triwulan.template', $slot) }}"
                                                    class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg text-center transition-colors inline-flex items-center justify-center gap-1.5">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                                <button onclick="openTemplateModal({{ $slot }})"
                                                    class="px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors inline-flex items-center gap-1.5">
                                                    <i class="bi bi-arrow-repeat"></i> Ganti
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-center py-6 flex-1 flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                                    <i class="bi bi-cloud-upload text-xl text-gray-400 dark:text-gray-500"></i>
                                                </div>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">Belum diupload</p>
                                                <button onclick="openTemplateModal({{ $slot }})"
                                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm inline-flex items-center gap-1.5">
                                                    <i class="bi bi-upload"></i> Upload
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endfor
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
