@section('meta_title', 'Galeri : ' . $galeri->judul)
@section('meta_image', $meta_image ? asset('storage/' . $meta_image) : asset('default-og.png'))
{{-- hero --}}
@section('hero_title', 'Album : ' . $galeri->judul)
@section('hero_bg', $meta_image ? asset('storage/' . $meta_image) : '/assets/image6.jpg')
<x-landing.layout>
    {{-- Hapus <x-landing.heading> karena judul ada di dalam konten --}}

    <section class="py-16 md:py-24 px-4 md:px-8 lg:px-16 -mt-[450px]">
        <div class="max-w-7xl mx-auto">

            {{-- Judul Halaman --}}
            <div class="flex justify-center mb-10">
                <h1 class="text-4xl md:text-3xl text-blue-700 font-bold text-center">{{ $galeri->judul }}</h1>
            </div>
            <!-- Social Share -->
            <div class="border-t border-gray-200 py-6 my-10">
                <div class="flex items-center justify-center">
                    @php
                        $shareUrl = route('galeri.public.show', $galeri);
                        $shareText = urlencode($galeri->judul);
                        $shareTextWithUrl = urlencode('Album BAPPERIDA : ' . $galeri->judul . ' ' . $shareUrl);
                    @endphp
                    <div class="flex items-center gap-2">
                        <span>Share Album : </span>
                        <a href="https://api.whatsapp.com/send?text={{ $shareTextWithUrl }}" target="_blank"
                            class="social-icon whatsapp h-14 w-14 hover:bg-green-300 hover:text-green-700 rounded-full flex items-center justify-center"
                            title="Bagikan ke WhatsApp"><i class="bi bi-whatsapp text-2xl"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"
                            class="social-icon facebook h-14 w-14 hover:bg-blue-300 hover:text-blue-700 rounded-full flex items-center justify-center"
                            title="Bagikan ke Facebook"><i class="bi bi-facebook text-2xl"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}"
                            target="_blank"
                            class="social-icon twitter h-14 w-14 hover:bg-black hover:text-white rounded-full flex items-center justify-center"
                            title="Bagikan ke X"><i class="bi bi-twitter-x text-2xl "></i></a>
                        <button onclick="copyToClipboard(this, '{{ $shareUrl }}')"
                            class="social-icon link h-14 w-14 hover:bg-gray-300 rounded-full flex items-center justify-center"
                            title="Salin tautan"><i class="bi bi-link-45deg text-xl"></i></button>
                    </div>
                </div>
            </div>

            {{-- Filter Tipe Konten (Tabs) --}}
            <div class="mb-8 flex justify-center">
                <div class="inline-flex rounded-lg shadow-sm bg-white p-1.5 space-x-1">
                    <button type="button" data-filter="all" class="filter-tab btn-tab-active">
                        Semua
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

            {{-- =================================== --}}
            {{-- === GRID masonry KONTEN GALERI === --}}
            {{-- =================================== --}}
            <div id="galeri-masonry-grid">
                {{-- Muat data awal (semua item) menggunakan partial view --}}
                @include('landing_page.galeri.partials._galeri_masonry_grid', [
                    'items' => $items,
                    'currentFilterType' => $currentFilterType,
                ])
            </div>
        </div>
    </section>

    {{-- =================================== --}}
    {{-- === ALBUM LAINNYA === --}}
    {{-- =================================== --}}
    @if ($albumLainnya->isNotEmpty())
        <section class="py-16 px-4 md:px-8 lg:px-16">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Album Lainnya</h2>

                {{-- Grid untuk 4 album lainnya --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($albumLainnya as $album)
                        @php
                            $coverItem = $album->firstItem;
                            $title = $album->judul;
                            $subtitle = $album->items_count . ' Item Media';
                            $date = $album->created_at->isoFormat('D MMMM YYYY');
                            $link = route('galeri.public.show', $album);
                        @endphp

                        <div
                            class="bg-white rounded-2xl shadow-lg flex flex-col transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20 group">
                            <a href="{{ $link }}">
                                <div class="relative w-full aspect-[4/3] rounded-t-2xl overflow-hidden bg-gray-100">
                                    @if ($coverItem)
                                        @if ($coverItem->tipe_file == 'image')
                                            <img src="{{ asset('storage/' . $coverItem->file_path) }}"
                                                alt="{{ $title }}"
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                loading="lazy">
                                        @elseif($coverItem->tipe_file == 'video')
                                            <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                                <i class="bi bi-film text-6xl text-gray-500"></i>
                                            </div>
                                        @elseif($coverItem->tipe_file == 'video_url')
                                            <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                                <i class="bi bi-youtube text-6xl text-red-600/80"></i>
                                            </div>
                                        @endif
                                    @else
                                        <img src="https://placehold.co/400x300/e2e8f0/667eea?text=Galeri"
                                            alt="{{ $title }}"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-blue-800/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                                        <i class="bi bi-eye-fill text-white text-4xl"></i>
                                    </div>
                                </div>
                            </a>
                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="text-sm font-bold text-blue-800 mb-1 leading-tight">
                                    <a href="{{ $link }}" class="hover:underline">
                                        {{ Str::limit($title, 50) }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-700 mb-2 leading-relaxed flex-grow">
                                    {{ Str::limit($subtitle, 75) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-auto pt-2 border-t border-gray-100">
                                    Album Dibuat: {{ $date }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- =================================== --}}
    {{-- === MODAL VIEWER BARU (LIGHTBOX) === --}}
    {{-- =================================== --}}
    <div id="item-viewer-modal"
        class="fixed inset-0 z-[60] hidden bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300 opacity-0">
        <div
            class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col transition-transform duration-300 scale-95">
            {{-- Konten Media (Gambar/Video/Embed) --}}
            <div id="viewer-content"
                class="flex-grow flex items-center justify-center p-4 bg-gray-100 rounded-t-2xl min-h-[300px]">
                {{-- Konten media akan di-inject oleh JS di sini --}}
            </div>
            {{-- Caption --}}
            <div id="viewer-caption-container" class="flex-shrink-0 p-4 border-b-2xl border-gray-200 text-center">
                <p id="viewer-caption" class="text-sm text-gray-700">Keterangan gambar.</p>
            </div>
            {{-- Tombol Close --}}
            <button type="button"
                class="close-viewer-btn absolute -top-4 -right-4 z-10 bg-white hover:bg-red-500 hover:text-white text-gray-800 rounded-full p-2 shadow-lg hover:bg-gray-200 transition-colors w-12 h-12">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
    </div>

    @push('scripts')
        {{-- Memuat Skrip AJAX --}}
        @include('landing_page.galeri.partials._galeri_detail_scripts')
    @endpush
</x-landing.layout>
