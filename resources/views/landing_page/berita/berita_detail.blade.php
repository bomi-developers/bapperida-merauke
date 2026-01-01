@section('meta_title', $berita->title)
@section('meta_description', Str::limit(strip_tags($berita->caption), 160))
@section('meta_image', $berita->cover_image ? asset('storage/' . $berita->cover_image) : asset('default-og.png'))
{{-- hero --}}
@section('hero_title', $berita->title)
@section('hero_bg', $berita->cover_image ? asset('storage/' . $berita->cover_image) : '/assets/image6.jpg')
<x-landing.layout>
    @push('styles')
        {{-- <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" /> --}}
        {{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> --}}
    @endpush

    <section class="py-16 md:py-24 -mt-[450px]">
        <div class="max-w-7xl mx-auto px-4 md:px-4 lg:px-5">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-5">
                <div class="lg:col-span-8">
                    <!-- Article Header -->
                    <div class="mb-6 pb-6">
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-blue-800 leading-tight mb-4">
                            {{ $berita->title }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                            <span class="text-gray-600">
                                <i class="bi bi-person-fill mr-1"></i>
                                {{ $berita->author->name ?? 'Admin' }}
                            </span>
                            <span class="text-gray-500">
                                <i class="bi bi-calendar-event mr-1"></i>
                                {{ $berita->created_at->isoFormat('dddd, D MMMM YYYY') }}
                            </span>
                            <span class="text-gray-500">
                                <i class="bi bi-eye-fill mr-1"></i>
                                {{ $berita->views_count }} Dilihat
                            </span>
                        </div>
                    </div>


                    {{-- Carousel Gambar Berita (Sesuai Permintaan) --}}
                    @php
                        // Mengumpulkan semua gambar: cover image + semua item bertipe 'image'
                        $images = collect();
                        if ($berita->cover_image) {
                            // Tambahkan cover image sebagai item pertama
                            $images->push(['url' => $berita->cover_image, 'caption' => 'Cover Berita']);
                        }
                        // Tambahkan item gambar, urutkan berdasarkan posisi
                        foreach ($berita->items->where('type', 'image')->sortBy('position') as $item) {
                            $images->push(['url' => $item->content, 'caption' => $item->caption ?? '']);
                        }
                        // Hapus duplikat jika cover image juga ada di items
                        $images = $images->unique('url')->values();
                    @endphp

                    @if ($images->isNotEmpty())
                        <div class="mb-8 relative w-full max-w-6xl mx-auto" id="image-slider">
                            <div class="slider-wrapper overflow-hidden rounded-lg shadow-lg">
                                <div class="slides flex transition-transform duration-500 ease-in-out">
                                    @foreach ($images as $image)
                                        <div class="slide min-w-full">
                                            {{-- Container utama untuk setiap slide yang menjadi frame --}}
                                            <div
                                                class="relative w-full h-64 md:h-80 lg:h-96 bg-black overflow-hidden flex items-center justify-center">

                                                <div class="absolute inset-0 bg-cover bg-center"
                                                    style="background-image: url('{{ asset('storage/' . $image['url']) }}'); filter: blur(20px) brightness(0.8); transform: scale(1.1);">
                                                </div>

                                                <img src="{{ asset('storage/' . $image['url']) }}"
                                                    alt="{{ $image['caption'] ?: $berita->title }}"
                                                    class="relative z-10 max-w-full max-h-full object-contain">

                                            </div>

                                            {{-- Caption gambar --}}
                                            @if (!empty($image['caption']))
                                                <p class="text-gray-500 text-sm mt-2 text-center">
                                                    {{ $image['caption'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($images->count() > 1)
                                <button id="prevBtn"
                                    class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none z-20">&#10094;</button>
                                <button id="nextBtn"
                                    class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none z-20">&#10095;</button>
                                <div class="pagination-dots flex justify-center space-x-2 mt-4"></div>
                            @endif
                        </div>
                    @endif


                    <!-- Article Content -->
                    <div class="prose prose-lg max-w-none prose-blue mt-8">
                        <div class="content-items space-y-6">
                            @foreach ($berita->items->sortBy('position') as $item)
                                @switch($item->type)
                                    @case('text')
                                        {!! $item->content !!}
                                    @break

                                    @case('quote')
                                        <blockquote
                                            class="border-l-4 border-blue-500 pl-6 my-8 italic text-gray-700 font-serif">
                                            <p class="text-xl leading-relaxed">"{{ $item->content }}"</p>
                                        </blockquote>
                                    @break

                                    @case('video')
                                        @php
                                            $embedUrl = $item->content;
                                            if (
                                                preg_match(
                                                    '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i',
                                                    $embedUrl,
                                                    $match,
                                                )
                                            ) {
                                                $videoId = $match[1];
                                                $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                                            } else {
                                                $embedUrl = null;
                                            }
                                        @endphp
                                        @if ($embedUrl)
                                            <div
                                                class="aspect-video w-full max-w-3xl mx-auto rounded-lg shadow-md overflow-hidden my-8">
                                                <iframe src="{{ $embedUrl }}" frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen class="w-full h-full"></iframe>
                                            </div>
                                        @endif
                                    @break

                                    @case('embed')
                                        <div class="my-8 w-full flex justify-center">
                                            <div class="max-w-md w-full">
                                                {!! $item->content !!}
                                            </div>
                                        </div>
                                    @break

                                    @case('image')
                                        <figure class="my-6">
                                            <img src="{{ asset('storage/' . $item->content) }}"
                                                alt="{{ $item->caption ?: $berita->title }}"
                                                class="w-full max-w-3xl mx-auto h-auto rounded-lg shadow-md object-contain">
                                            @if (!empty($item->caption))
                                                <figcaption class="text-center text-sm text-gray-500 mt-2">{{ $item->caption }}
                                                </figcaption>
                                            @endif
                                        </figure>
                                    @break

                                    {{-- Image items tidak lagi ditampilkan di sini, karena sudah di carousel --}}
                                @endswitch
                            @endforeach
                        </div>
                    </div>

                    <!-- Social Share -->
                    <div class="border-t border-gray-200 pt-6 mt-10">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <span class="text-gray-600 font-medium">Bagikan Berita Ini :</span>
                            @php
                                $shareUrl = route('berita.public.show', $berita);
                                $shareText = urlencode($berita->title);
                                $shareTextWithUrl = urlencode($berita->title . ' ' . $shareUrl);
                            @endphp
                            <div class="flex items-center gap-3">
                                <a href="https://api.whatsapp.com/send?text={{ $shareTextWithUrl }}" target="_blank"
                                    class="social-icon whatsapp" title="Bagikan ke WhatsApp"><i
                                        class="bi bi-whatsapp text-xl"></i></a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                                    target="_blank" class="social-icon facebook" title="Bagikan ke Facebook"><i
                                        class="bi bi-facebook text-xl"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}"
                                    target="_blank" class="social-icon twitter" title="Bagikan ke X"><i
                                        class="bi bi-twitter-x text-xl"></i></a>
                                <button onclick="copyToClipboard(this, '{{ $shareUrl }}')" class="social-icon link"
                                    title="Salin tautan"><i class="bi bi-link-45deg text-xl"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =================================== --}}
                {{-- === SIDEBAR (KANAN - 30%) === --}}
                {{-- =================================== --}}
                <div class="lg:col-span-4">
                    {{-- Widget Berita Terkini --}}
                    <div class="bg-white py-6 px-3 rounded-xl shadow-lg sticky top-28">
                        <h3 class="text-xl font-bold text-blue-800 border-b border-gray-200 pb-3 mb-4">Berita Terkini
                        </h3>
                        <div class="space-y-4">
                            @forelse ($beritaTerkini as $terkini)
                                <a href="{{ route('berita.public.show', $terkini) }}"
                                    class="flex items-center gap-4 group">
                                    <img src="{{ $terkini->cover_image ? asset('storage/' . $terkini->cover_image) : 'https://placehold.co/100x100/e2e8f0/667eea?text=Berita' }}"
                                        alt="Cover: {{ $terkini->title }}"
                                        class="w-20 h-20 object-cover rounded-md flex-shrink-0">
                                    <div class="flex-grow">
                                        <h4
                                            class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition-colors">
                                            {{ Str::limit($terkini->title, 55) }}
                                        </h4>
                                        <p class="text-sm text-gray-700 mt-1 leading-relaxed flex-grow">
                                            {{ Str::limit($terkini->excerpt, 100) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $terkini->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada berita terkini lainnya.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- "Baca Juga" Section (Berita Terpopuler) -->
    @if ($beritaTerpopuler->isNotEmpty())
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-center text-2xl md:text-3xl lg:text-4xl font-bold text-blue-800 leading-tight mb-10">
                    Baca Juga Berita Populer Lainnya</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($beritaTerpopuler as $populer)
                        <div
                            class="bg-white rounded-2xl shadow-lg flex flex-col transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20">
                            <a href="{{ route('berita.public.show', $populer) }}">
                                <img src="{{ $populer->cover_image ? asset('storage/' . $populer->cover_image) : 'https://placehold.co/600x400/e2e8f0/667eea?text=Berita' }}"
                                    alt="Cover Berita {{ $populer->title }}"
                                    class="w-full h-48 object-cover rounded-t-2xl">
                            </a>
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-blue-800 mb-2 leading-tight">
                                    <a href="{{ route('berita.public.show', $populer) }}" class="hover:underline">
                                        {{ Str::limit($populer->title, 50) }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-500 mb-3">{{ $populer->created_at->diffForHumans() }}</p>
                                <p class="text-sm text-gray-700 mb-4 leading-relaxed flex-grow">
                                    {{ Str::limit($populer->excerpt, 100) }}
                                </p>
                                <a href="{{ route('berita.public.show', $populer) }}"
                                    class="text-blue-600 hover:underline text-sm font-semibold mt-auto">
                                    Baca Selengkapnya &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @push('scripts')
        <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
        <script>
            // Skrip untuk Carousel Kustom
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.querySelector('#image-slider');
                if (!slider) return;

                const slidesContainer = slider.querySelector('.slides');
                const slides = slider.querySelectorAll('.slide');
                const prevBtn = slider.querySelector('#prevBtn');
                const nextBtn = slider.querySelector('#nextBtn');
                const paginationDots = slider.querySelector('.pagination-dots');

                if (slides.length <= 1) { // Jika hanya 1 gambar, sembunyikan navigasi
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    if (paginationDots) paginationDots.style.display = 'none';
                    return;
                }

                let currentIndex = 0;
                const totalSlides = slides.length;

                // Buat pagination dots
                if (paginationDots) {
                    for (let i = 0; i < totalSlides; i++) {
                        const dot = document.createElement('button');
                        dot.classList.add('dot', 'w-3', 'h-3', 'rounded-full', 'bg-gray-300', 'hover:bg-gray-500',
                            'transition-colors');
                        if (i === 0) dot.classList.add('bg-blue-600');
                        dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
                        dot.addEventListener('click', () => goToSlide(i));
                        paginationDots.appendChild(dot);
                    }
                }
                const dots = paginationDots ? paginationDots.querySelectorAll('.dot') : [];

                function goToSlide(index) {
                    currentIndex = index;
                    const offset = -currentIndex * 100;
                    slidesContainer.style.transform = `translateX(${offset}%)`;
                    updatePagination();
                }

                function updatePagination() {
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('bg-blue-600', index === currentIndex);
                        dot.classList.toggle('bg-gray-300', index !== currentIndex);
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        const nextIndex = (currentIndex + 1) % totalSlides;
                        goToSlide(nextIndex);
                    });
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        const prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                        goToSlide(prevIndex);
                    });
                }

                // Auto slide
                let autoSlideInterval = setInterval(() => {
                    const nextIndex = (currentIndex + 1) % totalSlides;
                    goToSlide(nextIndex);
                }, 5000); // Ganti slide setiap 5 detik

                // Hentikan auto slide saat hover
                slider.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
                // Mulai lagi saat tidak hover
                slider.addEventListener('mouseleave', () => {
                    autoSlideInterval = setInterval(() => {
                        const nextIndex = (currentIndex + 1) % totalSlides;
                        goToSlide(nextIndex);
                    }, 5000);
                });
            });

            // Skrip untuk tombol Copy to Clipboard
            function copyToClipboard(button, textToCopy) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(textToCopy).then(function() {
                        const originalContent = button.innerHTML;
                        const originalClasses = button.className;
                        button.innerHTML = '<i class="bi bi-check-lg text-xl text-green-500"></i>';
                        button.disabled = true;
                        button.className += ' cursor-not-allowed bg-green-100';

                        setTimeout(() => {
                            button.innerHTML = originalContent;
                            button.disabled = false;
                            button.className = originalClasses;
                        }, 2000);
                    }).catch(function(err) {
                        console.error('Gagal menyalin: ', err);
                        fallbackCopyTextToClipboard(button, textToCopy);
                    });
                } else {
                    fallbackCopyTextToClipboard(button, textToCopy);
                }
            }

            function fallbackCopyTextToClipboard(button, text) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    const originalContent = button.innerHTML;
                    const originalClasses = button.className;
                    button.innerHTML = '<i class="bi bi-check-lg text-xl text-green-500"></i>';
                    button.disabled = true;
                    button.className += ' cursor-not-allowed bg-green-100';

                    setTimeout(() => {
                        button.innerHTML = originalContent;
                        button.disabled = false;
                        button.className = originalClasses;
                    }, 2000);
                } catch (err) {
                    console.error('Fallback: Gagal menyalin', err);
                }
                document.body.removeChild(textArea);
            }
        </script>
        {{-- Style untuk social icon --}}
        <style>
            .social-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                /* Ukuran tombol */
                height: 36px;
                border-radius: 50%;
                background-color: #f3f4f6;
                /* Abu-abu muda */
                color: #4b5563;
                /* Abu-abu tua */
                transition: all 0.2s ease-in-out;
            }

            .social-icon:hover {
                transform: scale(1.1);
                /* Efek zoom saat hover */
            }

            /* Warna spesifik saat hover */
            .social-icon.whatsapp:hover {
                background-color: #e0fdf4;
                color: #10b981;
            }

            .social-icon.facebook:hover {
                background-color: #dbeafe;
                color: #3b82f6;
            }

            .social-icon.twitter:hover {
                background-color: #eef2ff;
                color: #1f2937;
            }

            /* Disesuaikan untuk ikon X */
            .social-icon.link:hover {
                background-color: #fef3c7;
                color: #f59e0b;
            }

            /* Hapus style Swiper */
        </style>
    @endpush
</x-landing.layout>
