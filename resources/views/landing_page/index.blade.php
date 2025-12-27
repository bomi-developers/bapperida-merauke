<x-landing.layout>

    <section id="first-section" class="py-24 -mt-[500px]">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center reveal-on-scroll">
            <div class="w-full max-w-4xl mx-auto aspect-video">
                <iframe src="https://www.youtube.com/embed/bTXA5LxI2ZM" frameborder="0" loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen class="w-full h-full rounded-xl shadow-lg"></iframe>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-[#] leading-tight text-[#004299]">
                    Badan Perkembangan, Riset dan Inovasi Daerah Kabupaten Merauke
                </h2>
                <p class="mt-4 text-gray-600">
                    Bappeda mempunyai tugas menyelenggarakan fungsi penunjang urusan
                    pemerintahan bidang perencanaan dan menunjang urusan
                    pemerintahan bidang penelitian dan pengembangan.
                </p>
            </div>
        </div>
    </section>
      <section>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="bg-white rounded-2xl shadow-lg p-12 reveal-on-scroll">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-[#004299]">Bidang-bidang</h2>
                    <p class="mt-2 text-gray-500">Bidang-bidang yang ada di BAPPERIDA</p>
                </div>

                <div class="mt-12 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-y-10 gap-x-8">
                    @foreach ($Bidang as $item)
                    <a href="#" class="text-center group">
                        <div
                            class="flex items-center justify-center h-24 w-24 mx-auto bg-blue-50 rounded-full transition-all duration-300 group-hover:bg-blue-100 group-hover:scale-110">
                            <svg class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-semibold text-gray-700">{{ $item->nama_bidang }}</h3>
                    </a>
                    @endforeach

                </div>
            </div>
        </div>
    </section> 

    @foreach ($LendingPage as $item)
        {!! Blade::render($item->template->content, [
            'dokumen' => $dokumen ?? [],
            'Str' => new \Illuminate\Support\Str(),
        ]) !!}
    @endforeach

    {{-- 
    <section class="relative py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="reveal-on-scroll">
                <h2 class="text-3xl font-bold text-[#004299]">Dokumen BAPPERIDA Publik</h2>
                <div class="w-24 h-1 bg-[#CCFF00] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="mt-16 flex flex-wrap justify-center gap-8">
                @forelse ($dokumen as $item)
                    <a href="{{ route('documents.by_category', [
                        'kategori' => $item->id,
                        'slug' => Str::slug($item->nama_kategori),
                    ]) }}"
                        class="bg-white p-8 rounded-2xl shadow-lg reveal-on-scroll hover:shadow-xl hover:-translate-y-1 transition-all duration-300 w-full max-w-sm">
                        <div class="flex items-center justify-between">
                            <svg class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span
                                class="bg-[#CCFF00] text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Selengkapnya</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mt-6 text-left">{{ $item->nama_kategori }}</h3>
                        <p class="mt-2 text-gray-500 text-left text-sm">{{ $item->deskripsi }}</p>
                    </a>
                @empty
                    <div
                        class="bg-white p-8 rounded-2xl shadow-lg reveal-on-scroll hover:shadow-xl hover:-translate-y-1 transition-all duration-300 w-full max-w-sm">
                        <div class="flex items-center justify-between">
                            <svg class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span
                                class="bg-[#CCFF00] text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Selengkapnya</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mt-6 text-left">Kategori Dokumen Tidak Ditemukan</h3>
                        <p class="mt-2 text-gray-500 text-left text-sm">Kategory Dokumen Tidak Ditemukan</p>
                    </div>
                @endforelse

            </div>
        </div>
    </section> --}}

   

    {{-- <section class="py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-[#004299]">Berita Terkini</h2>
                <div class="w-24 h-1 bg-[#CCFF00] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="swiper berita-slider mt-12">
                <div class="swiper-wrapper py-4">
                    <div class="swiper-slide w-3/4 md:w-1/2">
                        <a href="#"
                            class="block relative aspect-video rounded-2xl overflow-hidden group shadow-lg">
                            <img src="assets/image1.png" alt="Berita 1" loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <h3 class="text-white text-xl font-bold">Bappeda Papua Selatan Gelar
                                    Sosialisasi dan Bimtek SIPD RI
                                </h3>
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide w-3/4 md:w-1/2">
                        <a href="#"
                            class="block relative aspect-video rounded-2xl overflow-hidden group shadow-lg">
                            <img src="Assets/image2.png" alt="Berita 2" loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <h3 class="text-white text-xl font-bold">Inovasi Daerah: Kunci Peningkatan
                                    Pelayanan Publik</h3>
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide w-3/4 md:w-1/2">
                        <a href="#"
                            class="block relative aspect-video rounded-2xl overflow-hidden group shadow-lg">
                            <img src="assets/image3.png" alt="Berita 3" loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <h3 class="text-white text-xl font-bold">Rapat Koordinasi Pembangunan Kabupaten
                                    Merauke 2025</h3>
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide w-3/4 md:w-1/2">
                        <a href="#"
                            class="block relative aspect-video rounded-2xl overflow-hidden group shadow-lg">
                            <img src="assets/image4.png" alt="Berita 4" loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <h3 class="text-white text-xl font-bold">Pelatihan Peningkatan Kapasitas
                                    Aparatur Perencana</h3>
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide w-3/4 md:w-1/2">
                        <a href="#"
                            class="block relative aspect-video rounded-2xl overflow-hidden group shadow-lg">
                            <img src="Assets/image5.jpg" alt="Berita 4" loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <h3 class="text-white text-xl font-bold">Pelatihan Peningkatan Kapasitas
                                    Aparatur Perencana</h3>
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide w-3/4 md:w-1/2">
                        <a href="#"
                            class="block relative aspect-video rounded-2xl overflow-hidden group shadow-lg">
                            <img src="assets/image6.jpg" alt="Berita 4" loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <h3 class="text-white text-xl font-bold">Pelatihan Peningkatan Kapasitas
                                    Aparatur Perencana</h3>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="swiper-pagination mt-8 relative"></div>
            </div>
        </div>
    </section> --}}

    {{-- <section class="py-24 overflow-hidden p-8 md:p-16  bg-[url('Assets/Gradient1.svg')] bg-cover bg-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll p-8 md:p-16">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-[#004299]">Galeri</h2>
                <p class="mt-2 text-gray-500">Bidang-bidang yang ada di BAPPERIDA</p>
                <div class="w-24 h-1 bg-[#CCFF00] mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="relative rounded-2xl shadow-lg overflow-hidden group">

                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1470&auto=format&fit=crop"
                        alt="Rapat Bapperida Papua Selatan" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">

                    <div class="absolute bottom-0 left-0 w-full p-5 bg-gradient-to-t from-black/80 to-transparent">
                        <div class="flex flex-col">
                            <span class="text-white text-base font-bold leading-tight">Bapperida Papua
                                Selatan</span>
                            <span class="text-white text-sm font-light leading-tight">Gelar
                                Rakortekrenbang</span>
                        </div>
                    </div>
                </div>

                <div class="relative rounded-2xl shadow-lg overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1470&auto=format&fit=crop"
                        alt="Rapat Bapperida Papua Selatan" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute bottom-0 left-0 w-full p-5 bg-gradient-to-t from-black/80 to-transparent">
                        <div class="flex flex-col">
                            <span class="text-white text-base font-bold leading-tight">Bapperida Papua
                                Selatan</span>
                            <span class="text-white text-sm font-light leading-tight">Gelar
                                Rakortekrenbang</span>
                        </div>
                    </div>
                </div>

                <div class="relative rounded-2xl shadow-lg overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1470&auto=format&fit=crop"
                        alt="Rapat Bapperida Papua Selatan" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute bottom-0 left-0 w-full p-5 bg-gradient-to-t from-black/80 to-transparent">
                        <div class="flex flex-col">
                            <span class="text-white text-base font-bold leading-tight">Bapperida Papua
                                Selatan</span>
                            <span class="text-white text-sm font-light leading-tight">Gelar
                                Rakortekrenbang</span>
                        </div>
                    </div>
                </div>

                <div class="relative rounded-2xl shadow-lg overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1470&auto=format&fit=crop"
                        alt="Rapat Bapperida Papua Selatan" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute bottom-0 left-0 w-full p-5 bg-gradient-to-t from-black/80 to-transparent">
                        <div class="flex flex-col">
                            <span class="text-white text-base font-bold leading-tight">Bapperida Papua
                                Selatan</span>
                            <span class="text-white text-sm font-light leading-tight">Gelar
                                Rakortekrenbang</span>
                        </div>
                    </div>
                </div>

                <div class="relative rounded-2xl shadow-lg overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1470&auto=format&fit=crop"
                        alt="Rapat Bapperida Papua Selatan" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute bottom-0 left-0 w-full p-5 bg-gradient-to-t from-black/80 to-transparent">
                        <div class="flex flex-col">
                            <span class="text-white text-base font-bold leading-tight">Bapperida Papua
                                Selatan</span>
                            <span class="text-white text-sm font-light leading-tight">Gelar
                                Rakortekrenbang</span>
                        </div>
                    </div>
                </div>

                <div class="relative rounded-2xl shadow-lg overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1470&auto=format&fit=crop"
                        alt="Rapat Bapperida Papua Selatan" loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute bottom-0 left-0 w-full p-5 bg-gradient-to-t from-black/80 to-transparent">
                        <div class="flex flex-col">
                            <span class="text-white text-base font-bold leading-tight">Bapperida Papua
                                Selatan</span>
                            <span class="text-white text-sm font-light leading-tight">Gelar
                                Rakortekrenbang</span>
                        </div>
                    </div>
                </div>
            </div>
    </section> --}}

    {{-- <section class="py-24 overflow-hidden p-8 md:p-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll p-8 md:p-16">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-[#004299]">Statistik Pengunjung</h2>
                <div class="w-24 h-1 bg-[#CCFF00] mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="mt-16 grid grid-cols-1 gap-x-8 gap-y-16 text-center sm:grid-cols-2 lg:grid-cols-3">

                <div class="flex flex-col gap-y-4">
                    <p class="text-5xl font-bold tracking-tight text-blue-600">{{ number_format($pageView) }}</p>
                    <p class="text-sm font-semibold leading-6 text-gray-600 uppercase">Jumlah Pengunjung</p>
                </div>

                <div class="flex flex-col gap-y-4">
                    <p class="text-5xl font-bold tracking-tight text-blue-600">{{ number_format($pageViewToday) }}</p>
                    <p class="text-sm font-semibold leading-6 text-gray-600 uppercase">Pengunjung Hari Ini</p>
                </div>

                <div class="flex flex-col gap-y-4">
                    <p class="text-5xl font-bold tracking-tight text-blue-600">{{ number_format($pageViewUrl) }}</p>
                    <p class="text-sm font-semibold leading-6 text-gray-600 uppercase">Jumlah Halaman
                        Dikunjungi</p>
                </div>

    </section> --}}
</x-landing.layout>
{{-- @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@endpush --}}
