@section('meta_title', $meta_title)
@section('meta_description', $meta_description)
<x-landing.layout>
    {{-- Dependensi Swiper CSS (diperlukan untuk carousel) --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    @endpush

    <section class="py-16 px-4 md:px-8 lg:px-10 -mt-[450px]">
        <div class="max-w-7xl mx-auto">


            @if ($beritaPopuler->isEmpty())
                <div class="p-8 text-center text-gray-500 bg-white rounded-lg shadow">
                    Belum ada berita populer.
                </div>
            @else
                <!-- Wrapper konseptual, BUKAN swiper-container -->
                <div class="popular-news-slider relative">

                    <div id="popular-card-wrapper"
                        class="bg-blue-800 rounded-2xl overflow-hidden shadow-2xl transition-colors duration-500 ease-in-out">
                        <div class="flex flex-col md:flex-row p-8 md:p-12 items-center gap-y-8 md:gap-x-12">

                            <div class="w-full md:w-5/12 flex-shrink-0">
                                <div
                                    class="swiper-container swiper-image-slider aspect-video rounded-lg overflow-hidden shadow-inner">
                                    <div class="swiper-wrapper">
                                        @foreach ($beritaPopuler as $populer)
                                            <div class="swiper-slide">
                                                <a href="{{ route('berita.public.show', $populer) }}"
                                                    class="block w-full h-full">

                                                    <div
                                                        class="relative w-full h-full bg-black flex items-center justify-center">
                                                        {{-- 1. Latar Belakang Buram --}}
                                                        <div class="absolute inset-0 bg-cover bg-center"
                                                            style="background-image: url('{{ $populer->cover_image ? asset('storage/' . $populer->cover_image) : 'https://placehold.co/800x600/e2e8f0/e2e8f0' }}'); filter: blur(20px) brightness(0.8); transform: scale(1.1);">
                                                        </div>
                                                        {{-- 2. Gambar Utama (Contain) --}}
                                                        <img src="{{ $populer->cover_image ? asset('storage/' . $populer->cover_image) : 'https://placehold.co/800x600/e2e8f0/667eea?text=Berita' }}"
                                                            alt="{{ $populer->title }}"
                                                            class="relative z-10 max-w-full max-h-full object-contain">
                                                    </div>

                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div
                                class="w-full md:w-7/12 pt-8 md:pt-0 md:pl-12 flex flex-col justify-center text-white relative overflow-hidden">
                                <div class="swiper-container swiper-text-slider">
                                    <div class="swiper-wrapper">
                                        @foreach ($beritaPopuler as $populer)
                                            <div class="swiper-slide">
                                                <h3 class="text-2xl md:text-3xl font-bold leading-tight mb-3">
                                                    <a href="{{ route('berita.public.show', $populer) }}"
                                                        class="hover:underline">
                                                        {{ $populer->title }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-300 mb-4">
                                                    <i class="bi bi-person"></i> {{ $populer->author->name ?? 'Admin' }}
                                                    /
                                                    {{ $populer->created_at->isoFormat('D MMMM YYYY') }}
                                                </p>
                                                <p
                                                    class="text-sm md:text-base text-gray-200 mb-6 leading-relaxed hidden md:block">
                                                    {{ Str::limit($populer->excerpt, 150) }}
                                                </p>
                                                <div class="mt-auto">
                                                    <a href="{{ route('berita.public.show', $populer) }}"
                                                        class="inline-block bg-yellow-400 text-blue-800 font-semibold px-6 py-3 rounded-md text-sm hover:bg-yellow-500 transition-colors">
                                                        Baca Selengkapnya
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-pagination popular-pagination relative mt-4"></div>
                </div>
            @endif
        </div>
    </section>

    <section class="py-10 px-4 md:px-8 lg:px-16">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Berita Terkini</h2>
            {{-- Search & Filter Bar --}}
            <div class="mb-8 p-4 bg-gray-100 rounded-xl shadow-md">
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

            {{-- Grid untuk kartu berita (sekarang di dalam container) --}}
            <div id="berita-terkini-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Muat data awal menggunakan partial view --}}
                @include('landing_page.berita._berita_terkini_grid', ['beritaTerkini' => $beritaTerkini])
            </div>

            {{-- Link Paginasi (di dalam container) --}}
            <div id="pagination-container" class="mt-12 pagination">
                {{ $beritaTerkini->links() }}
            </div>
        </div>
    </section>

    @push('scripts')
        {{-- Swiper JS --}}
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            // Inisialisasi Swiper untuk Berita Populer
            if (document.querySelector('.popular-news-slider')) {

                // Definisikan palet warna (Tailwind Blue-800, Green-800, Purple-800, Sky-800, Indigo-800)
                const colors = ['#1e3a8a', '#065f46', '#581c87', '#075985', '#3730a3'];
                const cardWrapper = document.getElementById('popular-card-wrapper');

                // Inisialisasi Swiper Teks (Hanya Mengikuti)
                const swiperText = new Swiper('.swiper-text-slider', {
                    loop: true,
                    allowTouchMove: false, // Tidak bisa di-swipe manual
                    effect: 'fade', // Efek fade untuk teks
                    speed: 1000, // Kecepatan transisi 1000ms (1 detik)
                    fadeEffect: {
                        crossFade: true
                    },
                });

                // Inisialisasi Swiper Gambar (Kontrol Utama)
                const swiperImage = new Swiper('.swiper-image-slider', {
                    loop: true,
                    speed: 1000, // Kecepatan transisi 1000ms (1 detik)
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.popular-pagination', // Kontrol pagination ditaruh di sini
                        clickable: true,
                        renderBullet: function(index, className) {
                            // Gunakan dot abu-abu untuk background terang
                            return '<span class="' + className +
                                ' bg-gray-400 w-2.5 h-2.5 rounded-full mx-1 cursor-pointer"></span>';
                        }
                    },
                    effect: 'slide', // Efek slide untuk gambar
                });

                // Link Keduanya
                swiperImage.controller.control = swiperText;
                swiperText.controller.control = swiperImage;

                // Tambahkan event listener untuk mengubah warna card
                swiperImage.on('slideChange', function() {
                    if (cardWrapper) {
                        const realIndex = swiperImage.realIndex; // Indeks slide yang sebenarnya (0, 1, 2, 3, 4)
                        const nextColor = colors[realIndex % colors.length]; // Ambil warna dari palet
                        cardWrapper.style.backgroundColor = nextColor; // Ganti warna background
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const sortFilter = document.getElementById('sort-filter');
                const tanggalFilter = document.getElementById('tanggal-filter');
                const gridContainer = document.getElementById('berita-terkini-container');
                const paginationContainer = document.getElementById('pagination-container');

                let debounceTimer;

                // PERBAIKAN: Fungsi fetchBerita sekarang menerima nomor halaman
                function fetchBerita(page = 1) {
                    const search = searchInput.value;
                    const sort = sortFilter.value;
                    const tanggal = tanggalFilter.value;

                    // Selalu gunakan rute 'berita.public.search'
                    const params = new URLSearchParams({
                        search: search,
                        sort: sort,
                        tanggal: tanggal,
                        page: page // Tambahkan parameter halaman
                    });
                    const fetchUrl = `{{ route('berita.public.search') }}?${params.toString()}`;

                    // Tampilkan loading
                    gridContainer.innerHTML =
                        `<div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-16"><i class="bi bi-arrow-repeat text-4xl text-gray-400 animate-spin"></i><p class="mt-2 text-gray-500">Memuat berita...</p></div>`;
                    paginationContainer.innerHTML = ''; // Kosongkan paginasi

                    fetch(fetchUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) { // Cek jika respons tidak OK (misal 404, 500)
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            gridContainer.innerHTML = data.html;
                            paginationContainer.innerHTML = data.pagination;
                        })
                        .catch(error => {
                            console.error('Error fetching berita:', error);
                            gridContainer.innerHTML =
                                `<div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-16"><p class="text-red-500">Gagal memuat berita. Silakan coba lagi.</p></div>`;
                        });
                }

                // Event listener untuk input pencarian (dengan debounce)
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchBerita(1); // Selalu reset ke halaman 1 saat filter
                    }, 500);
                });

                // Event listener untuk dropdown sort dan tanggal
                sortFilter.addEventListener('change', () => fetchBerita(1)); // Selalu reset ke halaman 1
                tanggalFilter.addEventListener('change', () => fetchBerita(1)); // Selalu reset ke halaman 1

                // PERBAIKAN: Event listener untuk paginasi
                paginationContainer.addEventListener('click', function(e) {
                    let target = e.target;
                    // Klik bisa terjadi di <span> atau <svg> di dalam <a>
                    if (target.tagName !== 'A') {
                        target = target.closest('a');
                    }

                    // Cek apakah yang diklik adalah link paginasi yang valid
                    if (target && target.tagName === 'A' && (target.hasAttribute('rel') || target.getAttribute(
                            'href').includes('page='))) {
                        e.preventDefault(); // Mencegah reload halaman
                        const urlString = target.getAttribute('href');
                        if (urlString) {
                            try {
                                const urlObj = new URL(urlString);
                                const page = urlObj.searchParams.get('page'); // Ekstrak nomor halaman
                                if (page) {
                                    fetchBerita(page); // Panggil fetchBerita DENGAN nomor halaman
                                }
                            } catch (error) {
                                console.error('Error parsing pagination URL:', error);
                            }
                        }
                    }
                });
            });
        </script>

        {{-- Style untuk social icon kecil dan paginasi --}}
        <style>
            .social-icon-small {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                color: #6b7280;
                /* gray-500 */
                transition: all 0.2s ease-in-out;
            }

            .social-icon-small:hover {
                transform: scale(1.1);
            }

            .social-icon-small.whatsapp:hover {
                color: #10b981;
            }

            .social-icon-small.facebook:hover {
                color: #3b82f6;
            }

            .social-icon-small.twitter:hover {
                color: #1f2937;
            }

            /* Styling untuk Swiper pagination dot yang baru */
            .popular-pagination .swiper-pagination-bullet {
                background-color: #9ca3af;
                /* gray-400 */
            }

            .popular-pagination .swiper-pagination-bullet-active {
                background-color: #2563eb !important;
                /* blue-600 */
            }
        </style>
    @endpush
</x-landing.layout>
