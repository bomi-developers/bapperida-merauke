<x-landing.layout>
    <section id="first-section" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[400px] mb-24">
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div
                        class="border-2 border-blue-300 rounded-lg divide-y-2 divide-blue-300 bg-white/80 backdrop-blur-sm shadow-lg">
                        <div class="py-3 px-4 flex flex-col sm:flex-row items-center gap-4">
                            <!-- Input Pencarian -->
                            <div class="relative w-full sm:w-auto sm:flex-grow">
                                <label for="search-input" class="sr-only">Cari</label>
                                <input type="text" id="search-input"
                                    class="py-2 px-3 ps-9 block w-full border-blue-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 text-blue-800 placeholder-blue-800/70"
                                    placeholder="Cari berdasarkan judul atau visi...">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                                    <i class="bi bi-search text-blue-800"></i>
                                </div>
                            </div>
                            <!-- Filter Periode -->
                            <div class="w-full sm:w-auto">
                                <select id="periode-filter"
                                    class="py-2 px-3 pe-9 block w-full border-blue-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 text-blue-800">
                                    <option selected value="semua">Semua Periode</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Filter Urutkan -->
                            <div class="w-full sm:w-auto">
                                <select id="sort-filter"
                                    class="py-2 px-3 pe-9 block w-full border-blue-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 text-blue-800">
                                    <option selected value="terbaru">Urutkan: Paling Baru</option>
                                    <option value="terlama">Urutkan: Paling Lama</option>
                                </select>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y-2 divide-blue-300">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase">
                                            Judul Dokumen</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase">
                                            Visi</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase">
                                            Misi</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-start text-xs font-medium text-blue-800 uppercase">
                                            Tanggal Rilis</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-end text-xs font-medium text-blue-800 uppercase">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="data-table" class="divide-y-2 divide-blue-300">
                                    {{-- Baris tabel akan dimuat oleh AJAX, ini adalah tampilan awal --}}
                                    @include('landing_page.document.partials._document_list_public', [
                                        'documents' => $documents,
                                    ])
                                </tbody>
                            </table>
                        </div>
                        {{-- Kontainer untuk Paginasi --}}
                        <div id="pagination-container" class="py-3 px-4 border-t-2 border-blue-300">
                            {{ $documents->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Memuat Modal Detail --}}
    @include('landing_page.document.partials._document_detail_modal_public')

    @push('scripts')
        {{-- Memuat Skrip AJAX --}}
        @include('landing_page.document.partials._document_scripts_public')
    @endpush
</x-landing.layout>
