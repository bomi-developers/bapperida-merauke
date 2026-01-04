@php

    $currentRoute = Route::currentRouteName();

    $bgImage = asset('assets/image6.jpg');

    $sectionBg = View::getSection('hero_bg');

    $pageHero = \App\Models\PageHero::where('route_name', $currentRoute)->first();
    $globalSetting = $websiteSettings;

    if ($sectionBg) {
        $bgImage = $sectionBg;
    } elseif ($pageHero && $pageHero->hero_bg) {
        $bgImage = asset('storage/' . $pageHero->hero_bg);
    } elseif ($globalSetting && $globalSetting->hero_bg) {
        $bgImage = asset('storage/' . $globalSetting->hero_bg);
    }
@endphp

@php
    $heroGroups = [
        [
            'routes' => ['welcome'],
            'title' => 'Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah',
            'subtitle' => 'Kabupaten Merauke - Papua Selatan',
        ],
        [
            'routes' => ['berita.public.home', 'berita.public.show'],
            'title' => 'Berita dan Informasi',
            'subtitle' => 'Update Terkini Seputar Kegiatan BAPPERIDA',
            'description' =>
                'Dapatkan informasi terbaru mengenai kegiatan, program, dan inovasi dari BAPPERIDA Kabupaten Merauke.',
        ],
        [
            'routes' => ['about.struktur-organisasi'],
            'title' => 'Struktur Organisasi',
            'subtitle' => 'Struktur Organisasi BAPPERIDA',
            'description' =>
                'Kenali struktur organisasi BAPPERIDA Kabupaten Merauke untuk memahami peran dan tanggung jawab setiap unit kerja dalam mendukung pembangunan daerah.',
        ],
        [
            'routes' => ['about.pegawai'],
            'title' => 'Daftar Pegawai',
            'subtitle' => 'Daftar Pegawai BAPPERIDA',
            'description' =>
                'Temui tim profesional di balik BAPPERIDA Kabupaten Merauke yang berdedikasi untuk perencanaan dan pembangunan daerah.',
        ],
        [
            'routes' => ['about.sejarah'],
            'title' => 'Sejarah',
            'subtitle' => 'Sejarah BAPPERIDA',
            'description' =>
                'Pelajari perjalanan sejarah BAPPERIDA Kabupaten Merauke dalam mendukung pembangunan daerah dari masa ke masa.',
        ],
        [
            'routes' => ['about.tugas-fungsi'],
            'title' => 'Tugas dan Fungsi',
            'subtitle' => 'Tugas dan Fungsi BAPPERIDA',
            'description' =>
                'Pahami tugas dan fungsi utama BAPPERIDA Kabupaten Merauke dalam perencanaan pembangunan daerah yang berkelanjutan.',
        ],
        [
            'routes' => ['about.visi-misi'],
            'title' => 'Visi dan Misi',
            'subtitle' => 'Visi dan Misi BAPPERIDA',
            'description' =>
                'Jelajahi visi dan misi BAPPERIDA Kabupaten Merauke dalam mewujudkan pembangunan daerah yang inklusif dan berkelanjutan.',
        ],
        [
            'routes' => ['riset-inovasi.riset'],
            'title' => 'Publikasi Riset',
            'subtitle' => 'Data Publikasi Riset oleh BAPPERIDA',
            'description' =>
                'Temukan berbagai publikasi riset yang dihasilkan oleh BAPPERIDA Kabupaten Merauke untuk mendukung pembangunan daerah.',
        ],
        [
            'routes' => ['riset-inovasi.inovasi'],
            'title' => 'Pengajuan Inovasi',
            'subtitle' => 'Pengajuan Proposal Inovasi ke BAPPERIDA',
            'description' =>
                'Ajukan proposal inovasi Anda kepada BAPPERIDA Kabupaten Merauke untuk mendukung pembangunan daerah yang lebih baik.',
        ],
        [
            'routes' => ['riset-inovasi.data'],
            'title' => 'Data Riset dan Inovasi',
            'subtitle' => 'Data Riset dan Inovasi oleh BAPPERIDA',
            'description' =>
                'Akses berbagai data riset dan inovasi yang dikumpulkan oleh BAPPERIDA Kabupaten Merauke untuk mendukung pembangunan daerah.',
        ],
        [
            'routes' => ['riset-inovasi.kekayaan-intelektual'],
            'title' => 'Kekayaan Intelektual',
            'subtitle' => 'Kekayaan Intelektual oleh BAPPERIDA',
            'description' =>
                'Jelajahi berbagai kekayaan intelektual yang dimiliki oleh BAPPERIDA Kabupaten Merauke dalam mendukung pembangunan daerah.',
        ],
        [
            'routes' => ['galeri.public.index', 'galeri.public.show'],
            'title' => 'Galeri',
            'subtitle' => 'Galeri oleh BAPPERIDA',
            'description' =>
                'Jelajahi berbagai koleksi galeri yang dimiliki oleh BAPPERIDA Kabupaten Merauke dalam mendukung pembangunan daerah.',
        ],
    ];

    $hero = collect($heroGroups)->first(fn($group) => in_array($currentRoute, $group['routes'])) ?? [
        'title' => 'Selamat Datang',
        'subtitle' => 'Badan Perencanaan Daerah Kabupaten Merauke',
        'description' =>
            'Bersama BAPPERIDA Kabupaten Merauke, wujudkan pembangunan berkelanjutan, inklusif, dan berbasis potensi lokal.',
    ];

    // Logika khusus untuk halaman Kategori Dokumen (Dinamis)
    if ($currentRoute === 'documents.by_category') {
        $kategoriModel = Route::current()->parameter('kategori');
        if ($kategoriModel) {
            $hero = [
                'title' => 'Dokumen',
                'subtitle' => $kategoriModel->nama_kategori,
                'description' =>
                    'Temukan semua dokumen terkait kategori ' . $kategoriModel->nama_kategori . ' di sini.',
                'is_split_title' => true,
            ];
        }
    } else {
        $foundHero = collect($heroGroups)->first(fn($group) => in_array($currentRoute, $group['routes']));
        if ($foundHero) {
            $hero = $foundHero;
        }
    }
@endphp
<section id="heading" class="relative top-0 overflow-hidden h-[1200px] [clip-path:ellipse(120%_60%_at_50%_0%)]">

    @if (Route::is('welcome'))
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden bg-blue-900">
            <div id="video-placeholder" class="absolute inset-0 bg-cover bg-center z-0 transition-opacity duration-1000"
                style="background-image: url('{{ $bgImage }}');">
            </div>

            <video id="hero-video" autoplay muted loop playsinline poster="{{ $bgImage }}"
                class="absolute w-full h-full object-cover  transition-opacity duration-1000 z-0  ">
                <source src="{{ asset('img/drone.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    @else
        <div class="absolute top-0 left-0 w-full h-full bg-cover bg-center transition-all duration-700 ease-in-out"
            style="background-image: url('{{ $bgImage }}');">
        </div>
    @endif

    <div class="absolute inset-0 bg-blue-800/50"></div>
    <div class="relative z-10 h-full flex flex-col items-center justify-start text-center px-3"
        style="padding-top: 15rem;">
        <div class="{{ Route::is('berita.public.show') ? 'hidden' : 'flex' }} mb-6 animate-float">
            <img src="{{ asset('assets/LogoKabMerauke.png') }}" alt="Logo Kabupaten Merauke" loading="lazy"
                class="h-32 md:h-42 w-auto drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">
        </div>

        <h1 class="text-white text-3xl md:text-5xl font-black max-w-4xl tracking-tighter leading-[0.9]">
            <span id="hero-title" data-text="@yield('hero_title', $hero['title'])"></span>
            <span id="cursor"
                class="inline-block w-2 h-12 md:h-12 bg-[#CCFF00] ml-1 align-middle animate-pulse"></span>
        </h1>

        <h2 id="hero-subtitle"
            class="text-white text-2xl md:text-4xl font-bold max-w-4xl mt-2 opacity-0 -translate-y-8 transition-all duration-[1000ms] cubic-bezier(0.4, 0, 0.2, 1) drop-shadow-lg">
            @yield('hero_subtitle', $hero['subtitle'])
        </h2>

        <p id="hero-desc"
            class="mt-3 text-[#CCFF00] text-lg md:text-xl max-w-2xl font-medium opacity-0 -translate-y-8 transition-all duration-[1000ms] cubic-bezier(0.4, 0, 0.2, 1) delay-200">
            @yield('hero_description', $hero['description'] ?? 'Bersama BAPPERIDA Kabupaten Merauke, wujudkan pembangunan berkelanjutan.')
        </p>
    </div>

</section>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('hero-video');
            const placeholder = document.getElementById('video-placeholder');

            if (video) {
                video.oncanplaythrough = () => {
                    video.classList.remove('opacity-0');
                    video.classList.add('opacity-100');
                    setTimeout(() => {
                        placeholder.classList.add('opacity-0');
                    }, 1000);
                };

                setTimeout(() => {
                    if (video.readyState < 3) {
                        console.log("Koneksi lambat, tetap menggunakan gambar statis.");
                    }
                }, 5000);
            }


            const el = document.getElementById('hero-title');
            const subtitle = document.getElementById('hero-subtitle');
            const desc = document.getElementById('hero-desc');
            if (!el) return;

            const text = el.dataset.text;
            const typingSpeed = 70;
            const deletingSpeed = 40;
            const holdAfterType = 10000;

            let i = 0;
            let isDeleting = false;

            function showSubContent() {
                // Animasi MASUK: Turun dari atas ke posisi normal (0)
                setTimeout(() => {
                    subtitle.classList.remove('opacity-0', '-translate-y-8');
                    subtitle.classList.add('opacity-100', 'translate-y-0');
                }, 100);

                setTimeout(() => {
                    desc.classList.remove('opacity-0', '-translate-y-8');
                    desc.classList.add('opacity-100', 'translate-y-0');
                }, 300);
            }

            function hideSubContent() {
                // Animasi KELUAR: Naik ke atas dan menghilang
                subtitle.classList.remove('opacity-100', 'translate-y-0');
                subtitle.classList.add('opacity-0', '-translate-y-8');

                desc.classList.remove('opacity-100', 'translate-y-0');
                desc.classList.add('opacity-0', '-translate-y-8');
            }

            function typeLoop() {
                const currentContent = text.substring(0, i);
                el.textContent = currentContent;

                if (!isDeleting && i < text.length) {
                    i++;
                    setTimeout(typeLoop, typingSpeed);
                } else if (!isDeleting && i === text.length) {
                    // SELESAI KETIK -> MASUK (TURUN)
                    showSubContent();
                    setTimeout(() => {
                        isDeleting = true;
                        typeLoop();
                    }, holdAfterType);
                } else if (isDeleting && i > 0) {
                    // MULAI HAPUS -> KELUAR (NAIK)
                    if (i === text.length) hideSubContent();
                    i--;
                    setTimeout(typeLoop, deletingSpeed);
                } else {
                    isDeleting = false;
                    i = 0;
                    // Reset posisi awal untuk animasi berikutnya agar bisa turun lagi
                    setTimeout(typeLoop, typingSpeed);
                }
            }

            typeLoop();
        });
    </script>
@endpush
