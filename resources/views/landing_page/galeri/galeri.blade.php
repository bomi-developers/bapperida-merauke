<x-landing.layout>
    {{-- Dependensi Swiper CSS (diperlukan untuk carousel) --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    @endpush

    {{-- =================================== --}}
    {{-- === 1. GALERI POPULER (CAROUSEL) === --}}
    {{-- =================================== --}}
    <section class="py-16 px-4 md:px-8 lg:px-16 -mt-[450px]">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-center mb-8">
                <div class="bg-blue-500 text-white rounded-2xl px-6 py-3 shadow-md inline-block">
                    <h2 class="text-2xl font-bold text-center"> Highlight Galeri BAPPERIDA</h2>
                </div>
            </div>

            @if ($galeriPopuler->isEmpty())
                <div class="p-8 text-center text-gray-500 bg-white rounded-lg shadow">
                    Belum ada galeri.
                </div>
            @else
                <!-- Wrapper konseptual -->
                <div class="popular-gallery-slider-wrapper relative">

                    {{-- Carousel Full Image (Sesuai UI) --}}
                    <div
                        class="swiper-container popular-gallery-slider aspect-video rounded-xl overflow-hidden shadow-2xl">
                        <div class="swiper-wrapper">
                            @foreach ($galeriPopuler as $populer)
                                @php $coverItem = $populer->firstItem; @endphp
                                <div class="swiper-slide relative bg-gray-900 text-white flex items-end justify-start">

                                    {{-- Wrapper untuk gambar (mengisi seluruh slide) --}}
                                    <div class="absolute inset-0 w-full h-full">
                                        @if ($coverItem && $coverItem->tipe_file == 'image')
                                            {{-- 1. Latar Belakang Buram (Hanya untuk gambar) --}}
                                            <div class="absolute inset-0 bg-cover bg-center"
                                                style="background-image: url('{{ asset('storage/' . $coverItem->file_path) }}'); filter: blur(20px) brightness(0.7); transform: scale(1.1);">
                                            </div>
                                            {{-- 2. Gambar Utama (Contain) --}}
                                            <img src="{{ asset('storage/' . $coverItem->file_path) }}"
                                                alt="{{ $populer->judul }}"
                                                class="relative z-10 w-full h-full object-contain">
                                        @elseif($coverItem && ($coverItem->tipe_file == 'video' || $coverItem->tipe_file == 'video_url'))
                                            {{-- Placeholder gelap untuk video --}}
                                            <div
                                                class="absolute inset-0 w-full h-full bg-black flex items-center justify-center">
                                                @if ($coverItem->tipe_file == 'video')
                                                    <i class="bi bi-film text-8xl text-gray-600 z-10"></i>
                                                @else
                                                    <i class="bi bi-youtube text-8xl text-red-600/50 z-10"></i>
                                                @endif
                                            </div>
                                        @else
                                            {{-- Fallback jika tidak ada item --}}
                                            <div class="absolute inset-0 bg-gray-200"></div>
                                            <img src="https://placehold.co/800x600/e2e8f0/667eea?text=Galeri"
                                                alt="{{ $populer->judul }}"
                                                class="relative z-10 max-w-full max-h-full object-contain">
                                        @endif
                                    </div>


                                    {{-- Overlay Gradien --}}
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent">
                                    </div>

                                    {{-- Konten Teks di Atas Gambar --}}
                                    <div class="relative z-20 p-8 md:p-12 max-w-2xl"> {{-- Naikkan z-index ke 20 --}}
                                        <p class="text-sm text-lime-400 font-semibold mb-2">
                                            {{ $populer->created_at->isoFormat('D MMMM YYYY') }}
                                        </p>
                                        <h3 class="text-2xl md:text-3xl font-bold leading-tight text-white">
                                            <a href="#" {{-- TODO: Ganti ke route('galeri.public.show', $populer) --}} class="hover:underline">
                                                {{ $populer->judul }}
                                            </a>
                                        </h3>
                                        @if ($populer->keterangan)
                                            <p
                                                class="text-xl md:text-2xl text-gray-200 font-light leading-snug mt-2 mb-6">
                                                {{ Str::limit($populer->keterangan, 100) }}
                                            </p>
                                        @endif
                                        <div class="mt-auto pt-4">
                                            <a href="{{ route('galeri.public.show', $populer) }}"
                                                class="inline-block bg-lime-400 text-blue-800 font-semibold px-6 py-3 rounded-md text-sm hover:bg-blue-500 hover:text-white transition-colors">
                                                Buka Album
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pagination Dots (sesuai UI) --}}
                    <div class="swiper-pagination popular-pagination-galeri relative mt-4"></div>
                </div>
            @endif
        </div>
    </section>

    {{-- =================================== --}}
    {{-- === 2. FILTER DAN GRID KONTEN === --}}
    {{-- =================================== --}}
    <section class="py-16 px-4 md:px-8 lg:px-16 bg-gray-50">
        <div class="max-w-7xl mx-auto">

            {{-- Search & Filter Bar --}}
            <div class="mb-8 p-4 bg-white rounded-lg shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <!-- Input Pencarian -->
                    <div class="relative w-full md:col-span-2">
                        <label for="search-input" class="sr-only">Cari</label>
                        <input type="text" id="search-input"
                            class="py-2.5 px-4 ps-10 block w-full border-gray-300 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Cari berdasarkan judul album...">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                    </div>
                    <!-- Filter Urutkan -->
                    <div class="w-full">
                        <select id="sort-filter"
                            class="py-2.5 px-3 pe-9 block w-full border-gray-300 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option selected value="terbaru">Urutkan: Paling Baru</option>
                            <option value="terlama">Urutkan: Paling Lama</option>
                        </select>
                    </div>

                    {{-- PERBAIKAN: Filter Tanggal (Input Date) --}}
                    <div class="w-full relative">
                        <input type="date" id="tanggal-filter"
                            class="py-2.5 px-3 pe-10 block w-full border-gray-300 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 text-gray-500">
                        <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                            <i class="bi bi-calendar-event text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Tipe Konten (Tabs) --}}
            <div class="mb-8 flex justify-center">
                <div class="inline-flex rounded-lg shadow-sm bg-white p-1.5 space-x-1">
                    <button type="button" data-filter="album" class="filter-tab btn-tab-active">
                        Semua Album
                    </button>
                    <button type="button" data-filter="image" class="filter-tab btn-tab-inactive">
                        Foto
                    </button>
                    <button type="button" data-filter="video" class="filter-tab btn-tab-inactive">
                        Video
                    </button>
                    <button type="button" data-filter="video_url" class="filter-tab btn-tab-inactive">
                        Link Youtube
                    </button>
                </div>
            </div>

            {{-- Grid untuk kartu galeri --}}
            <div id="galeri-grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @include('landing_page.galeri.partials._galeri_grid', [
                    'items' => $items,
                    'currentFilterType' => $currentFilterType,
                ])
            </div>

            {{-- Link Paginasi (di dalam container) --}}
            <div id="pagination-container" class="mt-12 pagination">
                {{ $items->links() }}
            </div>
        </div>
    </section>

    @push('scripts')
        {{-- Memuat Skrip AJAX --}}
        @include('landing_page.galeri.partials._galeri_scripts')
    @endpush
</x-landing.layout>
