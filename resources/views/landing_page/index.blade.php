<x-landing.layout>

    <section id="first-section" class="py-24 -mt-[500px]">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center reveal-on-scroll">
            <div class="w-full max-w-4xl mx-auto aspect-video  dark:bg-gray-800 rounded-xl overflow-hidden ">
                @php
                    $bgImage = asset('assets/logoKabMerauke.png');

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
                @endphp

                @if ($isRemote)
                    {{-- JIKA VIDEO REMOTE (YOUTUBE/VIMEO) --}}
                    <iframe src="{{ $filePath }}" frameborder="0" loading="lazy"
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
                        <img src="{{ $filePath }}" alt="Hero Image" class="w-full h-full object-contain">
                    </div>
                @endif
            </div>
            <div>
                <h2 class="text-3xl font-bold text-[#] leading-tight text-[#004299]">
                    {{ $websiteSettings->judul_hero ?? 'Badan Perkembangan, Riset dan Inovasi Daerah Kabupaten Merauke' }}
                </h2>
                <p class="mt-4 text-gray-600">
                    {{ $websiteSettings->deskripsi_hero ?? 'Bappeda mempunyai tugas menyelenggarakan fungsi penunjang urusan pemerintahan bidang perencanaan dan menunjang urusan pemerintahan bidang penelitian dan pengembangan.' }}
                </p>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="bg-white reveal-on-scroll">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-[#004299]">Bidang Strategis</h2>
                    <div class="w-24 h-1 bg-[#CCFF00] mx-auto mt-4 rounded-full"></div>
                </div>
                <div class="mt-12 flex flex-wrap justify-center gap-16">
                    @foreach ($Bidang as $item)
                        <button
                            @click="openModal = true; selectedBidang = '{{ $item->nama_bidang }}'; idBidang = {{ $item->id }}"
                            class="text-center group px-5 focus:outline-none">
                            <div
                                class="flex items-center justify-center h-24 w-24 mx-auto bg-blue-50 rounded-full transition-all duration-300 group-hover:bg-blue-100 group-hover:scale-110">
                                <svg class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="mt-4 font-semibold text-gray-700">{{ $item->nama_bidang }}</h3>
                            <p
                                class="mt-2 text-gray-500 text-sm leading-relaxed line-clamp-3 group-hover:text-gray-600">
                                {{ $item->deskripsi ?? 'Deskripsi bidang belum tersedia untuk saat ini.' }}
                            </p>
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
                            <div>
                                <h3 class="text-2xl font-bold text-[#004299]" x-text="selectedBidang"></h3>
                                <p class="text-sm text-gray-500">Daftar Pegawai BAPPERIDA Merauke</p>
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
                                                <img x-bind:src="person.foto ? (storageUrl + person.foto) : (avatarUrl +
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
                                                <svg class="w-4 h-4 text-[#004299]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
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
    {{-- dokumen --}}
    <section class=" py-24 ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center  z-10">
            <div class="reveal-on-scroll">
                <h2 class="text-3xl font-bold text-[#004299]">Dokumen BAPPERIDA Publik</h2>
                <div class="w-24 h-1 bg-[#CCFF00] mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="mt-16 flex flex-wrap justify-center gap-8">
                @forelse ($dokumen as $item)
                    <a href="/dokumen/kategori/{{ $item->id }}/{{ Str::slug($item->nama_kategori) }}"
                        class="bg-white p-8 rounded-2xl shadow-lg  hover:shadow-xl hover:-translate-y-1 transition-all duration-300 w-full max-w-sm">
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
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-5">
                    <h2 class="text-3xl font-bold text-[#004299]">Berita Populer</h2>
                    <div class="w-24 h-1 bg-[#CCFF00] mx-auto mt-4 rounded-full"></div>
                </div>
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
    <section class="py-24 overflow-hidden p-8 md:p-16  bg-[url('Assets/Gradient1.svg')] bg-cover bg-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll p-8 md:p-16">
            <div class="text-center mb-4">
                <h2 class="text-3xl font-bold text-[#004299]">Galeri</h2>
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
    </section>
    {{-- section tambahan dari database --}}
    @foreach ($LendingPage as $item)
        {!! Blade::render($item->template->content, [
            'dokumen' => $dokumen ?? [],
            'Str' => new \Illuminate\Support\Str(),
        ]) !!}
    @endforeach

    <section class="py-24 overflow-hidden p-8 md:p-16">
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

    </section>


</x-landing.layout>
