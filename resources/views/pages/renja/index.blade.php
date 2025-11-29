<x-layout>
    <x-header />
    <main class="p-6 overflow-auto">

        {{-- Header Page --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    @if (Auth::user()->role == 'opd')
                        Renja
                    @else
                        RKPD
                    @endif
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manajemen Dokumen Perencanaan Daerah</p>
            </div>
            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
                <button onclick="openTahapanModal()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md flex items-center gap-2 transition-colors"><i
                        class="bi bi-gear-fill"></i> Atur Tahapan & Template</button>
            @endif
        </div>

        {{-- BANNER TAHAPAN AKTIF --}}
        <div class="mb-8 p-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg relative overflow-hidden"
            id="active-stage-banner">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex-1">
                    @if ($tahapanAktif)
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="bg-white/20 text-white text-xs font-bold px-2 py-1 rounded uppercase tracking-wider border border-white/30 backdrop-blur-sm animate-pulse">Status:
                                Buka</span>
                            @if ($tahapanAktif->end_date)
                                <span
                                    class="bg-red-500/80 text-white text-xs font-bold px-2 py-1 rounded border border-red-400/50 backdrop-blur-sm flex items-center gap-1"><i
                                        class="bi bi-clock-history"></i> Deadline:
                                    {{ \Carbon\Carbon::parse($tahapanAktif->end_date)->format('d M Y') }}</span>
                            @endif
                        </div>
                        <h3 class="text-3xl font-bold mt-1">{{ $tahapanAktif->nama_tahapan }} {{ $tahapanAktif->tahun }}
                        </h3>
                        <p class="text-blue-100 text-sm mt-1 opacity-90">Periode Upload:
                            {{ \Carbon\Carbon::parse($tahapanAktif->start_date)->format('d M') }} s/d
                            {{ \Carbon\Carbon::parse($tahapanAktif->end_date)->format('d M Y') }}</p>
                    @else
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-white/20 rounded-full backdrop-blur-sm"><i
                                    class="bi bi-calendar-x text-2xl"></i></div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-100">Belum Ada Tahapan Dibuka</h3>
                                <p class="text-gray-300 text-sm">Admin Bapperida belum mengaktifkan periode upload
                                    Renja/RKPD.</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    @if ($tahapanAktif && $tahapanAktif->file_template_rkpd)
                        <a href="{{ asset('storage/' . $tahapanAktif->file_template_rkpd) }}" target="_blank"
                            class="px-5 py-3 bg-white/10 border border-white/30 text-white font-bold rounded-xl shadow-sm hover:bg-white/20 flex items-center justify-center gap-2 transition-all backdrop-blur-sm"><i
                                class="bi bi-cloud-download-fill"></i> Download Template</a>
                    @endif
                    @if (Auth::user()->role == 'opd')
                        @if ($tahapanAktif)
                            @if (isset($myRenja) && $myRenja)
                                <button
                                    onclick="openUploadModal({{ $tahapanAktif->id }}, '{{ $myRenja->status_dokumen }}', '{{ $myRenja->status_matriks }}')"
                                    class="px-6 py-3 bg-white text-orange-600 font-bold rounded-xl shadow-lg hover:bg-orange-50 flex items-center justify-center gap-2 transition-all transform hover:scale-105"><i
                                        class="bi bi-pencil-square text-xl"></i> Update / Revisi Renja</button>
                            @else
                                <button onclick="openUploadModal({{ $tahapanAktif->id }}, 'MENUNGGU', 'MENUNGGU')"
                                    class="px-6 py-3 bg-white text-indigo-700 font-bold rounded-xl shadow-lg hover:bg-gray-50 flex items-center justify-center gap-2 transition-all transform hover:scale-105"><i
                                        class="bi bi-cloud-upload-fill text-xl"></i> Upload Renja Baru</button>
                            @endif
                        @else
                            <button disabled
                                class="px-6 py-3 bg-gray-400/50 text-gray-200 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2 border border-white/10"><i
                                    class="bi bi-lock-fill"></i> Upload Terkunci</button>
                        @endif
                    @endif
                </div>
            </div>
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
        </div>

        {{-- ADMIN ONLY: Tabel Manajemen --}}
        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'super_admin')
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white">Riwayat & Manajemen Tahapan</h3>
                </div>
                <div
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Tahun</th>
                                    <th class="px-6 py-3">Tahapan</th>
                                    <th class="px-6 py-3">Jadwal</th>
                                    <th class="px-6 py-3">Template</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allTahapan as $t)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-bold">{{ $t->tahun }}</td>
                                        <td class="px-6 py-4"><span
                                                class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded border border-indigo-200">{{ $t->nama_tahapan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($t->start_date)->format('d M') }} s/d
                                            {{ \Carbon\Carbon::parse($t->end_date)->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            @if ($t->file_template_rkpd)
                                                <a href="{{ asset('storage/' . $t->file_template_rkpd) }}"
                                                    target="_blank" class="text-blue-600 hover:underline text-xs"><i
                                                        class="bi bi-file-earmark-check"></i> Ada</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center"><button
                                                onclick="toggleTahapan({{ $t->id }})"
                                                id="btn-tahapan-{{ $t->id }}"
                                                class="px-3 py-1 text-xs font-bold rounded-full border {{ $t->is_active ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300' }}">{{ $t->is_active ? 'DIBUKA' : 'DITUTUP' }}</button>
                                        </td>
                                        <td class="px-6 py-4 text-center"><button onclick="editTahapan(this)"
                                                data-id="{{ $t->id }}" data-tahun="{{ $t->tahun }}"
                                                data-nama="{{ $t->nama_tahapan }}" data-start="{{ $t->start_date }}"
                                                data-end="{{ $t->end_date }}"
                                                data-file="{{ $t->file_template_rkpd ? asset('storage/' . $t->file_template_rkpd) : '' }}"
                                                data-filename="{{ $t->file_template_rkpd ? basename($t->file_template_rkpd) : '' }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-medium"><i
                                                    class="bi bi-pencil-square"></i> Edit</button></td>
                                    </tr>
                                @empty <tr>
                                        <td colspan="6" class="text-center py-6">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- FORM FILTER AJAX (PENTING) --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-5">
            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto items-center">
                <div class="relative w-full sm:w-64 group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none"><i
                            class="bi bi-search text-gray-400"></i></div>
                    <input type="text" id="filter-search"
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 block w-full pl-10 p-2.5 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        placeholder="Cari Nama OPD...">
                </div>
                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none"><i
                            class="bi bi-funnel text-gray-400"></i></div>
                    <select id="filter-status"
                        class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="MENUNGGU">Menunggu</option>
                        <option value="REVISI">Revisi</option>
                        <option value="DISETUJUI">Disetujui</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"><i
                            class="bi bi-chevron-down text-xs text-gray-500"></i></div>
                </div>
                <div class="relative w-full sm:w-56">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none"><i
                            class="bi bi-calendar-range text-gray-400"></i></div>
                    <select id="filter-tahapan"
                        class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 block w-full pl-10 pr-10 p-2.5 shadow-sm cursor-pointer dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Tahapan</option>
                        @foreach ($allTahapan as $t)
                            <option value="{{ $t->id }}">{{ $t->nama_tahapan }} {{ $t->tahun }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"><i
                            class="bi bi-chevron-down text-xs text-gray-500"></i></div>
                </div>
            </div>
        </div>

        {{-- TABEL DATA RENJA --}}
        <section
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-md overflow-hidden"
            id="renja-table-container">
            @include('pages.renja._table_list')
        </section>

    </main>

    @include('pages.renja._modals')
    @push('scripts')
        @include('pages.renja._scripts')
    @endpush
</x-layout>
