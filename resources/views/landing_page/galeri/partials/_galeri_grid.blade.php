@if ($items->isEmpty())
    <div class="text-center py-16 bg-white rounded-xl shadow-lg col-span-1 md:col-span-2 lg:col-span-4">
        <i class="bi bi-search text-6xl text-gray-400"></i>
        <h3 class="mt-4 text-2xl font-semibold text-gray-700">Tidak Ada Konten</h3>
        <p class="mt-2 text-gray-500">Tidak ada item yang cocok dengan kriteria pencarian Anda.</p>
    </div>
@else
    @foreach ($items as $item)
        {{-- Tentukan apakah ini Album (Galeri) atau Item (GaleriItem) --}}
        @php
            $isAlbum = $item instanceof \App\Models\Galeri;
            $coverItem = $isAlbum ? $item->firstItem : $item;
            $title = $isAlbum ? $item->judul : $item->galeri->judul ?? 'Galeri Item';
            $subtitle = $isAlbum ? $item->items_count . ' Item Media' : $item->caption ?? 'Item Media';
            $date = $item->created_at->isoFormat('D MMMM YYYY');
            // Tentukan link (Nanti ini akan mengarah ke halaman detail)
            $link = '#'; // TODO: Ganti ke route('galeri.public.show', $isAlbum ? $item : $item->galeri)
        @endphp

        {{-- Kartu --}}
        <div
            class="bg-white rounded-2xl shadow-lg flex flex-col transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20 group">
            <a href="{{ $link }}">
                {{-- Gunakan aspect-ratio 4:3 sesuai UI --}}
                <div class="relative w-full aspect-[4/3] rounded-t-2xl overflow-hidden bg-gray-100">
                    @if ($coverItem)
                        @if ($coverItem->tipe_file == 'image')
                            <img src="{{ asset('storage/' . $coverItem->file_path) }}" alt="{{ $title }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
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
                        <img src="https://placehold.co/400x300/e2e8f0/667eea?text=Galeri" alt="{{ $title }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @endif
                    {{-- Overlay biru sesuai UI --}}
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
                    {{ $isAlbum ? 'Album Dibuat' : 'Item Diunggah' }}: {{ $date }}
                </p>
            </div>
        </div>
    @endforeach
@endif