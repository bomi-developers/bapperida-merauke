<x-landing.layout>

    <section id="first-section" class="py-24 -mt-[500px]">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center reveal-on-scroll">
            <div class="w-full max-w-4xl mx-auto aspect-video  dark:bg-gray-800 rounded-xl overflow-hidden ">
                @php
                    $bgImage = asset('assets/LogoKabMerauke.png');

                    // Cek apakah file_hero ada, jika tidak pakai default
                    $file = $websiteSettings->file_hero ?? null;

                    if ($file) {
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        $isRemote =
                            str_contains($file, 'http') ||
                            str_contains($file, 'youtube') ||
                            str_contains($file, 'vimeo');
                        $filePath = $isRemote ? $file : asset('storage/' . $file);
                    } else {
                        $extension = 'png';
                        $isRemote = false;
                        $filePath = $bgImage;
                    }
                    function youtubeEmbed($url)
                    {
                        preg_match('%(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([^&?/]+)%', $url, $matches);
                        return isset($matches[1]) ? 'https://www.youtube.com/embed/' . $matches[1] : null;
                    }

                    $embedUrl = youtubeEmbed($file);
                @endphp

                @if ($isRemote)
                    {{-- JIKA VIDEO REMOTE (YOUTUBE/VIMEO) --}}
                    <iframe src="{{ $embedUrl }}" frameborder="0" loading="lazy"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen class="w-full h-full"></iframe>
                @elseif (in_array(strtolower($extension), ['mp4', 'webm', 'ogg']))
                    {{-- JIKA VIDEO LOKAL --}}
                    <video controls class="w-full h-full object-cover">
                        <source src="{{ $filePath }}" type="video/{{ $extension }}">
                        Your browser does not support the video tag.
                    </video>
                @else
                    {{-- JIKA GAMBAR (MENGGUNAKAN FIT/CONTAIN agar tidak terpotong) --}}
                    <div class="flex items-center justify-center w-full h-full">
                        <img src="{{ $filePath }}" loading="lazy" alt="Hero Image"
                            class="w-full h-full object-contain">
                    </div>
                @endif
            </div>
            <div>
                <h2 class="text-3xl font-bold text-[#] leading-tight text-[#004299]">
                    {{ $websiteSettings->judul_hero ?? 'Badan Perkembangan, Riset dan Inovasi Daerah Kabupaten Merauke' }}
                </h2>
                <div class="w-1/2 h-1 bg-[#CCFF00] mt-4 rounded-full"></div>
                <p class="my-4 text-gray-600">
                    {{ $websiteSettings->deskripsi_hero ?? 'Bappeda mempunyai tugas menyelenggarakan fungsi penunjang urusan pemerintahan bidang perencanaan dan menunjang urusan pemerintahan bidang penelitian dan pengembangan.' }}
                </p>
                <div class="flex  justify-start gap-3">

                    <a href="{{ route('about.visi-misi') }}"
                        class=" px-3 py-2 rounded-full hover:bg-blue-800 text-blue-800 hover:text-white border border-blue-700 mb-6 ">
                        <span class="text-sm font-bold  ">Visi & Misi</span>
                    </a>
                    <a href="{{ route('about.struktur-organisasi') }}"
                        class=" px-3 py-2 rounded-full hover:bg-blue-800 text-blue-800 hover:text-white border border-blue-700 mb-6 ">
                        <span class="text-sm font-bold  ">Struktur Organisasi</span>
                    </a>
                    <a href="{{ route('about.tugas-fungsi') }}"
                        class=" px-3 py-2 rounded-full hover:bg-blue-800 text-blue-800 hover:text-white border border-blue-700 mb-6 ">
                        <span class="text-sm font-bold  ">Tugas & Fungsi</span>
                    </a>

                </div>
            </div>
        </div>
    </section>
    {{-- bidang --}}
    <section x-data="{
        openModal: false,
        selectedBidang: '',
        idBidang: null,
        storageUrl: '{{ asset('storage/foto_pegawai') }}/',
        pegawai: {{ $pegawai->toJson() }},
        avatarUrl: 'https://ui-avatars.com/api/?background=004299&color=fff&bold=true&name='
    }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-50 rounded-full blur-3xl opacity-50 -z-10"></div>

            <div class="reveal-on-scroll">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-black text-[#004299] tracking-tight">
                        Bidang <span id="typewriter-text" class="text-blue-600"></span><span id="cursor-bidang"
                            class="animate-pulse text-[#CCFF00]">|</span>
                    </h2>
                    <div class="w-24 h-1.5 bg-[#CCFF00] mx-auto mt-4 rounded-full shadow-sm mb-2"></div>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-3">
                    @foreach ($Bidang as $item)
                        <button
                            @click="openModal = true; selectedBidang = '{{ $item->nama_bidang }}'; idBidang = {{ $item->id }}"
                            class="group relative bg-white rounded-xl p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 hover:-translate-y-2 text-left focus:outline-none overflow-hidden">

                            <div
                                class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 scale-0 group-hover:scale-150">
                            </div>

                            <div class="relative z-10">
                                <div
                                    class="inline-flex items-center justify-center h-16 w-16 bg-blue-100 rounded-2xl text-blue-600 mb-6 hover:bg-blue-800 hover:text-white transition-all duration-300 shadow-inner">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>

                                <h3
                                    class="text-xl font-bold text-gray-800 group-hover:text-[#004299] transition-colors duration-300 line-clamp-2">
                                    {{ $item->nama_bidang }}
                                </h3>

                                <div
                                    class="w-8 h-1 bg-[#CCFF00] my-4 rounded-full group-hover:w-16 transition-all duration-500">
                                </div>

                                <p
                                    class="text-gray-500 text-sm leading-relaxed line-clamp-3 group-hover:text-gray-600 transition-colors duration-300">
                                    {{ $item->deskripsi ?? 'Optimalisasi perencanaan pembangunan melalui koordinasi dan riset strategis berkelanjutan.' }}
                                </p>

                                <div
                                    class="mt-6 flex items-center text-blue-600 font-bold text-xs opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-[-10px] group-hover:translate-x-0">
                                    LIHAT DETAIL
                                    <svg class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>

            </div>
        </div>
        <template x-teleport="body">
            <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">

                <div class="fixed inset-0 bg-black/20 backdrop-blur-sm" @click="openModal = false"></div>

                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div class="bg-white w-full max-w-5xl rounded-3xl overflow-hidden">

                        <div class="py-2 px-5 flex justify-between items-center ">
                            <div class="flex gap-x-4">
                                <div
                                    class="flex items-center justify-center h-12 w-12 mx-auto bg-blue-50 rounded-full transition-all duration-300 group-hover:bg-blue-100 group-hover:scale-110">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-[#004299]" x-text="selectedBidang"></h3>
                                    <p class="text-sm text-gray-500">Daftar Pegawai BAPPERIDA Merauke</p>
                                </div>
                            </div>
                            <button @click="openModal = false"
                                class="text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="p-8 max-h-[75vh] overflow-y-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <template x-for="person in pegawai.filter(p => p.id_bidang == idBidang)"
                                    :key="person.id">
                                    <div
                                        class="group/card relative bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-[#CCFF00] transform hover:-translate-y-2">

                                        <div class="h-2 bg-gradient-to-r from-[#004299] via-blue-500 to-[#CCFF00]">
                                        </div>

                                        <div class="p-6">
                                            <div class="relative mb-4 mx-auto w-32 h-32">
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-br from-[#004299] to-blue-500 rounded-full opacity-20 group-hover/card:opacity-30 transition-opacity">
                                                </div>
                                                <img loading="lazy"
                                                    x-bind:src="person.foto ? (storageUrl + person.foto) : (avatarUrl +
                                                        encodeURIComponent(person.nama))"
                                                    x-bind:alt="person.nama"
                                                    class="relative w-full h-full rounded-full object-cover border-4 border-white shadow-lg group-hover/card:border-[#CCFF00] transition-all duration-300"
                                                    x-on:error="$el.src = avatarUrl + encodeURIComponent(person.nama)">
                                                <div
                                                    class="absolute
                                                    -bottom-2 -right-2 bg-green-500 rounded-full p-2 shadow-lg border-2
                                                    border-white">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="text-center mb-3">
                                                <h4 class="text-lg font-bold text-gray-900 group-hover/card:text-[#004299] transition-colors line-clamp-2 min-h-[3.5rem]"
                                                    x-text="person.nama"></h4>
                                            </div>

                                            <div
                                                class="flex items-center justify-center space-x-2 bg-blue-50 rounded-lg py-2 px-3 border border-blue-100">
                                                <svg class="w-4 h-4 text-[#004299]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                </svg>
                                                <span class="text-xs font-semibold text-gray-700"
                                                    x-text="person.nip ?? '-'"></span>
                                            </div>

                                            <template x-if="person.jabatan">
                                                <div class="mt-3 text-center">
                                                    <span
                                                        class="inline-block px-3 py-1 bg-[#CCFF00]/20 text-[#004299] text-xs font-medium rounded-full border border-[#CCFF00]"
                                                        x-text="person.jabatan.jabatan"></span>
                                                </div>
                                            </template>
                                        </div>

                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-[#004299]/5 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity pointer-events-none">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <template x-if="pegawai.filter(p => p.id_bidang == idBidang).length === 0">
                                <div class="text-center py-20">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p class="mt-4 text-gray-400 font-medium">Belum ada data pegawai di bidang ini.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </section>
    <section class="max-w-7xl relative mx-auto px-4 sm:px-6 py-12 reveal-on-scroll">
        <div
            class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-red-700 via-red-800 to-whhite px-4 py-8 md:px-10 md:py-20 shadow-[0_20px_50px_rgba(153,27,27,0.4)]">

            <div
                class="absolute right-[-5%] top-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-red-500/20 rounded-full blur-[120px] pointer-events-none">
            </div>

            <div class="relative z-10 max-w-4xl">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 mb-6 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#CCFF00] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#CCFF00]"></span>
                    </span>
                    <span class="text-xs font-bold text-white tracking-widest uppercase italic">BAPPERIDA
                        Merauke</span>
                </div>

                <h3 class="mb-4 text-xl font-medium text-red-100 md:text-2xl italic font-serif">
                    "Ubah gagasan menjadi perubahan nyata"
                </h3>

                <div class="space-y-4">
                    <h2 class="text-5xl font-black tracking-tighter text-white md:text-7xl leading-[1.1]">
                        Wujudkan Ide dan<br class="hidden md:block"> Inovasi Anda
                    </h2>

                    <div class="flex flex-col items-start gap-6 pt-4 md:flex-row md:items-center">
                        <p
                            class="max-w-md text-base leading-relaxed text-red-50/80 md:text-lg border-l-4 border-[#CCFF00] pl-4">
                            Daftarkan proposal inovasi Anda sekarang. Kami siap membantu memperkuat dampak positif bagi
                            pembangunan daerah.
                        </p>

                        <a href="{{ route('riset-inovasi.inovasi') }}"
                            class="group flex items-center gap-3 rounded-2xl bg-[#CCFF00] px-10 py-4 text-sm font-black text-[#7f1d1d] transition-all hover:bg-white hover:shadow-[0_0_30px_rgba(204,255,0,0.5)] hover:scale-105 active:scale-95">
                            <i
                                class="bi bi-rocket-takeoff-fill text-2xl transition-transform group-hover:-translate-y-1"></i>
                            DAFTAR SEKARANG
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="absolute right-[-5%] top-1/2 -translate-y-1/2 hidden lg:block w-[450px] pointer-events-none select-none animate-float-slow opacity-30 mix-blend-screen">
                <img src="{{ asset('assets/LogoKabMerauke.png') }}" alt="Watermark Logo" loading="lazy"
                    class="w-full h-auto transform rotate-[-12deg] drop-shadow-[0_0_30px_rgba(0,0,0,0.5)]">
            </div>
        </div>
    </section>



    {{-- dokumen --}}
    <section class=" py-24 ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center  z-10">
            <div class="reveal-on-scroll">
                <h2 class="text-4xl md:text-5xl font-black text-[#004299] tracking-tight">Dokumen BAPPERIDA Publik</h2>
                <div class="w-24 h-1 bg-[#CCFF00] mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="mt-16 flex flex-wrap justify-center gap-8">
                @forelse ($dokumen as $item)
                    <a href="/dokumen/kategori/{{ $item->id }}/{{ Str::slug($item->nama_kategori) }}"
                        class="bg-white p-8 rounded-2xl shadow-lg  hover:shadow-xl hover:-translate-y-1 transition-all duration-300 w-full max-w-sm">
                        <div class="flex items-center justify-between">
                            <div
                                class="flex justify-center items-center h-16 w-16 bg-blue-50 rounded-full transition-all duration-300 group-hover:bg-blue-100 ">

                                <svg class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="bg-[#CCFF00] text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
                                Selengkapnya
                            </span>
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
                            <span class="bg-[#CCFF00] text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
                                Selengkapnya
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mt-6 text-left">Kategori Dokumen Tidak Ditemukan
                        </h3>
                        <p class="mt-2 text-gray-500 text-left text-sm">Kategory Dokumen Tidak Ditemukan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- berita --}}
    @if ($beritaTerpopuler->isNotEmpty())
        <section class="py-24 bg-gray-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Header Section --}}
                <div class="text-left mb-12 md:flex  justify-between items-center">
                    <div class="mb-3">
                        <h2 class="text-4xl md:text-5xl font-black text-[#004299] tracking-tight">Berita Populer</h2>
                        <div class="w-32 h-1.5 bg-[#CCFF00]  mt-5 rounded-full shadow-sm"></div>
                        <p class="text-gray-500 mt-3 text-sm md:text-base">Ikuti perkembangan informasi terkini dan
                            paling
                            banyak dibaca.</p>
                    </div>
                    <div class="">
                        <a href="{{ route('berita.public.home') }}"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-[#004299] text-white font-bold rounded-full hover:bg-blue-800 transition-all hover:shadow-lg hover:gap-4">
                            Lihat semua berita terbaru
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                    {{-- KIRI: BERITA UTAMA (BIG FEATURE) --}}
                    @if ($beritaUtama = $beritaTerpopuler->first())
                        <div
                            class="relative group cursor-pointer overflow-hidden rounded-xl shadow-2xl reveal-on-scroll">
                            <a href="{{ route('berita.public.show', $beritaUtama) }}"
                                class="block relative h-[500px] md:h-[650px]">
                                {{-- Image dengan Overlay Gelap --}}
                                <img src="{{ $beritaUtama->cover_image ? asset('storage/' . $beritaUtama->cover_image) : 'https://placehold.co/800x1000' }}"
                                    loading="lazy"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-[#004299] via-[#004299]/40 to-transparent">
                                </div>

                                {{-- Floating Badge --}}
                                <div class="absolute top-6 left-6 flex gap-2">
                                    <span
                                        class="bg-[#CCFF00] text-[#004299] px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest shadow-lg">
                                        Headline
                                    </span>
                                </div>

                                {{-- Content Overlay --}}
                                <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full">

                                    <h2
                                        class="text-3xl md:text-3xl font-black text-white leading-[1.1] mb-6 group-hover:text-[#CCFF00] transition-colors">
                                        {{ Str::limit($beritaUtama->title, 100) }}
                                    </h2>
                                    <p class="text-white text-lg line-clamp-2 mb-8 max-w-2xl">
                                        {{ Str::limit($beritaUtama->excerpt, 160) }}
                                    </p>
                                    <div
                                        class="inline-flex items-center gap-3 text-[#CCFF00] font-bold text-sm uppercase tracking-widest">
                                        Baca Berita <i class="bi bi-arrow-right text-xl"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    {{-- KANAN: DAFTAR BERITA POPULER --}}
                    <div class="space-y-8">
                        @foreach ($beritaTerpopuler->skip(1)->take(4) as $populer)
                            <div class="group flex gap-5 items-start transition-all duration-300">

                                {{-- Thumbnail dengan Nomor/Index --}}
                                <a href="{{ route('berita.public.show', $populer) }}"
                                    class="relative w-28 h-28 sm:w-36 sm:h-36 flex-shrink-0 rounded-xl overflow-hidden shadow-lg border-2 border-transparent group-hover:border-[#CCFF00] transition-all">
                                    <img src="{{ $populer->cover_image ? asset('storage/' . $populer->cover_image) : 'https://placehold.co/400x400' }}"
                                        alt="{{ $populer->title }}" loading="lazy"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:rotate-3 group-hover:scale-110">
                                    <div
                                        class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors">
                                    </div>
                                </a>

                                {{-- Content --}}
                                <div class="flex-1 py-1">
                                    <div
                                        class="flex items-center text-[10px] font-bold text-blue-500 uppercase tracking-[0.2em] mb-2">
                                        <i class="bi bi-lightning-fill mr-1 text-[#CCFF00]"></i> Trending
                                    </div>

                                    <h4
                                        class="text-lg font-bold text-gray-800 leading-snug group-hover:text-[#004299] transition-colors mb-2 line-clamp-2">
                                        <a href="{{ route('berita.public.show', $populer) }}">
                                            {{ $populer->title }}
                                        </a>
                                    </h4>

                                    <div class="flex items-center gap-4 text-xs text-gray-400">
                                        <span class="flex items-center gap-1"><i class="bi bi-clock"></i>
                                            {{ $populer->created_at->diffForHumans() }}</span>
                                        <span
                                            class="flex items-center gap-1 font-bold text-[#004299] group-hover:translate-x-1 transition-transform">
                                            Selengkapnya <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if (!$loop->last)
                                <div class="h-[1px] w-full bg-gray-100"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>
        </section>
    @endif
    <section class="py-24 overflow-hidden relative bg-blue-800 ">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 reveal-on-scroll">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight">Galeri Kegiatan</h2>
                <div class="w-24 h-1.5 bg-[#CCFF00] mx-auto my-6 rounded-full shadow-sm"></div>
                <p class="text-gray-200 mt-4 max-w-2xl mx-auto">Dokumentasi momen krusial dalam perencanaan dan
                    pembangunan daerah Kabupaten Merauke.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 py-20 px-4 overflow-hidden"
                id="gallery-container">
                @forelse ($Galeri as $index => $item)
                    @php
                        // Menghitung urutan kolom (1, 2, atau 3) untuk memicu animasi GSAP
                        // $index dimulai dari 0, jadi kita gunakan ($index + 1)
                        $pos = ($index + 1) % 3;
                        $col = $pos == 1 ? 'col-kiri' : ($pos == 0 ? 'col-kanan' : 'col-tengah');
                    @endphp

                    <div class="gallery-card {{ $col }} opacity-0">
                        <div class="group relative rounded-[2.5rem] overflow-hidden bg-white shadow-2xl aspect-[4/3]">
                            {{-- Foto Galeri --}}
                            <img src="{{ $item->file_path ? asset('storage/' . $item->file_path) : 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1470' }}"
                                alt="{{ $item->caption }}" loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                            {{-- Overlay Gradien --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-black/10 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-500">
                            </div>

                            {{-- Konten Teks --}}
                            <div
                                class="absolute bottom-0 left-0 w-full p-8 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                                <span
                                    class="text-[#CCFF00] text-xs font-black uppercase tracking-[0.2em] mb-3 block opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                                    {{ $item->caption ? $item->caption : 'BAPPERIDA Merauke' }}
                                </span>
                                <h3 class="text-white text-xl font-bold leading-tight drop-shadow-lg">
                                    {{ $item->galeri?->judul }}
                                </h3>

                                @if ($item->galeri)
                                    <p
                                        class="text-gray-300 text-sm mt-3 line-clamp-2 opacity-0 group-hover:opacity-100 transition-opacity duration-700 delay-100">
                                        {{ $item->galeri->keterangan }}
                                    </p>
                                @endif
                            </div>

                            {{-- Aksesoris Border saat Hover --}}
                            <div
                                class="absolute inset-0 border-[12px] border-white/10 rounded-[2.5rem] pointer-events-none">
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika galeri kosong --}}
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                            <i class="bi bi-images text-3xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada foto dalam galeri kegiatan.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-16">
                <a href="{{ route('galeri.public.index') }}"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-800 font-bold rounded-full  transition-all hover:shadow-[0_0_30px_rgba(204,255,0,0.5)] hover:scale-105 active:scale-95 hover:gap-4">
                    Lihat Semua Galeri
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    {{-- section tambahan dari database --}}
    @foreach ($LendingPage as $item)
        {!! Blade::render($item->template->content, [
            'dokumen' => $dokumen ?? [],
            'Str' => new \Illuminate\Support\Str(),
        ]) !!}
    @endforeach


    <section class="py-24 overflow-hidden relative bg-white">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-50 rounded-full blur-3xl -z-10 opacity-50"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                {{-- KOLOM KIRI: STATISTIK --}}
                <div class="reveal-on-scroll mb-3">
                    <div class="mb-10 text-left">
                        <h2 class="text-4xl md:text-5xl font-black text-[#004299] tracking-tight">Statistik Pengunjung
                        </h2>
                        <div class="w-20 h-1.5 bg-[#CCFF00] mt-4 rounded-full"></div>
                        <p class="text-gray-500 mt-6 max-w-md italic">Data real-time interaksi masyarakat dengan
                            platform informasi BAPPERIDA Kabupaten Merauke.</p>
                    </div>

                    <div class="space-y-8">
                        <div
                            class="group flex items-center p-6 bg-blue-800 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-[0_0_30px_rgba(204,255,0,0.5)] hover:scale-105 active:scale-95 transition-all duration-300">
                            <div
                                class="flex-shrink-0 w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-blue-800 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="bi bi-people-fill text-3xl"></i>
                            </div>
                            <div class="ml-6">
                                <p class="text-4xl font-black text-white leading-none mb-1">
                                    {{ number_format($pageView) }}</p>
                                <p class="text-sm font-bold text-gray-200 uppercase tracking-widest">Total Pengunjung
                                </p>
                            </div>
                        </div>

                        <div
                            class="group flex items-center p-6 bg-blue-800 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:scale-105 active:scale-95 transition-all duration-300">
                            <div
                                class="flex-shrink-0 w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-blue-800 shadow-lg group-hover:scale-110 transition-transform">
                                <i class="bi bi-person-check-fill text-3xl"></i>
                            </div>
                            <div class="ml-6">
                                <p class="text-4xl font-black text-white leading-none mb-1">
                                    {{ number_format($pageViewToday) }}</p>
                                <p class="text-sm font-bold text-gray-200 uppercase tracking-widest">Pengunjung Hari
                                    Ini</p>
                            </div>
                        </div>

                        <div
                            class="group flex items-center p-6 bg-blue-800 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-300 hover:scale-105 active:scale-95 transition-all duration-300">
                            <div
                                class="flex-shrink-0 w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-blue-800 shadow-lg group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                            <div class="ml-6">
                                <p class="text-4xl font-black text-white leading-none mb-1">
                                    {{ number_format($pageViewUrl) }}</p>
                                <p class="text-sm font-bold text-gray-200 uppercase tracking-widest">Halaman Diakses
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: ANIMASI/GAMBAR --}}
                <div class="hidden relative md:flex justify-center lg:justify-end reveal-on-scroll ">
                    <div class="relative z-10 animate-float">
                        <img src="{{ asset('img/data2.svg') }}" loading="lazy" alt="Data Analysis Illustration"
                            class="max-w-full h-auto drop-shadow-2xl">
                    </div>

                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-72 h-72 bg-[#CCFF00]/20 rounded-full blur-3xl animate-pulse"></div>
                    </div>


                    <div
                        class="absolute top-1/2 right-10 bg-white p-4 rounded-2xl shadow-2xl animate-bounce-slow z-20 hidden md:block border border-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                            <span class="text-xs font-bold text-gray-600 tracking-tighter uppercase">Proposal
                                Inovasi</span>
                        </div>

                    </div>
                    <div
                        class="absolute top-2/4 left-20 bg-white p-4 rounded-2xl shadow-2xl animate-bounce-slow z-20 hidden md:block border border-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                            <span class="text-xs font-bold text-gray-600 tracking-tighter uppercase">Dokumen
                                Publik</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                gsap.registerPlugin(ScrollTrigger);

                // Animasi untuk Kolom Kiri (Muncul dari kiri)
                gsap.utils.toArray('.col-kiri').forEach(card => {
                    gsap.fromTo(card, {
                        x: -200,
                        opacity: 0,
                        rotate: -10,
                        scale: 0.8
                    }, {
                        x: 0,
                        opacity: 1,
                        rotate: 0,
                        scale: 1,
                        scrollTrigger: {
                            trigger: card,
                            start: "top bottom", // mulai saat bagian atas card menyentuh bawah layar
                            end: "top center", // selesai saat bagian atas card sampai di tengah layar
                            scrub: 1, // PENTING: membuat animasi mengikuti scroll (nilai 1 membuatnya smooth)
                        }
                    });
                });

                // Animasi untuk Kolom Kanan (Muncul dari kanan)
                gsap.utils.toArray('.col-kanan').forEach(card => {
                    gsap.fromTo(card, {
                        x: 200,
                        opacity: 0,
                        rotate: 10,
                        scale: 0.8
                    }, {
                        x: 0,
                        opacity: 1,
                        rotate: 0,
                        scale: 1,
                        scrollTrigger: {
                            trigger: card,
                            start: "top bottom",
                            end: "top center",
                            scrub: 1,
                        }
                    });
                });

                // Animasi untuk Kolom Tengah (Muncul dari bawah)
                gsap.utils.toArray('.col-tengah').forEach(card => {
                    gsap.fromTo(card, {
                        y: 150,
                        opacity: 0,
                        scale: 0.9
                    }, {
                        y: 0,
                        opacity: 1,
                        scale: 1,
                        scrollTrigger: {
                            trigger: card,
                            start: "top bottom",
                            end: "top center",
                            scrub: 1,
                        }
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const textElement = document.getElementById('typewriter-text');
                const phrases = ["Strategis BAPPERIDA", "BAPPERIDA Merauke"];
                let phraseIndex = 0;
                let charIndex = 0;
                let isDeleting = false;
                let typeSpeed = 150;

                function type() {
                    const currentPhrase = phrases[phraseIndex];

                    if (isDeleting) {
                        // Menghapus teks
                        textElement.textContent = currentPhrase.substring(0, charIndex - 1);
                        charIndex--;
                        typeSpeed = 100;
                    } else {
                        // Mengetik teks
                        textElement.textContent = currentPhrase.substring(0, charIndex + 1);
                        charIndex++;
                        typeSpeed = 200;
                    }

                    // Logika perpindahan status
                    if (!isDeleting && charIndex === currentPhrase.length) {
                        isDeleting = true;
                        typeSpeed = 2000; // Jeda saat kata sudah lengkap
                    } else if (isDeleting && charIndex === 0) {
                        isDeleting = false;
                        phraseIndex = (phraseIndex + 1) % phrases.length;
                        typeSpeed = 500;
                    }

                    setTimeout(type, typeSpeed);
                }

                type();
            });
        </script>
    @endpush


    @push('styles')
        <style>
            /* Definisikan Timeline Scroll */
            @keyframes reveal-entry {
                from {
                    opacity: 0;
                    filter: blur(10px);
                }

                to {
                    opacity: 1;
                    filter: blur(0);
                    transform: translate(0, 0) rotate(0deg) scale(1);
                }
            }

            .scroll-animate {
                /* Animasi mengikuti progress scroll */
                view-timeline-name: --item-reveal;
                view-timeline-axis: block;

                animation-linear-direction: alternate;
                animation-name: reveal-entry;
                animation-fill-mode: both;

                /* Animasi berjalan mulai dari elemen muncul 10% sampai 40% di layar */
                animation-timeline: --item-reveal;
                animation-range: entry 10% cover 40%;
            }

            /* Posisi Awal Berdasarkan Arah */
            .reveal-left {
                transform: translateX(-150px) rotate(-10deg) scale(0.8);
            }

            .reveal-right {
                transform: translateX(150px) rotate(10deg) scale(0.8);
            }

            .reveal-bottom {
                transform: translateY(150px) scale(0.8);
            }

            /* Fallback untuk Browser Lama (Safari/Firefox) */
            @supports not (animation-timeline: view()) {
                .scroll-animate {
                    opacity: 1;
                    transform: none;
                    filter: none;
                }
            }
        </style>
        <style>
            @keyframes float-slow {

                0%,
                100% {
                    transform: translate(0, -50%) rotate(-12deg);
                }

                50% {
                    transform: translate(-15px, -55%) rotate(-8deg);
                }
            }

            .animate-float-slow {
                animation: float-slow 6s ease-in-out infinite;
            }
        </style>
        <style>
            @keyframes float {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-20px);
                }
            }

            @keyframes bounce-slow {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-10px);
                }
            }

            .animate-float {
                animation: float 5s ease-in-out infinite;
            }

            .animate-bounce-slow {
                animation: bounce-slow 3s ease-in-out infinite;
            }
        </style>
    @endpush
</x-landing.layout>
