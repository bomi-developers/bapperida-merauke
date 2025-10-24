<x-landing.layout>
    <section class="py-16 px-4 md:px-8 lg:px-16 -mt-[400px]">
        <div class="max-w-4xl mx-auto">
            <!-- Article Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-blue-800 leading-tight mb-4">
                    {{ $berita->title }}
                </h1>
                <p class="text-gray-600 text-sm mb-2">{{ $berita->author->name ?? 'Admin' }} - Bapperida News</p>
                <p class="text-gray-500 text-sm">{{ $berita->created_at->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>

            @php
                // Mengumpulkan semua gambar: cover image + semua item bertipe 'image'
                $images = collect();
                if ($berita->cover_image) {
                    $images->push(['url' => $berita->cover_image, 'caption' => '']);
                }
                foreach ($berita->items->where('type', 'image') as $item) {
                    // Asumsi konten berisi path gambar dan caption dipisah oleh '||'
                    $parts = explode('||', $item->content, 2);
                    $images->push(['url' => $parts[0], 'caption' => $parts[1] ?? '']);
                }
            @endphp

            @if ($images->isNotEmpty())
                <div class="mb-8 relative w-full max-w-4xl mx-auto" id="image-slider">
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
                                        <p class="text-gray-500 text-sm mt-2 text-center">{{ $image['caption'] }}</p>
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
            <div class="prose prose-lg max-w-none">
                {{-- Container untuk mengatur tata letak dua embed video secara berdampingan dan di tengah --}}
                <div class="flex flex-wrap justify-center gap-8 my-6">
                    @foreach ($berita->items as $item)
                        @switch($item->type)
                            @case('text')
                                {{-- Tutup div flex untuk video sebelum menampilkan teks, lalu buka lagi setelahnya jika ada video lagi --}}
                        </div>
                        <p class="text-gray-700 leading-relaxed my-6 text-justify">{!! $item->content !!}</p>
                        <div class="flex flex-wrap justify-center gap-8 my-4">
                        @break

                        @case('quote')
                            {{-- TAMPILAN BARU UNTUK KUTIPAN --}}
                            <blockquote class="border-l-4 border-blue-500 pl-6 my-8">
                                <p class="text-xl italic text-gray-700 font-serif leading-relaxed">
                                    "{{ $item->content }}"
                                </p>
                            </blockquote>
                        @break

                        @case('video')
                            @php
                                // Fungsi sederhana untuk mengubah URL YouTube biasa menjadi URL embed
                                $embedUrl = $item->content;
                                if (str_contains($embedUrl, 'youtube.com/watch?v=')) {
                                    $videoId = substr($embedUrl, strpos($embedUrl, 'v=') + 2);
                                    $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                                } elseif (str_contains($embedUrl, 'youtu.be/')) {
                                    $videoId = substr($embedUrl, strpos($embedUrl, 'youtu.be/') + 9);
                                    $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                                }
                            @endphp
                            {{-- Setiap embed video akan menjadi item 'card' dengan ukuran tetap, berdampingan --}}
                            <div
                                class="w-full sm:w-96 md:w-[900px] lg:w-[950px] aspect-video rounded-lg shadow-md overflow-hidden">
                                <iframe src="{{ $embedUrl }}" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen class="w-full h-full"></iframe>
                            </div>
                        @break

                        @case('embed')
                            {{-- Tutup div flex untuk video sebelum menampilkan embed kustom, lalu buka lagi setelahnya jika ada video lagi --}}
                        </div>
                        <div class="my-8 w-full flex justify-center"> {{-- Menambahkan flex justify-center untuk menengahkan embed kustom --}}
                            <div class="max-w-sm w-full"> {{-- Menambahkan max-w untuk kontrol lebar embed kustom --}}
                                {!! $item->content !!}
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-center gap-8 my-6">
                        @break

                        @case('image')
                            <figure class="my-8">
                                <img src="{{ asset('storage/' . $item->content) }}"
                                    alt="{{ $item->caption ?: $berita->title }}"
                                    class="w-full max-w-3xl mx-auto rounded-lg shadow-md object-contain" style="height:25rem">
                                @if (!empty($item->caption))
                                    <figcaption class="text-center text-sm text-gray-500 mt-2">{{ $item->caption }}
                                    </figcaption>
                                @endif
                            </figure>
                        @break

                        {{-- Tipe 'image' sudah ditampilkan di carousel, jadi kita abaikan di sini --}}
                    @endswitch
                    @endforeach
                </div> {{-- Penutup div flex untuk video --}}
            </div>

            <!-- Social Share -->
            <div class="border-t pt-6 mt-8">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">Bagikan :</span>

                    @php
                        // Siapkan URL dan Teks sekali saja agar lebih rapi
                        $shareUrl = route('berita.public.show', $berita);
                        $shareText = urlencode($berita->title);
                        $shareTextWithUrl = urlencode($berita->title . ' ' . $shareUrl);
                    @endphp

                    <div class="flex items-center gap-3">
                        <a href="https://api.whatsapp.com/send?text={{ $shareTextWithUrl }}" target="_blank"
                            class="social-icon whatsapp" aria-label="Bagikan ke WhatsApp" title="Bagikan ke WhatsApp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.894 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.886-.001 2.269.655 4.357 1.846 6.069l-1.25 4.562 4.649-1.219zm7.54-6.239c-.196-.099-1.157-.577-1.338-.644-.181-.065-.315-.099-.449.099-.133.197-.505.644-.619.77-.114.126-.228.143-.424.048-.196-.099-1.043-.383-1.986-1.223-.733-.655-1.229-1.47-1.381-1.721-.152-.251-.016-.387.083-.486.092-.091.205-.232.308-.346.102-.114.133-.197.198-.33.065-.134.034-.248-.016-.347-.05-.099-.449-1.076-.616-1.47-.164-.389-.328-.335-.449-.34-.114-.007-.247-.007-.38-.007-.133 0-.347.049-.529.247-.182.198-.691.677-.691 1.654s.71 1.915.81 2.049c.098.134 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.157-.473 1.328-.925.17-.452.17-.835.119-.925-.05-.092-.182-.144-.378-.243z" />
                            </svg>
                        </a>

                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"
                            class="social-icon facebook" aria-label="Bagikan ke Facebook" title="Bagikan ke Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v2.385z" />
                            </svg>
                        </a>

                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}"
                            target="_blank" class="social-icon twitter" aria-label="Bagikan ke X" title="Bagikan ke X">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>

                        <button onclick="copyToClipboard(this, '{{ $shareUrl }}')" class="social-icon instagram"
                            aria-label="Salin tautan untuk Instagram" title="Salin tautan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919-4.919C8.416 2.175 8.796 2.163 12 2.163m0-2.163C8.74 0 8.333.011 7.053.069 2.59.285.285 2.59.069 7.053.011 8.333 0 8.74 0 12s.011 3.667.069 4.947c.216 4.463 2.522 6.769 7.053 6.984 1.28.058 1.687.069 4.878.069s3.598-.011 4.878-.069c4.531-.216 6.837-2.522 7.053-7.053.058-1.28.069-1.687.069-4.878s-.011-3.598-.069-4.878C21.715 2.59 19.41.285 14.947.069 13.667.011 13.26 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- "Baca Juga" Section -->
        @if ($beritaTerkait->isNotEmpty())
            <div class="max-w-7xl mx-auto mt-16 pt-12 border-t">
                <h2 class="text-center text-2xl md:text-3xl lg:text-4xl font-bold text-blue-800 leading-tight mb-10">
                    Baca Juga Berita Lainnya</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($beritaTerkait as $related)
                        <div
                            class="bg-white rounded-2xl shadow-lg flex flex-col transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20">
                            <a href="{{ route('berita.public.show', $related) }}">
                                <img src="{{ $related->cover_image ? asset('storage/' . $related->cover_image) : 'https://placehold.co/600x400/e2e8f0/667eea?text=Berita' }}"
                                    alt="Cover Berita {{ $related->title }}"
                                    class="w-full h-48 object-cover rounded-t-2xl">
                            </a>
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-blue-800 mb-2 leading-tight">
                                    <a href="{{ route('berita.public.show', $related) }}" class="hover:underline">
                                        {{ Str::limit($related->title, 50) }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-500 mb-3">{{ $related->created_at->diffForHumans() }}</p>
                                <p class="text-sm text-gray-700 mb-4 leading-relaxed flex-grow">
                                    {{ Str::limit($related->excerpt, 100) }}
                                </p>
                                <a href="{{ route('berita.public.show', $related) }}"
                                    class="text-blue-600 hover:underline text-sm font-semibold mt-auto">
                                    Baca Selengkapnya &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.querySelector('#image-slider');
                if (!slider) return;

                const slidesContainer = slider.querySelector('.slides');
                const slides = slider.querySelectorAll('.slide');
                const prevBtn = slider.querySelector('#prevBtn');
                const nextBtn = slider.querySelector('#nextBtn');
                const paginationDots = slider.querySelector('.pagination-dots');

                if (slides.length <= 1) return;

                let currentIndex = 0;
                const totalSlides = slides.length;

                // Create pagination dots
                for (let i = 0; i < totalSlides; i++) {
                    const dot = document.createElement('button');
                    dot.classList.add('dot', 'w-3', 'h-3', 'rounded-full', 'bg-gray-300', 'hover:bg-gray-500',
                        'transition-colors');
                    if (i === 0) dot.classList.add('bg-blue-600');
                    dot.addEventListener('click', () => goToSlide(i));
                    paginationDots.appendChild(dot);
                }
                const dots = paginationDots.querySelectorAll('.dot');

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

                nextBtn.addEventListener('click', () => {
                    const nextIndex = (currentIndex + 1) % totalSlides;
                    goToSlide(nextIndex);
                });

                prevBtn.addEventListener('click', () => {
                    const prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                    goToSlide(prevIndex);
                });

                // Auto slide
                setInterval(() => {
                    const nextIndex = (currentIndex + 1) % totalSlides;
                    goToSlide(nextIndex);
                }, 5000); // Ganti slide setiap 5 detik
            });

            function copyToClipboard(button, textToCopy) {
                // Gunakan navigator.clipboard API modern jika tersedia
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(textToCopy).then(function() {
                        // Beri feedback visual ke pengguna
                        const originalContent = button.innerHTML;
                        button.innerHTML = 'Disalin!';
                        button.disabled = true;

                        setTimeout(() => {
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }, 2000); // Kembalikan ke semula setelah 2 detik
                    }).catch(function(err) {
                        console.error('Gagal menyalin: ', err);
                        // Fallback jika gagal
                        fallbackCopyTextToClipboard(button, textToCopy);
                    });
                } else {
                    // Fallback untuk browser lama
                    fallbackCopyTextToClipboard(button, textToCopy);
                }
            }

            // Fungsi fallback jika navigator.clipboard tidak didukung
            function fallbackCopyTextToClipboard(button, text) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed"; // Hindari scroll saat fokus
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    // Beri feedback visual
                    const originalContent = button.innerHTML;
                    button.innerHTML = 'Disalin!';
                    button.disabled = true;

                    setTimeout(() => {
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }, 2000);
                } catch (err) {
                    console.error('Fallback: Gagal menyalin', err);
                }
                document.body.removeChild(textArea);
            }
        </script>
    @endpush
</x-landing.layout>
